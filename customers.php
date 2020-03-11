<?php ob_start();
if(isset($_GET['action']) && $_GET['action'] == 'a') {
	$conn = mysqli_connect('localhost','inv_user','S)*2=tFxj2ua','inv_oms');
	$cust_id = $_GET['id'];
	$ql = "update customers set status='1' where id='$cust_id'";
	mysqli_query($conn,$ql);
}
  $page_title = 'View All Customers';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $customers = find_all_customers();
?>
<?php include_once('layouts/header.php'); ?>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
           <a href="add_customers.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered" id="datasets">
            <thead>
              <tr>
                <th class="text-center" style="width: 50px;">#</th>
                <th> Name</th>
                <th> Email </th>
                <th> Phone </th>
                <th> Company </th>
                <th> Vat No </th>
                <th> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($customers as $customer):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>
                <td> <?php echo remove_junk($customer['name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($customer['email']); ?></td>
                <td class="text-center"> <?php echo remove_junk($customer['phone']); ?></td>
                <td class="text-center"> <?php echo remove_junk($customer['company_name']); ?></td>
                <td class="text-center"> <?php echo remove_junk($customer['vat_no']); ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="edit_customers.php?id=<?php echo (int)$customer['id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_customers.php?id=<?php echo (int)$customer['id']; ?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
