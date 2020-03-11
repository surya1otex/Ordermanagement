<?php ob_start();
//$con = mysqli_connect('localhost','root','','inventory');
$page_title = 'Add Sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $customers = find_all('customers');
   $salespersons = find_all('suppliers');
   $orderId = $_GET['id'];
   

if($_POST) {	
  $orderId = $orderId;
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
				$orderItemSql = "INSERT INTO order_item (order_id, product_id, quantity, boxes, rate, total, order_item_status) 
				VALUES ({$orderId}, '".$_POST['productName'][$x]."', '".$_POST['quantity'][$x]."', '".$_POST['boxes'][$x]."', '".$_POST['rateValue'][$x]."', '".$_POST['totalValue'][$x]."', 1)";

				$db->query($orderItemSql);		
			} // while	
		} // /for quantity
	}

$session->msg('s',"Sales Order Updated successfully. ");	
redirect('orderDetails.php', false);
}










?>
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">!-->
<?php include_once('layouts/header.php'); ?>
<div class="row">

  <div class="col-md-12">
   <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>View Order Details</span>
       </strong>
      </div>
	 <!--<button onclick="printOrder(<?php echo $orderId; ?>)">Click me</button>!-->
      <div class="panel-body form-horizontal" id="pdf">
  		<form class="form-horizontal" method="POST" action="editorders.php" id="editOrderForm">

  			<?php //$orderId = $_GET['id'];
            //echo $orderId;
  			$sql = "SELECT orders.order_id, orders.order_date, orders.company_name, orders.client_contact, orders.due_date, orders.sub_total, orders.total_amount, orders.discount, orders.grand_total, orders.paid, orders.due, orders.payment_type, orders.payment_status, orders.order_status FROM orders 	
					WHERE orders.order_id = {$orderId}";

				$result = $db->query($sql);
				$data = $result->fetch_row();
  			?>
			
			<div class="row">
			  <div class="col-sm-8 col-md-10">
			  <div class="form-group">
			    <label for="orderDate" class="col-sm-3 col-md-2 control-label">Order Number</label>
			    <div class="col-sm-9 col-md-10">
				<p class="control-labels"><?php echo $orderId; ?></p>
			     <!-- <input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" value="<?php echo $data[1] ?>" />!-->
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="orderDate" class="col-sm-3 col-md-2 control-label">Order Date</label>
			    <div class="col-sm-9 col-md-10">
			     <input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" value="<?php echo $data[1] ?>" />
			    </div>
			  </div> <!--/form-group-->
			  <div class="form-group">
			    <label for="clientName" class="col-sm-3 col-md-2 control-label">Company Name</label>
			    <div class="col-sm-9 col-md-10">
			     <input type="text" class="form-control" id="clientName" name="companyName" placeholder="Client Name" autocomplete="off" value="<?php echo $data[2] ?>" />
			    </div>
			  </div> <!--/form-group-->
			  <div class="form-group" style="display:none">
			    <label for="clientContact" class="col-sm-3 col-md-2 control-label">Client Contact</label>
			    <div class="col-sm-9 col-md-10">
			     <input type="text" class="form-control" id="clientContact" name="companyContact" placeholder="Contact Number" autocomplete="off" value="<?php echo $data[3] ?>" />
			    </div>
			  </div> <!--/form-group-->			  
             <div class="form-group">
               <label for="orderDate" class="col-sm-3 col-md-2 control-label">Delivery Date</label>
               <div class="col-sm-9 col-md-10">
                <input type="date" class="form-control" id="orderDate" name="duedate" autocomplete="off" value="<?php echo $data[4] ?>" required/>
            </div>
           </div>
		    </div>
			<div class="col-sm-4 col-md-2">
			<img src="https://forestflavour.com/wp-content/uploads/2019/02/LOGO.svg" class="logo-header">
			</div>
		  </div>
		   
		   
			  <table class="table" id="productTable">
			  	<thead>
			  		<tr>			  			
			  			<th style="width:40%;">Product</th>
			  			<th style="width:20%;">Rate</th>			  			
			  			<th style="width:15%;">Quantity(kg)</th>
                        <th style="width:15%;">Number of Boxes</th>			  			
			  			<th style="width:15%;">Total</th>			  			
			  			<th style="width:10%;"></th>
			  		</tr>
			  	</thead>
			  	<tbody>
			  		<?php

			  		$orderItemSql = "SELECT order_item.order_item_id, order_item.order_id, order_item.product_id, order_item.quantity, order_item.boxes, order_item.rate, order_item.total FROM order_item WHERE order_item.order_id = {$orderId}";
						$orderItemResult = $db->query($orderItemSql);
						// $orderItemData = $orderItemResult->fetch_all();						
						
						// print_r($orderItemData);
			  		$arrayNumber = 0;
			  		// for($x = 1; $x <= count($orderItemData); $x++) {
			  		$x = 1;
			  		while($orderItemData = $orderItemResult->fetch_array()) { 
			  			// print_r($orderItemData); ?>
			  			<tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">			  				
			  				<td style="margin-left:20px;">
							
			  				

			  					<select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)">
			  						<option value="">~~SELECT~~</option>
			  						<?php
			  							$productSql = "SELECT * FROM products WHERE status = 1";
			  							$productData = $db->query($productSql);

			  							while($row = $productData->fetch_array()) {	
                                            print_r($row);								 		
			  								$selected = "";
			  								if($row['id'] == $orderItemData['product_id']) {
			  								$selected = "selected";
			  								} else {
			  									$selected = "";
			  								}
			  								echo "<option value='".$row['id']."' id='changeProduct".$row['id']."' ".$selected." >".$row['name']."</option>";
											} 
										 	 //} // /while 

			  						?>
		  						</select>
			  				
			  				</td>
			  				<td style="padding-left:20px;">							
			  					<input type="text" name="rate[]" id="rate<?php echo $x; ?>" autocomplete="off" disabled="true" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />			  					
			  					<input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />			  					
			  				</td>


			  				<td style="padding-left:20px;">
			  					<div class="form-group">
			  					<input type="text" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" value="<?php echo $orderItemData['quantity']; ?>"/>
			  					</div>
			  				</td>
							<td style="padding-left:20px;">
							<div class="form-group">
							<input type="text" name="boxes[]" id="boxes<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['boxes']; ?>"/>
							</div>
							</td>
			  				<td style="padding-left:20px;">
                                <!--<span class="glyphicon glyphicon-euro"></span>&nbsp;<span><?php echo $orderItemData['total']; ?></span>!-->							
			  					<input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" value="<?php echo $orderItemData['total']; ?>"/>	  					
			  					<input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['total']; ?>"/>			  					
			  				</td>

			  			</tr>
		  			<?php
		  			$arrayNumber++;
		  			$x++;
			  		} // /for
			  		?>
			  	</tbody>			  	
			  </table>

			  <div class="col-md-4 col-md-offset-8 col-sm-offset-5">
			  	<div class="form-group">
				    <label for="subTotal" class="col-sm-8 control-label">Sub Amount</label>
				    <div class="col-sm-4 custom">
					  <!--<span class="glyphicon glyphicon-euro"></span>&nbsp;<span><?php echo $data[5]; ?></span>!-->
				      <input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" value="<?php echo $data[5] ?>" />
				      <input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" value="<?php echo $data[5] ?>" />
				    </div>
				  </div> <!--/form-group-->			  
				  			  
				  <div class="form-group">
				    <label for="totalAmount" class="col-sm-8 control-label">Total Amount</label>
				    <div class="col-sm-4 custom">
					<!--<span class="glyphicon glyphicon-euro"></span>&nbsp;<span><?php echo $data[6]; ?></span>!-->
				      <input type="text" class="form-control" id="totalAmount" name="totalAmount" disabled="true" value="<?php echo $data[6] ?>" />
				      <input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" value="<?php echo $data[6] ?>"  />
				    </div>
				  </div> <!--/form-group-->			  
				  <div class="form-group">
				    <label for="discount" class="col-sm-8 control-label">Discount</label>
				    <div class="col-sm-4 custom">
					<!--<span class="glyphicon glyphicon-euro"></span>&nbsp;<span><?php echo $data[7]; ?></span>!-->
				      <input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" value="<?php echo $data[7] ?>" />
				    </div>
				  </div> <!--/form-group-->	
				  <hr>
				  <div class="form-group">
				    <label for="grandTotal" class="col-sm-8 control-label">Grand Total</label>
				    <div class="col-sm-4 custom">
					<!--<span class="glyphicon glyphicon-euro"></span>&nbsp;<span><?php echo $data[8]; ?></span>!-->
				      <input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" value="<?php echo $data[8] ?>"  />
				      <input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" value="<?php echo $data[8] ?>"  />
				    </div>
				  </div> <!--/form-group-->	
				  <div class="form-group" style="display:none">
				    <label for="vat" class="col-sm-8 control-label gst"><?php if($data[13] == 2) {echo "IGST 18%";} else echo "GST 18%"; ?></label>
				    <div class="col-sm-4">
				      <input type="text" class="form-control" id="vat" name="vat" disabled="true"  />
				      <input type="hidden" class="form-control" id="vatValue" name="vatValue" />
				    </div>
				  </div> 
				  <div class="form-group" style="display:none">
				    <label for="gstn" class="col-sm-8 control-label gst">G.S.T.IN</label>
				    <div class="col-sm-4">
				      <input type="text" class="form-control" id="gstn" name="gstn" value="<?php echo $data[14] ?>"  />
				    </div>
				  </div><!--/form-group-->		  		  
			  </div> <!--/col-md-6-->

			  <div class="col-md-6">
			  	<!--<div class="form-group">
				    <label for="paid" class="col-sm-3 control-label">Paid Amount</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" value="<?php echo $data[9] ?>"  />
				    </div>
				  </div> <!--/form-group-->			  
				 <!-- <div class="form-group">
				    <label for="due" class="col-sm-3 control-label">Due Amount</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="due" name="due" disabled="true" value="<?php echo $data[10] ?>"  />
				      <input type="hidden" class="form-control" id="dueValue" name="dueValue" value="<?php echo $data[10] ?>"  />
				    </div>
				  </div> <!--/form-group-->		
				  <!--<div class="form-group">
				    <label for="clientContact" class="col-sm-3 control-label">Payment Type</label>
				    <div class="col-sm-9">
				      <select class="form-control" name="paymentType" id="paymentType" >
				      	<option value="">~~SELECT~~</option>
				      	<option value="1" <?php if($data[11] == 1) {
				      		echo "selected";
				      	} ?> >Cheque</option>
				      	<option value="2" <?php if($data[11] == 2) {
				      		echo "selected";
				      	} ?>  >Cash</option>
				      	<option value="3" <?php if($data[11] == 3) {
				      		echo "selected";
				      	} ?> >Credit Card</option>
				      </select>
				    </div>
				  </div> <!--/form-group-->							  
				  <!--<div class="form-group">
				    <label for="clientContact" class="col-sm-3 control-label">Payment Status</label>
				    <div class="col-sm-9">
				      <select class="form-control" name="paymentStatus" id="paymentStatus">
				      	<option value="">~~SELECT~~</option>
				      	<option value="1" <?php if($data[12] == 1) {
				      		echo "selected";
				      	} ?>  >Full Payment</option>
				      	<option value="2" <?php if($data[12] == 2) {
				      		echo "selected";
				      	} ?> >Advance Payment</option>
				      	<option value="3" <?php if($data[10] == 3) {
				      		echo "selected";
				      	} ?> >No Payment</option>
				      </select>
				    </div>
				  </div> <!--/form-group-->
				  <!--<div class="form-group">
				    <label for="clientContact" class="col-sm-3 control-label">Payment Place</label>
				    <div class="col-sm-9">
				      <select class="form-control" name="paymentPlace" id="paymentPlace">
				      	<option value="">~~SELECT~~</option>
				      	<option value="1" <?php if($data[13] == 1) {
				      		echo "selected";
				      	} ?>  >In Gujarat</option>
				      	<option value="2" <?php if($data[13] == 2) {
				      		echo "selected";
				      	} ?> >Out Gujarat</option>
				      </select>
				    </div>
				  </div>							  
			  </div> <!--/col-md-6-->


			  <div class="form-group editButtonFooter">
			    <div class="col-sm-10">
			    <!--<button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> Add Row </button>!-->

			    <input type="hidden" name="orderId" id="orderId" value="<?php echo $orderId; ?>" />
                <button onclick="printOrder(<?php echo $orderId; ?>)" class="btn btn-danger">Export PDF</button>
				<button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> Add Row </button>
			    <button type="submit" id="editOrderBtn" data-loading-text="Loading..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
			      
			    </div>
			  </div>
			</form>
<!--         <form method="post" action="add_sale.php">
         <table class="table table-bordered">
           <thead>
            <th> Item </th>
            <th> Price </th>
            <th> Qty </th>
            <th> Total </th>
            <th> Date</th>
            <th> Action</th>
           </thead>
             <tbody  id="product_info"> </tbody>
         </table>
       </form> -->
        </div>
      </div>
      <div class="footer-print" style="display:none">
      	<div class="row">
      		<div class="col-md-3">
      			<div class="footer-print-text">
      				<p>Forest Flavour BV</p>
      				<p>Vosdeel 10</p>
      				<p>5427RK Boekel</p>
      				<p>Nederland</p>
      			</div>
      		</div>
      		<div class="col-md-3">
      			<div class="footer-print-text">
      				<p>+31(0)85 743 01 40</p>
      				<p>info@forestflavour.nl</p>
      				<p>www.forestflavour.nl</p>
      			</div>
      		</div>
      		<div class="col-md-3">
      			<div class="footer-print-text">
      				<div class="row">
      					<div class="col-md-4">
      						<p>Bank</p>
      					</div>
      					<div class="col-md-8">
      						<p>:1554.13.155</p>
      					</div>
      					<div class="col-md-4">
      						<p>Bic</p>
      					</div>
      					<div class="col-md-8">
      						<p>:RABONL2U</p>
      					</div>
      					<div class="col-md-4">
      						<p>IBAN</p>
      					</div>
      					<div class="col-md-8">
      						<p>:NL49RABO0155413155</p>
      					</div>
      					<div class="col-md-4">
      						<p>Btw</p>
      					</div>
      					<div class="col-md-8">
      						<p>:NL853.732.863.B01</p>
      					</div>
      					<div class="col-md-4">
      						<p>Kvk</p>
      					</div>
      					<div class="col-md-8">
      						<p>:60017198</p>
      					</div>
      				</div>
      			</div>
      		</div>
      		<div class="col-md-3">
      			<div class="footer-print-img">
      				<img src="uploads/ISO-CERTIFICATE.png" alt="" title="" />
      			</div>
      		</div>
      	</div>
      </div>
    </div>

  </div>
</div>
  <script>
  //$( function() {
    //$("#orderDate").datepicker();
  //} );
function subAmount() {
  var tableProductLength = $("#productTable tbody tr").length;
  var totalSubAmount = 0;
  for(x = 0; x < tableProductLength; x++) {
    var tr = $("#productTable tbody tr")[x];
    var count = $(tr).attr('id');
    count = count.substring(3);

    totalSubAmount = Number(totalSubAmount) + Number($("#total"+count).val());
  } // /for

  totalSubAmount = totalSubAmount.toFixed(2);

  // sub total
  $("#subTotal").val(totalSubAmount);
  $("#subTotalValue").val(totalSubAmount);

  // vat
  var vat = 0;
  //vat = vat.toFixed(2);
  //$("#vat").val(vat);
  //$("#vatValue").val(vat);

  // total amount
  var totalAmount = (Number($("#subTotal").val()) + Number($("#vat").val()));
  totalAmount = totalAmount.toFixed(2);
  $("#totalAmount").val(totalAmount);
  $("#totalAmountValue").val(totalAmount);

  var discount = $("#discount").val();
  if(discount) {
    var grandTotal = Number($("#totalAmount").val()) - Number(discount);
    grandTotal = grandTotal.toFixed(2);
    $("#grandTotal").val(grandTotal);
    $("#grandTotalValue").val(grandTotal);
  } else {
    $("#grandTotal").val(totalAmount);
    $("#grandTotalValue").val(totalAmount);
  } // /else discount 

  var paidAmount = $("#paid").val();
  if(paidAmount) {
    paidAmount =  Number($("#grandTotal").val()) - Number(paidAmount);
    paidAmount = paidAmount.toFixed(2);
    $("#due").val(paidAmount);
    $("#dueValue").val(paidAmount);
  } else {  
    $("#due").val($("#grandTotal").val());
    $("#dueValue").val($("#grandTotal").val());
  } // else

} // /sub total amount
  </script>

  <script type="text/javascript">
function getProductData(row = null) {
//alert(row);
  if(row) {
    var productId = $("#productName"+row).val();    
    //alert(productId);
    if(productId == "") {
      $("#rate"+row).val("");

      $("#quantity"+row).val("");           
      $("#total"+row).val("");

      // remove check if product name is selected
      // var tableProductLength = $("#productTable tbody tr").length;     
      // for(x = 0; x < tableProductLength; x++) {
      //  var tr = $("#productTable tbody tr")[x];
      //  var count = $(tr).attr('id');
      //  count = count.substring(3);

      //  var productValue = $("#productName"+row).val()

      //  if($("#productName"+count).val() == "") {         
      //    $("#productName"+count).find("#changeProduct"+productId).removeClass('div-hide'); 
      //    console.log("#changeProduct"+count);
      //  }                     
      // } // /for

    } else {
      //alert('This is a alert');
      $.ajax({
        url: 'fetchSelectedProduct.php',
        type: 'post',
        data: {productId : productId},
        dataType: 'json',
        success:function(response) {
          // setting the rate value into the rate input field
          //alert(response);
          $("#rate"+row).val(response.sale_price);
          $("#rateValue"+row).val(response.sale_price);

          $("#quantity"+row).val(1);
          $("#available_quantity"+row).text(response.quantity);

          var total = Number(response.sale_price) * 1;
          total = total.toFixed(2);
          $("#total"+row).val(total);
          $("#totalValue"+row).val(total);
          
          // check if product name is selected
          // var tableProductLength = $("#productTable tbody tr").length;         
          // for(x = 0; x < tableProductLength; x++) {
          //  var tr = $("#productTable tbody tr")[x];
          //  var count = $(tr).attr('id');
          //  count = count.substring(3);

          //  var productValue = $("#productName"+row).val()

          //  if($("#productName"+count).val() != productValue) {
          //    // $("#productName"+count+" #changeProduct"+count).addClass('div-hide');  
          //    $("#productName"+count).find("#changeProduct"+productId).addClass('div-hide');                
          //    console.log("#changeProduct"+count);
          //  }                     
          // } // /for
      
          subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
        
  } else {
    alert('no row! please refresh the page');
  }
} // /select on product data

// Delete order row table 
function removeProductRow(row = null) {
  if(row) {
    $("#row"+row).remove();


    subAmount();
  } else {
    alert('error! Refresh the page again');
  }
}

// table total
function getTotal(row = null) {
  if(row) {
    var total = Number($("#rate"+row).val()) * Number($("#quantity"+row).val());
    total = total.toFixed(2);
    $("#total"+row).val(total);
    $("#totalValue"+row).val(total);
    
    subAmount();

  } else {
    alert('no row !! please refresh the page');
  }
}
// check paid ammount
function paidAmount() {
  var grandTotal = $("#grandTotal").val();

  if(grandTotal) {
    var dueAmount = Number($("#grandTotal").val()) - Number($("#paid").val());
    dueAmount = dueAmount.toFixed(2);
    $("#due").val(dueAmount);
    $("#dueValue").val(dueAmount);
  } // /if
} // /paid amoutn function

function discountFunc() {
  var discount = $("#discount").val();
  var totalAmount = Number($("#totalAmount").val());
  totalAmount = totalAmount.toFixed(2);

  var grandTotal;
  if(totalAmount) {   
    grandTotal = Number($("#totalAmount").val()) - Number($("#discount").val());
    grandTotal = grandTotal.toFixed(2);

    $("#grandTotal").val(grandTotal);
    $("#grandTotalValue").val(grandTotal);
  } else {
  }

  var paid = $("#paid").val();

  var dueAmount;  
  if(paid) {
    dueAmount = Number($("#grandTotal").val()) - Number($("#paid").val());
    dueAmount = dueAmount.toFixed(2);

    $("#due").val(dueAmount);
    $("#dueValue").val(dueAmount);
  } else {
    $("#due").val($("#grandTotal").val());
    $("#dueValue").val($("#grandTotal").val());
  }

} // /discount function

function addRow() {
  $("#addRowBtn").button("loading");

  var tableLength = $("#productTable tbody tr").length;

  var tableRow;
  var arrayNumber;
  var count;

  if(tableLength > 0) {   
    //alert('hii');
    tableRow = $("#productTable tbody tr:last").attr('id');
    arrayNumber = $("#productTable tbody tr:last").attr('class');
    count = tableRow.substring(3);  
    count = Number(count) + 1;
    arrayNumber = Number(arrayNumber) + 1;          
  } else {
    // no table row
    count = 1;
    arrayNumber = 0;
  }
  //alert('Welcome');
  $.ajax({
    url: 'fetchProductData.php',
    type: 'post',
    dataType: 'json',
    success:function(response) {
      //alert(response);
      $("#addRowBtn").button("reset");      

      var tr = '<tr id="row'+count+'" class="'+arrayNumber+'">'+                
        '<td>'+
          '<div class="form-group">'+

          '<select class="form-control js-example-basic-sample" name="productName[]" id="productName'+count+'" onchange="getProductData('+count+')" >'+
            '<option value="">~~SELECT~~</option>';
            // console.log(response);
            $.each(response, function(index, value) {
              tr += '<option value="'+value[0]+'">'+value[1]+'</option>';             
            });
                          
          tr += '</select>'+
          '</div>'+
        '</td>'+
        '<td style="padding-left:20px;"">'+
         '<div class="input-group">'+
		 '<span class="input-group-addon">'+
		 '<i class="glyphicon glyphicon-euro" aria-hidden="true"></i>'+
		 '</span>'+
          '<input type="text" name="rate[]" id="rate'+count+'" autocomplete="off" disabled="true" class="form-control" />'+
          '<input type="hidden" name="rateValue[]" id="rateValue'+count+'" autocomplete="off" class="form-control" />'+
		  '</div>'+
        '</td style="padding-left:20px;">'+
        '<td style="padding-left:20px;">'+
          '<div class="form-group">'+
          '<input type="number" name="quantity[]" id="quantity'+count+'" onkeyup="getTotal('+count+')" autocomplete="off" class="form-control" placeholder="Kg" min="1" />'+
          '</div>'+
        '</td>'+
		'<td style="padding-left:20px;">'+
		'<div class="form-group">'+
		'<input type="text" name="boxes[]" autocomplete="off" class="form-control">'+
		'</div>'+
		'</td>'+
        '<td style="padding-left:20px;">'+
          '<input type="text" name="total[]" id="total'+count+'" autocomplete="off" class="form-control" disabled="true" />'+
          '<input type="hidden" name="totalValue[]" id="totalValue'+count+'" autocomplete="off" class="form-control" />'+
        '</td>'+
        '<td>'+
          '<button class="btn btn-default removeProductRowBtn" type="button" onclick="removeProductRow('+count+')"><i class="glyphicon glyphicon-trash"></i></button>'+
        '</td>'+
      '</tr>';
      if(tableLength > 0) {             
        $("#productTable tbody tr:last").after(tr);
      } else { 
             
        $("#productTable tbody").append(tr);
		
      }  
      //location.reload();	  
      //$('.js-example-basic-sample').select2(); 
    } // /success
	
  }); // get the product data
 $('.js-example-basic-sample').select2(); 
} // /add row


  </script>
<script type="text/javascript">
function printOrder(orderId = null) {
	if(orderId) {		
			
		$.ajax({
			url: 'printorder.php',
			type: 'post',
			data: {id: orderId},
			dataType: 'text',
			success:function(response) {
			//alert(response);
		var mywindow = window.open('', 'Stock Management System', 'height=400,width=600');

		$(mywindow.document.head).html('<html><head><title>Order Invoice</title>');
		$(mywindow.document.head).html('</head>');
		$(mywindow.document.body).html( '<body>' + response + '</body>');
		mywindow.document.close();
        mywindow.focus(); // necessary for IE >= 10
        mywindow.print();
        mywindow.close();
		
		
        //mywindow.document.write('<html><head><title>Order Invoice</title>');        
        //mywindow.document.write('</head><body>');
        //mywindow.document.write(response);
        //mywindow.document.write('</body></html>');

        //mywindow.document.close(); // necessary for IE >= 10
        //mywindow.focus(); // necessary for IE >= 10
		//mywindow.print();
		//mywindow.close();
        //mywindow.resizeTo(screen.width, screen.height);
        //setTimeout(function() {
        //mywindow.print();
        //mywindow.close();
        //}, 1250);

        //mywindow.print();
        //mywindow.close();
				
			}// /success function
		}); // /ajax function to fetch the printable order
	} // /if orderId
} // /print order function
</script>

<style>
.form-horizontal .form-group {
	margin-right:0px !important;
}
.control-labels {
	padding-top:7px;
}
hr {
	border-top: 1px solid #c5c5c5 !important;
}
.glyphicon {
	top:2px !important;
}
.custom {
	padding-top:7px;
}
</style>
<?php include_once('layouts/footer.php'); ?>
