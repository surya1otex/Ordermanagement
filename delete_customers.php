<?php ob_start();
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
?>
<?php
  $product = find_by_id('customers',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","Missing Product id.");
    redirect('customers.php');
  }
?>
<?php
  $delete_id = delete_by_id('customers',(int)$product['id']);
  if($delete_id){
      $session->msg("s","Company deleted.");
      redirect('customers.php');
  } else {
      $session->msg("d","Products deletion failed.");
      redirect('customers.php');
  }
?>
