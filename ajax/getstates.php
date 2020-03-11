<?php
  //require_once('includes/load.php');
$con = mysqli_connect('localhost','inv_user','S)*2=tFxj2ua','inv_oms');
$countid = $_POST['countryid'];

$sql = "SELECT * FROM states WHERE country_id='$countid'";

$stmt = mysqli_query($con,$sql);

while($row = $stmt->fetch_array()) {
	?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
	<?php
}

