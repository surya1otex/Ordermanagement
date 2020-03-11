<?php
ob_start();
  $page_title = 'Add Boxes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_photo = find_all('media');
?>
<?php
 if(isset($_POST['add_cust'])){
   $req_fields = array('tax_title','tax_price');
   validate_fields($req_fields);
   if(empty($errors)){
     $box_name  = remove_junk($db->escape($_POST['tax_title']));
     $box_price   = remove_junk($db->escape($_POST['tax_price']));
     $box_desc   = remove_junk($db->escape($_POST['tax_desc']));

     //$date    = make_date();
     $query  = "INSERT INTO boxes (";
     $query .=" box_name,price,description";
     $query .=") VALUES (";
     $query .=" '{$box_name}', '{$box_price}', '{$box_desc}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE box_name='{$box_name}'";
     if($db->query($query)){
       $session->msg('s',"Tax added ");
       redirect('add_taxes.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('add_taxes.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_taxes.php',false);
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
            <span>Add Taxes</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_boxes.php" class="clearfix">
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="tax_title" placeholder="Tax Name">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="tax_price" placeholder="Percentage">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="tax_desc" placeholder="Tax Description">
               </div>
              </div>
              <button type="submit" name="add_cust" class="btn btn-danger">Add Tax</button>
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
