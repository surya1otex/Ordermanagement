<?php
  //require_once('includes/load.php');
$con = mysqli_connect('localhost','inv_user','S)*2=tFxj2ua','inv_oms');
$stateid = $_POST['stateid'];

$sql = "SELECT * FROM cities WHERE state_id='$stateid'";

$stmt = mysqli_query($con,$sql);

while($row = $stmt->fetch_array()) {
	?>
	<option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
	<?php
}

