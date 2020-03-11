<?php 	

require_once('includes/load.php');

//$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
//echo "Action Starts";	
	$orderId = $_POST['orderId'];
    $ordertotal = $_POST['totalAmountValue'];
	$sql = "UPDATE orders SET  grand_total = '$ordertotal' WHERE order_id = {$orderId}";	
	$db->query($sql);	
    //echo $orderId;
	$readyToUpdateOrderItem = false;

	// add the quantity from the order item to product table
	for($x = 0; $x < count($_POST['productName']); $x++) {		
		//  product table
		$updateProductQuantitySql = "SELECT products.quantity FROM products WHERE products.id = ".$_POST['productName'][$x]." ";
		$updateProductQuantityData = $db->query($updateProductQuantitySql);			
			echo 'Another Action start';
		while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
			echo "<h2>"."Test"."</h2>";
			// order item table add product quantity
			$orderItemTableSql = "SELECT order_item.quantity FROM order_item WHERE order_item.order_id = {$orderId}";
			$orderItemResult = $db->query($orderItemTableSql);
			$orderItemData = $orderItemResult->fetch_row();

			$editQuantity = $updateProductQuantityResult[0] + $orderItemData[0];							

			$updateQuantitySql = "UPDATE products SET quantity = $editQuantity WHERE id = ".$_POST['productName'][$x]."";
			$db->query($updateQuantitySql);	
		} // while	
		
		if(count($_POST['productName']) == count($_POST['productName'])) {
			$readyToUpdateOrderItem = true;
            //echo $readyToUpdateOrderItem;		
		}
	} // /for quantity





	// remove the order item data from order item table
	for($x = 0; $x < count($_POST['productName']); $x++) {			
		$removeOrderSql = "DELETE FROM order_item WHERE order_id = {$orderId}";
		$db->query($removeOrderSql);	
	} // /for quantity


	if($readyToUpdateOrderItem) {
			// insert the order item data 
		for($x = 0; $x < count($_POST['productName']); $x++) {			
			$updateProductQuantitySql = "SELECT products.quantity FROM products WHERE products.id = ".$_POST['productName'][$x]."";
			$updateProductQuantityData = $db->query($updateProductQuantitySql);
			
			while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
				$updateQuantity[$x] = $updateProductQuantityResult[0] - $_POST['quantity'][$x];							
					// update product table
					$updateProductTable = "UPDATE products SET quantity = '".$updateQuantity[$x]."' WHERE id = ".$_POST['productName'][$x]."";
					$db->query($updateProductTable);

					// add into order_item
				//$updateItemSql = "UPDATE order_item SET quantity='".$_POST['quantity'][$x]."' , boxes='".$_POST['boxes'][$x]."' WHERE order_id= {$orderId}";
				//$db->query($updateItemSql);
				$orderItemSql = "INSERT INTO order_item (order_id, sku, product_id, quantity, adjust_quan, rate, total, order_item_status) 
				VALUES ({$orderId}, '".$_POST['skus'][$x]."', '".$_POST['productName'][$x]."', '".$_POST['quantity'][$x]."', '".$_POST['adjustquantity'][$x]."', '".$_POST['rateValue'][$x]."', '".$_POST['totalValue'][$x]."', 1)";

				$db->query($orderItemSql);		
			} // while	
		} // /for quantity
	}

redirect('Orderspicklist.php', false);
//echo "Success";
 
} // /if $_POST
// echo json_encode($valid);