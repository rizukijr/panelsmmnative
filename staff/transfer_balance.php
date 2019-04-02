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
	} else if ($data_user['level'] == "Member") {
		header("Location: ".$cfg_baseurl);
	} else {
		if (isset($_POST['add'])) {
			$post_username = $_POST['username'];
			$post_balance = $_POST['balance'];

			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
			if (empty($post_username) || empty($post_balance)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Mohon mengisi semua input.";
			} else if (mysqli_num_rows($checkdb_user) == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> User $post_username tidak ditemukan.";
			} else if ($post_balance < $cfg_min_transfer) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Minimal transfer adalah Rp $cfg_min_transfer.";
			} else if ($data_user['balance'] < $post_balance) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Saldo Anda tidak mencukupi untuk melakukan transfer dengan jumlah tersebut.";
			} else {
			    $check_top = mysqli_query($db, "SELECT * FROM top_depo WHERE username = '$post_username'");
			    $data_top = mysqli_fetch_assoc($check_top);				    
				$insert_tf = mysqli_query($db, "UPDATE users SET balance = balance-$post_balance WHERE username = '$sess_username'"); // cut sender
				$insert_tf = mysqli_query($db, "UPDATE users SET balance = balance+$post_balance WHERE username = '$post_username'"); // send receiver
				$insert_tf = mysqli_query($db, "INSERT INTO transfer_balance (sender, receiver, quantity, date) VALUES ('$sess_username', '$post_username', '$post_balance', '$date')");
				$insert_tf = mysqli_query($db, "INSERT INTO balance_history (id, username, action, quantity, msg, date, time) VALUES ('', '$post_username', 'Add Balance', '$post_balance', 'Saldo Ditambahkan Oleh $sess_username Sebesar $post_balance', '$date', '$time')");
			    $insert_tf = mysqli_query($db, "INSERT INTO balance_history (id, username, action, quantity, msg, date, time) VALUES ('', '$sess_username', 'Cut Balance', '$post_balance', 'Saldo Ditransfer Ke $post_username Sebesar $post_balance', '$date', '$time')");
				if ($insert_tf == TRUE) {
					if (mysqli_num_rows($check_top) == 0) {
				        $insert_topup = mysqli_query($db, "INSERT INTO top_depo (method, username, jumlah, total) VALUES ('Deposit', '$post_username', '$post_balance', '1')");
				    } else {
				        $insert_topup = mysqli_query($db, "UPDATE top_depo SET jumlah = ".$data_top['jumlah']."+$post_balance, total = ".$data_top['total']."+1 WHERE username = '$post_username' AND method = 'Deposit'");
				    }					    
					$msg_type = "success";
					$msg_content = "<b>Berhasil:</b> Transfer saldo berhasil.<br /><b>Pengirim:</b> $sess_username<br /><b>Penerima:</b> $post_username<br /><b>Jumlah Transfer:</b> Rp ".number_format($post_balance,0,',','.')." Saldo";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal:</b> Error system.";
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
                                    <h4 class="page-title">Transfer Saldo</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li>
                                            <a href="#">Fitur Staff</a>
                                        </li>
                                        <li class="active">
                                            Transfer Saldo
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
                                        <h3 class="panel-title"><i class="mdi mdi-account-multiple-outline"></i> Transfer Saldo</h3>
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
												- Saldo Anda akan dipotong sesuai jumlah transfer saldo.<br />
												- Minimal transfer Rp <?php echo number_format($cfg_min_transfer,0,',','.'); ?>.
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Username Penerima</label>
												<div class="col-md-10">
													<input type="text" name="username" class="form-control" placeholder="Username">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Jumlah Transfer</label>
												<div class="col-md-10">
													<input type="number" name="balance" class="form-control" placeholder="Jumlah Transfer">
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
												<button type="submit" class="btn btn-info waves-effect w-md waves-light" name="add">Transfer</button>
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