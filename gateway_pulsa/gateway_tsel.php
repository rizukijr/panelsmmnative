b<?php
/* 
Script Topup Otomatis Via Pulsa TSEL
Creator : Salman El Faris
 */
require_once "config.php";
require_once "EnvayaSMS.php";
require_once "../mainconfig.php";
$request = EnvayaSMS::get_request();
header("Content-Type: {$request->get_response_type()}");
if (!$request->is_validated($PASSWORD))
{
    header("HTTP/1.1 403 Forbidden");
    error_log("Invalid password");    
    echo $request->render_error_response("Invalid password");
    return;
}
$action = $request->get_action();
switch ($action->type)
{
    case EnvayaSMS::ACTION_INCOMING:    
        
        // Send an auto-reply for each incoming message.
    
        $type = strtoupper($action->message_type);
        $isi_pesan = $action->message;
     if($action->from == '858' AND preg_match("/Anda mendapatkan penambahan pulsa/i", $isi_pesan)) {
         $pesan_isi = $action->message;
         $insert_order = mysqli_query($db, "INSERT INTO pesan_tsel (isi, status, date) VALUES ('$pesan_isi', 'UNREAD', '$date')");
         $check_history_topup = mysqli_query($db, "SELECT * FROM history_topup WHERE status = 'NO' AND provider = 'TSEL' AND date = '$date'");
         if (mysqli_num_rows($check_history_topup) == 0) {
                error_log("History TopUp Not Found .");
         } else {
             while($data_history_topup = mysqli_fetch_assoc($check_history_topup)) {
                        $id_history = $data_history_topup['id'];
                        $no_pegirim = $data_history_topup['no_pengirim'];
                        $username_user = $data_history_topup['username'];
                        $amount = $data_history_topup['amount'];
                        $date_transfer = $data_history_topup['date'];
                        $date_type = $data_history_topup['type'];
                        $tanggal = date('m');
                        $jumlah_transfer = $data_history_topup['jumlah_transfer'];
                        $cekpesan = preg_match("/Anda mendapatkan penambahan pulsa Rp $jumlah_transfer dari nomor $no_pegirim tgl $date_transfer/i", $isi_pesan);
                        if($cekpesan == true) {
                            
                            if($date_type == 'WEB') {
                            $update_history_topup = mysqli_query($db, "UPDATE history_topup SET status = 'YES' WHERE id = '$id_history'");
                            $update_history_topup = mysqli_query($db, "UPDATE users SET balance = balance+$amount WHERE username = '$username_user'");
                            } 
                            if($update_history_topup == TRUE) {
                                error_log("Saldo $username_user Telah Ditambahkan Sebesar $amount");
                                 $update_history_topup = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, msg, date, time) VALUES ('$username_user', 'Penambahan Saldo', '$amount', 'Deposit Otomatis anda Via Pulsa Telkomsel telah berhasil ditambahkan oleh Server Sebesar : $amount', '$date', '$time')");
                            } else {
                                error_log("System Error");
                            }
                        } else {
                            error_log("data Transfer Pulsa Tidak Ada");
                        }
                }
         }
     } else {
        error_log("Received $type from {$action->from}");
        error_log(" message: {$action->message}");
     }                     
        
        return;
}