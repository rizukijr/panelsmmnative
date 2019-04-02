<?php
session_start();
require("mainconfig.php");

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	}
}

include("lib/header.php");
?>

            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Staff list</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                           Staff list
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
                                        <h3 class="panel-title"><i class="mdi mdi-users"></i> Staff list</h3>
                                    </div>
                                    <div class="panel-body">
						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-info">
									<i class="fa fa-info-circle"></i> ANDA DAPAT MENGHUBUNGI KAMI UNTUK MENGISI SALDO
								</div>
							</div> <!-- end col -->
								<div class="col-md-5">
									
									<div class="white-box text-center bg-info" style="padding: 20px 0;">
										<img src="assets/images/icons/getmo.png" class="img-thumbnail" style="width: 100px; border-radius: 50px;"><br />
										<h3 class="text-white text-uppercase">Fuad Akmal</h3>
										<p class="text-white text-uppercase">CEO</p>
										<p class="text-white">WhatsApp : </p><a href="https://api.whatsapp.com/send?phone=6283175889459" target="_blank">083175889459</a>
										<p class="text-white">Facebook : </p><a href="https://facebook.com/fuadakmaI" target="_blank">Fuad Akmal</a>
									</div>
								</div> <!-- end col -->
								
								<div class="col-md-5">
									
									<div class="white-box text-center bg-info" style="padding: 20px 0;">
										<<img src="assets/images/icons/getmo.png" class="img-thumbnail" style="width: 100px; border-radius: 50px;"><br />
										<h3 class="text-white text-uppercase">Bima Hangesa</h3>
										<p class="text-white text-uppercase">FOUNDER</p>
										<p class="text-white">WhatsApp : </p><a href="https://api.whatsapp.com/send?phone=6282211365134" target="_blank">082211365134</a>
										<p class="text-white">Facebook : </p><a href="https://facebook.com/hangesa.bima" target="_blank">Bima Hangesa Vector</a>
									</div>
								</div> <!-- end col -->
						</div>
						<!-- end row -->
								

                               
                               <div></div>

                              
</p>
                                </div>
                            </div>
                        </div>
                    </div>
						<!-- end row -->
<?php
include("lib/footer.php");
?>