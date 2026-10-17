<?php
include'../dbconnection.php';
include_once"../inc/titlecase.php";

$try=0;
$nameerror="";
$moberror="";
$ageerror="";
$countryerror="";
$response="";


$cid=$_REQUEST['cid'];

if(isset($_REQUEST['c_name']) && $_REQUEST['c_name'] != null)
{
      
  $cname=titlecase(addslashes($_REQUEST['c_name']));
    $try++;
    //echo "name".$try;
 }
 else
 {
    $nameerror="Enter Client Name";
 }

 if($_REQUEST['age'] != null)
 { 
  $age= $_REQUEST['age'];
  $try++;
  //echo "add".$try;
}
else
{
    $cadderror="Enter Age";
}

  //$mobcount=strlen(($_REQUEST['mob']));
  if($_REQUEST['fullno'] !=null)
  {
  $mob= $_REQUEST['fullno'];
  $try++;
  //echo "mob".$try;
 }
 else{
  $moberror="Enter Valid Mobile number";
 }




$conzy = rtrim($_REQUEST['fulldetails']);

//echo $conzy;

if($_REQUEST['fulldetails'] != null && $conzy == "India")
  {
  $country= trim($_REQUEST['fulldetails']);
  $sp= $_REQUEST['address_country'];
    //echo "\n"."correct";
    $in=0;
    $try++;
    //echo "country".$try;
    //echo "first conition";
  }
  else if ($_REQUEST['fulldetails'] != null && $_REQUEST['fulldetails'] != "India"){
  #$countryerror="Enter Valid Mobile number for Country selection";
   $country= trim($_REQUEST['fulldetails']);
  $sp= trim($_REQUEST['address_country']);
   $in=1;
   //echo "2nd condition";
   $try++;
   //echo "country".$try;
 }

 else {
  $countryerror="Enter Valid Mobile number for Country selection";
 //echo " enter else";
 }

//echo "\n Country print".$_REQUEST['fulldetails'];
//echo "\n Ind value print".$in;






  $date= date("Y-m-d");

//echo "try value ::::::::::".$try;

//echo "INSERT INTO `client` (`cid`, `c_name`, `age`,`mob`,`country`, `created`) VALUES ($cid,'$cname', '$age', '$mob','$country', '$date')";

$response=array();
if($try == 4 )
{

 $c=mysqli_query($con,"INSERT INTO `client` (`cid`, `c_name`, `age`,`mob`,`country`, `created`) VALUES ($cid,'$cname', '$age', '$mob','$country', '$date')") or die("Error: " . mysqli_error($con));

if(!$c)
{
  
  //$_SESSION['msg']="Error Occured ".mysqli_error($con);
  $response['success'] = false;
    $response['message'] = "Error Occurred";
    $response['errors'] = array(
        'c_name' => $nameerror,
        'age' => $ageerror,
        'mob' => $moberror,
        'country' => $countryerror,
      
    );

}
else
{

 $response['success'] = true;
    $response['message'] = "Client Added successfully";

$_SESSION['msg']="Client Added successfully";

 } 
}
 else{
  $response['success'] = false;
    $response['message'] = "Error Occurred";
    $response['errors'] = array(
        'c_name' => $nameerror,
        'age' => $ageerror,
        'mob' => $moberror,
        'country' => $countryerror,
      
    );
 
}
echo json_encode($response);

?>