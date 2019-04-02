<?php
require("../../mainconfig.php");
header("Content-Type: application/json");

if (isset($_POST['key']) AND isset($_POST['action'])) {
	$post_key = mysqli_real_escape_string($db, trim($_POST['key']));
	$post_action = $_POST['action'];
	if (empty($post_key) || empty($post_action)) {
		$array = array("error" => "Incorrect request");
	} else {
		$check_user = mysqli_query($db, "SELECT * FROM users WHERE api_key = '$post_key'");
		$data_user = mysqli_fetch_assoc($check_user);
		if (mysqli_num_rows($check_user) == 1) {
			$username = $data_user['username'];
			if ($post_action == "order") {
				if (isset($_POST['service']) AND isset($_POST['target']) AND isset($_POST['quantity'])) {
					$post_service = $_POST['service'];
					$post_link = $_POST['target'];
					$post_quantity = $_POST['quantity'];
					if (empty($post_service) || empty($post_link) || empty($post_quantity)) {
						$array = array("error" => "Incorrect request");
					} else {
						$check_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_service' AND status = 'Active'");
						$data_service = mysqli_fetch_assoc($check_service);
						if (mysqli_num_rows($check_service) == 0) {
							$array = array("error" => "Service not found");
						} else {
							$oid = random_number(7);
							$rate = $data_service['price'] / 1000;
							$price = $rate*$post_quantity;
							$service = $data_service['service'];
							$provider = $data_service['provider'];
							$pid = $data_service['pid'];
							if ($post_quantity < $data_service['min']) {
								$array = array("error" => "Quantity inccorect");
							} else if ($post_quantity > $data_service['max']) {
								$array = array("error" => "Quantity inccorect");
							} else if ($data_user['balance'] < $price) {
								$array = array("error" => "Low balance");
							} else {
								$check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = '$provider'");
								$data_provider = mysqli_fetch_assoc($check_provider);
								$provider_key = $data_provider['api_key'];
								$provider_link = $data_provider['link'];
	
								if ($provider == "MANUAL") {
									$api_postdata = "";
								} else if ($provider == "IRVANKEDE") {
                                $postdata = "api_key=$provider_key&service=$pid&target=$post_link&quantity=$post_quantity&custom_comments=$post_comment";
                                
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $provider_link);
                                curl_setopt ($ch, CURLOPT_POST, 1);
                                curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                                $chresult = curl_exec($ch);
                                curl_close($ch);
                                $json_result = json_decode($chresult, true);
								}
								
								if ($provider == "IRVANKEDE" AND $json_result['status'] == false) {
									$array = array("error" => "Server Maintance");
								} else {
									if ($provider == "IRVANKEDE") {
										$poid = $json_result['data']['id'];
									} else if ($provider == "MANUAL") {
										$poid = $oid;
									}
			                        $check_top = mysqli_query($db, "SELECT * FROM top_user WHERE username = '$username'");
			                        $data_top = mysqli_fetch_assoc($check_top);									
									$update_user = mysqli_query($db, "UPDATE users SET balance = balance-$price WHERE username = '$username'");
							
									if ($update_user == TRUE) {
									    if (mysqli_num_rows($check_top) == 0) {
				                            $insert_order = mysqli_query($db, "INSERT INTO top_user (method, username, jumlah, total) VALUES ('Order', '$username', '$price', '1')");
				                        } else {
				                            $insert_order = mysqli_query($db, "UPDATE top_user SET jumlah = ".$data_top['jumlah']."+$price, total = ".$data_top['total']."+1 WHERE username = '$username' AND method = 'Order'");
				                        }									    
									    $insert_order = mysqli_query($db, "INSERT INTO balance_history (id, username, action, quantity, msg, date, time) VALUES ('', '$username', 'Cut Balance', '$price', 'Pemesanan Sosial Media Dengan Order ID $oid (API)', '$date', '$time')");
										$insert_order = mysqli_query($db, "INSERT INTO orders (oid, poid, user, service, link, quantity, price, status, date, provider, place_from) VALUES ('$oid', '$poid', '$username', '$service', '$post_link', '$post_quantity', '$price', 'Pending', '$date', '$provider', 'API')");
										if ($insert_order == TRUE) {
											$array = array(
												"data" => array(
													"id" => "$oid",
											));
										} else {
											$array = array("error" => "System error");
										}
									} else {
										$array = array("error" => "System error");
									}
								}
							}
						}
					}
				} else {
					$array = array("error" => "Incorrect request");
				}
			} else {
				$array = array("error" => "Wrong action");
			}
		} else {
			$array = array("error" => "Invalid API key");
		}
	}
} else {
	$array = array("error" => "Incorrect request");
}

$print = json_encode($array);
print_r($print);