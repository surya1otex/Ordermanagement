<?php
ob_start();
  $page_title = 'Add Company';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>
<?php
 if(isset($_POST['add_cust'])){
   $req_fields = array('cust_name','cust_email', 'cust_comp', 'cust_shipp');
   validate_fields($req_fields);
   if(empty($errors)){
     $c_name  = remove_junk($db->escape($_POST['cust_name']));
     $c_email   = remove_junk($db->escape($_POST['cust_email']));
     //$c_pass   = remove_junk($db->escape(md5($_POST['cust_passwd'])));
     $c_phone   = remove_junk($db->escape($_POST['cust_phone']));
     $c_comp  = remove_junk($db->escape($_POST['cust_comp']));
     $c_iben  = remove_junk($db->escape($_POST['cust_iben']));
	 $c_kvk   = remove_junk($db->escape($_POST['cust_kvk']));
     $c_vat =   remove_junk($db->escape($_POST['cust_vat']));
     $c_ship =  remove_junk($db->escape($_POST['cust_shipp']));

     //$date    = make_date();
     $query  = "INSERT INTO customers (";
     $query .=" name,email,phone,company_name,vat_no,shipping,iben,kvk";
     $query .=") VALUES (";
     $query .=" '{$c_name}', '{$c_email}', '{$c_phone}', '{$c_comp}', '{$c_vat}', '{$c_ship}', '{$c_iben}', '{$c_kvk}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$c_name}'";
     if($db->query($query)){
       $session->msg('s',"Company added ");
       redirect('add_customers.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('customers.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_customers.php',false);
   }

 }

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-12">
    <?php echo display_msg($msg); ?>
  </div>
</div>
  <div class="row">
  <div class="col-md-8">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Company</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_customers.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_comp" placeholder="Company Name">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-user"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_name" placeholder="Contact Person">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-envelope"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_email" placeholder="Email">
               </div>
              </div>
              <!--<div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="password" class="form-control" name="cust_passwd" placeholder="Customer Password">
               </div>
              </div>!-->
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-phone"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_phone" placeholder="Phone">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                      <div class="input-group">
                       <span class="input-group-addon">
                         <i class="glyphicon glyphicon-th-large"></i>
                        </span>
                        <input type="text" class="form-control" name="cust_iben" placeholder="IBEN">
                       </div>
                  </div>
                  <div class="col-md-6">
                      <div class="input-group">
                       <span class="input-group-addon">
                         <i class="glyphicon glyphicon-th-large"></i>
                        </span>
                        <input type="text" class="form-control" name="cust_kvk" placeholder="KVK">
                       </div>
                  </div>
                </div>
              </div>
			  <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_vat" placeholder="Vat Number">
               </div>
			  </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <textarea class="form-control" name="cust_shipp" placeholder="Shipping Address"></textarea>
               </div>
              </div>
              <!--<div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <textarea class="form-control" name="cust_bill" placeholder="Billing Address"></textarea>
               </div>
              </div>!-->
              <button type="submit" name="add_cust" class="btn btn-danger">Add Company</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
<style type="text/css">
  .top-buffer { margin-top:20px; }
</style>
<?php include_once('layouts/footer.php'); ?>
