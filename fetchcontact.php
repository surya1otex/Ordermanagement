<?php 	

require_once('includes/load.php');

$customerId = $_POST['customerId'];

$sql = "SELECT phone,email,shipping FROM customers WHERE company_name='$customerId'";
$result = $db->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows
//print_r($row);
//$connect->close();
//echo "Hello DD";
echo json_encode($row);