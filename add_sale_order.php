<?php ob_start();
$con = mysqli_connect('localhost','root','','inventory');
$page_title = 'Add Sale';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
   $customers = find_all('customers');
   $salespersons = find_all('suppliers');
          $mtime = time(); 
		  $currentDate = date('m/d/Y', $mtime);


  if($_POST){
  
    //$req_fields = array('orderDate','customer','sales','duedate', 'productName' );
    //validate_fields($req_fields);
          
		  if(!isset($_POST['productName'])) {
		  $session->msg('d',"Order Can't be Placed without Item ");
          redirect('add_sale_order.php', false);  
		  }
		  
		  else {
		  
          $orderDate = date('Y-m-d', strtotime($_POST['orderDate']));
          $custmername = $db->escape($_POST['customer']);
          //$clientContact = $db->escape($_POST['clientContact']);
          $salesperson = $db->escape($_POST['sales']);
          $duedate     = date('Y-m-d', strtotime($_POST['duedate']));
          $subTotalValue        = $_POST['subTotalValue'];
          $vatValue             = $_POST['vatValue'];
          $totalAmountValue     = $_POST['totalAmountValue'];
          $discount             = $_POST['discount'];
          $grandTotalValue      = $_POST['grandTotalValue'];
          $paid                 = $_POST['paid'];
          $dueValue             = $_POST['dueValue'];
          $paymentType          = $_POST['paymentType'];
          $paymentStatus        = $_POST['paymentStatus'];
          $paymentPlace         = $_POST['paymentPlace'];
		  $shipadress           = $_POST['shipaddress'];
          $gstn         = $_POST['gstn'];        
        
  $sql = "INSERT INTO orders (order_date, company_name, sub_total, due_date, total_amount, discount, grand_total,ship_address, order_status) VALUES ('$orderDate', '$custmername',
   '$subTotalValue', '$duedate', '$totalAmountValue', '$discount', '$grandTotalValue','$shipadress', 1)";

    //$order_id;
    $orderStatus = false;

                if($db->query($sql)){
                //$last_id = mysqli_insert_id($con);
                $order_id = $db->insert_id();
                $orderStatus = true;
                  //update_product_qty($s_qty,$p_id);
                  //$session->msg('s',"Sale added. ");
                  //redirect('add_sale.php', false);
                } 
      $orderItemStatus = false;

      for($x = 0; $x < count($_POST['productName']); $x++) {      
    $updateProductQuantitySql = "SELECT products.quantity FROM products WHERE products.id = ".$_POST['productName'][$x]."";
    $updateProductQuantityData = $db->query($updateProductQuantitySql);
    
    
    while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
      $updateQuantity[$x] = $updateProductQuantityResult[0] - $_POST['quantity'][$x];             
        // update product table
        $updateProductTable = "UPDATE products SET quantity = '".$updateQuantity[$x]."' WHERE id = ".$_POST['productName'][$x]."";
        $db->query($updateProductTable);

        // add into order_item
        $orderItemSql = "INSERT INTO order_item (order_id, sku, country, product_id, quantity, tax, rate, total, order_item_status) 
        VALUES ('$order_id', '".$_POST['skus'][$x]."', '".$_POST['country'][$x]."', '".$_POST['productName'][$x]."', '".$_POST['quantity'][$x]."', '".$_POST['taxes'][$x]."', '".$_POST['rateValue'][$x]."', '".$_POST['totalValue'][$x]."', 1)";

        $db->query($orderItemSql);   

        if($x == count($_POST['productName'])) {
          $orderItemStatus = true;
        } 


    } 
  } 
  $session->msg('s',"Sales Order added successfully. ");
  redirect('add_sale_order.php', false);
		  }
  }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">

  <div class="col-md-12">
   <?php echo display_msg($msg); ?>
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>New Sales Order</span>
       </strong>
      </div>
      <div class="panel-body">
<form class="form-horizontal" method="post" action="add_sale_order.php" autocomplete="off">
<div class="form-group">
          <label for="orderDate" class="col-sm-2 control-label">Order Date</label>
          <div class="col-sm-8">
          <input type="text" class="form-control" id="orderDate" name="orderDate" value="<?php echo $currentDate; ?>" required/>
          </div>
        </div>
        <div class="form-group">
          <label for="orderDate" class="col-sm-2 control-label">Company Name</label>
          <div class="col-sm-8">
            <select class="form-control js-example-basic-single" name="customer" onchange="getphone(this.value)" required>
              <option value="">Select Company Name</option>
                    <?php  foreach ($customers as $customer): ?>
                      <option value="<?php echo $customer['company_name'] ?>">
                        <?php echo $customer['company_name'] ?></option>
                    <?php endforeach; ?> 
            </select>  
          </div>
        </div>
		<!--<div class="form-group">
          <label for="orderDate" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-8">
          <input type="text" class="form-control" id="contactemail" name="clientContact" autocomplete="off" required/> 
          </div>
        </div>!-->
<!--<div class="form-group">
          <label for="orderDate" class="col-sm-2 control-label">Salesperson</label>
          <div class="col-sm-8">
            <select class="form-control" name="sales" required>
              <option value="">Select Salesperson</option>
                    <?php  foreach ($salespersons as $sales): ?>
                      <option value="<?php echo (int)$sales['id'] ?>">
                        <?php echo $sales['supp_name'] ?></option>
                    <?php endforeach; ?> 
            </select>
          </div>
        </div>!-->
<div class="form-group">
          <label for="orderDate" class="col-sm-2 control-label">Delivery Date</label>
          <div class="col-sm-8">
          <input type="text" class="form-control" id="dueDate" name="duedate" autocomplete="off" required/>
          </div>
        </div>
        <table class="table" id="productTable">
          <thead>
            <tr> 
              <th >ID</th>			
              <th >Product</th>
              <th >Rate</th>
			  <th >Origin</th>
              <th >Quantity</th>
			  <th> Tax</th>
	  
              <th >Total</th>             
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php
            $arrayNumber = 0;
            for($x = 1; $x < 4; $x++) { ?>
              <tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">
                <td>
                  <div class="form-group">
                  <select class="form-control" style="width:120px;" name="skus[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" required>
                    <option value="">ID</option>
                    <?php
                      $productSql = "SELECT * FROM products ORDER BY name ASC";
                      $productData = $db->query($productSql);

                      while($row = $productData->fetch_array()) {                     
                        echo "<option value='".$row['prod_SKU']."' id='changeProduct".$row['id']."'>".$row['prod_SKU']."</option>";
                      } // /while 

                    ?>
                  </select>
				  
				  
				  
                  </div>
                </td>			  
                <td>
                  <div class="form-group">
                  <select class="form-control js-example-basic-single myselect<?php echo $x; ?>" name="productName[]" id="productNames<?php echo $x; ?>" onchange="getProductDatatwo(<?php echo $x; ?>)" required>
                    <option value="">~~Product~~</option>
                    <?php
                      $productSql = "SELECT * FROM products ORDER BY name ASC";
                      $productData = $db->query($productSql);

                      while($row = $productData->fetch_array()) {                     
                        echo "<option value='".$row['id']."' id='changeProduct".$row['id']."'>".$row['name'].'-'.$row['prod_SKU']."</option>";
                      } // /while 

                    ?>
                  </select>
				  
				  
				  
                  </div>
                </td>
                <td>
                     <div class="input-group">
                     <span class="input-group-addon">
                       <i class="glyphicon glyphicon-euro" aria-hidden="true"></i>
                     </span>                
                  <input type="text" name="rate[]" id="rate<?php echo $x; ?>" onkeyup="creatediscount(<?php echo $x ?>)" autocomplete="off" class="form-control" />                  
                  <input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" />
				  
                     </div>				  
                </td>
              <td>
                  <div class="form-group">
                  <!--<p id="countrycode<?php echo $x; ?>"></p>!-->
				  <input type="text" name="country[]" class="form-control" id="country<?php echo $x; ?>" />
                  </div>
                </td>
                <td>
                  <div class="form-group mr-2">
                  <input type="text" name="quantity[]" id="quantity<?php echo $x; ?>"  onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" placeholder="Kg" required/>
                  </div>
                </td>

				<td>
				<div class="form-group">
				<select name="taxes[]" id="boxName<?php echo $x; ?>" class="form-control" style="width:77px;">
                <option value="">Tax</option>
                <option value="0">0%</option>
				<option value="9">9%</option>
				<option value="24">24%</option>
                </select>				
				<!--<input type="text" name="boxes[]" autocomplete="off" class="form-control">!-->
				</div>
				</td>				
				

                <td>
                  <input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" />                  
                  <input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" />                  
                </td>
                <td>

                  <button class="btn btn-default removeProductRowBtn" type="button" id="removeProductRowBtn" onclick="removeProductRow(<?php echo $x; ?>)"><i class="glyphicon glyphicon-trash"></i></button>
                </td>
              </tr>
            <?php
            $arrayNumber++;
            } // /for
            ?>
          </tbody>          
        </table>
        <div class="col-md-6">
          <div class="form-group" style="display:none">
            <label for="subTotal" class="col-sm-3 control-label">Sub Amount</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" />
              <input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" />
            </div>
          </div> <!--/form-group-->       
           <!--/form-group-->       
         <div class="form-group">
            <label for="totalAmount" class="col-sm-3 control-label">Total Amount</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="totalAmount" name="totalAmount" disabled="true"/>
              <input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" />
            </div>
          </div> <!--/form-group-->       
          <div class="form-group" style="display:none">
            <label for="discount" class="col-sm-3 control-label">Tax(%)</label>
            <div class="col-sm-9">
			<select name="tax" class="form-control">
			<option value="">~~Select~~</option>
			<option value="">0</option>
			<option value="">9</option>
			<option value="">21</option>
			</select>
              <!--<input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" />!-->
            </div>
          </div> <!--/form-group--> 
          <div class="form-group">
            <label for="grandTotal" class="col-sm-3 control-label">Grand Total</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" />
              <input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" />
            </div>
          </div> <!--/form-group--> 
          <!--<div class="form-group">
            <label for="clientContact" class="col-sm-3 control-label">Payment Status</label>
            <div class="col-sm-9">
              <select class="form-control" name="paymentStatus" id="paymentStatus" required>
                <option value="">~~SELECT~~</option>
                <option value="1">Full Payment</option>
                <option value="2">Advance Payment</option>
                <option value="3">No Payment</option>
              </select>
            </div>
          </div>!-->
          <div class="form-group" style="display:none">
            <label for="vat" class="col-sm-3 control-label gst">GST 18%</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="vat" name="gstn" readonly="true" />
              <input type="hidden" class="form-control" id="vatValue" name="vatValue" />
            </div>
          </div>          
        </div> <!--/col-md-6-->
        <div class="col-md-6">
          <!--<div class="form-group">
            <label for="paid" class="col-sm-3 control-label">Paid Amount</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" required/>
            </div>
          </div> <!--/form-group-->       
          <!--<div class="form-group">
            <label for="due" class="col-sm-3 control-label">Due Amount</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" id="due" name="due" disabled="true" />
              <input type="hidden" class="form-control" id="dueValue" name="dueValue" />
            </div>
          </div> <!--/form-group-->   
         <!--<div class="form-group">
            <label for="clientContact" class="col-sm-3 control-label">Payment Type</label>
            <div class="col-sm-9">
              <select class="form-control" name="paymentType" id="paymentType" required>
                <option value="">~~SELECT~~</option>
                <option value="1">Cheque</option>
                <option value="2">Cash</option>
                <option value="3">Credit Card</option>
              </select>
            </div>
          </div>         
          <div class="form-group">
            <label for="clientContact" class="col-sm-3 control-label">Payment Status</label>
            <div class="col-sm-9">
              <select class="form-control" name="paymentStatus" id="paymentStatus" required>
                <option value="">~~SELECT~~</option>
                <option value="1">Full Payment</option>
                <option value="2">Advance Payment</option>
                <option value="3">No Payment</option>
              </select>
            </div>
          </div>

		  <!--/form-group-->
          <!--<div class="form-group">
            <label for="clientContact" class="col-sm-3 control-label">Payment Place</label>
            <div class="col-sm-9">
              <select class="form-control" name="paymentPlace" id="paymentPlace">
                <option value="">~~SELECT~~</option>
                <option value="1">In Gujarat</option>
                <option value="2">Out Of Gujarat</option>
              </select>
            </div>
          </div> <!--/form-group-->               
        </div> <!--/col-md-6-->
        <div class="form-group submitButtonFooter">
          <div class="col-sm-10">
		  <input type="hidden" name="shipaddress" id="shipaddress">
           <button type="button" class="btn btn-default" onclick="addRow(event)" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> Add Row </button>
           <button type="submit" name="placeorder" id="createOrderBtn" data-loading-text="Loading..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Place Order</button>
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
<script type="text/javascript">
function getphone(val) {
	var custID = val;
	//alert(custID);
	      $.ajax({
        url: 'fetchcontact.php',
        type: 'post',
        data: {customerId : custID},
        dataType: 'json',
        success:function(response) {
			//alert(response);
			//$("#contactnum").val(response.phone);
			$("#shipaddress").val(response.shipping);
		}
		  });
}
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
  //preventDefault();
//alert(row);
  if(row) {
	  //alert(row);
    var productId = $("#productName"+row).val();    
    //alert(productId);
    if(productId == "") {
      $("#rate"+row).val("");

      $("#quantity"+row).val("");           
      $("#total"+row).val("");


    } else {
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
		  //$("#countrycode"+row).val(response.country);
		  $("#country"+row).val(response.country);

	      //$(".myselect"+row).select2('val', [response.id]);
		  $("#productNames"+row).select2('val', [response.id]);
          //$("#productName"+row).val('').trigger("change");
          var total = Number(response.sale_price) * 1;
		  //alert(total);
          total = total.toFixed(2);
          $("#total"+row).val(total);
          $("#totalValue"+row).val(total);
          
          subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
    }
        
  }
  else {
    alert('no row! please refresh the page');
  }
} // /select on product data




function getBoxData(row = null) {
	if(row) {
	var boxId = $("#boxName"+row).val();
	
    if(boxId == "") {
      $("#rate"+row).val("");

      $("#quantity"+row).val("");           
      $("#total"+row).val("");
	  
    } else {
      //alert('This is a alert');
      $.ajax({
        url: 'fetchboxdetails.php',
        type: 'post',
        data: {boxid : boxId},
        dataType: 'json',
        success:function(response) {
		//alert(response.price);
		var discountprice = $("#discounttotal"+row).val();
		//alert(discountprice);
		//var boxprice = $("#boxpricerow"+row).val(response.price);
		$("#boxes"+row).val(1);
		var countbox = $("#boxes"+row).val();
		var boxprice = response.price;
		$("#boxpricerow"+row).val(boxprice);
		//alert(boxprice);
          // setting the rate value into the rate input field
          //alert(response);
          //$("#rate"+row).val(response.sale_price);
          //$("#rateValue"+row).val(response.sale_price);

          //$("#quantity"+row).val(1);
          //$("#available_quantity"+row).text(response.quantity);
		  
		  var mainrate = $("#rate"+row).val();
		  
		  var checkbsequan = $("#quantity"+row).val();
		  
		  if(checkbsequan > 1) {
		  
		  
		  var totalratewithquan = $("#ratewithquan"+row).val();
		  $("#overallprice"+row).val(totalratewithquan);
		  //alert(totalratewithquan);
		  
		  }
		  else {
			
			var totalratewithquan = $("#rate"+row).val();
			
			
 			 
		  }
		  //alert(totalratewithquan);
		  if(totalratewithquan) {
			  
			  
			  
			 var total = Number(totalratewithquan) + Number(countbox) * Number(boxprice);
			 
			 total = total.toFixed(2);
			 //alert(total);
			 $("#totalwithbox"+row).val(total);
             $("#total"+row).val(total);
             $("#totalValue"+row).val(total);
			 
			 
			 

		  }
        
         else {		
		  
          var total = Number(countbox) * Number(boxprice);
          //var total = Number(response.sale_price) * 1;
          total = total.toFixed(2);
          $("#total"+row).val(total);
          $("#totalValue"+row).val(total);
		  
		 }
		  
          subAmount();
        } // /success
      }); // /ajax function to fetch the product data 
     }
	}
	else {
    alert('no row! please refresh the page');
  }
}

function conutboxprice(row = null) {
	if(row) {
		
	var boxprice = $("#boxpricerow"+row).val();
	//alert(boxprice);
	var boxquan  = $("#boxes"+row).val();
	var totalboxprice = Number(boxprice) * Number(boxquan);
	//alert(totalboxprice);
	//totalboxprice.toFixed(2);
	
	var totalpricesbyquan = $("#overallprice"+row).val();
	
	if(totalpricesbyquan) {
		
		var total = Number(totalpricesbyquan) + Number(totalboxprice);
		total = total.toFixed(2);
        $("#total"+row).val(total);
        $("#totalValue"+row).val(total);
	}
	else {
	var total = Number($("#rate"+row).val()) + Number(totalboxprice);	
	total = total.toFixed(2);
	//alert(total);
    $("#total"+row).val(total);
    $("#totalValue"+row).val(total);
	}
	
	subAmount();
	}
    else {
    alert('no row !! please refresh the page');
  }
}

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
	   
	   $("#ratewithquan"+row).val(total);
       $("#total"+row).val(total);
       $("#totalValue"+row).val(total);
    //}
    subAmount();

  } else {
    alert('no row !! please refresh the page');
  }
}
// edit price
function creatediscount(row = null) {
	if(row) {

    var quantity = Number($("#quantity"+row).val());
	
	if(quantity) {
		var newprice = Number($("#rate"+row).val());
		newprice = newprice.toFixed(2);
		alert(newprice);
		var total = Number($("#rate"+row).val()) * quantity;
		$("#rateValue"+row).val(newprice);
		total = total.toFixed(2);
	   $("#total"+row).val(total);
       $("#totalValue"+row).val(total);
	}
    else {	
	var total = Number($("#rate"+row).val());
		total = total.toFixed(2);
	   $("#total"+row).val(total);
       $("#totalValue"+row).val(total);
}
    subAmount();	
  } else {
    alert('no row !! please refresh the page');
  }		
				
}
// discount price
function getTotalDiscount(row = null) {
  if(row) {
    var total = Number($("#rate"+row).val()) - Number($("#discount"+row).val())* Number($("#quantity"+row).val());
    total = total.toFixed(2);
    $("#total"+row).val(total);
	$("#discounttotal"+row).val(total);
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
function addRow(event) {
  
   //$('.js-example-basic-samples').select2(); 
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
		'<select class="form-control" name="skus[]" id="productName'+count+'"  onchange="getProductData('+count+')" required>'+
        '<option value="">ID</option>';
            $.each(response, function(index, value) {
              tr += '<option value="'+value[2]+'">'+value[2]+'</option>';             
            });
          tr += '</select>'+
          '</div>'+
        '</td>'+			
        '<td>'+
          '<div class="form-group">'+

          '<select class="form-control js-example-basic-samples" name="productName[]" id="productNames'+count+'" onchange="getProductDatatwo('+count+')" >'+
            '<option value="">~~Product~~</option>';
            // console.log(response);
            $.each(response, function(index, value) {
              tr += '<option value="'+value[0]+'">'+value[1]+'</option>';             
            });
                          
          tr += '</select>'+
          '</div>'+
        '</td>'+
        '<td>'+
         '<div class="input-group">'+
		 '<span class="input-group-addon">'+
		 '<i class="glyphicon glyphicon-euro" aria-hidden="true"></i>'+
		 '</span>'+
          '<input type="text" name="rate[]" id="rate'+count+'" autocomplete="off" disabled="true" class="form-control" />'+
          '<input type="hidden" name="rateValue[]" id="rateValue'+count+'" autocomplete="off" class="form-control" />'+
		  '</div>'+
		  
        '</td >'+
		'<td>'+
		'<div class="form-group">'+
		'<input type="text" name="country[]" class="form-control" id="country'+count+'"  />'+
		'</div>'+
		'</td>'+
        '<td >'+
          '<div class="form-group">'+
          '<input type="number" name="quantity[]" id="quantity'+count+'" onkeyup="getTotal('+count+')" autocomplete="off" class="form-control" placeholder="Kg" min="1" />'+
          '</div>'+
        '</td>'+
		
		'<td>'+
		'<div class="form-group">'+
		'<select name="taxes[]" id="boxName'+count+'" class="form-control">'+
		'<option value="">Tax</option>'+
		'<option value="0">0%</option>'+
		'<option value="9">9%</option>'+
		'<option value="24">24%</option>'+
		'</select>'+
		'</div>'+
		'</td>'+
        '<td >'+
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
	  event.preventDefault();

	  
	  $('.js-example-basic-singles').select2();
	  
	  $('.js-example-basic-singles').removeClass('js-example-basic-singles');
	  
      $('.js-example-basic-samples').select2();
    
	  $('.js-example-basic-samples').removeClass('js-example-basic-samples');
     
	 
    } // /success
	
  }); // get the product data
 $('.js-example-basic-sample').select2(); 
} // /add row
  </script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script type="text/javascript">
  $(document).ready(function(e) {

    $('.js-example-basic-single').select2();
});
</script>
  <script type="text/javascript">
  //$.noConflict();
  $(document).ready(function() {
    $('.js-example-basic-samples').select2();
});
</script>
  <script>
  $( function() {
    $( "#orderDate" ).datepicker().on('changeDate', function (e) {
    $(this).datepicker('hide');
});
  } );
  </script>
  <script>
  $( function() {
    $( "#dueDate" ).datepicker().on('changeDate', function (e) {
    $(this).datepicker('hide');
   });
  } );
  </script>

<script>
  $(function() {
    function log( message ) {
      $( "<div>" ).text( message ).prependTo( "#log" );
      $( "#log" ).scrollTop( 0 );
    }

    $( "#search-box" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "autocomplete.php",
          dataType: "jsonp",
          data: {
            q: request.term
          },
          success: function( data ) {
            response( data );
          }
        });
      },
      minLength: 3,
      select: function( event, ui ) {
        log( ui.item ?
          "Selected: " + ui.item.label :
          "Nothing selected, input was " + this.value);
      },
      open: function() {
        $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
      },
      close: function() {
        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
      }
    });
  });
</script>
<style>
.form-horizontal .form-group {
	margin-right:0px !important;
}

.js-example-basic-single {
	width:100% !important;
}
.select2-dropdown.select2-dropdown--above{
width:100px!important;
}
.select2-container .select2-selection--single{
height: 35px!important;
border: 1px solid #ccc!important;
}
</style>

<?php include_once('layouts/footer.php'); ?>
<script type="text/javascript">

function getProductDatatwo(row = null) {
	//$.noConflict();
  //$.noConflict();
  //preventDefault();
//alert('hii');
//alert(row);
  if(row) {
	  //alert(row);
    var productId = $("#productNames"+row).val();    
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
        url: 'fetchSelectedProducts.php',
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
		  //$("#countrycode"+row).text(response.country);
		  $("#country"+row).val(response.country);
		  
		  $("#productName"+row).val(response.prod_SKU);
		  
		  //$(".myselect"+row).select2('val', ['']);
		  //$("#productName"+row).val(response.prod_SKU).trigger("change");
		  
          //$("#productNames"+row).select2('val', [response.id]);
		  //$("#productName"+row).select2('val', [response.prod_SKU]);


          var total = Number(response.sale_price) * 1;
		  //alert(total);
          total = total.toFixed(2);
          $("#total"+row).val(total);
          $("#totalValue"+row).val(total);
          var skuid = response.prod_SKU;
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
          //$("#productName"+row).select2('val', [response.prod_SKU]);
          subAmount();
          //$("#productName"+row).select2('val', [response.id]);
        } // /success
		//$("#productName"+row).select2('val', [skuid]);
		//$("#productName"+row).select2('val', [skuid]);
      }); 
      //$("#productName"+row).select2('val', [response.prod_SKU]);
      // /ajax function to fetch the product data 
    }
        
  }
  else {
    alert('no row! please refresh the page');
  }

} // /select on product data

</script>
