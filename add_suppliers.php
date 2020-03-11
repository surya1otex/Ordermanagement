<?php
ob_start();
  $page_title = 'Add Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
  $countries = find_all('countries');
?>
<?php
 if(isset($_POST['add_supplier'])){
   $req_fields = array('supplier-name','company-name','adress','country', 'state', 'city','phone', 'email' );
   validate_fields($req_fields);
   if(empty($errors)){
     $s_name  = remove_junk($db->escape($_POST['supplier-name']));
     $s_com   = remove_junk($db->escape($_POST['company-name']));
     $s_add   = remove_junk($db->escape($_POST['adress']));
     $s_cou   = remove_junk($db->escape($_POST['country']));
     $s_sta  =  remove_junk($db->escape($_POST['state']));
     $s_cit  =  remove_junk($db->escape($_POST['city']));
	 $s_phone = remove_junk($db->escape($_POST['phone']));
	 $s_email = remove_junk($db->escape($_POST['email']));
	 
     $date    = make_date();
     $query  = "INSERT INTO pro_suppliers (";
     $query .=" supplier_name,company_name,adress,country,state,city,phone,email";
     $query .=") VALUES (";
     $query .=" '{$s_name}', '{$s_com}', '{$s_add}', '{$s_cou}', '{$s_sta}', '{$s_cit}', '{$s_phone}', '{$s_email}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE supplier_name='{$s_name}'";
     if($db->query($query)){
       $session->msg('s',"Supplier added ");
       redirect('add_suppliers.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_suppliers.php',false);
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
            <span>Add New Supplier</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_suppliers.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="supplier-name" placeholder="Supplier Name">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="company-name" placeholder="Company Name">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="adress" placeholder="Adress">
               </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-6">
                    <select class="form-control" name="country" onchange="getstates(this.value)">
                      <option value="">Select Country</option>
                    <?php  foreach ($countries as $country): ?>
                      <option value="<?php echo (int)$country['id'] ?>">
                        <?php echo $country['name'] ?></option>
                    <?php endforeach; ?>                      
                    </select>
                  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="state" id="state" onchange="getcities(this.value)">
                      <option value="">Select State</option>
                    </select>
                  </div>
                </div>
                <div class="row top-buffer">
                 <div class="col-md-6">
                    <select class="form-control" name="city" id="city">
                      <option value="">Select City</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <input type="text" class="form-control" name="phone" placeholder="Phone Number">
                  </div>
                </div>
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-6">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-envelope"></i>
                     </span>
                     <input type="text" class="form-control" name="email" placeholder="Email Adress">
                  </div>
                 </div>
               </div>
              </div>
              <button type="submit" name="add_supplier" class="btn btn-danger">Add Supplier</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
 <script type="text/javascript">
  function getstates(val) {
  $.ajax({
  type: "POST",
  url: "ajax/getstates.php",
  data:{countryid: val},
  success: function(data){
  $('#state').html(data);
  }
  });
}
  function getcities(val) {
  $.ajax({
  type: "POST",
  url: "ajax/getcities.php",
  data:{stateid: val},
  success: function(data){
  $('#city').html(data);
  }
  });
}
</script>
<style type="text/css">
  .top-buffer { margin-top:20px; }
</style>
<?php include_once('layouts/footer.php'); ?>
