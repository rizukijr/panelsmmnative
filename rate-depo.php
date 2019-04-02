<?php
require_once ("mainconfig.php");
$payment = mysqli_real_escape_string($db, $_POST['payment']);
$nominal = mysqli_real_escape_string($db, $_POST['jumlah']);
$check = mysqli_query($db,"SELECT * FROM deposit_method WHERE id = '$payment'");
$hasilcheck = mysqli_fetch_array($check);
$menghitung = $nominal * $hasilcheck['rate'];
echo $menghitung;
?>