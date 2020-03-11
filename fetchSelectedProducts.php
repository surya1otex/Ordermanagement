<?php 	

require_once('includes/load.php');

$productId = $_POST['productId'];

$sql = "SELECT id, name,prod_SKU, quantity, categorie_id,country, sale_price FROM products WHERE id = '$productId'";
$result = $db->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows
//print_r($row);
//$connect->close();
//echo "Hello DD";
echo json_encode($row);