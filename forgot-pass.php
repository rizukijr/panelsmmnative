<?php
session_start();
require("mainconfig.php");
$msg_type = "nothing";
function dapetin($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $data = curl_exec($ch);
        curl_close($ch);
                return json_decode($data, true);
}
	if (isset($_POST['reset'])) {
		$post_username = mysqli_real_escape_string($db, trim($_POST['username']));
		$post_email = mysqli_real_escape_string($db, trim($_POST['email']));		
		$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");

        
	    $data_user = mysqli_fetch_assoc($check_user);
		$check_email = mysqli_query($db, "SELECT * FROM users WHERE email = '$post_email'");
	    $data_email = mysqli_fetch_assoc($check_email);	    
			
		if (empty($post_username) || empty($post_email)) {
			$msg_type = "error";
			$msg_content = "<script>swal('Error!', 'Mohon mengisi input.', 'error');</script><b>Gagal:</b> Mohon mengisi input.";
		} else if (mysqli_num_rows($check_user) == 0) {
			$msg_type = "error";
			$msg_content = "<script>swal('Error!', 'User tidak ditemukan.', 'error');</script><b>Gagal:</b> User tidak ditemukan.";
		} else if (mysqli_num_rows($check_email) == 0) {
			$msg_type = "error";
			$msg_content = "<script>swal('Error!', 'Email tidak ditemukan.', 'error');</script><b>Gagal:</b> Email tidak ditemukan.";			
		} else if (strlen($post_username) > 80) {
			$msg_type = "error";
			$msg_content = "<script>swal('Error!', 'Username Maksimal 80 karakter.', 'error');</script><b>Gagal:</b> Username Maksimal 8 karakter.";
		} else {
			
			    $to = $data_user['email'];
                $new_password = random(10);
                $msg = "Password akun anda adalah <b>$new_password.</b>";
                $subject = "Reset Password";
                $header = "From:admin@famediaku.xyz \r\n";
         $header .= "Cc:admin@famediaku.xyz \r\n";
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
                $send = mail ($to, $subject, $msg, $header);
                $send = mysqli_query($db, "UPDATE users SET password = '$new_password' WHERE username = '$post_username'");
                if ($send == true) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil:</b> Kata sandi baru telah dikirim.<META HTTP-EQUIV=Refresh CONTENT=\"0.1; URL=reset-success\">";
                } else {
                    $msg_type = "error";
					$msg_content = "<script>swal('Error!', 'Error system (1).', 'error');</script><b>Gagal:</b> Error system (1).";
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
                                    <h4 class="page-title">Lupa Kata Sandi </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                          Lupa Kata Sandi 
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
                                        <h3 class="panel-title"><i class="mdi mdi-account-multiple"></i> Lupa Kata Sandi </h3>
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
												<label class="col-md-2 control-label">Username</label>
												<div class="col-md-10">
													<input type="text" name="username" class="form-control" placeholder="Username">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Email</label>
												<div class="col-md-10">
													<input type="text" name="email" class="form-control" placeholder="Email">
												</div>
											</div>											
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
													<button type="submit" class="btn btn-info waves-effect w-md waves-light" name="reset">Reset</button>
													<button type="reset" class="btn btn-default waves-effect w-md waves-light">Ulangi</button>

												</div>
											</div>
										</form>
									</div>
									
								</div>
							</div>
						</div>
						<!-- end row -->

<script src='https://www.google.com/recaptcha/api.js'></script>
						
						
<?php
include("lib/footer.php");
?>