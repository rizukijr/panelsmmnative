<?php
require("../mainconfig.php");

if (isset($_POST['line'])) {
	$post_sid = mysqli_real_escape_string($db, $_POST['line']);
	$check_service = mysqli_query($db, "SELECT * FROM services_line WHERE id = '$post_sid'");
	if (mysqli_num_rows($check_service) == 1) {
		$data_service = mysqli_fetch_assoc($check_service);
		$result = $data_service['price'];
		echo $result;
	} else {
		die("0");
	}
} else {
	die("0");
}