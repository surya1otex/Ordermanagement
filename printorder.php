
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css" />
<link rel="stylesheet" href="http://enki-erp.nl/libs/css/main.css" />
<?php
error_reporting(0);
ini_set('display_errors', 0);
  require_once('includes/load.php');
   $orderId = $_POST['id'];
     //$orderId = 1;
  			$sql = "SELECT orders.order_id, orders.order_date, orders.company_name, orders.client_contact, orders.due_date, orders.sub_total, orders.total_amount, orders.discount, orders.grand_total, orders.paid, orders.due, orders.payment_type, orders.payment_status,orders.gstn FROM orders 	
					WHERE orders.order_id = {$orderId}";

				$result = $db->query($sql);
				$data = $result->fetch_row();
$produds = "SELECT order_item.product_id, order_item.rate, order_item.quantity, order_item.total, products.name FROM 
order_item INNER JOIN products ON order_item.product_id = products.id WHERE order_item.order_id = {$orderId}";
$result2 = $db->query($produds);
$datanew = $result2->fetch_array();
$printdetails = '
<div class="container-fluid">
<div class="row">

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-body form-horizontal" id="pdf">			
			<div class="row">
			<h3 style="text-align:center">Invoice</h3>
			  <div class="col-sm-8 col-md-10 col-xs-8">
			  <div class="form-group">
			    <label for="orderDate" class="col-xs-4 col-sm-3 col-md-2 control-label">Order Number</label>
			    <div class="col-xs-8 col-sm-9 col-md-10">
				<p class="control-labels">'. $orderId . '</p>
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="orderDate" class="col-xs-4 col-sm-3 col-md-2 control-label">Order Date</label>
			    <div class="col-xs-8 col-sm-9 col-md-10">
				<p class="control-labels">'. $data[1]. '</p>
			    </div>
			  </div> 
			  <div class="form-group">
			    <label for="clientName" class="col-xs-4 col-sm-3 col-md-2 control-label">Company Name</label>
			    <div class="col-xs-8 col-sm-9 col-md-10">
				<p class="control-labels">'. $data[2]. '</p>
			    </div>
			  </div> 
			  <div class="form-group">
			    <label for="clientContact" class="col-xs-4 col-sm-3 col-md-2 control-label">Client Contact</label>
			    <div class="col-xs-8 col-sm-9 col-md-10">
				<p class="control-labels">'.$data[3].'</p>
			    </div>
			  </div>		  
             <div class="form-group">
               <label for="orderDate" class="col-xs-4 col-sm-3 col-md-2 control-label">Due Date</label>
               <div class="col-xs-8 col-sm-9 col-md-10">
			   <p class="control-labels">'. $data[4].'</p>
             </div>
             </div>
		    </div>
			<div class="col-xs-4 col-sm-4 col-md-2">
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
			  	<tbody>';
				
	          $orderItemSql = "SELECT order_item.product_id, order_item.rate, order_item.quantity,order_item.boxes,order_item.total, products.name FROM 
              order_item INNER JOIN products ON order_item.product_id = products.id WHERE order_item.order_id = {$orderId}";
				    $orderItemResult = $db->query($orderItemSql);					
					
			  		while($orderItemData = $orderItemResult->fetch_array()) {
                        //print_r($orderItemData);						
			  			$printdetails2 .= '<tr>
                            <td style="padding-left:20px;">
							<p>'.$orderItemData['name'].'</p>
			  				</td>
			  				<td style="padding-left:20px;">
                                <p>'.'<span><i class="glyphicon glyphicon-euro"></i></span>'. $orderItemData['rate']. '</p>										  					  			  					
			  				</td>
			  				<td style="padding-left:20px;">
			  					<div class="form-group">
								<p>'.$orderItemData['quantity']. '</p>
			  					</div>
			  				</td>
							<td style="padding-left:20px;">
							<div class="form-group">
							<p>'. $orderItemData['boxes']. '</p>
							</div>
							</td>
			  				<td style="padding-left:5px;">
                                <p>'.'<span><i class="glyphicon glyphicon-euro"></i></span>'. $orderItemData['total']. '</p>							  					
			  				</td>
			  			</tr>';
			  		}
			  	 $printdetails3 =  '</tbody>			  	
			  </table>

			  <div class="col-xs-8 col-md-4 pull-right g-total">
			  	<div class="form-group">
				    <label for="subTotal" class="col-xs-8 col-sm-8 control-label">Sub Amount</label>
				    <div class="col-xs-4 col-sm-4">
					  <p>'.'<span><i class="glyphicon glyphicon-euro"></i></span>'. $data[5].'</p>
				    </div>
				  </div> <!--/form-group-->			  
				  			  
				  <div class="form-group">
				    <label for="totalAmount" class="col-xs-8 col-sm-8 control-label">Total Amount</label>
				    <div class="col-xs-4 col-sm-4">
					<p>'.'<span><i class="glyphicon glyphicon-euro"></i></span>'. $data[6]. '</p>
				    </div>
				  </div> 		  
				  <div class="form-group">
				    <label for="discount" class="col-xs-8 col-sm-8 control-label">Discount</label>
				    <div class="col-xs-4 col-sm-4">
					<p>'. '<span><i class="glyphicon glyphicon-euro"></i></span>'. $data[7]. '</p>
				    </div>
				  </div>	
				  <hr style="border-top: 1px solid #ddd;">
				  <div class="form-group">
				    <label for="grandTotal" class="col-xs-8 col-sm-8 control-label">Grand Total</label>
				    <div class="col-xs-4 col-sm-4">
					<p>'. '<span><i class="glyphicon glyphicon-euro"></i></span>'. $data[8]. '</p>
				    </div>
				  </div> 			  		  
			  </div> 
           </div>
        </div>
     </div>
      <div class="footer-print">
      	<div>
      		<div class="col-xs-3 col-md-3">
      			<div class="footer-print-text">
      				<p>Forest Flavour BV</p>
      				<p>Vosdeel 10</p>
      				<p>5427RK Boekel</p>
      				<p>Nederland</p>
      			</div>
      		</div>
      		<div class="col-xs-3 col-md-3">
      			<div class="footer-print-text">
      				<p>+31(0)85 743 01 40</p>
      				<p>info@forestflavour.nl</p>
      				<p>www.forestflavour.nl</p>
      			</div>
      		</div>
      		<div class="col-xs-3 col-md-3">
      			<div class="footer-print-text">
      				<div class="row">
      					<div class="col-xs-4 col-md-4">
      						<p>Bank</p>
      					</div>
      					<div class="col-xs-8 col-md-8">
      						<p>:1554.13.155</p>
      					</div>
      					<div class="col-xs-4 col-md-4">
      						<p>Bic</p>
      					</div>
      					<div class="col-xs-8 col-md-8">
      						<p>:RABONL2U</p>
      					</div>
      					<div class="col-xs-4 col-md-4">
      						<p>IBAN</p>
      					</div>
      					<div class="col-xs-8 col-md-8">
      						<p>:NL49RABO0155413155</p>
      					</div>
      					<div class="col-xs-4 col-md-4">
      						<p>Btw</p>
      					</div>
      					<div class="col-xs-8 col-md-8">
      						<p>:NL853.732.863.B01</p>
      					</div>
      					<div class="col-xs-4 col-md-4">
      						<p>Kvk</p>
      					</div>
      					<div class="col-xs-8 col-md-8">
      						<p>:60017198</p>
      					</div>
      				</div>
      			</div>
      		</div>
      		<div class="col-xs-3 col-md-3">
      			<div class="footer-print-img">
      				<img src="uploads/ISO-CERTIFICATE.png" alt="" title="" />
      			</div>
      		</div>
      	</div>
      </div> 
  </div>
  </div>
  <style>
.g-total p{
	padding-top:7px;
}
@media print {
    .footer-print {
        background-color: #85b93e !important;
        -webkit-print-color-adjust: exact; 
    }
}
.glyphicon-euro {
	font-size:14px !important;
}

</style>
  ';



echo $printdetails.$printdetails2.$printdetails3;



