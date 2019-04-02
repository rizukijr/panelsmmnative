<?php
require("../../mainconfig.php");
header("Content-Type: application/json");

if (isset($_POST['key']) AND isset($_POST['action'])) {
	$post_key = mysqli_real_escape_string($db, trim($_POST['key']));
	$post_action = $_POST['action'];
	if (empty($post_key) || empty($post_action)) {
		$array = array("error" => "Request Tidak Sesuai");
	} else {
		$check_user = mysqli_query($db, "SELECT * FROM users WHERE api_key = '$post_key'");
		$data_user = mysqli_fetch_assoc($check_user);
		if (mysqli_num_rows($check_user) == 1) {
			$username = $data_user['username'];
			if ($post_action == "status") {
				if (isset($_POST['id'])) {
					$post_oid = $_POST['id'];
					$post_oid = $_POST['id'];
					$check_order = mysqli_query($db, "SELECT * FROM orders WHERE oid = '$post_oid' AND user = '$username'");
					$data_order = mysqli_fetch_array($check_order);
					if (mysqli_num_rows($check_order) == 0) {
						$array = array("error" => "Order ID Tidak Di Temukan ");
					} else {
						$array = array(
							"data" => array(
								"start_count" => $data_order['start_count'], "remains" => $data_order['remains'], "status" => $data_order['status']
						));
					}
				} else {
					$array = array("error" => "Request Tidak Sesuai");
				}
			} else {
				$array = array("error" => "Action Tidak Sesuai");
			}
		} else {
			$array = array("error" => "Api Key Salah");
		}
	}
} else {
	$array = array("error" => "Request Tidak Sesuai");
}

$print = json_encode($array);
print_r($print);