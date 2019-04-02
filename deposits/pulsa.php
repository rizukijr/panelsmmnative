<?php
session_start();
require("../mainconfig.php");

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	}

	include("../lib/header.php");
	$msg_type = "nothing";

	
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	
	$check_depo = mysqli_query($db, "SELECT * FROM topup WHERE user = '$sess_username' AND status = 'Pending'");
		if (isset($_POST['submit'])) {
		$post_method = $_POST['method'];
		$post_quantity = (int)$_POST['quantity'];
		$no_pengirim = $_POST['nopengirim'];
		$nohp=$no_pengirim;
if(!preg_match('/[^+0-9]/',trim($nohp))){
         // cek apakah no hp karakter 1-3 adalah +62
         if(substr(trim($nohp), 0, 3)=='62'){
             $no_pengirim_pulsa = trim($nohp);
         }
         // cek apakah no hp karakter 1 adalah 0
         elseif(substr(trim($nohp), 0, 1)=='0'){
             $no_pengirim_pulsa = '62'.substr(trim($nohp), 1);
         }
     }
    		if($post_method == "081212352990") { ///MASUKIN NOMOR LO
			    $operator = "Deposito saldo via Pulsa TSEL";
			    $quantity = $post_quantity;
			    $provider = "TSEL";
			    $balance_amount = $post_quantity;
    	
    		} else if($post_method == "087894166099") {
		    $post_method="087894166099";
			    $operator = "Deposito saldo via Pulsa XL";
			    $quantity = $post_quantity;
			    $provider = "XL";
			    $balance_amount = $post_quantity;
		} else {
			die("Incorrect input!");
			
		}
         $check_data_history = mysqli_query($db, "SELECT * FROM history_topup WHERE jumlah_transfer = '$quantity' AND no_pengirim = '$no_pengirim_pulsa' AND date = '$date'");
		if ($post_quantity < 1000) {
			$msg_type = "error";
			$msg_content = "<b>Failed:</b> Minimum deposit is 5000";
		} else if(mysqli_num_rows($check_data_history) > 0) {
			$msg_type = "error";
			$msg_content = "<b>Failed:</b> Deposit lewat Transfer Pulsa lebih dari 1x dalam 1 hari dari nomor yang sama harap mentransfer dengan jumlah berbeda dari sebelumnya.";
		} else {
			$insert_topup = mysqli_query($db, "INSERT INTO history_topup VALUES ('$id','$provider','$balance_amount','$quantity','$sess_username','$no_pengirim_pulsa','$date','$time','NO','WEB')");
			if ($insert_topup == TRUE) {
				$msg_type = "success";
				$msg_content = "<b>Permintaan deposito saldo diterima.</b><br /><b>Oprator:</b> $operator<br /><b>Tujuan:</b> $post_method<br /><b>Jumlah:</b> ".number_format($quantity,0,',','.')."<br /><b>Tanggal & Waktu:</b> $date $time<br /><b>Saldo Yang Didapat :</b> $balance_amount";
				$msg_depo = "Silakan transfer Pulsa sebesar <span style='color: red'><b>Rp. ".number_format($quantity,0,',','.')."</b></span> ke Nomor ".$post_method." <br /><span style='color: red'>Silahkan Konfirmasi ke salah satu staff untuk memproses deposit anda.</span><br>
				<span style='color: red'>DIBERIKAN WAKTU 10 MENIT UNTUK TRANSFER PULSA </span><hr>
Jika sudah konfirmasi lewat staff silahkan menunggu 10-30 menit, maka saldo Anda akan otomatis terisi.<br>
pastikan username anda benar saat memberikan konfirmasi ke staff.";
			} else {
				$msg_type = "error";
				$msg_content = "<b>Failed:</b> System error.";
			}
		}
	}
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	
	if(isset($_POST['code'])) {
	    $post_code = $_POST['code'];
	    
	    $select = mysqli_query($db, "SELECT * FROM deposits_history WHERE code = '$post_code'");
	    $datana = mysqli_fetch_assoc($select);
	    
	    if(mysqli_num_rows($select) == 0) {
	        $msg_type = "error";
	        $msg_content = "<b>Gagal:</b> Data tidak di temukan.";
	    } else if($datana['status'] !== "Pending" AND $datana['status'] !== "Processing") {
             $msg_type = "error";
	        $msg_content = "<b>Gagal:</b> Data tidak bisa di batalkan.";	 
	    } else {
	        $update = mysqli_query($db, "UPDATE deposits_history set status = 'Error' WHERE code = '$post_code'");
	        if($update == TRUE) {
	            $msg_type = "success";
	            $msg_content = "Berhasil membatalkan!";
	        } else {
	            $msg_type = "error";
	            $msg_content = "GAGAL MEMBATALKAN #1";
	        }
	    }
	}

 ?>      
     <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
									<?php 
										if ($msg_type == "success") {
										?>
										<div class="alert alert-success">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
											<i class="fa fa-check-circle"></i>
											<?php echo $msg_content; ?>
										</div>
										<div class="alert alert-info" role="alert" style="color: #000;">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
											<i class="fa fa-info-circle"></i>
											<?php echo $msg_depo; ?>
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
																				</div>
																														</div>
																																								</div>
																																																		</div>
																																																												</div>
                    <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Deposit Baru</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                           Deposit Baru
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
<div class="alert alert-info"> Deposit dengan Bank BCA akan mendapat tambahan saldo senilai 10% (HUBUNGI STAFF) ! </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="panel panel-color panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="mdi mdi-money"></i> Deposit Baru</h3>
                                    </div>
                                    <div class="panel-body">
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group">
												<label class="col-md-2 control-label">Metode</label>
												<div class="col-md-10">
													<select class="form-control" name="method" id="method">
														<option value="0">Pilih salah satu...</option>
															<option value="081212352990">Telkomsel #1</option> <!-- ///MASUKIN NOMOR LO -->
																<option value="087894166099">XL #1</option> <!-- ///MASUKIN NOMOR LO -->
																														
													</select>
												</div>
											</div>
                                      	<div class="form-group">
												<label class="col-md-2 control-label">Pengirim</label>
												<div class="col-md-10">
													<input type="text" name="nopengirim" class="form-control" placeholder="628xxxxxx">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Jumlah</label>
												<div class="col-md-10">
												    <div class="input-group">
                                                     <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Jumlah" onkeyup="get_total(this.value).value;">
                                                        
                                                    </div>
												</div>
											</div>
											
											
											<button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="submit">Buat Permintaan Deposit</button>
										</form>
                                        <div class="clearfix"></div>
									</div>
              </div>
              </div>
              <!-- /.tab-pane -->
							
		<div class="col-md-5">
                                <div class="panel panel-color panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="mdi mdi-information-outline"></i> Tutorial Deposit</h3>
                                    </div>
                                    <div class="panel-body">
										
										<iframe width="350" height="200" src="https://www.youtube.com/embed/eU6k-YeAEJw" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
								
              </div>
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
					
						<!-- end row -->
						<!--- Riwayat History --->
						
	 <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-color panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="mdi mdi-history"></i> Riwayat Deposit</h3>
                                    </div>
                                    <div class="panel-body">
											<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover m-0">
												<thead>
													<tr>
														<th>Tanggal</th>
														<th>Time</th>
														<th>Provider</th>
														<th>Nomer Pengirim</th>
														<th>Jumlah</th>
														<th>Saldo didapat</th>
														<th>Status</th>
													
													</tr>
												</thead>
												<tbody>
												<?php
// start paging config
$query_order = "SELECT * FROM history_topup WHERE username = '$sess_username' ORDER BY id DESC"; // edit
$records_per_page = 30; // edit

$starting_position = 0;
if(isset($_GET["page_no"])) {
	$starting_position = ($_GET["page_no"]-1) * $records_per_page;
}
$new_query = $query_order." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query);
// end paging config
												while ($data_order = mysqli_fetch_assoc($new_query)) {
													if($data_order['status'] == "NO") {
													    $statusnya="Waiting";
														$label = "warning";
													} else if($data_order['status'] == "CANCEL") {
													    $statusnya="Canceled";
														$label = "danger";
													} else if($data_order['status'] == "YES") {
													    $statusnya="Success";
														$label = "success";
													}
													$no_pengirimnya=$data_order['no_pengirim'];
													$no_pengrim_asli=str_replace('62','0',$no_pengirimnya);
												?>
													<tr>
														<th><?php echo $data_order['date']; ?></th>
														<th><?php echo $data_order['time']; ?></th>
														<td><?php echo $data_order['provider']; ?></td>
														<td><?php echo $no_pengrim_asli; ?></td>
														<td>Rp <?php echo number_format($data_order['jumlah_transfer'],0,',','.'); ?></td>
														<td>Rp <?php echo number_format($data_order['amount'],0,',','.'); ?></td>
														<td><label class="label label-<?php echo $label; ?>"><?php echo $statusnya; ?></label></td>
														<?php if($data_order['status'] == "NO") { ?>
                                       
                                                    	<?php } ?>			
													</tr>
													<?php
												}
												?>
												</tbody>
											</table>
										</div>
										<ul class="pagination">
											<?php
// start paging link
$self = $_SERVER['PHP_SELF'];
$query_order = mysqli_query($db, $query_order);
$total_no_of_records = mysqli_num_rows($query_order);
echo "<li class='disabled'><a href='#'>Total: ".$total_no_of_records."</a></li>";
if($total_no_of_records > 0) {
	$total_no_of_pages = ceil($total_no_of_records/$records_per_page);
	$current_page = 1;
	if(isset($_GET["page_no"])) {
		$current_page = $_GET["page_no"];
	}
	if($current_page != 1) {
		$previous = $current_page-1;
		echo "<li><a href='".$self."?page_no=1'>? First</a></li>";
		echo "<li><a href='".$self."?page_no=".$previous."'><i class='fa fa-angle-left'></i> Previous</a></li>";
	}
	for($i=1; $i<=$total_no_of_pages; $i++) {
		if($i==$current_page) {
			echo "<li class='active'><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
		} else {
			echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
		}
	}
	if($current_page!=$total_no_of_pages) {
		$next = $current_page+1;
		echo "<li><a href='".$self."?page_no=".$next."'>Next <i class='fa fa-angle-right'></i></a></li>";
		echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Last ?</a></li>";
	}
}
// end paging link
											?>
										</ul>
              </div>
             </div>
             
             
<?php

	include("../lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>
