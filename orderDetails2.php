<?php ob_start();
$con = mysqli_connect('localhost','root','','inventory');
$page_title = 'Add Sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $customers = find_all('customers');
   $salespersons = find_all('suppliers');
   //$orderId = $_GET['id'];
   



?>
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
      <div class="panel-body">
  		<form class="form-horizontal" method="POST" action="php_action/editOrder.php" id="editOrderForm">

  			<?php $orderId = $_GET['id'];
            //echo $orderId;
  			$sql = "SELECT orders.order_id, orders.order_date, orders.company_name, orders.client_contact, orders.due_date, orders.sub_total, orders.total_amount, orders.discount, orders.grand_total, orders.paid, orders.due, orders.payment_type, orders.payment_status,orders.gstn FROM orders 	
					WHERE orders.order_id = {$orderId}";

				$result = $db->query($sql);
				$data = $result->fetch_row();
  			?>
			  <div class="form-group">
			    <label for="orderDate" class="col-sm-2 control-label">Order Number</label>
			    <div class="col-sm-10">
				<p class="control-labels"><?php echo $orderId; ?></p>
			     <!-- <input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" value="<?php echo $data[1] ?>" />!-->
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="orderDate" class="col-sm-2 control-label">Order Date</label>
			    <div class="col-sm-10">
				<p class="control-labels"><?php echo $data[1] ?></p>
			     <!-- <input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" value="<?php echo $data[1] ?>" />!-->
			    </div>
			  </div> <!--/form-group-->
			  <div class="form-group">
			    <label for="clientName" class="col-sm-2 control-label">Company Name</label>
			    <div class="col-sm-10">
				<p class="control-labels"><?php echo $data[2] ?></p>
			      <!--<input type="text" class="form-control" id="clientName" name="clientName" placeholder="Client Name" autocomplete="off" value="<?php echo $data[2] ?>" />!-->
			    </div>
			  </div> <!--/form-group-->
			  <div class="form-group">
			    <label for="clientContact" class="col-sm-2 control-label">Client Contact</label>
			    <div class="col-sm-10">
				<p class="control-labels"><?php echo $data[3] ?></p>
			      <!--<input type="text" class="form-control" id="clientContact" name="clientContact" placeholder="Contact Number" autocomplete="off" value="<?php echo $data[3] ?>" />!-->
			    </div>
			  </div> <!--/form-group-->			  
             <div class="form-group">
               <label for="orderDate" class="col-sm-2 control-label">Due Date</label>
               <div class="col-sm-8">
			   <p class="control-labels"><?php echo $data[4] ?></p>
                <!--<input type="date" class="form-control" id="orderDate" name="duedate" autocomplete="off" value="<?php echo $data[4] ?>" required/>!-->
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
							
			  				<div class="form-group">

			  					<select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" disabled>
			  						<option value="">~~SELECT~~</option>
			  						<?php
			  							$productSql = "SELECT * FROM products WHERE status = 1 AND quantity != 0";
			  							$productData = $db->query($productSql);

			  							while($row = $productData->fetch_array()) {									 		
			  								$selected = "";
			  								if($row['id'] == $orderItemData['product_id']) {
			  									$selected = "selected";
			  								} else {
			  									$selected = "";
			  								}
                                           
			  								echo "<option value='".$row['product_id']."' id='changeProduct".$row['product_id']."' ".$selected." >".$row['name']."</option>";
										 	 } // /while 

			  						?>
		  						</select>
			  					</div>
			  				</td>
			  				<td style="padding-left:20px;">			  					
			  					<input type="text" name="rate[]" id="rate<?php echo $x; ?>" autocomplete="off" disabled="true" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />			  					
			  					<input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />			  					
			  				</td>


			  				<td style="padding-left:20px;">
			  					<div class="form-group">
			  					<input type="text" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" value="<?php echo $orderItemData['quantity']; ?>" disabled/>
			  					</div>
			  				</td>
							<td style="padding-left:20px;">
							<input type="text" name="boxes[]" id="boxes<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['boxes']; ?>" disabled/>
							</td>
			  				<td style="padding-left:20px;">			  					
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

			  <div class="col-md-6">
			  	<div class="form-group">
				    <label for="subTotal" class="col-sm-3 control-label">Sub Amount</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" value="<?php echo $data[5] ?>" />
				      <input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" value="<?php echo $data[5] ?>" />
				    </div>
				  </div> <!--/form-group-->			  
				  			  
				  <div class="form-group">
				    <label for="totalAmount" class="col-sm-3 control-label">Total Amount</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="totalAmount" name="totalAmount" disabled="true" value="<?php echo $data[6] ?>" />
				      <input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" value="<?php echo $data[6] ?>"  />
				    </div>
				  </div> <!--/form-group-->			  
				  <div class="form-group">
				    <label for="discount" class="col-sm-3 control-label">Discount</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" value="<?php echo $data[7] ?>" />
				    </div>
				  </div> <!--/form-group-->	
				  <div class="form-group">
				    <label for="grandTotal" class="col-sm-3 control-label">Grand Total</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" value="<?php echo $data[8] ?>"  />
				      <input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" value="<?php echo $data[8] ?>"  />
				    </div>
				  </div> <!--/form-group-->	
				  <div class="form-group" style="display:none">
				    <label for="vat" class="col-sm-3 control-label gst"><?php if($data[13] == 2) {echo "IGST 18%";} else echo "GST 18%"; ?></label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="vat" name="vat" disabled="true"  />
				      <input type="hidden" class="form-control" id="vatValue" name="vatValue" />
				    </div>
				  </div> 
				  <div class="form-group" style="display:none">
				    <label for="gstn" class="col-sm-3 control-label gst">G.S.T.IN</label>
				    <div class="col-sm-9">
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


			  <div class="form-group editButtonFooter" style="display:none">
			    <div class="col-sm-offset-2 col-sm-10">
			    <button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> Add Row </button>

			    <input type="hidden" name="orderId" id="orderId" value="<?php echo $_GET['i']; ?>" />

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
  </div>

</div>
  <script>
  $( function() {
    $("#orderDate").datepicker();
  } );
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
  </script>
<style>
.form-horizontal .form-group {
	margin-right:0px !important;
}
.control-labels {
	padding-top:7px;
}
</style>
<?php include_once('layouts/footer.php'); ?>
