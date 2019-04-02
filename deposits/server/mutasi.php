<?php
require 'BCAParser.php';
$Parser = new BCAParser('DHIFOAKS1503', '257215');
$oneday_ago=date('Y-m-d',strtotime("-1 day"));
$date_now=date('Y-m-d');

$Html = $Parser->getMutasiRekening($oneday_ago, $date_now);

$Parser->logout();
$jumlah="400814";
if (!function_exists('fix_angka'))
{
    function fix_angka($string)
    {
        $string = str_replace(',', '', $string);
        $string = strtok($string, '.');
        return $string;
    }
}

  $exp = explode('<b>Saldo</b></font></div></td>', $Parser);
    $invoices = array();
    $lunas = array();
    $jkt_time = time() + (3600 * 7);
    $tahun = date('Y', $jkt_time);
    if (isset($exp[1]))
    {
        $table = explode("</table>", $exp[1]);
        $tr = explode("<tr>", $table[0]);
        for ($i = 1; $i < count($tr); $i++)
        {
            $str = str_ireplace('</font>', '#~#~#</font>', $tr[$i]);
            $str = str_ireplace('<br>', '<br> ', $str);
            $str = preg_replace('!\s+!', ' ', trim(strip_tags($str)));
            $arr = array_map('trim', explode("#~#~#", $str));
            $tgl = $arr[0] . '/' . $tahun;
            $keterangan = $arr[1];
            $kredit = fix_angka($arr[3]);
            $status = $arr[4];
            if($kredit == $jumlah){
            $result = "sukses";
            echo $result;
            }
        }
        return $result;
    }
    