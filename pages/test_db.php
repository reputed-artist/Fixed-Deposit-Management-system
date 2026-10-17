<?php
include 'dbconnection.php';

echo "<h3>Testing Database Connection and Structure</h3>";

// Test connection
if ($con) {
    echo "✓ Database connected successfully<br>";
} else {
    echo "✗ Database connection failed<br>";
    exit;
}

// Check table structure
$result = mysqli_query($con, "DESCRIBE fixed_deposits");
if ($result) {
    echo "✓ fixed_deposits table exists<br>";
    
    // Check for auto_increment
    $has_auto_increment = false;
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['Field'] == 'id' && $row['Extra'] == 'auto_increment') {
            $has_auto_increment = true;
            break;
        }
    }
    
    if ($has_auto_increment) {
        echo "✓ id column is auto_increment<br>";
    } else {
        echo "✗ id column is NOT auto_increment<br>";
    }
} else {
    echo "✗ fixed_deposits table does not exist<br>";
}

// Test insert
echo "<h4>Testing Insert:</h4>";
$test_query = "INSERT INTO fixed_deposits 
               (fdholder, fdofbank, principleamt, nodays, intrate, 
                intamt, finalamt, fdissueddate, maturitydate, status) 
               VALUES ('Test User', 'Test Bank', 1000.00, 365, 5.0, 
                       50.00, 1050.00, '2024-01-25', '2025-01-25', 'active')";

if (mysqli_query($con, $test_query)) {
    $new_id = mysqli_insert_id($con);
    echo "✓ Test insert successful! New ID: $new_id<br>";
    
    // Delete test record
    mysqli_query($con, "DELETE FROM fixed_deposits WHERE fdholder = 'Test User'");
    echo "✓ Test record deleted<br>";
} else {
    echo "✗ Test insert failed: " . mysqli_error($con) . "<br>";
}

// Check auto_increment value
$result = mysqli_query($con, "SHOW TABLE STATUS LIKE 'fixed_deposits'");
$row = mysqli_fetch_assoc($result);
echo "✓ Current auto_increment value: " . $row['Auto_increment'] . "<br>";

// List all tables with foreign keys
echo "<h4>Foreign Key Constraints:</h4>";
$result = mysqli_query($con, "SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE() 
AND REFERENCED_TABLE_NAME IS NOT NULL");

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Table: {$row['TABLE_NAME']}, Column: {$row['COLUMN_NAME']}, References: {$row['REFERENCED_TABLE_NAME']}.{$row['REFERENCED_COLUMN_NAME']}<br>";
    }
} else {
    echo "No foreign key constraints found<br>";
}

mysqli_close($con);
?>