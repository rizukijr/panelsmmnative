<?php
session_start();
require("../mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['level'] == "Member" OR $data_user['level'] == "Agen" OR $data_user['level'] == "Reseller") {
		header("Location: ".$cfg_baseurl);
	} else {
		$post_balance = $cfg_reseller_bonus; // bonus reseller
		$post_price = $cfg_reseller_price; // price reseller for registrant
		if (isset($_POST['add'])) {
			$post_username = trim($_POST['username']);
			$post_password = $_POST['password'];
			$post_email = $_POST['email'];

			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
			if (empty($post_username) || empty($post_password)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Mohon mengisi semua input.";
			} else if (mysqli_num_rows($checkdb_user) > 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Username $post_username sudah terdaftar dalam database.";
			} else if ($data_user['balance'] < $post_price) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Saldo Anda tidak mencukupi untuk melakukan pendaftaran Reseller.";
			} else {
				$post_api = random(20);
			    $check_top = mysqli_query($db, "SELECT * FROM top_reff WHERE username = '$sess_username'");
			    $data_top = mysqli_fetch_assoc($check_top);
				$update_user = mysqli_query($db, "UPDATE users SET balance = balance-$post_price WHERE username = '$sess_username'");
				if ($update_user == TRUE) {
                $insert_user = mysqli_query($db, "INSERT INTO balance_history (username, action, quantity, msg, date, time) VALUES ('$sess_username', 'Cut Balance', '$post_price' , 'Saldo dipotong untuk Penambahan user : $post_username dengan level Resseler', '$date', 'time')");	
				$insert_user = mysqli_query($db, "INSERT INTO users (username, password, balance, level, registered, status, api_key, email, uplink) VALUES ('$post_username', '$post_password', '$post_balance', 'Reseller', '$date', 'Active', '$post_api', '$post_email', '$sess_username')");
				if ($insert_user == TRUE) {
					if (mysqli_num_rows($check_top) == 0) {
				        $insert_topup = mysqli_query($db, "INSERT INTO top_reff (method, username, jumlah, total) VALUES ('Order', '$sess_username', '$price', '1')");
				    } else {
				        $insert_topup = mysqli_query($db, "UPDATE top_reff SET jumlah = ".$data_top['jumlah']."+$post_price, total = ".$data_top['total']."+1 WHERE username = '$sess_username' AND method = 'Order'");
				    }
					$msg_type = "success";
					$msg_content = "<b>Berhasil:</b> Reseller telah ditambahkan.<br /><b>Username:</b> $post_username<br /><b>Password:</b> $post_password<br /><b>Level:</b> Reseller<br /><b>Saldo:</b> Rp ".number_format($post_balance,0,',','.');
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal:</b> Error system.";
				}
			}
		}
	}
	include("../lib/header.php");
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Tambah Reseller</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li>
                                            <a href="#">Fitur Staff</a>
                                        </li>
                                        <li class="active">
                                            Tambah Reseller
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
                                        <h3 class="panel-title"><i class="mdi mdi-account-multiple-outline"></i> Tambah Reseller</h3>
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
											<div class="alert alert-info">
												- Saldo Anda terpotong Rp <?php echo number_format($post_price,0,',','.'); ?> untuk 1x pendaftaran.<br />
												- Pengguna baru akan mendapat saldo Rp. <?php echo number_format($post_balance,0,',','.'); ?>.
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">E-Mail</label>
												<div class="col-md-10">
													<input type="text" name="email" class="form-control" placeholder="E-Mail Aktif">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Username</label>
												<div class="col-md-10">
													<input type="text" name="username" class="form-control" placeholder="Username">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Password</label>
												<div class="col-md-10">
													<input type="text" name="password" class="form-control" placeholder="Password">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
												<button type="submit" class="btn btn-info waves-effect w-md waves-light" name="add">Tambah</button>
												<button type="reset" class="btn btn-default waves-effect w-md waves-light">Ulangi</button>
											</div>
										</div>
										</form>
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