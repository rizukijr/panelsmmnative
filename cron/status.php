<?php
require("../mainconfig.php");

$check_order = mysqli_query($db, "SELECT * FROM orders WHERE status IN ('','Pending','Processing') AND provider = 'IRVANKEDE'");

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
    
    $check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = 'IRVANKEDE'");
    $data_provider = mysqli_fetch_assoc($check_provider);
    
    $p_apikey = $data_provider['api_key'];
    $p_link = $data_provider['link'];
    $p_id = $data_provider['api_id'];
    
    if ($o_provider !== "MANUAL") {
      $api_postdata = "api_id=$p_id&api_key=$p_apikey&id=$o_poid";
    } else {
      die("System error!");
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://irvankede-smm.co.id/api/status");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $chresult = curl_exec($ch);
    curl_close($ch);
    $json_result = json_decode($chresult, true);
    print_r($json_result);
    
    if ($o_provider !== "MANUAL") {
      $u_status = $json_result['data']['status'];
      $u_start = $json_result['data']['start_count'];
      $u_remains = $json_result['data']['remains'];
    }
    
    $update_order = mysqli_query($db, "UPDATE orders SET status = '$u_status', start_count = '$u_start', remains = '$u_remains' WHERE oid = '$o_oid'");
    if ($update_order == TRUE) {
      echo "$o_oid status $u_status | start $u_start | remains $u_remains<br />";
    } else {
      echo "Error database.";
    }
  }
  }
}