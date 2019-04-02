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
		if (isset($_POST['add'])) {
			$post_sid = $_POST['sid'];
			$post_name = $_POST['name'];
			$post_operator = $_POST['operator'];
			$post_price = $_POST['price'];
			$post_provider = $_POST['provider'];
			$post_pid = $_POST['pid'];

			$checkdb_service = mysqli_query($db, "SELECT * FROM services_pulsa WHERE sid = '$post_sid'");
			$datadb_service = mysqli_fetch_assoc($checkdb_service);

			if (empty($post_sid) || empty($post_name) || empty($post_operator) || empty($post_price) || empty($post_provider) || empty($post_pid)) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Mohon mengisi semua input.";
			} else if (mysqli_num_rows($checkdb_service) > 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Service ID $post_sid sudah terdaftar di database.";
			} else {
				$insert_service = mysqli_query($db, "INSERT INTO services_pulsa (sid, name, oprator, price, status, pid, provider) VALUES ('$post_sid', '$post_name', '$post_operator', '$post_price', 'Active', '$post_pid', '$post_provider')");
				if ($insert_service == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil:</b> Layanan berhasil ditambahkan.<br /><b>Service ID:</b> $post_sid<br /><b>Service Name:</b> $post_name<br /><b>Operator:</b> $post_operator<br /><b>Harga:</b> ".number_format($post_price,0,',','.')."<br /><b>Provider ID:</b> $post_pid<br /><b>Provider Code:</b> $post_provider";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal:</b> Error system.";
				}
			}
		}

	include("../../lib/header.php");
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Tambah Layanan</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li>
                                            <a href="#">Fitur Developer</a>
                                        </li>
                                        <li class="active">
                                            Tambah Layanan
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
                                        <h3 class="panel-title"><i class="fa fa-plus"></i> Tambah Layanan </h3> 
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
													<input type="number" name="sid" class="form-control" placeholder="Service ID">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Service Name</label>
												<div class="col-md-10">
													<input type="text" name="name" class="form-control" placeholder="Service Name">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Operator</label>
												<div class="col-md-10">
													<input type="text" name="operator" class="form-control" placeholder="Etc: TELKOMSEL, AXIS">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Harga</label>
												<div class="col-md-10">
													<input type="number" name="price" class="form-control" placeholder="Harga">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Provider ID</label>
												<div class="col-md-10">
													<input type="number" name="pid" class="form-control" placeholder="Provider ID">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Provider Code</label>
												<div class="col-md-10">
													<select class="form-control" name="provider">
														<option value="MP-PULSA">MEDANPEDIA PULSA</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
											<button type="submit" class="btn btn-info btn-bordered waves-effect w-md waves-light" name="add"><i class="fa fa-plus"></i> Tambah</button>
											<a href="<?php echo $cfg_baseurl; ?>admin/services_pulsa" class="btn btn-default btn-bordered waves-effect w-md waves-light"><i class="fa fa-refresh"></i> Kembali </a>
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
	header("Location: ".$cfg_baseurl);
}
?>