<?php ob_start();
  $page_title = 'View All Customers';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   //page_require_level(2);
  //$customers = find_all_customers();

//$sql = "SELECT order_id, order_date, client_name, client_contact, payment_status FROM orders WHERE order_status = 1";
$sql = "SELECT orders.order_id,orders.order_date,orders.client_contact,orders.order_status,customers.company_name,customers.id FROM orders,customers
        WHERE customers.company_name = orders.company_name ORDER BY orders.order_id ASC";
$result = $db->query($sql);

?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
<!--          <div class="pull-right">
           <a href="" class="btn btn-primary">Add New</a>
         </div> -->
        </div>
        <div class="panel-body">
          <table class="table table-bordered" id="datasets">
            <thead>
              <tr>
            <th>#</th>
            <th>Order Date</th>
            <th>Company Name</th>
            <th>Contact</th>
            <th>Total Order Item</th>
            <th>Status</th>
            <th>Option</th>
            </tr>
            </thead>
            <tbody>
              
             <?php

             while($getorders = $result->fetch_array()) {
             $orderId = $getorders[0];
             $countOrderItemSql = "SELECT count(*) FROM order_item WHERE order_id = $orderId";
             $itemCountResult = $db->query($countOrderItemSql);
             $itemCountRow = $itemCountResult->fetch_row();
             ?>
              <tr>
                <td> <?php echo $getorders['order_id']; ?></td>
                <td class="text-center"> <?php echo remove_junk($getorders['order_date']); ?></td>
                <td class="text-center"><a class="modalLink" href="#myModal" data-toggle="modal" data-target="#myModal" data-id="<?php echo $getorders["id"]; ?>"><?php echo remove_junk($getorders['company_name']); ?></a></td>
                <td class="text-center"> <?php echo remove_junk($getorders['client_contact']); ?></td>
                <td class="text-center"> <?php echo $itemCountRow[0]; ?></td>
                <td>
                <?php
				//echo $orderId;
				$stmt_chk = "SELECT order_item_status FROM order_item WHERE order_id='$orderId' and order_item_status='1'";
				$ex_stmt = $db->query($stmt_chk);
				$st_count = mysqli_num_rows($ex_stmt);
				//echo $st_count;
				if($st_count > 0) {
					//echo 'pending';
				echo '<b style=color:#f70606>'.'Pending'."</b>";
				}
				else {
				echo '<b style=color:#63b10d>'.'Confirmed'."</b>";
					//echo 'Picked';
				
				}
                ?>	
                </td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="adminpicklist.php?id=<?php echo $getorders['order_id'];?>" class="btn btn-info btn-xs"  title="View Order" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <!--<a href="#" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>!-->
                  </div>
                </td>
                <td class="text-center"> <?php //echo read_date($customer['business_type']); ?></td>
              </tr>
              <?php
            }
            ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Company Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
	  <div class="modal-body">
       <div id='loadingmessage' style='display:none'>
        <p>please wait....</p>
        </div>
	  </div>
    </div>
  </div>
</div>

  <?php include_once('layouts/footer.php'); ?>
  <script type="text/javascript">
$('.modalLink').click(function(){
    var compid=$(this).attr('data-id');
	 $('#loadingmessage').show();
	$.ajax({
	type: "GET",
	url: "company_details.php",
	data:{compid: compid},
	success: function(data){
		$(".modal-body").html(data);
		$('#loadingmessage').hide();
		//document.getElementById("ringimage").src = data;
		//$("#rdshankimages").html(data);
		//alert(val);
	}
	});
});
</script>
