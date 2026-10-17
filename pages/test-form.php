<?php
session_start();
include 'dbconnection.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test FD Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <h2>Test Fixed Deposit Form</h2>
    
    <form id="testForm">
        <div>
            <label>FD Holder:</label>
            <input type="text" id="fdholder" name="fdholder" required>
        </div>
        
        <div>
            <label>Bank Name:</label>
            <input type="text" id="fdofbank" name="fdofbank" required>
        </div>
        
        <div>
            <label>Principle Amount:</label>
            <input type="number" step="0.01" id="principleamt" name="principleamt" required>
        </div>
        
        <div>
            <label>Tenure (days):</label>
            <input type="number" id="nodays" name="nodays" required>
        </div>
        
        <div>
            <label>Interest Rate (%):</label>
            <input type="number" step="0.01" id="intrate" name="intrate" required>
        </div>
        
        <div>
            <label>Issued Date (DD-MM-YYYY):</label>
            <input type="text" id="fdissueddate" name="fdissueddate" placeholder="25-01-2024" required>
        </div>
        
        <button type="submit">Submit Test</button>
    </form>
    
    <div id="result"></div>
    
    <script>
    $(document).ready(function() {
        $('#testForm').on('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            var formData = $(this).serialize();
            console.log('Form Data:', formData);
            
            $.ajax({
                url: 'ajax/simple-test.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log('Response:', response);
                    $('#result').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                    
                    if (response.success) {
                        alert('Success! ID: ' + response.fd_id);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    $('#result').html('AJAX Error: ' + error);
                }
            });
        });
    });
    </script>
</body>
</html>