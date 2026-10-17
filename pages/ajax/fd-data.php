<?php
// fd-data.php - DataTables JSON source for #example2 on fd.php
// Must output PURE JSON - no warnings / HTML before or after.
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');

// Never leak PHP warnings into JSON (DataTables tn/1). Log them instead.
ini_set('display_errors', '0');
error_reporting(E_ALL);

include '../dbconnection.php';
// Discard any stray output (e.g. "Failed to connect..." echo from dbconnection.php)
ob_end_clean();

if (!isset($con) || !$con) {
    echo json_encode(array('data' => array(), 'error' => 'DB connection failed: ' . mysqli_connect_error()));
    exit;
}
mysqli_set_charset($con, 'utf8mb4');

// Support both schemas: new `fixed_deposits` and legacy `fd` (from fd_mgmt.sql)
$table = 'fixed_deposits';
$check = mysqli_query($con, "SHOW TABLES LIKE 'fixed_deposits'");
if (!$check || mysqli_num_rows($check) == 0) {
    $table = 'fd'; // fallback to original dump
}

if ($table === 'fixed_deposits') {
    // Return YYYY-MM-DD so fd.php JS (parts[2]-parts[1]-parts[0]) displays DD-MM-YYYY correctly.
    // NULLIF zero-dates so renderer can show '-' instead of 00-00-0000.
    $query = "SELECT
        id,
        fdholder,
        fdofbank,
        principleamt,
        nodays,
        intrate,
        intamt,
        finalamt,
        CASE WHEN CAST(fdissueddate AS CHAR) = '0000-00-00' THEN NULL ELSE DATE_FORMAT(fdissueddate, '%Y-%m-%d') END as fdissueddate,
        CASE WHEN CAST(maturitydate AS CHAR) = '0000-00-00' THEN NULL ELSE DATE_FORMAT(maturitydate, '%Y-%m-%d') END as maturitydate,
        status,
        created_at
    FROM fixed_deposits
    WHERE status IS NULL OR status != 'deleted'
    ORDER BY id DESC";
} else {
    // Legacy `fd` table has no status / created_at columns.
    // NULLIF zero-dates so renderer can show '-' instead of 00-00-0000.
    $query = "SELECT
        id,
        fdholder,
        fdofbank,
        principleamt,
        nodays,
        intrate,
        intamt,
        finalamt,
        CASE WHEN CAST(fdissueddate AS CHAR) = '0000-00-00' THEN NULL ELSE DATE_FORMAT(fdissueddate, '%Y-%m-%d') END as fdissueddate,
        CASE WHEN CAST(maturitydate AS CHAR) = '0000-00-00' THEN NULL ELSE DATE_FORMAT(maturitydate, '%Y-%m-%d') END as maturitydate,
        'active' as status,
        fdentrydate as created_at
    FROM fd
    ORDER BY id DESC";
}

$result = mysqli_query($con, $query);
if (!$result) {
    // Return valid DataTables empty set + error (still valid JSON, no tn/1)
    error_log('fd-data.php SQL error: ' . mysqli_error($con));
    echo json_encode(array('data' => array(), 'error' => 'database error: ' . mysqli_error($con)));
    exit;
}

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(array('data' => $data), JSON_UNESCAPED_UNICODE);
exit;
?>