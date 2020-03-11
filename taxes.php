<?php ob_start();
  $page_title = 'View All Boxes';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
  $boxes = find_all_boxes();
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
           <a href="add_taxes.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
          <table class="table table-bordered" id="datasets">
            <thead>
              <tr>
                <th> Tax Title</th>
                <th> Percentage (%) </th>
                <th> Description </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($boxes as $box):?>
              <tr>
                <td> <?php echo remove_junk($box['box_name']); ?></td>
                <td> <?php echo remove_junk($box['price']); ?></td>
                <td> <?php echo remove_junk($box['description']); ?></td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
