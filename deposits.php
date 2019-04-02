<?php
session_start();
require_once("mainconfig.php");

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	}

	include("lib/header.php");
	$msg_type = "nothing";
	if (isset($_POST['deposit'])) {
	    $jumlah = $_POST['jumlah'];
	    $payment = $_POST['payment'];
	    $nomor_pengirim = $_POST['nomor_pengirim'];
	    $code = random_number(3);
	    $username = $sess_username;
	    
	    $jumlah = mysqli_real_escape_string($db, $jumlah);
	    $qcheckd= mysqli_query($db,"SELECT * FROM deposit WHERE username = '$username' AND status = 'Pending'");
	    $countd = mysqli_num_rows($qcheckd);
	    
	    $qcs = mysqli_query($db,"SELECT * FROM deposit_method WHERE id = '$payment'");
	    $cs = mysqli_num_rows($qcs);
	    $rows = mysqli_fetch_array($qcs);
	    if (!$jumlah || !$nomor_pengirim) {
	        $msg_type = "error";
	        $msg_content = "Masih ada data yang kosong.";
	    } else if ($countd >= 3) {
	        $msg_type = "error";
	        $msg_content = "Kamu masih memiliki tiket Pending. Segera lakukan pembayaran !";
	    } else if ($qcs == 0) {
	        $msg_type = "error";
	        $msg_content = "Metode pembayaran tidak ditemukan.";
	    } else if ($jumlah < 10000) {
	        $msg_type = "error";
	        $msg_content = "Jumlah terlalu sedikit. Minimal Rp 10.000.";
	    } else {
	        $methodname = $rows['payment'];
	        $balance = $jumlah * $rows['rate'];
	        $insert = mysqli_query($db,"INSERT INTO deposit VALUES ('', '$code', '$username', '$methodname', '$nomor_pengirim','$jumlah', '$balance', 'Pending', '$date')");
	        if ($insert == TRUE) {
	            $jumlah_2 = number_format($jumlah,0,',','.');
	            $saldo = number_format($balance,0,',','.');
	            $payment = $rows['payment'];
	            $msg_type = "success";
	            $msg_content = "<i class='fa fa-circle-done'></i>Kode Deposit : $code<br />
	            Jumlah Transfer :  Rp $jumlah_2<br />
	            Saldo Yang Di Dapat : $balance<br />
	            Nomor Pengirim : $nomor_pengirim <br />
	            Kirimkan Deposit ke Nomor 081212352990 <br />
	            Kemudian Hubungi Staff Untuk Konfirmasi Deposit <br />
	            Lihat Ketentuan deposite disamping";
	            
	            
	        } else {
	            $msg_type = "error";
	            $msg_content = "Error database. (Insert)";
	        }
	    }
	}
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Deposit Manual</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                           Pemesanan Baru
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="panel panel-color panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="mdi mdi-cart"></i> Deposit Manual</h3>
                                    </div>
                                    <div class="panel-body">
										<?php 
										if ($msg_type == "success") {
										?>
										<div class="alert alert-success">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
											<i class="fa fa-check-circle"></i>
											<?php echo $msg_content; ?>
										</div>
										<?php
										} else if ($msg_type == "error") {
										?>
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
											<i class="fa fa-times-circle"></i>
											<?php echo $msg_content; ?>
										</div>
										<?php
										}
										?>
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group">
												<label class="col-md-2 control-label">Kategori</label>
												<div class="col-md-10">
												<select class="form-control" name="payment" id="payment">
													<option value="0">Pilih salah satu.</option>
<?php 
	$sql = mysqli_query($db,"SELECT * FROM deposit_method ORDER BY id ASC");
if (mysqli_num_rows($sql) == 0) { ?>

													<option value="0">Tidak ditemukan!</option>
<?php } else {
	while($data = $sql->fetch_array()){ ?>
													<option value="<?php echo $data['id']; ?>"><?php echo $data['payment']; ?></option>
<?php } }   ?>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label">Nomor Pengirim</label>
											<div class="col-sm-5">
												<input type="text" class="form-control"  placeholder="Nomor Pengirim" id="nomor_pengirim" name="nomor_pengirim">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label">Jumlah</label>
											<div class="col-sm-10">
												<input type="number" class="form-control" name="jumlah" placeholder="Jumlah Deposit" id="jumlah">
											</div>
										</div>
									<div class="form-group">
											<label class="col-sm-2 control-label">Saldo Yang Didapatkan</label>
											<div class="col-sm-5">
												<input type="text" class="form-control"  name="saldo" placeholder="Saldo Yang Didapatkan" id="cutbalance" readonly>
											</div>
										</div>	
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
											<button type="submit" class="btn btn-info waves-effect w-md waves-light" name="deposit">Request Deposit</button>
											<button type="reset" class="btn btn-default waves-effect w-md waves-light">Ulangi</button>
											    </div>
											</div>    
										</form>
									</div>
								</div>
							</div>
                            <div class="col-md-5">
                                <div class="panel panel-color panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="mdi mdi-information-outline"></i> Peraturan Deposit</h3>
                                    </div>
                                    <div class="panel-body">
										<ul>
<li>Pertama, pilih salah satu metode pembayaran deposit.</li>
<li>Kedua, masukkan nomor atau rekening kamu.</li>
<li>Ketiga, masukkan jumlah yang akan kamu transfer.</li>
<li>Keempat, Klik Request Proses dan akan keluar informasi kode depositmu</li>
<li>Kelima, Lakukan pembayaran ke nomor atau rekening tercantum, kemudian hubungi salah satu staff dengan melampirkan bukti dan kode depositmu.</li>
<li>Jika permintaan kamu sesuai dan diterima, maka saldo kamu akan ditambahkan oleh Staff.</li>
<li>Atau anda dapat langsung menghubungi staff untuk melakukan deposit.</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!-- end row -->
<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
  $("#jumlah").change(function(){
    var payment = $("#payment").val();
    var jumlah = $("#jumlah").val();
    $.ajax({
      url : 'rate-depo.php',
      type  : 'POST',
      dataType: 'html',
      data  : 'payment='+payment+'&jumlah='+jumlah,
      success : function(result){
        $("#cutbalance").val(result);
      }
      });
    });  
</script>
<?php
	include("lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>