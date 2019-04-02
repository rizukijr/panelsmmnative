<?php
require("../../mainconfig.php");
require("function_bca1.php");
$methode = "BANK BCA";

$get = mysqli_query($db, "SELECT * FROM deposits WHERE status = 'Pending' AND method = '$methode'");
if(mysqli_num_rows($get) == 0) {
	die("No data available");
} else {
    while($data_deposito = mysqli_fetch_assoc($get)) {
        $invoice = $data_deposito['code'];
        $user = $data_deposito['user'];
        $jumlah = $data_deposito['quantity'];
        $balance = $data_deposito['balance'];
        
        $check = check_bca($jumlah);
        
        if($check == "sukses") {
            $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$user'");
	$data_user = mysqli_fetch_assoc($check_user);
             $saldoskr=$balance+$data_user['balance'];
            $pesannya="Atlantic-Pedia:thank you for adding balance of ".number_format($balance,0,',','.')." Rupiah your balance now Rp ".number_format($saldoskr,0,',','.')." Rupiah";
$nomerhp=$data_user['nohp'];
$postdata = "api_key=fGuHn-ZCESX-GMXia-yz8zZ&pesan=$pesannya&nomer=$nomerhp";
$apibase= "https://serverh2h.web.id/sms_gateway";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apibase);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$chresult = curl_exec($ch);
curl_close($ch);
$json_result = json_decode($chresult, true);
            $update_user = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, catatan, date, time) VALUES ('$user', 'add balance', '$balance', 'Add Balance. Via Auto Deposit BCA', '$date', '$time')");
			                
            $update = mysqli_query($db, "UPDATE deposits set status = 'Success' WHERE code = '$invoice'");
            $update = mysqli_query($db, "UPDATE users set balance = balance+$balance WHERE username = '$user'");
            if($update) {
              echo "Faktur Pembayaran untuk $invoice => Telah berhasil di update menjadi Sukses <br />";
            } else {
                echo "Gagal Update!!! <br />";
            }
        } else {
           echo "Faktur Pembayaran untuk $invoice => Dana tidak diterima <br /> $jumlah";
      }
   }
}


