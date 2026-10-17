<?php
session_start();
include '../dbconnection.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple test - no complex validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST Data received: " . print_r($_POST, true) . "\n";
    
    // Basic sanitization
    $fdholder = mysqli_real_escape_string($con, $_POST['fdholder']);
    $fdofbank = mysqli_real_escape_string($con, $_POST['fdofbank']);
    $principleamt = floatval($_POST['principleamt']);
    $nodays = intval($_POST['nodays']);
    $intrate = floatval($_POST['intrate']);
    $fdissueddate = $_POST['fdissueddate'];
    
    // Simple date parsing
    $date_parts = explode('-', $fdissueddate);
    if (count($date_parts) === 3) {
        $fdissueddate_formatted = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
    } else {
        $fdissueddate_formatted = date('Y-m-d', strtotime($fdissueddate));
    }
    
    // Calculate
    $intamt = ($principleamt * $intrate * $nodays) / (100 * 365);
    $finalamt = $principleamt + $intamt;
    $maturitydate = date('Y-m-d', strtotime($fdissueddate_formatted . " + $nodays days"));
    $status = 'active';
    
    echo "Values to insert:\n";
    echo "FD Holder: $fdholder\n";
    echo "Bank: $fdofbank\n";
    echo "Principle: $principleamt\n";
    echo "Days: $nodays\n";
    echo "Rate: $intrate\n";
    echo "Interest: $intamt\n";
    echo "Final: $finalamt\n";
    echo "Issued: $fdissueddate_formatted\n";
    echo "Maturity: $maturitydate\n";
    
    // Simple insert
    $query = "INSERT INTO fixed_deposits 
              (fdholder, fdofbank, principleamt, nodays, intrate, 
               intamt, finalamt, fdissueddate, maturitydate, status) 
              VALUES ('$fdholder', '$fdofbank', $principleamt, $nodays, $intrate,
                      $intamt, $finalamt, '$fdissueddate_formatted', '$maturitydate', '$status')";
    
    echo "Query: $query\n";
    
    if (mysqli_query($con, $query)) {
        $new_id = mysqli_insert_id($con);
        echo "Insert successful! ID: $new_id\n";
        
        $response = [
            'success' => true,
            'message' => 'FD added successfully',
            'fd_id' => $new_id
        ];
    } else {
        echo "Insert failed: " . mysqli_error($con) . "\n";
        
        $response = [
            'success' => false,
            'message' => 'Database error: ' . mysqli_error($con)
        ];
    }
    
    // Output as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    
} else {
    echo "Invalid request method";
}
?>