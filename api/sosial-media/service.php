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
			if ($post_action == "service"){
			    //INI LAYANAN
			    $querys = mysqli_query($db, "SELECT * FROM services2 WHERE status = 'Active' ORDER BY sid ASC");
		        $exe = mysqli_query($querys);
		
				while($row = mysqli_fetch_array($querys)){
				    $array = "-";
				    $datas[] = array('sid' => $row['sid'], 'category' => $row['category'], 'service' => $row['service'], 'note' => $row['note'], 'min' => $row['min'],'max' => $row['max'], 'status' => $row['status'], 'price' => $row['price']);
                }
                $array = array("result" => $datas);
			} else {
				$array = array("error" => "Action Salah !!");
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