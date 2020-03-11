<?php 	

require_once('includes/load.php');

$boxid = $_POST['boxid'];

$sql = "SELECT id,box_name,price FROM boxes WHERE id='$boxid'";
$result = $db->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows
//print_r($row);
//$connect->close();
//echo "Hello DD";
echo json_encode($row);