<?php ob_start();
  $page_title = 'View All Sales';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  //$customers = find_all_customers();

//$sql = "SELECT order_id, order_date, client_name, client_contact, payment_status FROM orders WHERE order_status = 1";
$sql = "SELECT orders.order_id,orders.order_date,orders.client_contact,customers.company_name,customers.id FROM orders,customers
        WHERE customers.company_name = orders.company_name  ORDER BY orders.order_id ASC";
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
		<div class="row" style="margin-bottom:10px">
		<form action="filterorder.php" method="post">
		<div class="col-md-4">
		<input type="text" class="form-control date" name="from" id="frmdate" placeholder="From" autocomplete="off">
		</div>
		<div class="col-md-4">
		<input type="text" class="form-control date" name="to" id="todate" placeholder="To" autocomplete="off">
		</div>
		<div class="col-md-4"><button type="submit" class="btn btn-primary" id="button" name="submit" value="Search">Search</button></div>
		</form>
		
		</div>
          <table class="table table-bordered" id="datasets">
            <thead>
              <tr>
            <th>#</th>
            <th>Order Date</th>
            <th>Company Name</th>
            <th>Contact</th>
            <th>Total Order Item</th>
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
                <td class="text-center">
                  <div class="btn-group">
                    <a href="orderDetails.php?id=<?php echo $getorders['order_id'];?>" class="btn btn-info btn-xs"  title="View Order" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_orders.php?order_id=<?php echo $getorders['order_id']; ?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
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

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>!-->

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
<script type="text/javascript">
$("#button").click(function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "filterorder.php",
        data: { 
            from: $("#frmdate").val(), // < note use of 'this' here
            to: $("#todate").val() 
        },
        success: function(result) {
			$("tbody").html(result);
			//alert(result);
            //alert('ok');
        },
        error: function(result) {
            alert('error');
        }
    });
});

</script>
<script type="text/javascript">
    $('.date').datepicker({
       format: 'yyyy-mm-dd'
     });
</script>



