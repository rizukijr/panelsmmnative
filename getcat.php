<?php
require_once ("mainconfig.php");
$api_postdata = "api_key=ROwojgT-tjX9lOr-LDGNOmt-vtbVyI8&action=services";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://irvankede-smm.co.id/api/services");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $chresult = curl_exec($ch);
    curl_close($ch);
    $json_result = json_decode($chresult, true);
    print_r($json_result);
$indeks=0; 
$i = 1;
// get data service
while($indeks < count($json_result['data'])){ 
    
$category=$json_result['data'][$indeks]['category'];
$indeks++; 
$i++;
// end get data service 
// setting price 
$rate = $price; 
$rate_asli = $rate + 1500; //setting penambahan harga
// setting price 
 $check_services = mysqli_query($db, "SELECT * FROM service_cat WHERE code = '$category'");
            $data_services = mysqli_fetch_assoc($check_orders);
        if(mysqli_num_rows($check_services) > 0) {
            echo "Service Sudah Ada Di database => $category \n <br />";
        } else {
            
$insert=mysqli_query($db, "INSERT INTO service_cat (code,name) VALUES ('$category','$category')");//Memasukan Kepada Database (OPTIONAL)
if($insert == TRUE){
echo"SUKSES INSERT -> Kategori : $category <br />";
}else{
    echo "GAGAL MEMASUKAN DATA";
    
}
}
}
?>