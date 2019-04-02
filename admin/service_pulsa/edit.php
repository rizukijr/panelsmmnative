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
		if (isset($_GET['id'])) {
			$post_sid = $_GET['id'];
			$checkdb_service = mysqli_query($db, "SELECT * FROM services_pulsa WHERE id = '$post_sid'");
			$datadb_service = mysqli_fetch_assoc($checkdb_service);
			if (mysqli_num_rows($checkdb_service) == 0) {
				header("Location: ".$cfg_baseurl."admin/services_pulsa.php");
			} else {
				if (isset($_POST['edit'])) {
					$post_operator = $_POST['operator'];
					$post_name = $_POST['name'];
					$post_price = $_POST['price'];
					$post_pid = $_POST['pid'];
					$post_provider = $_POST['provider'];
					$post_status = $_POST['status'];
					if (empty($post_name) || empty($post_price) || empty($post_pid) || empty($post_provider)) {
						$msg_type = "error";
						$msg_content = "<b>Gagal:</b> Mohon mengisi input.";
					} else if ($post_status != "Active" AND $post_status != "Not active") {
						$msg_type = "error";
						$msg_content = "<b>Gagal:</b> Input tidak sesuai.";
					} else {
						$update_service = mysqli_query($db, "UPDATE services_pulsa SET oprator = '$post_operator', service = '$post_name', price = '$post_price', pid = '$post_pid', provider = '$post_provider', status = '$post_status' WHERE id = '$post_sid'");
						if ($update_service == TRUE) {
							$msg_type = "success";
							$msg_content = "<b>Berhasil:</b> Layanan berhasil diubah.<br /><b>Service ID:</b> $post_sid<br /><b>Service Name:</b> $post_name<br /><b>Operator:</b> $post_operator<br />Price:</b> ".number_format($post_price,0,',','.')."<br /><b>Provider ID:</b> $post_pid<br /><b>Provider Code:</b> $post_provider<br /><b>Status:</b> $post_status";
						} else {
							$msg_type = "error";
							$msg_content = "<b>Gagal:</b> Error system.";
						}
					}
				}
				$checkdb_service = mysqli_query($db, "SELECT * FROM services_pulsa WHERE id = '$post_sid'");
				$datadb_service = mysqli_fetch_assoc($checkdb_service);
				include("../../lib/header.php");
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Ubah Layanan</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li>
                                            <a href="#">Fitur Developer</a>
                                        </li>
                                        <li class="active">
                                            Ubah Layanan
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
                                        <h3 class="panel-title"><i class="mdi mdi-cards-outline"></i> Ubah Layanan</h3>
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
												<label class="col-md-2 control-label">Service ID</label>
												<div class="col-md-10">
													<input type="text" class="form-control" placeholder="Service ID" value="<?php echo $datadb_service['id']; ?>" readonly>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Service Name</label>
												<div class="col-md-10">
													<input type="text" class="form-control" name="name" placeholder="Service Name" value="<?php echo $datadb_service['service']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Operator</label>
												<div class="col-md-10">
													<input type="text" class="form-control" name="operator" placeholder="Operator" value="<?php echo $datadb_service['oprator']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Harga</label>
												<div class="col-md-10">
													<input type="number" class="form-control" name="price" placeholder="Price" value="<?php echo $datadb_service['price']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Provider ID</label>
												<div class="col-md-10">
													<input type="text" class="form-control" name="pid" placeholder="Provider ID" value="<?php echo $datadb_service['pid']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Provider Code</label>
												<div class="col-md-10">
													<select class="form-control" name="provider">
														<option value="MP-PULSA">Medan Pedia Pulsa(Selected)</option>
														<option value="MP-PULSA">Medan Pedia Pulsa</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Status</label>
												<div class="col-md-10">
													<select class="form-control" name="status">
														<option value="<?php echo $datadb_service['status']; ?>"><?php echo $datadb_service['status']; ?> (Selected)</option>
														<option value="Active">Active</option>
														<option value="Not active">Not active</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
												<button type="submit" class="btn btn-info waves-effect w-md waves-light" name="edit">Ubah</button>
											<a href="<?php echo $cfg_baseurl; ?>admin/services_pulsa" class="btn btn-default waves-effect w-md waves-light">Kembali</a>
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
			header("Location: ".$cfg_baseurl."admin/services_pulsa.php");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>