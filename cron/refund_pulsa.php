<?php
// Recode By SPETRMEDIA.COM
require("../mainconfig.php");

$check_order = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE status IN ('Error','Partial') AND refund = '0'");

if (mysqli_num_rows($check_order) == 0) {
	die("Order Error or Partial not found.");
} else {
	while($data_order = mysqli_fetch_assoc($check_order)) {
		$o_oid = $data_order['oid'];
		
		    $priceone = $data_order['price'];
		    $refund = $priceone;
		    $buyer = $data_order['user'];
		    
		    
			$update_user = mysqli_query($db, "UPDATE users SET balance = balance+$refund WHERE username = '$buyer'");
			$update_order = mysqli_query($db, "INSERT INTO balance_history (id, username, action, quantity, msg, date, time) VALUES ('', '$buyer', 'Add Balance', '$refund', 'Pengembalian Dana Dari Pemesanan Pada Fitur Pulsa Akibat Status Pesanan Error Dengan Order ID $o_oid', '$date', '$time')");
    		$update_order = mysqli_query($db, "UPDATE orders_pulsa SET refund = '1'  WHERE oid = '$o_oid'");
    		if ($update_order == TRUE) {
    			echo "Refunded Rp $refund - $o_oid<br />";
    		} else {
    			echo "Error database.";
    		}
	}
}
?>