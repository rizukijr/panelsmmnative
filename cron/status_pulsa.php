<?php
require("../mainconfig.php");

$check_order = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE status IN ('Pending','Processing')");

if (mysqli_num_rows($check_order) == 0) {
  die("Order Pending not found.");
} else {
  while($data_order = mysqli_fetch_assoc($check_order)) {
    $o_oid = $data_order['oid'];
    $o_poid = $data_order['poid'];
    $o_provider = $data_order['provider'];
  if ($o_provider == "MANUAL") {
    echo "Order manual<br />";
  } else {
    $check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = '$o_provider'");
			$data_provider = mysqli_fetch_assoc($check_provider);
			
			$p_apikey = $data_provider['api_key'];
			$p_link = $data_provider['link'];
			
			if ($o_provider == "MP-PULSA") {
				$api_postdata = "api_key=$p_apikey&action=status&id=$o_poid";
			} else {
				die("System error!");
			}
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://medanpedia.co.id/pulsa/json.php");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $chresult = curl_exec($ch);
    echo $chresult;
    curl_close($ch);
    $json_result = json_decode($chresult, true);
            $indeks=0;
            while($indeks < count($json_result['data'])){
              
                $u_status = $json_result['data']['status'];
                $sn = $json_result['data']['sn'];
                $indeks++;
    
    if (empty($u_status)) {
				    $u_status = "Pending";
				}
           
    $update_order = mysqli_query($db, "UPDATE orders_pulsa SET status = '$u_status', sn = '$sn' WHERE oid = '$o_oid'");
    if ($update_order == TRUE) {
      echo "$o_oid status $u_status | SN $sn <br />";
    } else {
      echo "Error database.";
    }
  }
  }
}
}