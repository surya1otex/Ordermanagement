<?php 	

require_once('includes/load.php');

$keyword = $_POST["keyword"];

$query ="SELECT * FROM products WHERE name like '" . $keyword . "%' ORDER BY name";
$result = $db->query($query);

if(!empty($result)) {
?>
<ul id="country-list">
<?php
foreach($result as $product) {
?>
<li onClick="selectCountry('<?php echo $product["id"]; ?>');"><?php echo $product["name"]; ?></li>
<?php } ?>
</ul>
<?php }  ?>

