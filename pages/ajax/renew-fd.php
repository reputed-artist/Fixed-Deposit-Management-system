<?php
// renew-fd.php - RENEW updates the SAME row (no new FD entry).
// New fdissueddate = previous maturitydate; maturitydate = prev maturity + tenure days.
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
error_reporting(E_ALL);

function renew_json($arr) {
    while (ob_get_level() > 0) { ob_end_clean(); }
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit;
}

include '../dbconnection.php';

if (!isset($con) || !$con || mysqli_connect_errno()) {
    renew_json(['success' => false, 'message' => 'DB connection failed: ' . mysqli_connect_error()]);
}
mysqli_set_charset($con, 'utf8mb4');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    renew_json(['success' => false, 'message' => 'Invalid request method']);
}

// Get data from POST
$fd_id = isset($_POST['fd_id']) ? intval($_POST['fd_id']) : 0;
$tenure = isset($_POST['tenure']) ? intval($_POST['tenure']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($fd_id <= 0 || $tenure <= 0 || $action !== 'renew') {
    renew_json(['success' => false, 'message' => 'Invalid parameters']);
}

// Detect schema: new `fixed_deposits` vs legacy `fd` (fd_mgmt.sql)
$table = 'fixed_deposits';
$chk = @mysqli_query($con, "SHOW TABLES LIKE 'fixed_deposits'");
if (!$chk || mysqli_num_rows($chk) == 0) {
    $table = 'fd';
}
$has_history = false;
$hchk = @mysqli_query($con, "SHOW TABLES LIKE 'fd_renewal_history'");
if ($hchk && mysqli_num_rows($hchk) > 0) { $has_history = true; }

try {
    // Start transaction
    if (!mysqli_begin_transaction($con)) {
        throw new Exception("Could not start transaction: " . mysqli_error($con));
    }
    
    // 1. Get the FD details
    $query = "SELECT * FROM `$table` WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    if (!$stmt) { throw new Exception("Prepare failed (select): " . mysqli_error($con)); }
    mysqli_stmt_bind_param($stmt, 'i', $fd_id);
    if (!mysqli_stmt_execute($stmt)) { throw new Exception("Select failed: " . mysqli_stmt_error($stmt)); }
    $result = mysqli_stmt_get_result($stmt);
    if (!$result) { throw new Exception("Select failed: " . mysqli_error($con)); }
    $fd_data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    if (!$fd_data) {
        throw new Exception("Fixed Deposit not found (id=$fd_id)");
    }
    
    // 2. Calculate new dates and amounts
    // New FD issued date = PREVIOUS maturity date. Next maturity = prev maturity + tenure days.
    $prev_maturity_raw = isset($_POST['renew_start_date']) ? trim($_POST['renew_start_date']) : '';
    $prev_from_row = isset($fd_data['maturitydate']) ? trim((string)$fd_data['maturitydate']) : '';
    // Prefer posted start date, fall back to row's maturitydate, then row's issued date
    $issued_from_row = isset($fd_data['fdissueddate']) ? trim((string)$fd_data['fdissueddate']) : '';
    $start_candidate = ($prev_maturity_raw !== '' && $prev_maturity_raw !== '0000-00-00') ? $prev_maturity_raw : $prev_from_row;
    if (($start_candidate === '' || $start_candidate === '0000-00-00') && $issued_from_row !== '' && $issued_from_row !== '0000-00-00') {
        $start_candidate = $issued_from_row;
    }
    $start_candidate = str_replace('/', '-', $start_candidate);
    $start_ts = strtotime($start_candidate);
    if ($start_ts === false) {
        throw new Exception("Previous maturity date is invalid: $start_candidate");
    }
    $current_date = date('Y-m-d', $start_ts); // new FD's issued date
    if ($current_date === '1970-01-01' || $current_date === '0000-00-00') {
        throw new Exception("Previous maturity date is invalid: $start_candidate");
    }
    $tenure_days = intval($tenure);
    if ($tenure_days <= 0) { throw new Exception("Invalid tenure: $tenure"); }
    $new_maturity_date = date('Y-m-d', strtotime($current_date . " + $tenure_days days"));
    if ($new_maturity_date === false || $new_maturity_date === '1970-01-01' || $new_maturity_date === '0000-00-00') {
        throw new Exception("Could not compute maturity date for tenure $tenure_days");
    }
    
    // Use the maturity amount as the new principle amount
    $new_principle_amt = floatval($fd_data['finalamt']);
    $interest_rate = floatval($fd_data['intrate']);
    
    // Simple interest calculation: (P * R * T) / (100 * 365)
    $interest_amt = ($new_principle_amt * $interest_rate * $tenure_days) / (100 * 365);
    $new_maturity_amt = $new_principle_amt + $interest_amt;
    
    // 3. UPDATE the SAME row (no new FD entry).
    if ($table === 'fixed_deposits') {
    $query = "UPDATE fixed_deposits SET fdissueddate = ?, principleamt = ?, nodays = ?, intrate = ?, intamt = ?, finalamt = ?, maturitydate = ?, status = 'active' WHERE id = ?";
    $status = 'active'; // kept: row stays active after same-row renew
    $stmt = mysqli_prepare($con, $query);
    if (!$stmt) { throw new Exception("Prepare failed (update): " . mysqli_error($con)); }
    // s=issued, d=principle, i=nodays, d=intrate, d=intamt, d=finalamt, s=maturity, i=id
    mysqli_stmt_bind_param($stmt, 'sdidddsi',
        $current_date,
        $new_principle_amt,
        $tenure_days,
        $interest_rate,
        $interest_amt,
        $new_maturity_amt,
        $new_maturity_date,
        $fd_id
    );
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Failed to renew FD: " . mysqli_stmt_error($stmt));
    }
    mysqli_stmt_close($stmt);
    } else {
        $query = "UPDATE fd SET fdissueddate = ?, principleamt = ?, nodays = ?, intrate = ?, intamt = ?, finalamt = ?, maturitydate = ? WHERE id = ?";
        $nodays_str = (string)$tenure_days;
        $intrate_str = (string)$interest_rate;
        // fd columns principleamt/intamt/finalamt are INT - round, don't send decimals
        $new_principle_str = (string)intval(round($new_principle_amt));
        $interest_str = (string)intval(round($interest_amt));
        $new_maturity_str = (string)intval(round($new_maturity_amt));
        $stmt = mysqli_prepare($con, $query);
        if (!$stmt) { throw new Exception("Prepare failed (update fd): " . mysqli_error($con)); }
        // fd: issued date, principle INT, nodays/intrate VARCHAR, intamt/finalamt INT, maturity date, id
        mysqli_stmt_bind_param($stmt, 'sssssssi',
            $current_date,
            $new_principle_str, $nodays_str, $intrate_str,
            $interest_str, $new_maturity_str, $new_maturity_date,
            $fd_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to renew FD: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    }
    
    // 4. History is best-effort, only if table exists (same-row renew: old = new = fd_id)
    if ($has_history) {
    $history_query = "INSERT INTO fd_renewal_history 
                     (old_fd_id, new_fd_id, renewal_date, tenure_days, created_at) 
                     VALUES (?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($con, $history_query);
    if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'iisi', $fd_id, $fd_id, $current_date, $tenure_days);
    @mysqli_stmt_execute($stmt);
    @mysqli_stmt_close($stmt);
    }
    }
    
    // Commit transaction
    mysqli_commit($con);
    
    // Format dates for display
    $formatted_start_date = date('d-m-Y', strtotime($current_date));
    $formatted_maturity_date = date('d-m-Y', strtotime($new_maturity_date));
    
    renew_json([
        'success' => true,
        'message' => 'FD renewed successfully!<br>' .
                     'FD ID: ' . $fd_id . ' (same row updated)<br>' .
                     'New FD Issued Date (prev maturity): ' . $formatted_start_date . '<br>' .
                     'New Maturity Date: ' . $formatted_maturity_date . '<br>' .
                     'New Maturity Amount: Rs.' . number_format($new_maturity_amt, 2)
    ]);
    
} catch (Exception $e) {
    // Rollback transaction on error
    try { mysqli_rollback($con); } catch (Exception $ignored) {}
    error_log("FD Renewal Error: " . $e->getMessage());
    renew_json([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}