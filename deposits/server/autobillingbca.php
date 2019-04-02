<?php

 

/*---------------------------------------------------------

Author  : VbA

Contact : Admin@TheHoster.Net

---------------------------------------------------------*/

require_once 'class.curl.php';

header("Content-Type:text/plain");

 

$usernameKlikBca="USERNAME-KLIKBCA";  //username klikbca

$passwordKlikBca="PASSWORD";  //password klikbca

$tglCekMulai="16-8-2014";  //tgl mulai cek

$tglCekBerakhir="17-8-2014"; //tgl akhir cek

 

$VCurl= New Vcurl;

$VCurl->setCurl();

$VCurl->disableSSL(true);  //agar support https

$VCurl->enableCookies(true,'klikbca'); //buat cookies bernama klikbca

$VCurl->costumHeader(true,array( //manipulasi header

'Host: ibank.klikbca.com',

'User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0'));

$output=$VCurl->goCurl('https://ibank.klikbca.com/');

if(strstr($output[0],"Please enter Your USER"))

{

$VCurl->disableSSL(true);

$VCurl->enableCookies(true,'klikbca');

$VCurl->costumHeader(true,array(

'Host: ibank.klikbca.com',

'User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0'));

$data = array(

'value(actions)' =>'login',

'value(user_id)' => $usernameKlikBca,

'value(user_ip)' => $_SERVER["REMOTE_ADDR"],

'value(pswd)' => $passwordKlikBca,

'value(Submit)' => 'LOGIN'

);

$VCurl->enablePost(true);

$VCurl->dataPost($data);

$output=$VCurl->goCurl('https://ibank.klikbca.com/authentication.do');

}

$tglStart=explode('-',$tglCekMulai); //pecah tanggal sesuai format yang dibutuhkan di klikbca

$tglEnd=explode('-',$tglCekBerakhir); //pecah tanggal sesuai format yang dibutuhkan di klikbca

$VCurl->enablePost(true);

$VCurl->dataPost(str_replace(" ","+","value(D1)=0&value(r1)=1&value(startDt)=".$tglStart[0]."&value(startMt)=".$tglStart[1]."&value(startYr)=".$tglStart[2]."&value(endDt)=".$tglEnd[0]."&value(endMt)=".$tglEnd[1]."&value(endYr)=".$tglEnd[2]."&value(fDt)=&value(tDt)=&value(submit1)=Lihat Mutasi Rekening"));

$output=$VCurl->goCurl('https://ibank.klikbca.com/accountstmt.do?value(actions)=acctstmtview');

$output= trim(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $output[0])); //karna output hasil curl banyak whitespace, jadi ane bersihkan seluruh whitespace menjadi 1 baris output

preg_match_all('@<td width="130" bgcolor="#(.*?)"><div align="left"><font face="verdana" size="1" color="#(.*?)">(.*?)</font></div></td><td width="30" bgcolor="#(.*?)"><div align="center"><font face="verdana" size="1" color="#(.*?)">0000</font></div></td>@',  $output,$info);

foreach($info[3] as $newInfo)

{

preg_match_all("#TRSF E-BANKING CR<br>(.*?)<br>(.*?)<br>(.*?)<br>(.*?)<br>(.*?)#",$newInfo,$data);

if($data[2] && $data[3]){

$dataTrf['id'][]=trim($data[1][0]);

$dataTrf['total'][]=trim($data[2][0]);

$dataTrf['berita'][]=trim($data[3][0]);

$dataTrf['nama'][]=trim($data[4][0]);

}

}

 

print_r($dataTrf);

 