<?php ob_start();
  $page_title = 'Admin Home Page';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   //page_require_level(1);
?>
<?php
 $c_categorie     = count_by_id('categories');
 $c_product       = count_by_id('products');
 //$c_sale          = count_by_orderid('orders');
 $c_user          = count_by_id('customers');
 //$products_sold   = find_higest_saleing_product('10');
 //$recent_products = find_recent_product_added('5');
 //$recent_sales    = find_recent_sale_added('5')
 $salesql = "SELECT orders.order_id,orders.order_date,orders.client_contact,customers.company_name,customers.id FROM orders,customers
        WHERE customers.company_name = orders.company_name  ORDER BY orders.order_id ASC";
$chks = $db->query($salesql);
$countit = mysqli_num_rows($chks);

?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
  <div class="row">
    <div class="col-md-6">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-red">
          <i class="glyphicon glyphicon-list"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_categorie['total']; ?> </h2>
          <p class="text-muted">Categories</p>
        </div>
       </div>
    </div>
    <div class="col-md-6">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-green">
          <i class="glyphicon glyphicon-user"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_user['total']; ?> </h2>
          <p class="text-muted">Contacts</p>
        </div>
       </div>
    </div>
    <div class="col-md-6">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-blue">
          <i class="glyphicon glyphicon-shopping-cart"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $c_product['total']; ?> </h2>
          <p class="text-muted">Products</p>
        </div>
       </div>
    </div>
    <div class="col-md-6">
       <div class="panel panel-box clearfix">
         <div class="panel-icon pull-left bg-yellow">
          <i class="glyphicon glyphicon-usd"></i>
        </div>
        <div class="panel-value pull-right">
          <h2 class="margin-top"> <?php  echo $countit; ?></h2>
          <p class="text-muted">Sales Order</p>
        </div>
       </div>
    </div>
</div>
  <div class="row">

  </div>



<?php include_once('layouts/footer.php'); ?>
