<?php ob_start();
  $page_title = 'Edit product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$customer = find_by_id('customers',(int)$_GET['id']);
//$all_categories = find_all('categories');
//$all_photo = find_all('media');
if(!$customer){
  $session->msg("d","Missing product id.");
  redirect('customers.php');
}
?>
<?php
 if(isset($_POST['updcustomer'])){
    $req_fields = array('cust_name','cust_email','cust_phone', 'cust_comp', 'cust_vat', 'cust_shipp', 'cust_iben', 'cust_kvk');
    validate_fields($req_fields);

   if(empty($errors)){
     $c_name  = remove_junk($db->escape($_POST['cust_name']));
     $c_email   = remove_junk($db->escape($_POST['cust_email']));
     $c_pass   = remove_junk($db->escape(md5($_POST['cust_passwd'])));
     $c_phone   = remove_junk($db->escape($_POST['cust_phone']));
     $c_comp  = remove_junk($db->escape($_POST['cust_comp']));
     $c_type =  remove_junk($db->escape($_POST['cust_type']));
     $c_vat =   remove_junk($db->escape($_POST['cust_vat']));
     $c_ship =  remove_junk($db->escape($_POST['cust_shipp']));
     $c_iben  = remove_junk($db->escape($_POST['cust_iben']));
	 $c_kvk   = remove_junk($db->escape($_POST['cust_kvk']));
     //$c_bill =  remove_junk($db->escape($_POST['cust_bill']));

       $query   = "UPDATE customers SET";
       $query  .=" name ='{$c_name}', email ='{$c_email}',";
       $query  .=" phone ='{$c_phone}', company_name ='{$c_comp}',vat_no='{$c_vat}',shipping='{$c_ship}',iben='{$c_iben}',kvk='{$c_kvk}'";
       $query  .=" WHERE id ='{$customer['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Company updated ");
                 redirect('customers.php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_customers.php?id='.$customer['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_customers.php?id='.$customer['id'], false);
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
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Update Customer</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_customers.php?id=<?php echo (int)$customer['id'] ?>">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_comp" value="<?php echo remove_junk($customer['company_name']); ?>">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_name" value="<?php echo remove_junk($customer['name']);?>">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_email" value="<?php echo remove_junk($customer['email']); ?>">
               </div>
              </div>
              <!--<div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="password" class="form-control" name="cust_passwd" value="<?php echo remove_junk($customer['password']); ?>">
               </div>
              </div>!-->
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_phone" value="<?php echo remove_junk($customer['phone']); ?>">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                      <div class="input-group">
                       <span class="input-group-addon">
                         <i class="glyphicon glyphicon-th-large"></i>
                        </span>
                        <input type="text" class="form-control" name="cust_iben" value="<?php echo remove_junk($customer['iben']); ?>">
                       </div>
                  </div>
                  <div class="col-md-6">
                      <div class="input-group">
                       <span class="input-group-addon">
                         <i class="glyphicon glyphicon-th-large"></i>
                        </span>
                        <input type="text" class="form-control" name="cust_kvk" value="<?php echo remove_junk($customer['kvk']); ?>">
                       </div>
                  </div>
                </div>
              </div>
			  <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="cust_vat" value="<?php echo remove_junk($customer['vat_no']); ?>">
               </div>
			  </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <textarea class="form-control" name="cust_shipp" value=""><?php echo remove_junk($customer['shipping']); ?></textarea>
               </div>
              </div>
              <!--<div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <textarea class="form-control" name="cust_bill" value=""><?php echo remove_junk($customer['billing_address']); ?></textarea>
               </div>
              </div>!-->
              <button type="submit" name="updcustomer" class="btn btn-danger">Update</button>
          </form>
         </div>
        </div>
      </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
