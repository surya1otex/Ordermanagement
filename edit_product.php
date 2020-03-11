<?php ob_start();
  $page_title = 'Edit product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
?>
<?php
$product = find_by_id('products',(int)$_GET['id']);
$all_categories = find_all('categories');
$all_photo = find_all('media');
$all_countries = find_all('countries');
if(!$product){
  $session->msg("d","Missing product id.");
  redirect('product.php');
}
?>
<?php
 if(isset($_POST['product'])){
    $req_fields = array('product-title','product-categorie','product-quantity','buying-price', 'saleing-price' );
    validate_fields($req_fields);

   if(empty($errors)){
       $p_name  = remove_junk($db->escape($_POST['product-title']));
	   $p_sku   = remove_junk($db->escape($_POST['product-SKU']));
	   $p_tax   = remove_junk($db->escape($_POST['product-tax']));
	   $p_country = remove_junk($db->escape($_POST['country']));
	   $p_category = remove_junk($db->escape($_POST['product-categorie']));
	   $p_weight = remove_junk($db->escape($_POST['product-weight']));
	   $w_bruto  = remove_junk($db->escape($_POST['weight-bruto']));
       $p_cat   = (int)$_POST['product-categorie'];
       $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
       $p_buy   = remove_junk($db->escape($_POST['buying-price']));
       $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
	   $p_info =  remove_junk($db->escape($_POST['prod_info']));
       if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
         $media_id = '0';
       } else {
         $media_id = remove_junk($db->escape($_POST['product-photo']));
       }
       $query   = "UPDATE products SET";
       $query  .=" name ='{$p_name}',prod_SKU='{$p_sku}', quantity ='{$p_qty}',weight='{$p_weight}',country='{$p_country}',weight_bruto='{$w_bruto}',";
       $query  .=" buy_price ='{$p_buy}', sale_price ='{$p_sale}',Tax='{$p_tax}',categorie_id ='{$p_cat}',prod_info='{$p_info}'";
       $query  .=" WHERE id ='{$product['id']}'";
       $result = $db->query($query);
               if($result && $db->affected_rows() === 1){
                 $session->msg('s',"Product updated ");
                 redirect('product.php', false);
               } else {
                 $session->msg('d',' Sorry failed to updated!');
                 redirect('edit_product.php?id='.$product['id'], false);
               }

   } else{
       $session->msg("d", $errors);
       redirect('edit_product.php?id='.$product['id'], false);
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
            <span>Edit Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-7">
           <form method="post" action="edit_product.php?id=<?php echo (int)$product['id'] ?>">
              <div class="form-group">
               <div class="row">
                <div class="col-md-6">
                  <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type='file' onchange="readURL(this);" class="form-control" name="fileToUpload" />
                  </div>
                </div>
                  <div class="col-md-6">
				  <?php
				  if(!empty($product['prod_img'])) { ?>
				  <img src="uploads/<?php echo $product['prod_img']; ?>" height="110px">
                  <?php }				  
				  else { ?>
                  <img id="blah" src="#" alt="" height="110px" />
				  <?php } ?>
                  </div>
        
              </div>
            </div>          
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Product Title" value="<?php echo remove_junk($product['name']); ?>" required>
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-SKU" placeholder="Product SKU/NR" value="<?php echo remove_junk($product['prod_SKU']); ?>">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-tax" placeholder="Goverment Tax(%)" value="<?php echo remove_junk($product['Tax']); ?>">
               </div>
              </div>
              <div class="form-group">
			   <div class="row">
			     <div class="col-md-6">
                   <select class="form-control" name="country" required>
                      <option value="">Select Country</option>
                    <?php  foreach ($all_countries as $country): ?>
                      <option value="<?php echo $country['name'] ?>" <?php if($product['country'] == $country['name']): echo "selected"; endif; ?>>
                        <?php echo $country['name'] ?></option>
                    <?php endforeach; ?>
                </select>
				  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>" <?php if($product['categorie_id'] === $cat['id']): echo "selected"; endif; ?>>
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
			    </div>
              </div>			  
              <div class="form-group">
                <div class="row">
                 <div class="col-md-4">
                    <select class="form-control">
                      <option value="">Select Supplier</option>
                    </select>
                  </div>
                 <div class="col-md-4">
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
				  <input type="text" class="form-control" name="product-weight" placeholder="Product Weight(Kg)" value="<?php echo remove_junk($product['weight']); ?>">
                  </div>
				 </div>
                 <div class="col-md-4">
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
				  <input type="text" class="form-control" name="weight-bruto" placeholder="Weight Bruto" value="<?php echo remove_junk($product['weight_bruto']); ?>">
                  </div>
				 </div>
                </div>
                <!--<div class="row top-buffer">
                 <div class="col-md-6">
                    <select class="form-control">
                      <option value="">Select Supplier</option>
                    </select>
                  </div>
                </div>!-->
              </div>

              <div class="form-group">
               <div class="row">
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                      <i class="glyphicon glyphicon-shopping-cart"></i>
                     </span>
                     <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity" value="<?php echo remove_junk($product['quantity']); ?>" required>
                  </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                       <i class="fa fa-rupee"></i>
                     </span>
                     <input type="number" class="form-control" name="buying-price" placeholder="Buying Price" value="<?php echo remove_junk($product['buy_price']); ?>">
                     <span class="input-group-addon">.00</span>
                  </div>
                 </div>
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="fa fa-rupee"></i>
                      </span>
                      <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price" value="<?php echo remove_junk($product['sale_price']); ?>">
                      <span class="input-group-addon">.00</span>
                   </div>
                  </div>
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <textarea class="form-control" name="prod_info" rows="2" cols="10" placeholder="Enter Product Details"><?php echo remove_junk($product['prod_info']); ?></textarea>
               </div>
              </div>
			  <div class="form-group">
			  <input type="checkbox" name="basket" id="status" value="1">&nbsp;Basket
			  </div>
              <button type="submit" name="product" class="btn btn-danger">Update</button>
          </form>
         </div>
        </div>
      </div>
  </div>
<script type="text/javascript">
var status = "<?php echo $product['status']; ?>";
if(status == 1) {
$( "#status" ).prop( "checked", true );
}
//alert('Hii');
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<?php include_once('layouts/footer.php'); ?>
