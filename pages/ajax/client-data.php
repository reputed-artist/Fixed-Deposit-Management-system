<?php
// client-data.php - DataTables JSON source for #example2 on manage-clients.php
// DataTables 1.10 legacy format (sEcho/aaData) + new format (data/draw) for compat.
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', '0');
error_reporting(E_ALL);

include_once("../dbconnection.php");
ob_end_clean();

if (!isset($con) || !$con) {
    echo json_encode(array(
        "sEcho" => 1,
        "iTotalRecords" => 0,
        "iTotalDisplayRecords" => 0,
        "aaData" => array(),
        "data" => array(),
        "error" => "DB connection failed: " . mysqli_connect_error()
    ));
    exit;
}
mysqli_set_charset($con, 'utf8mb4');

$sql = "SELECT cid,c_name,age,mob, country, created From client";
$resultset = mysqli_query($con, $sql);
if (!$resultset) {
    error_log('client-data.php SQL error: ' . mysqli_error($con));
    echo json_encode(array(
        "sEcho" => 1,
        "iTotalRecords" => 0,
        "iTotalDisplayRecords" => 0,
        "aaData" => array(),
        "data" => array(),
        "error" => "database error:" . mysqli_error($con)
    ));
    exit;
}
$data = array();
while( $rows = mysqli_fetch_assoc($resultset) ) {
	$data[]=$rows;

	//echo var_dump($rows);

}


//echo var_dump($data);
// DataTables 1.10 server-side legacy keys (what manage-clients.php expects via aaData)
// + modern `data` key so any 1.10.16+ default also works.
$draw = isset($_GET['draw']) ? intval($_GET['draw']) : (isset($_GET['sEcho']) ? intval($_GET['sEcho']) : 1);
$results = array(

	"sEcho" => $draw,
    "draw" => $draw,
"iTotalRecords" => count($data),
"iTotalDisplayRecords" => count($data),
"recordsTotal" => count($data),
"recordsFiltered" => count($data),
  "aaData"=>$data,
  "data"=>$data);

echo json_encode($results, JSON_UNESCAPED_UNICODE);
exit;
?>