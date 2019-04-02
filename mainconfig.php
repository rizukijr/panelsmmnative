<?php
// Hargailah orang lain jika Anda ingin dihargai

date_default_timezone_set('Asia/Jakarta');
error_reporting(0);

$cfg_mt = 0; // Maintenance? 1 = ya 0 = tidak
if($cfg_mt == 1) {
    die("Site under Maintenance.");
}

// web
$cfg_webtitle = "Famediaku - Website Penyedia Layanan Sosial Media, Pulsa & PPOB";
$cfg_webname = "Famediaku";
$cfg_baseurl = "https://panel.famediaku.net/";
$cfg_desc = "Famediaku Merupakan sebuah website penyedia layanan sosial media & Pulsa serta PPOB termurah, cepat & berkualitas.";
$cfg_author = "Fuad Akmad";
$logo_white = "Fa";
$logo_blue = "mediaku";
$cfg_about = "Famediaku Merupakan sebuah website penyedia layanan sosial media & Pulsa serta PPOB termurah, cepat & berkualitas.";

// fitur staff
$cfg_min_transfer = 5000; // jumlah minimal transfer saldo
$cfg_member_price = 20000; // harga pendaftaran member
$cfg_member_bonus = 10000; // bonus saldo member
$cfg_agen_price = 40000; // harga pendaftaran agen
$cfg_agen_bonus = 15000; // bonus saldo agen
$cfg_reseller_price = 100000; // harga pendaftaran reseller
$cfg_reseller_bonus = 50000; // bonus saldo reseller
$cfg_admin_price = 200000; // harga pendaftaran admin
$cfg_admin_bonus = 100000; // bonus saldo admin

// database
$db_server = "localhost";
$db_user = "famediak_panel";
$db_password = "famediak_panel";
$db_name = "famediak_panel";

// date & time
$date = date("Y-m-d");
$time = date("H:i:s");

// require
require("lib/database.php");
require("lib/function.php");