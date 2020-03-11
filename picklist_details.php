<?php ob_start();
$con = mysqli_connect('localhost','root','','inventory');
$page_title = 'View order Picklist';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
   $customers = find_all('customers');
   $salespersons = find_all('suppliers');
   $orderId = $_GET['id'];
   



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
          <span>View Picklist Details</span>
       </strong>
      </div>
      <div class="panel-body form-horizontal" id="pdf">
 
  			<?php //$orderId = $_GET['id'];
            //echo $orderId;
  			$sql = "SELECT orders.order_id, orders.order_date, orders.company_name, orders.client_contact, orders.due_date, orders.sub_total,orders.order_status, orders.total_amount, orders.discount, orders.grand_total, orders.paid, orders.due, orders.payment_type, orders.payment_status,orders.ship_address FROM orders 	
					WHERE orders.order_id = {$orderId}";

				$result = $db->query($sql);
				$data = $result->fetch_array();
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
				<p class="control-labels"><?php echo $data[1] ?></p>
			     <!-- <input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" value="<?php echo $data[1] ?>" />!-->
			    </div>
			  </div> <!--/form-group-->
			  <div class="form-group">
			    <label for="clientName" class="col-sm-3 col-md-2 control-label">Company Name</label>
			    <div class="col-sm-9 col-md-10">
				<p class="control-labels"><?php echo $data[2] ?></p>
			      <!--<input type="text" class="form-control" id="clientName" name="clientName" placeholder="Client Name" autocomplete="off" value="<?php echo $data[2] ?>" />!-->
			    </div>
			  </div> <!--/form-group-->
			  <div class="form-group" style="display:none">
			    <label for="clientContact" class="col-sm-3 col-md-2 control-label">Contact</label>
			    <div class="col-sm-9 col-md-10">
				<p class="control-labels"><?php echo $data[3] ?></p>
			      <!--<input type="text" class="form-control" id="clientContact" name="clientContact" placeholder="Contact Number" autocomplete="off" value="<?php echo $data[3] ?>" />!-->
			    </div>
			  </div> <!--/form-group-->			  
             <div class="form-group">
               <label for="orderDate" class="col-sm-3 col-md-2 control-label">Due Date</label>
               <div class="col-sm-9 col-md-10">
			   <p class="control-labels"><?php echo $data[4] ?></p>
                <!--<input type="date" class="form-control" id="orderDate" name="duedate" autocomplete="off" value="<?php echo $data[4] ?>" required/>!-->
            </div>
           </div>
             <div class="form-group">
               <label for="orderDate" class="col-sm-3 col-md-2 control-label">Shipping Address</label>
               <div class="col-sm-9 col-md-10">
			   <p class="control-labels"><?php echo $data['ship_address'] ?></p>
                <!--<input type="date" class="form-control" id="orderDate" name="duedate" autocomplete="off" value="<?php echo $data[4] ?>" required/>!-->
            </div>
           </div>		   
		 </div>
	</div>
		   
		    <form method="POST" action="updatepicklist.php">
			  <table class="table" id="productTable">
			  	<thead>
			  		<tr>
                        <th>ID</th>		  			
			  			<th>Product</th>
						<th>Origin</th>		  			
			  			<th>Quantity</th>
						<th>Adjust</th>					
                        <th>Status</th>						
			  		</tr>
			  	</thead>
			  	<tbody>
			  		<?php

			  		$orderItemSql = "SELECT order_item.order_item_id, order_item.order_id, order_item.sku, order_item.country, order_item.order_item_status, order_item.product_id, order_item.quantity, order_item.adjust_quan, order_item.boxes, order_item.adjust_box, order_item.rate, order_item.total FROM order_item WHERE order_item.order_id = {$orderId}";
					//$orderItemSql = "SELECT order_item.order_item_id, order_item.order_id, order_item.product_id, order_item.quantity, order_item.boxes, order_item.rate, order_item.total, customers.shipping FROM order_item,orders,customers WHERE
					//orders.company_name = customers.company_name AND order_item.order_id ={$orderId}";
						$orderItemResult = $db->query($orderItemSql);
						// $orderItemData = $orderItemResult->fetch_all();						
						
						// print_r($orderItemData);
			  		$arrayNumber = 0;
			  		// for($x = 1; $x <= count($orderItemData); $x++) {
			  		$x = 1;
			  		while($orderItemData = $orderItemResult->fetch_array()) { 
			  			// print_r($orderItemData); ?>
			  			<tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">

                            <td>
							<div class="form-group">
                             <input type="text" name="skus[]" class="form-control" value="<?php echo $orderItemData['sku']; ?>" disabled>
							 </div>
                            </td>							
			  				<td>
			  					<select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" disabled>
			  						<option value="">~~SELECT~~</option>!-->
			  						<?php
			  							$productSql = "SELECT * FROM products WHERE status = 1";
			  							$productData = $db->query($productSql);
			  							while($row = $productData->fetch_array()) {	
                                            //print_r($row);								 		
			  								$selected = "";
			  								if($row['id'] == $orderItemData['product_id']) {
			  								$selected = "selected";
			  								} else {
			  									$selected = "";
			  								}
			  								echo "<option value='".$row['id']."' id='changeProduct".$row['id']."' ".$selected." >".$row['name']."</option>";
											
											
											} 
                                    $x++;
			  						?>
		  						</select>
			  				</td>
							<td>
							<?php echo $orderItemData['country']; ?>
							</td>


			  				<td>
			  				<div class="form-group">
			  					<input type="text" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" value="<?php echo $orderItemData['quantity']; ?>" disabled/>
			  					</div>
			  				</td>
							
			  				<td>
			  				<div class="form-group">
			  					<input type="text" name="adjustquantity[]" id="quantity<?php echo $x; ?>"  autocomplete="off" class="form-control" value="<?php echo $orderItemData['adjust_quan']; ?>"  />
			  					</div>
			  				</td>														
							
                            <td>
							<?php
							
							$order_item_id = $orderItemData['order_item_id'];
							
							?>
						   <input type="hidden" name="pick_status" id="pick_status" data-id="<?php echo $order_item_id; ?>">
						   
		                   <?php

		                   $ordstatus = $orderItemData['order_item_status'];
						   //echo "<h2>".$ordstatus."</h2>";
		                    ?>
		                   <select class="form-control status" name="status" id="<?php echo $order_item_id; ?>" onchange="updatestatus(this.value,<?php echo $order_item_id; ?>)">

		                   <option value="1" <?=$ordstatus == '1' ? ' selected="selected"' : '';?>>Pending</option>
		                   <option value="2" <?=$ordstatus == '2' ? ' selected="selected"' : '';?>>Picked</option>
		                   </select>			
							</td>
			  			</tr>
		  			<?php
		  			//$arrayNumber++;
		  			//$x++;
			  		} // /for
			  		?>
			  	</tbody>			  	
			  </table>
            

			 <div class="col-md-6">
			   <div class="form-group editButtonFooter">
			    <div class="col-sm-10">
				<input type="hidden" class="form-control" id="subTotal" name="subTotal" value="<?php echo $data[5] ?>" />
                <input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue"  />
			    <input type="hidden" name="orderId" id="orderId" value="<?php echo $orderId; ?>" />

			    <button type="submit" id="editOrderBtn" data-loading-text="Loading..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Update Changes</button>
			      
			    </div>
			  </div>
        </div>
		</form>
      </div>
    </div>

  </div>
</div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  
  <script type="text/javascript">
  jQuery(function ($) {        
  $('form').bind('submit', function () {
    $(this).find('select').prop('disabled', false);
  });
});
</script>
  <script type="text/javascript">
  jQuery(function ($) {        
  $('form').bind('submit', function () {
    $(this).find('input').prop('disabled', false);
  });
});
</script>
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
  //$("#subTotal").val(totalSubAmount);
  $("#subTotalValue").val(totalSubAmount);

  // vat
  var vat = 0;
  //vat = vat.toFixed(2);
  //$("#vat").val(vat);
  //$("#vatValue").val(vat);

  // total amount
  var totalAmount = $("#subTotal").val();
  //alert(totalAmount);
  totalAmount = totalAmount.toFixed(2);
  //alert(totalAmount);
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
	//alert(total);
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
<script type="text/javascript">
function printOrder(orderId = null) {
	if(orderId) {		
			
		$.ajax({
			url: 'printorder.php',
			type: 'post',
			data: {id: orderId},
			dataType: 'text',
			success:function(response) {
				
		var mywindow = window.open('', 'Stock Management System', 'height=400,width=600');
        mywindow.document.write('<html><head><title>Order Invoice</title>');        
        mywindow.document.write('</head><body>');
        mywindow.document.write(response);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10
        mywindow.resizeTo(screen.width, screen.height);
        setTimeout(function() {
        mywindow.print();
        mywindow.close();
        }, 1250);

        //mywindow.print();
        //mywindow.close();
				
			}// /success function
		}); // /ajax function to fetch the printable order
	} // /if orderId
} // /print order function
</script>
<script type="text/javascript">
//$(".status").on('change',function() {
	//var status = val;
	function updatestatus(val,id) {
	var option = val;
    var id = id;
	//var orderid= $(".status").attr('id');
	//alert(option);
	//alert(id);
	    $.ajax({
        url: 'updatestatusid.php',
        type: 'post',
        data: {statusid : option, orderid: id},
        success:function(response) {
          alert(response);
		  location.reload();
		}
	});
}
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
