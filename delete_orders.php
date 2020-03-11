<?php ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $product = find_by_orderid('orders',(int)$_GET['order_id']);
  if(!$product){
    $session->msg("d","Missing Order id.");
    redirect('manage_sales_order.php');
  }
?>
<?php
  $delete_id = delete_by_orderid('orders',(int)$product['order_id']);
  if($delete_id){
	  echo $delete_id;
      $session->msg("s","Order deleted.");
      redirect('manage_sales_order.php');
  } else {
      $session->msg("s","Order Deleted.");
      redirect('manage_sales_order.php');
  }
?>
