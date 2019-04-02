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

	include("../../lib/header.php");

	if (isset($_GET['status'])) {
	    $uid = mysqli_real_escape_string($db, trim($_GET['id']));
	    $status = mysqli_real_escape_string($db, trim($_GET['status']));
	    $qcs = mysqli_query($db,"SELECT * FROM deposit WHERE code = '$uid' AND status = 'Pending'");
	    $cs = mysqli_num_rows($qcs);
	    $rows = mysqli_fetch_array($qcs,MYSQLI_ASSOC);
	    if ($cs == 0) {
	        $msg_type = "error";
	        $msg_content = "Deposit tidak ditemukan.";
	    } else if ($status !== "Error" AND $status !== "Success") {
	        $msg_type = "error";
	        $msg_content = "Status tidak sesuai.";
	    } else {
			    $check_top = mysqli_query($db, "SELECT * FROM top_user WHERE username = '$receiver'");
			    $data_top = mysqli_fetch_assoc($check_top);				    
	        $balance = $rows['saldo'];
	        $receiver = $rows['username'];
	        $update = mysqli_query($db,"UPDATE deposit SET status = '$status' WHERE code = '$uid'");
				$insert_tf = mysqli_query($db, "INSERT INTO balance_history (id, username, action, quantity, msg, date, time) VALUES ('', '$receiver', 'Add Balance', '$balance', 'Penambahan Saldo Dari Kode Deposit : $uid', '$date', '$time')");
	        if ($status == "Success") {
	            $update2 = mysqli_query($db,"UPDATE users SET balance = balance+$balance WHERE username = '$receiver'");
	        }
	        if ($update == TRUE) {
	            if ($status == "Success") {
					if (mysqli_num_rows($check_top) == 0) {
				        $insert_topup = mysqli_query($db, "INSERT INTO top_user (method, username, jumlah, total) VALUES ('Deposit', '$receiver', '$balance', '1')");
				    } else {
				        $insert_topup = mysqli_query($db, "UPDATE top_user SET jumlah = ".$data_top['jumlah']."+$balance, total = ".$data_top['total']."+1 WHERE username = '$receiver' AND method = 'Deposit'");
				    }		                
	                $blnc = number_format($balance,0,',','.');
	                $msg_type = "success";
	                $msg_content = "Request Deposit Dengan Ticket ID #$uid.<br />Berhasil Diterima. Saldo sebesar Rp $blnc berhasil dikirim ke $receiver.";
	            } else {
	                $msg_type = "success";
	                $msg_content = "Berhasil mengubah status deposit #$uid menjadi $status.";
	            }
	        } else {
	            $msg_type = "error";
	            $msg_content = "Error database. (Update)";
	        }
	    }
	}

?>		
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Kelola Deposit</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li>
                                            <a href="#">Fitur Developer</a>
                                        </li>
                                        <li class="active">
                                           Kelola Deposit
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>

                        <div class="row">
                            <div class="col-md-12">
						<?php 
						if ($msg_type == "success") {
						?>
						<div class="alert alert-icon alert-success alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<i class="fa fa-check-circle"></i>
							<?php echo $msg_content; ?>
						</div>
						<?php
						} else if ($msg_type == "error") {
						?>
						<div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<i class="fa fa-times-circle"></i>
							<?php echo $msg_content; ?>
						</div>
						<?php
						}
						?>
                                <div class="panel panel-color panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="ti ti-credit-card"></i> Daftar Deposit</h3>
                                    </div>
								<div class="panel-body">
								<table class="display table table-bordered table-striped" id="dynamic-table">
									<thead>
										<tr>
											<th>Kode</th>
											<th>Username</th>
											<th>No Pengirim</th>
											<th>Payment</th>
											<th>Jumlah</th>
											<th>Saldo diterima</th>
											<th>Status</th>
											<th>Tanggal</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
                                    <?php
                                    $sql = mysqli_query($db,"SELECT * FROM deposit ORDER BY id DESC");
                                    while($data = $sql->fetch_array()){ ?>
										<tr>
											<td><?php echo $data['code']; ?></td>
											<td><?php echo $data['username']; ?></td>
											<td><?php echo $data['nomor_pengirim']; ?></td>
											<td><?php echo $data['payment']; ?></td>
											<td>Rp <?php echo number_format($data['jumlah'],0,',','.'); ?></td>
											<td>Rp <?php echo number_format($data['saldo'],0,',','.'); ?></td>
											<td>
<?php
if ($data['status'] == "Pending") {
$type = "warning";
} else if ($data['status'] == "Error") {
$type = "danger";
} else if ($data['status'] == "Success") {
$type = "success";
} else {
$type = "warning";
} ?>
<span class="label label-table label-<?php echo $type; ?>"><?php echo $data['status']; ?></span></td>
											<td><?php echo $data['date']; ?></td>
											<td>
												<div class="btn-group">
													<button type="button" class="btn-default dropdown-toggle <?php if ($data['status'] == "Success") { ?>disabled<? } ?>" data-toggle="dropdown" aria-expanded="false">Status <span class="caret"></span></button>
													<ul class="dropdown-menu" role="menu">
														<li><a href="<?php echo $cfg_baseurl; ?>admin/deposit/deposit.php?id=<?php echo $data['code']; ?>&status=Error">Error</a></li>
														<li><a href="<?php echo $cfg_baseurl; ?>admin/deposit/deposit.php?id=<?php echo $data['code']; ?>&status=Success">Success</a></li>
													</ul>
												</div>
										</tr>
<? } ?>
									</tbody>
								</table>
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