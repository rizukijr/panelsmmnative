<?php
session_start();
require("../mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	$check_user_data = mysqli_query($db, "SELECT * FROM users_data WHERE username = '$sess_username'");
	$users_data = mysqli_fetch_assoc($check_user_data);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	} else {
		if (isset($_POST['add'])) {
			$post_code = mysqli_real_escape_string($db,$_POST['code']);
			if (empty($post_code)) {
				$msg_type = "error";
				$msg_content = "×</span></button><b>Gagal:</b> Mohon mengisi semua input.";
			} else 	{
$insert_order = mysqli_query($db, "SELECT * FROM voc_reg WHERE kode ='$post_code' AND status ='Active' AND uplink='VOUCHER'");
$data_nya=mysqli_fetch_assoc($insert_order);
$hasil=$data_nya['balance'];
                          if (mysqli_num_rows($insert_order)==TRUE) {
					$msg_type = "success";
					$msg_content = "×</span></button><b>Berhasil:</b> Code Berhasil di Redem Sebesar $hasil";
					$balance=$data_nya['balance'];
					 $update_user = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, catatan, date, time) VALUES ('$sess_username', 'add balance', '$balance', 'Add Balance. Via Code', '$date', '$time')");
			                
            $update = mysqli_query($db, "UPDATE voc_reg set status = 'Not Active' WHERE kode = '$post_code'");
            $update = mysqli_query($db, "UPDATE users set balance = balance+$balance WHERE username = '$sess_username'");
				$saldoskr=$balance+$data_user['balance'];
           
                          } else {
					$msg_type = "error";
					$msg_content = "×</span></button><b>Gagal:</b> Kode Sudah Tidak Aktif/ Tidak Ditemukan ! .";
				}
			}
		}

	include("../lib/header.php");
?>

						<div class="row">
							<div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-credit-card"></i> Deposit Via Code</h5>
                                </div>
                                    <div class="ibox-content">
										<?php 
										if ($msg_type == "success") {
										?>
										<div class="alert alert-success alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><?php echo $msg_content; ?></div>
										<?php
										} else if ($msg_type == "error") {
										?>
									<div class="alert alert-danger alert-dismissible" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><?php echo $msg_content; ?></div>
										<?php
										}
										?>
										<form class="form-horizontal" role="form" method="POST">
										    <div class="alert alert-info">
										       -Jika anda Melakukan Pembelian Code via Admin/Dev silahkan Masukan Disini <br />
										    </div>
										    
									<div class="form-group">
												<label class="col-md-2 control-label">Code</label>
												<div class="col-md-10">
													<input name="code" class="form-control" placeholder="Masukan Code Deposit" >
											</div>
											
											<div class="pull-right">
												<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Ulangi</button>
												<button type="submit" class="btn btn-success btn-bordered waves-effect w-md waves-light" name="add">Redem</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						</div>
								</div>
							</div>
						</div>
						<!-- end row -->
<?php
	include("../lib/footer.php");
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>