<?php
session_start();
include 'dbconnection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log received data for debugging
    error_log("POST Data received: " . print_r($_POST, true));
    
    // Validate required fields
    $required_fields = ['fdholder', 'fdofbank', 'principleamt', 'nodays', 'intrate', 'fdissueddate'];
    $errors = [];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            $errors[] = "Please fill in: " . ucfirst($field);
        }
    }
    
    if (!empty($errors)) {
        die(json_encode(['success' => false, 'message' => implode(', ', $errors)]));
    }
    
    // Sanitize input
    $fdholder = trim(mysqli_real_escape_string($con, $_POST['fdholder']));
    $fdofbank = trim(mysqli_real_escape_string($con, $_POST['fdofbank']));
    $principleamt = trim($_POST['principleamt']);
    $nodays = trim($_POST['nodays']);
    $intrate = trim($_POST['intrate']);
    $fdissueddate = trim($_POST['fdissueddate']);
    
    // Debug log
    error_log("Raw inputs - principleamt: $principleamt, nodays: $nodays, intrate: $intrate");
    
    // Validate numeric values
    if (!is_numeric($principleamt) || floatval($principleamt) <= 0) {
        die(json_encode(['success' => false, 'message' => 'Principle amount must be a valid number greater than 0']));
    }
    
    if (!is_numeric($nodays) || intval($nodays) <= 0) {
        die(json_encode(['success' => false, 'message' => 'Tenure must be a valid number of days greater than 0']));
    }
    
    if (!is_numeric($intrate) || floatval($intrate) <= 0) {
        die(json_encode(['success' => false, 'message' => 'Interest rate must be a valid percentage greater than 0']));
    }
    
    // Convert to proper types
    $principleamt = floatval($principleamt);
    $nodays = intval($nodays);
    $intrate = floatval($intrate);
    
    // Debug log after conversion
    error_log("Converted - principleamt: $principleamt, nodays: $nodays, intrate: $intrate");
    
    // Parse date (handle dd-mm-yyyy format). Never allow 00-00-0000 / zero dates.
    $fdissueddate = str_replace('/', '-', trim($fdissueddate));
    if ($fdissueddate === '' || $fdissueddate === '00-00-0000' || $fdissueddate === '0000-00-00') {
        die(json_encode(['success' => false, 'message' => 'Invalid issued date. Use DD-MM-YYYY']));
    }
    
    // Try different date formats
    $date_formats = ['d-m-Y', 'Y-m-d'];
    $parsed_date = null;
    
    foreach ($date_formats as $format) {
        $d = DateTime::createFromFormat($format, $fdissueddate);
        // Strict check + reject zero dates (checkdate guards 00 day/month)
        if ($d && $d->format($format) === $fdissueddate) {
            $y = (int)$d->format('Y'); $m = (int)$d->format('m'); $day = (int)$d->format('d');
            if ($y >= 1900 && $y <= 2100 && checkdate($m, $day, $y)) {
                $parsed_date = $d->format('Y-m-d');
                break;
            }
        }
    }
    
    if (!$parsed_date) {
        die(json_encode(['success' => false, 'message' => 'Invalid date format. Use DD-MM-YYYY']));
    }
    
    error_log("Parsed date: $parsed_date");
    
    // Calculate maturity date - guard against strtotime failure (1970/zero date)
    $mat_ts = strtotime($parsed_date . " + $nodays days");
    if ($mat_ts === false) {
        die(json_encode(['success' => false, 'message' => 'Could not compute maturity date']));
    }
    $maturitydate = date('Y-m-d', $mat_ts);
    if ($maturitydate === '1970-01-01' || $maturitydate === '0000-00-00') {
        die(json_encode(['success' => false, 'message' => 'Could not compute maturity date']));
    }
    
    // Calculate interest amount (simple interest)
    $intamt = ($principleamt * $intrate * $nodays) / (100 * 365);
    
    // Calculate final amount
    $finalamt = $principleamt + $intamt;
    
    // Status is 'active' by default
    $status = 'active';
    
    try {
        // Prepare INSERT statement - DO NOT include id column
        $query = "INSERT INTO fixed_deposits 
                  (fdholder, fdofbank, principleamt, nodays, intrate, 
                   intamt, finalamt, fdissueddate, maturitydate, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        error_log("SQL Query: $query");
        error_log("Values: $fdholder, $fdofbank, $principleamt, $nodays, $intrate, $intamt, $finalamt, $parsed_date, $maturitydate, $status");
        
        $stmt = mysqli_prepare($con, $query);
        
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . mysqli_error($con));
        }
        
        // Bind parameters - 10 parameters total: s s d i d d d d s s
        $bound = mysqli_stmt_bind_param($stmt, 'ssdidddsss', 
            $fdholder,
            $fdofbank,
            $principleamt,
            $nodays,
            $intrate,
            $intamt,
            $finalamt,
            $parsed_date,
            $maturitydate,
            $status
        );
        
        if (!$bound) {
            throw new Exception("Failed to bind parameters: " . mysqli_stmt_error($stmt));
        }
        
        // Execute query
        if (mysqli_stmt_execute($stmt)) {
            $new_id = mysqli_insert_id($con);
            
            error_log("Insert successful, new ID: $new_id");
            
            if ($new_id > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Fixed Deposit added successfully! FD ID: ' . $new_id,
                    'fd_id' => $new_id
                ]);
            } else {
                throw new Exception("Failed to get insert ID. MySQL error: " . mysqli_error($con));
            }
        } else {
            throw new Exception("Failed to execute query: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        
    } catch (Exception $e) {
        // Log error for debugging
        error_log("FD Insert Error: " . $e->getMessage());
        
        echo json_encode([
            'success' => false,
            'message' => 'Database Error: ' . $e->getMessage()
        ]);
    }
    
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Use POST.'
    ]);
}

mysqli_close($con);
?>
<script type="text/javascript">
// Form validation and submission
$(document).ready(function() {
    // Number validation function
    function isValidNumber(value) {
        if (!value || value.trim() === '') return false;
        var num = parseFloat(value);
        return !isNaN(num) && isFinite(num) && num > 0;
    }
    
    // Date validation function
    function isValidDate(dateString) {
        // Check format dd-mm-yyyy or dd/mm/yyyy
        var regex = /^\d{2}[-\/]\d{2}[-\/]\d{4}$/;
        if (!regex.test(dateString)) return false;
        
        // Parse the date
        var parts = dateString.split(/[-\/]/);
        var day = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var year = parseInt(parts[2], 10);
        
        // Check the ranges
        if (year < 1000 || year > 3000 || month === 0 || month > 12) return false;
        
        var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        
        // Adjust for leap years
        if (year % 400 === 0 || (year % 100 !== 0 && year % 4 === 0)) {
            monthLength[1] = 29;
        }
        
        return day > 0 && day <= monthLength[month - 1];
    }
    
    // Real-time validation
    $('#principleamt, #nodays, #intrate').on('input', function() {
        var value = $(this).val();
        var isValid = isValidNumber(value);
        
        if (isValid) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
        
        console.log('Validating ' + $(this).attr('id') + ': ' + value + ' = ' + isValid);
    });
    
    $('#fdissueddate').on('input', function() {
        var value = $(this).val();
        var isValid = isValidDate(value);
        
        if (isValid) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });
    
    // Form submission
    $('#addFdForm').on('submit', function(e) {
        e.preventDefault();
        console.log('submit event');
        
        // Reset validation states
        $('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        
        // Get form values
        var fdholder = $('#fdholder').val().trim();
        var fdofbank = $('#fdofbank').val().trim();
        var principleamt = $('#principleamt').val().trim();
        var nodays = $('#nodays').val().trim();
        var intrate = $('#intrate').val().trim();
        var fdissueddate = $('#fdissueddate').val().trim();
        
        console.log('Issued Date:', fdissueddate);
        console.log('Invalid principleamt:', !isValidNumber(principleamt));
        console.log('Invalid nodays:', !isValidNumber(nodays));
        console.log('Invalid intrate:', !isValidNumber(intrate));
        
        // Validate all fields
        var isValid = true;
        var errorMessages = [];
        
        if (!fdholder) {
            $('#fdholder').addClass('is-invalid');
            errorMessages.push('FD Holder is required');
            isValid = false;
        }
        
        if (!fdofbank) {
            $('#fdofbank').addClass('is-invalid');
            errorMessages.push('Bank Name is required');
            isValid = false;
        }
        
        if (!isValidNumber(principleamt)) {
            $('#principleamt').addClass('is-invalid');
            errorMessages.push('Valid Principle Amount is required');
            isValid = false;
        }
        
        if (!isValidNumber(nodays)) {
            $('#nodays').addClass('is-invalid');
            errorMessages.push('Valid Tenure (days) is required');
            isValid = false;
        }
        
        if (!isValidNumber(intrate)) {
            $('#intrate').addClass('is-invalid');
            errorMessages.push('Valid Interest Rate is required');
            isValid = false;
        }
        
        if (!isValidDate(fdissueddate)) {
            $('#fdissueddate').addClass('is-invalid');
            errorMessages.push('Valid Date (DD-MM-YYYY) is required');
            isValid = false;
        }
        
        if (!isValid) {
            Swal.fire({
                title: 'Validation Error',
                html: errorMessages.join('<br>'),
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        // Format date for submission (ensure DD-MM-YYYY format)
        var dateParts = fdissueddate.split(/[-\/]/);
        if (dateParts.length === 3) {
            fdissueddate = dateParts[0] + '-' + dateParts[1] + '-' + dateParts[2];
        }
        
        console.log('Formatted Date for submission:', fdissueddate);
        
        // Show loading
        Swal.fire({
            title: 'Processing...',
            text: 'Adding Fixed Deposit',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Prepare form data
        var formData = {
            fdholder: fdholder,
            fdofbank: fdofbank,
            principleamt: principleamt,
            nodays: nodays,
            intrate: intrate,
            fdissueddate: fdissueddate
        };
        
        console.log('Sending data:', formData);
        
        // Submit via AJAX
        $.ajax({
            url: 'ajax/add-fd-process.php', // Change this to your actual PHP file
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                Swal.close();
                console.log('Response:', response);
                
                if (response.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect to FD list
                            window.location.href = 'fd.php';
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('AJAX Error:', xhr.responseText);
                
                Swal.fire({
                    title: 'Server Error!',
                    text: 'Failed to add FD. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
    
    // Auto-calculate maturity date
    $('#fdissueddate, #nodays').on('change', function() {
        var issuedDate = $('#fdissueddate').val().trim();
        var days = $('#nodays').val().trim();
        
        if (isValidDate(issuedDate) && isValidNumber(days)) {
            var dateParts = issuedDate.split(/[-\/]/);
            var issuedDateObj = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
            var maturityDate = new Date(issuedDateObj);
            maturityDate.setDate(maturityDate.getDate() + parseInt(days));
            
            var formattedMaturity = ('0' + maturityDate.getDate()).slice(-2) + '-' + 
                                   ('0' + (maturityDate.getMonth() + 1)).slice(-2) + '-' + 
                                   maturityDate.getFullYear();
            
            $('#maturitydate').val(formattedMaturity);
            console.log('Calculated Maturity Date:', formattedMaturity);
        }
    });
    
    // Auto-calculate interest and final amount
    $('#principleamt, #nodays, #intrate').on('input', function() {
        var principle = parseFloat($('#principleamt').val()) || 0;
        var days = parseFloat($('#nodays').val()) || 0;
        var rate = parseFloat($('#intrate').val()) || 0;
        
        if (principle > 0 && days > 0 && rate > 0) {
            var interest = (principle * rate * days) / (100 * 365);
            var finalAmount = principle + interest;
            
            $('#intamt').val(interest.toFixed(2));
            $('#finalamt').val(finalAmount.toFixed(2));
        }
    });
});
</script>