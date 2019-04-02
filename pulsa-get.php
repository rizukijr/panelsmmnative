<?php
require("mainconfig.php");
function infojson($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
                return $data;
}
$lol = '0';
$infoapi3 = infojson('https://all-orionpanel-5.co.id/API?type=pulsa&method=service&key=81c905c12ab16859174fe0c4beb8705d5b87ca1f9c6a7');
$infoapi2 = json_decode($infoapi3,true);
foreach($infoapi2 as $infoapi) {
    $lol++;
	$post_sid = $infoapi['id'];
	$post_cat = $infoapi['produk'];
	$post_service = $infoapi['name'];
	$post_price = $infoapi['harga']; // harganya ditambah 2000 perak
	$post_pid = $infoapi['id'];
	$post_provider = "ORION-PULSA";
	$check_services = mysqli_query($db, "SELECT * FROM services_pulsa2 WHERE pid = '$post_pid' AND provider='ORION-PULSA'");
            $data_services = mysqli_fetch_assoc($check_orders);
        if(mysqli_num_rows($check_services) > 0) {
            echo "Service Sudah Ada Di database => $post_service | $post_pid \n <br />";
        } else {
	$insert_service = mysqli_query($db, "INSERT INTO services_pulsa2 (id,pid,service,oprator, price, status, provider) VALUES ('$post_sid','$post_pid','$post_service','$post_cat','$post_price','Active','ORION-PULSA')");//Memasukan Kepada Database (OPTIONAL)
    if($insert_service){
        echo $lol.". ".$post_service." ~ Berhasil Ditambah!<br />";
    }
}
}
