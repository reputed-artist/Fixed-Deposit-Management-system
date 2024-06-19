<?php
//include 'dbconnection.php';

function getmaturity($con){

//include 'dbconnection.php';
// Get today's date in Y-m-d format
$today = date("Y-m-d");

// Query the database to find FDs maturing today
$query = "SELECT * FROM fd WHERE maturitydate = '$today'";

//echo $query;

$cv=mysqli_query($con,$query);
// $stmt = $con->prepare($query);
// $stmt->bind_param("s", $today);
// $stmt->execute();
// $result = $stmt->get_result();



$fds = [];

while ($row = mysqli_fetch_array($cv)) {
    $fds[] = $row;
}

// Pass the FD details to the frontend

$fdsJson = json_encode($fds);

return $fdsJson;

}

//getmaturity($con);
?>