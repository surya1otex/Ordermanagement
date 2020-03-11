<?php 	

require_once('includes/load.php');

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
	$orderId = $_POST['orderId'];

  $orderDate 						= date('Y-m-d', strtotime($_POST['orderDate']));
  $companyName 					= $_POST['companyName'];
  $clientContact 				= $_POST['companyContact'];
  $duedate                      = date('Y-m-d', strtotime($_POST['duedate']));
  $subTotalValue 				= $_POST['subTotalValue'];
  //$vatValue 						=	$_POST['vatValue'];
  $totalAmountValue     = $_POST['totalAmountValue'];
  $discount 						= $_POST['discount'];
  $grandTotalValue 			= $_POST['grandTotalValue'];
  //$paid 								= $_POST['paid'];
  //$dueValue 						= $_POST['dueValue'];
  //$paymentType 					= $_POST['paymentType'];
  $orderStatus 				= 1;
  //$paymentPlace 				= $_POST['paymentPlace'];
  //$gstn 				= $_POST['gstn'];
	//$userid 				= $_SESSION['userId'];
				
	$sql = "UPDATE orders SET order_date = '$orderDate', company_name = '$companyName', client_contact = '$clientContact', sub_total = '$subTotalValue', total_amount = '$totalAmountValue', discount = '$discount', grand_total = '$grandTotalValue', order_status = '$orderStatus' WHERE order_id = {$orderId}";	
	$db->query($sql);
	
	$readyToUpdateOrderItem = false;
	// add the quantity from the order item to product table
	//$prname = $_POST['productName'];
	//echo $prname;
	for($x = 0; $x < count($_POST['productName']); $x++) {		
		//  product table
		$updateProductQuantitySql = "SELECT products.quantity FROM products WHERE products.id = ".$_POST['productName'][$x]."";
		$updateProductQuantityData = $db->query($updateProductQuantitySql);			
			
		while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
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
				$orderItemSql = "INSERT INTO order_item (order_id, sku, product_id, quantity, country, rate, total, order_item_status) 
				VALUES ({$orderId}, '".$_POST['skus'][$x]."', '".$_POST['productName'][$x]."', '".$_POST['quantity'][$x]."', '".$_POST['country'][$x]."', '".$_POST['rateValue'][$x]."', '".$_POST['totalValue'][$x]."', 1)";

				$db->query($orderItemSql);		
			} // while	
		} // /for quantity
	}

	

	$valid['success'] = true;
	$valid['messages'] = "Successfully Updated";		
	
	//$connect->close();

	//echo json_encode($valid);
	redirect('manage_sales_order.php', false);
 
} // /if $_POST
// echo json_encode($valid);