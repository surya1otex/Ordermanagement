<?php
require_once('includes/load.php');

$id = $_GET['compid'];
$sql = "SELECT * FROM customers WHERE id='$id'";
$result = $db->query($sql);
$getdata = $result->fetch_array();

?>
<table class="table">
<tr>
<th>Company Name</th>
<th>Contact Person</th>
<th>Contact</th>
<th>Shipping Address</th>
<th>Billing Address</th>
</tr>
<tr>
<td><?php echo $getdata['company_name']; ?></td>
<td><?php echo $getdata['name']; ?></td>
<td><?php echo $getdata['phone']; ?></td>
<td><?php echo $getdata['shipping']; ?></td>
<td><?php echo $getdata['billing_address']; ?></td>
</tr>
</table>