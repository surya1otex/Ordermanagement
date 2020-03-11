<?php 	

require_once('includes/load.php');

$sql = "SELECT id, name, prod_SKU FROM products";
$result = $db->query($sql);

$data = $result->fetch_all();

//$connect->close();

echo json_encode($data);
?>
