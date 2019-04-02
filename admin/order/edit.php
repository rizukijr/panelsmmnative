<?php
session_start();
require("../../mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['level'] != "Developers") {
		header("Location: ".$cfg_baseurl);
	} else {
		if (isset($_GET['oid'])) {
			$post_oid = $_GET['oid'];
			$checkdb_order = mysqli_query($db, "SELECT * FROM orders WHERE oid = '$post_oid'");
			$datadb_order = mysqli_fetch_assoc($checkdb_order);
			if (mysqli_num_rows($checkdb_order) == 0) {
				header("Location: ".$cfg_baseurl."admin/orders.php");
			} else if ($datadb_order['status'] == "Success" || $datadb_order['status'] == "Error" || $datadb_order['status'] == "Partial") {
				header("Location: ".$cfg_baseurl."admin/orders.php");
			} else {
				if (isset($_POST['edit'])) {
					$post_status = $_POST['status'];
					$post_start = $_POST['start_count'];
					$post_remains = $_POST['remains'];
					if ($post_status != "Pending" AND $post_status != "Processing" AND $post_status != "Error" AND $post_status != "Partial" AND $post_status != "Success") {
						$msg_type = "error";
						$msg_content = "<b>Gagal:</b> Input tidak sesuai.";
					} else {
						$update_order = mysqli_query($db, "UPDATE orders SET start_count = '$post_start', remains = '$post_remains', status = '$post_status' WHERE oid = '$post_oid'");
						if ($update_order == TRUE) {
							$msg_type = "success";
							$msg_content = "<b>Berhasil:</b> Pesanan berhasil diubah.<br /><b>Order ID:</b> $post_oid<br /><b>Status:</b> $post_status<br /><b>Start Count:</b> ".number_format($post_start,0,',','.')."<br /><b>Remains:</b> ".number_format($post_remains,0,',','.');
						} else {
							$msg_type = "error";
							$msg_content = "<b>Gagal:</b> Error system.";
						}
					}
				}
				$checkdb_order = mysqli_query($db, "SELECT * FROM orders WHERE oid = '$post_oid'");
				$datadb_order = mysqli_fetch_assoc($checkdb_order);
				include("../../lib/header.php");
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Ubah Pesanan</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li>
                                            <a href="#">Fitur Developer</a>
                                        </li>
                                        <li class="active">
                                            Ubah Pesanan
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-color panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="mdi mdi-cart"></i> Ubah Pesanan</h3>
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
												<label class="col-md-2 control-label">Order ID</label>
												<div class="col-md-10">
													<input type="text" class="form-control" placeholder="Order ID" value="<?php echo $datadb_order['oid']; ?>" readonly>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Start Count</label>
												<div class="col-md-10">
													<input type="number" name="start_count" class="form-control" placeholder="Start Count" value="<?php echo $datadb_order['start_count']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Remains</label>
												<div class="col-md-10">
													<input type="number" name="remains" class="form-control" placeholder="Remains" value="<?php echo $datadb_order['remains']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Status</label>
												<div class="col-md-10">
													<select class="form-control" name="status">
														<option value="<?php echo $datadb_order['status']; ?>"><?php echo $datadb_order['status']; ?> (Selected)</option>
														<option value="Pending">Pending</option>
														<option value="Processing">Processing</option>
														<option value="Error">Error</option>
														<option value="Partial">Partial</option>
														<option value="Success">Success</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
											<button type="submit" class="btn btn-info waves-effect w-md waves-light" name="edit">Ubah</button>
											<a href="<?php echo $cfg_baseurl; ?>admin/orders" class="btn btn-default waves-effect w-md waves-light">Kembali ke daftar</a>												
											    </div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- end row -->
<?php
				include("../../lib/footer.php");
			}
		} else {
			header("Location: ".$cfg_baseurl."admin/orders.php");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>