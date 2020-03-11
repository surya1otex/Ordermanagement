<?php
ob_start();

  $page_title = 'Add Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
  page_require_level(2);
  $all_categories = find_all('categories');
  $all_suppliers  = find_all('pro_suppliers');
  $all_photo = find_all('media');
  $all_countries = find_all('countries');
?>
<?php
 if(isset($_POST['add_product'])){
   $req_fields = array('product-title','product-quantity','buying-price', 'saleing-price' );
   validate_fields($req_fields);
   if(empty($errors)){
     $target_dir = 'uploads/';
     $imgname = $_FILES["fileToUpload"]["name"];
     $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
     $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
     if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
  }
     $p_name  = remove_junk($db->escape($_POST['product-title']));
	 $p_sku   = remove_junk($db->escape($_POST['product-SKU']));
	 $p_tax   = remove_junk($db->escape($_POST['product-tax']));
	 $p_country = remove_junk($db->escape($_POST['country']));
	 $p_supp    = remove_junk($db->escape($_POST['product-supplier']));
	 $p_weight = remove_junk($db->escape($_POST['product-weight']));
	 $p_bruto = remove_junk($db->escape($_POST['weight-bruto']));
	 $p_status = remove_junk($db->escape($_POST['basket']));
     $p_cat   = remove_junk($db->escape($_POST['product-categorie']));
     $p_qty   = remove_junk($db->escape($_POST['product-quantity']));
     $p_buy   = remove_junk($db->escape($_POST['buying-price']));
     $p_sale  = remove_junk($db->escape($_POST['saleing-price']));
	 $p_info  = remove_junk($db->escape($_POST['prod_info']));
     if (is_null($_POST['product-photo']) || $_POST['product-photo'] === "") {
       $media_id = '0';
     } else {
       $media_id = remove_junk($db->escape($_POST['product-photo']));
     }
     $date    = make_date();
     $query  = "INSERT INTO products (";
     $query .=" name,prod_SKU,prod_img,quantity,weight,country,supplier_name,weight_bruto,buy_price,sale_price,Tax,categorie_id,status,prod_info,date";
     $query .=") VALUES (";
     $query .=" '{$p_name}','{$p_sku}','{$imgname}','{$p_qty}','{$p_weight}','{$p_country}','{$p_supp}','{$p_bruto}','{$p_buy}', '{$p_sale}','{$p_tax}','{$p_cat}','{$p_status}','{$p_info}', '{$date}'";
     $query .=")";
     $query .=" ON DUPLICATE KEY UPDATE name='{$p_name}'";
     if($db->query($query)){
       $session->msg('s',"Product added ");
       redirect('add_product.php', false);
     } else {
       $session->msg('d',' Sorry failed to added!');
       redirect('product.php', false);
     }

   } else{
     $session->msg("d", $errors);
     redirect('add_product.php',false);
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
  <div class="col-md-10">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <span class="glyphicon glyphicon-th"></span>
            <span>Add New Product</span>
         </strong>
        </div>
        <div class="panel-body">
         <div class="col-md-12">
          <form method="post" action="add_product.php" class="clearfix" enctype="multipart/form-data">
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
                  <img id="blah" src="#" alt="" height="110px" />
                  </div>
        
              </div>
            </div>          
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-title" placeholder="Product Title" required>
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-SKU" placeholder="Product SKU/NR">
               </div>
              </div>
              <div class="form-group">
                <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-tax" placeholder="Tax(%)">
               </div>
              </div>
              <div class="form-group">
			   <div class="row">
			     <div class="col-md-6">
                   <select class="form-control" name="country">
                      <option value="">Select Country</option>
                    <?php  foreach ($all_countries as $country): ?>
                      <option value="<?php echo $country['sortname'] ?>">
                        <?php echo $country['name'] ?></option>
                    <?php endforeach; ?>
                </select>
				  </div>
                  <div class="col-md-6">
                    <select class="form-control" name="product-categorie">
                      <option value="">Select Product Category</option>
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['name'] ?></option>
                    <?php endforeach; ?>
                    </select>
                  </div>
			    </div>
              </div>			  
              <div class="form-group">
                <div class="row">
                 <div class="col-md-4">
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
                  <input type="text" class="form-control" name="product-supplier" placeholder="Enter Supplier Name">				  
				   </div>
                  </div>
                 <div class="col-md-4">
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
				  <input type="text" class="form-control" name="product-weight" placeholder="Product Weight(Kg)">
                  </div>
				 </div>
                 <div class="col-md-4">
				 <div class="input-group">
                  <span class="input-group-addon">
                   <i class="glyphicon glyphicon-th-large"></i>
                  </span>
				  <input type="text" class="form-control" name="weight-bruto" placeholder="Weight Bruto">
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
                     <input type="number" class="form-control" name="product-quantity" placeholder="Product Quantity" required>
                  </div>
                 </div>
                 <div class="col-md-4">
                   <div class="input-group">
                     <span class="input-group-addon">
                       <i class="glyphicon glyphicon-euro"></i>
                     </span>
                     <input type="number" class="form-control" name="buying-price" placeholder="Buying Price" required>
                     <span class="input-group-addon">.00</span>
                  </div>
                 </div>
                  <div class="col-md-4">
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="glyphicon glyphicon-euro"></i>
                      </span>
                      <input type="number" class="form-control" name="saleing-price" placeholder="Selling Price" required>
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
                  <textarea class="form-control" name="prod_info" rows="2" cols="10" placeholder="Enter Product Details"></textarea>
               </div>
              </div>
			  <div class="form-group">
			  <input type="hidden" name="basket" value="1">
			  <!--<input type="checkbox" name="basket" value="1" required>&nbsp;Basket!-->
			  </div>
              <button type="submit" name="add_product" class="btn btn-danger">Add product</button>
          </form>
         </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
<style type="text/css">
  .top-buffer { margin-top:20px; }
</style>
<?php include_once('layouts/footer.php'); ?>
