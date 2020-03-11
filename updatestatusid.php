<?php 	

require_once('includes/load.php');

$statusid = $_POST['statusid'];

$orderid = $_POST['orderid'];

$sql = "UPDATE order_item SET order_item_status='$statusid' WHERE order_item_id='$orderid'";
$result = $db->query($sql);

echo "Status Updated";
//echo json_encode($row);