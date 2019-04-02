<?php
session_start();
require("mainconfig.php");
$msg_type = "nothing";

	if (isset($_POST['verif'])) {
		$post_code = mysqli_real_escape_string($db, trim($_POST['code']));
		$check_code = mysqli_query($db, "SELECT * FROM users WHERE verif_code = '$post_code'");
	    $data_user = mysqli_fetch_assoc($check_user);
			
		if (empty($post_code)) {
			$msg_type = "error";
			$msg_content = "<script>swal('Error!', 'Mohon mengisi input.', 'error');</script><b>Gagal:</b> Mohon mengisi input.";
		} else if (mysqli_num_rows($check_code) == 0) {
			$msg_type = "error";
			$msg_content = "<script>swal('Error!', 'Kode verifikasi tidak ditemukan.', 'error');</script><b>Gagal:</b> Verification Code tidak ditemukan.";
		} else if ($_POST['captcha'] !== "manusia") {
			$msg_type = "error";
			$msg_content = "<script>swal('Error!', 'Sila Pilih Captcha.', 'error');</script><b>Gagal:</b> Sila Pilih Captcha.";
		} else {

    $update_user = mysqli_query($db, "UPDATE users SET status = 'Active' WHERE verif_code = '$post_code'");
    if ($update_user == TRUE) {
           $msg_type = "success";
		   $msg_content = "<b>Berjaya:</b> Accoun berhasil Di Verified."; 
    } else {
           $msg_type = "error";
		   $msg_content = "<b>Gagal:</b> ERROR.";        
        }	   
	}
}
include_once("lib/header.php");
?>

            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title"> </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                          Verification Account
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>

                        <div class="row">
                            <div class="col-md-2"></div><div class="col-md-8">
                                <div class="panel panel-color panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="mdi mdi-account-key"></i> Verification Account </h3>
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
												<label class="col-md-2 control-label">Code Verification</label>
												<div class="col-md-10">
													<input type="number" name="code" class="form-control" placeholder="Code Verification">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Captcha</label>
												<div class="col-md-10">
													<select class="form-control" id="captcha" name="captcha">
												<option selected="true" style="display:none;">Apakah anda manusia ?</option>
												<option value="manusia">Yes</option>
								</select>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
													<button type="submit" class="btn btn-info waves-effect w-md waves-light" name="verif">Submit</button>
													<button type="reset" class="btn btn-default waves-effect w-md waves-light">Reset</button>

												</div>
											</div>
										</form>
									</div>
									
								</div>
							</div>
						</div>
						<!-- end row -->
						
<?php
include("lib/footer.php");
?>