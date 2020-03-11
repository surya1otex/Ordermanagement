<?php
require_once('includes/load.php');

	
$from = $_POST["from"];
$to   = $_POST["to"];

echo $from;

$sql = "SELECT orders.order_id,orders.order_date,orders.client_contact,customers.company_name,customers.id FROM orders,customers
        WHERE customers.company_name = orders.company_name AND (order_date BETWEEN '$from' AND '$to')";

$result = $db->query($sql);

             while($getorders = $result->fetch_array()) {
             $orderId = $getorders[0];
             $countOrderItemSql = "SELECT count(*) FROM order_item WHERE order_id = $orderId";
             $itemCountResult = $db->query($countOrderItemSql);
             $itemCountRow = $itemCountResult->fetch_row();
             ?>
              <tr>
                <td> <?php echo $getorders['order_id']; ?></td>
                <td class="text-center"> <?php echo remove_junk($getorders['order_date']); ?></td>
                <td class="text-center"><a class="modalLink" href="#myModal" data-toggle="modal" data-target="#myModal" data-id="<?php echo $getorders["id"]; ?>"><?php echo remove_junk($getorders['company_name']); ?></a></td>
                <td class="text-center"> <?php echo remove_junk($getorders['client_contact']); ?></td>
                <td class="text-center"> <?php echo $itemCountRow[0]; ?></td>
                <td class="text-center">
                  <div class="btn-group">
                    <a href="orderDetails.php?id=<?php echo $getorders['order_id'];?>" class="btn btn-info btn-xs"  title="View Order" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_orders.php?order_id=<?php echo $getorders['order_id']; ?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
                <td class="text-center"> <?php //echo read_date($customer['business_type']); ?></td>
              </tr>
              <?php
            }



	