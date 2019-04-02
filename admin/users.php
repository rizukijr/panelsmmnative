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
	} else if ($data_user['level'] != "Developers") {
		header("Location: ".$cfg_baseurl);
	} else {
		if (isset($_POST['delete'])) {
			$post_username = $_POST['username'];
			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			if (mysqli_num_rows($checkdb_user) == 0) {
				$msg_type = "error";
				$msg_content = "<b>Gagal:</b> Pengguna tidak ditemukan.";
			} else {
				$delete_user = mysqli_query($db, "DELETE FROM users WHERE username = '$post_username'");
				if ($delete_user == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Berhasil:</b> Pengguna <b>$post_username</b> dihapus.";
				}
			}
		}

	include("../lib/header.php");
	$check_wuser = mysqli_query($db, "SELECT SUM(balance) AS total FROM users");
	$data_wuser = mysqli_fetch_assoc($check_wuser);
	$check_wuser = mysqli_query($db, "SELECT * FROM users");
	$count_wuser = mysqli_num_rows($check_wuser);
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Kelola Pengguna</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li>
                                            <a href="#">Fitur Developer</a>
                                        </li>
                                        <li class="active">
                                           Kelola Pengguna
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-box widget-box-three">
                                    <div class="bg-icon pull-left">
										<i class="mdi mdi-account-multiple-outline"></i>
									</div>
                                    <div class="text-right">
                                        <p class="text-success m-t-5 text-uppercase font-600 font-secondary">Total Saldo Seluruh Pengguna</p>
                                        <h2 class="m-b-10"><span data-plugin="">Rp <?php echo number_format($data_wuser['total'],0,',','.'); ?> (Dari <?php echo number_format($count_wuser,0,',','.'); ?> pengguna)</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-color panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="mdi mdi-account-multiple-outline"></i> Daftar Pengguna</h3>
                                    </div>
                                    <div class="panel-body">
										<div class="col-md-12">
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
											<div class="alert alert-info">
												<i class="fa fa-check fa-fw"></i>: Pengguna active.<br />
												<i class="fa fa-times fa-fw"></i>: Pengguna suspended.
											</div>
										</div>
										<div class="col-md-6">
											<a href="<?php echo $cfg_baseurl; ?>admin/user/add.php" class="btn btn-info"><i class="fa fa-plus"></i> Tambah</a>
										</div>
										<div class="col-md-6">
											<form method="GET">
											<div class="input-group m-b-20">
												<input type="text" name="search" class="form-control" placeholder="Cari username">
												<span class="input-group-btn">
													<button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
												</span>
											</div>
											</form>
										</div>
										<div class="clearfix"></div>
										<br />
										<div class="col-md-12 table-responsive">
											<table class="table table-striped table-bordered table-hover m-0">
												<thead>
													<tr>
														<th></th>
														<th>Username</th>
														<th>Password</th>
														<th>Level</th>
														<th>Saldo</th>
														<th>Terdaftar</th>
														<th>Uplink</th>
														<th>API Key</th>
														<th>Email</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
												<?php
// start paging config
if (isset($_GET['search'])) {
	$search = $_GET['search'];
	$query_list = "SELECT * FROM users WHERE username LIKE '%$search%' ORDER BY id DESC"; // edit
} else {
	$query_list = "SELECT * FROM users ORDER BY id DESC"; // edit
}
$records_per_page = 30; // edit

$starting_position = 0;
if(isset($_GET["page_no"])) {
	$starting_position = ($_GET["page_no"]-1) * $records_per_page;
}
$new_query = $query_list." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query);
// end paging config
												while ($data_show = mysqli_fetch_assoc($new_query)) {
												?>
													<tr>
														<td align="center"><?php if($data_show['status'] == "Active") { ?><i class="fa fa-check"></i><?php } else { ?><i class="fa fa-times"></i><?php } ?></td>
														<td><?php echo $data_show['username']; ?></td>
														<td><?php echo $data_show['password']; ?></td>
														<td><?php echo $data_show['level']; ?></td>
														<td>Rp <?php echo number_format($data_show['balance'],0,',','.'); ?></td>
														<td><?php echo $data_show['registered']; ?></td>
														<td><?php echo $data_show['uplink']; ?></td>
														<td><?php echo $data_show['api_key']; ?></td>
														<td><?php echo $data_show['email']; ?></td>
														<td align="center">
														<a href="<?php echo $cfg_baseurl; ?>admin/user/edit.php?username=<?php echo $data_show['username']; ?>" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
														<a href="<?php echo $cfg_baseurl; ?>admin/user/delete.php?username=<?php echo $data_show['username']; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
														</td>
													</tr>
												<?php
												}
												?>
												</tbody>
											</table>
											<ul class="pagination">
											<?php
// start paging link
$self = $_SERVER['PHP_SELF'];
$query_list = mysqli_query($db, $query_list);
$total_no_of_records = mysqli_num_rows($query_list);
echo "<li class='disabled'><a href='#'>Total: ".$total_no_of_records."</a></li>";
if($total_no_of_records > 0) {
	$total_no_of_pages = ceil($total_no_of_records/$records_per_page);
	$current_page = 1;
	if(isset($_GET["page_no"])) {
		$current_page = $_GET["page_no"];
	}
	if($current_page != 1) {
		$previous = $current_page-1;
		echo "<li><a href='".$self."?page_no=1'>← First</a></li>";
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
		echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Last →</a></li>";
	}
}
// end paging link
											?>
											</ul>
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