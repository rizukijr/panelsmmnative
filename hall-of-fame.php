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

	include("lib/header.php");
	$msg_type = "nothing";

?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Top Pengguna</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                           Top Pengguna
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
        <h1 class="text-center"><i class="fa fa-bullhorn"></i> Terimakasih atas partisipasi anda!</h1>
        <h2 class="text-center">Kami official <?php echo $cfg_webname; ?> mengucapkan terima kasih kepada para pengguna setia <?php echo $cfg_webname; ?>!</h2><hr>
<div class="row">
            <div class="col-lg-12">
                <div class="panel panel-border panel-danger">
                    <div class="panel-heading">
                        <h3 class="header-title"><center><i class="fa fa-trophy"></i>Top 5 Layanan Sosial Media</center></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 table-responsive">
                    	<table class="table table-striped table-bordered table-hover m-0">
                    		<thead>
								<tr>
									<th>Peringkat</th>
									<th>Nama Layanan</th>
									<th>Jumlah Pesanan</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 1;
                                $query_list = mysqli_query($db, "select A.* from top_layanan A inner join (SELECT layanan,max(jumlah) as maxRev FROM top_layanan group by layanan) B on A.layanan=B.layanan and A.jumlah=B.maxRev ORDER BY jumlah DESC LIMIT 5"); // edit
								while ($data_hof = mysqli_fetch_assoc($query_list)) {
								if ($no == 1) {
									$label = "danger";
								} else if ($no == 2) {
									$label = "warning";
								} else if ($no == 3) {
									$label = "success";
								} else if ($no == 4) {
									$label = "primary";
								} else if ($no == 5) {
									$label = "default";
								}
							    ?>
								<tr>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $no; ?></span></td>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_hof['layanan']; ?></span></td>
									<td>Rp <?php echo number_format($data_hof['jumlah'],0,',','.'); ?> <span class="badge badge-<?php echo $label; ?>">(dari <?php echo number_format($data_hof['total'],0,',','.'); ?> pesanan)</span></td>
								</tr>
								<?php
								$no++;
								}
								?>
							</tbody>
						</table>
                    </div>
                </div> 
                </div>
                </div>
    </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-border panel-danger">
                    <div class="panel-heading">
                        <h3 class="header-title"><center><i class="fa fa-trophy"></i>Top 5 Pengguna Dengan Pembelian Sosial Media Tertinggi</center></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 table-responsive">
                    	<table class="table table-striped table-bordered table-hover m-0">
                    		<thead>
								<tr>
									<th>Peringkat</th>
									<th>Nama Pengguna</th>
									<th>Jumlah Pesanan</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 1;
                                $query_list = mysqli_query($db, "select A.* from top_users A inner join (SELECT username,max(jumlah) as maxRev FROM top_users group by username) B on A.username=B.username and A.jumlah=B.maxRev ORDER BY jumlah DESC LIMIT 5"); // edit
								while ($data_hof = mysqli_fetch_assoc($query_list)) {
								if ($no == 1) {
									$label = "danger";
								} else if ($no == 2) {
									$label = "warning";
								} else if ($no == 3) {
									$label = "success";
								} else if ($no == 4) {
									$label = "primary";
								} else if ($no == 5) {
									$label = "default";
								}
							    ?>
								<tr>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $no; ?></span></td>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_hof['username']; ?></span></td>
									<td>Rp <?php echo number_format($data_hof['jumlah'],0,',','.'); ?> <span class="badge badge-<?php echo $label; ?>">(dari <?php echo number_format($data_hof['total'],0,',','.'); ?> pesanan)</span></td>
								</tr>
								<?php
								$no++;
								}
								?>
							</tbody>
						</table>
                    </div>
                </div> 
            </div>
        </div>
            <div class="col-lg-6">
                <div class="panel panel-border panel-danger">
                    <div class="panel-heading">
                        <h3 class="header-title"><center><i class="fa fa-trophy"></i>Top 5 Pengguna Dengan Deposit Tertinggi</center></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 table-responsive">
                    	<table class="table table-striped table-bordered table-hover m-0">
                    		<thead>
								<tr>
									<th>Peringkat</th>
									<th>Nama Pengguna</th>
									<th>Jumlah Pesanan</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 1;
                                $query_list = mysqli_query($db, "select A.* from top_depo A inner join (SELECT username,max(jumlah) as maxRev FROM top_depo group by username) B on A.username=B.username and A.jumlah=B.maxRev ORDER BY jumlah DESC LIMIT 5"); // edit
								while ($data_hof = mysqli_fetch_assoc($query_list)) {
								if ($no == 1) {
									$label = "danger";
								} else if ($no == 2) {
									$label = "warning";
								} else if ($no == 3) {
									$label = "success";
								} else if ($no == 4) {
									$label = "primary";
								} else if ($no == 5) {
									$label = "default";
								}
							    ?>
								<tr>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $no; ?></span></td>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_hof['username']; ?></span></td>
									<td>Rp <?php echo number_format($data_hof['jumlah'],0,',','.'); ?> <span class="badge badge-<?php echo $label; ?>">(dari <?php echo number_format($data_hof['total'],0,',','.'); ?> deposit)</span></td>
								</tr>
								<?php
								$no++;
								}
								?>
							</tbody>
						</table>
                    </div>
                </div> 
                </div>
        </div>
            <div class="col-lg-6">
                <div class="panel panel-border panel-danger">
                    <div class="panel-heading">
                        <h3 class="header-title"><center><i class="fa fa-trophy"></i>Top 5 Pengguna Dengan Pesanan Pulsa Tertinggi</center></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 table-responsive">
                    	<table class="table table-striped table-bordered table-hover m-0">
                    		<thead>
								<tr>
									<th>Peringkat</th>
									<th>Nama Pengguna</th>
									<th>Jumlah Pesanan</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 1;
                                $query_list = mysqli_query($db, "select A.* from top_userp A inner join (SELECT username,max(jumlah) as maxRev FROM top_userp group by username) B on A.username=B.username and A.jumlah=B.maxRev ORDER BY jumlah DESC LIMIT 5"); // edit
								while ($data_hof = mysqli_fetch_assoc($query_list)) {
								if ($no == 1) {
									$label = "danger";
								} else if ($no == 2) {
									$label = "warning";
								} else if ($no == 3) {
									$label = "success";
								} else if ($no == 4) {
									$label = "primary";
								} else if ($no == 5) {
									$label = "default";
								}
							    ?>
								<tr>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $no; ?></span></td>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_hof['username']; ?></span></td>
									<td>Rp <?php echo number_format($data_hof['jumlah'],0,',','.'); ?> <span class="badge badge-<?php echo $label; ?>">(dari <?php echo number_format($data_hof['total'],0,',','.'); ?> Pesanan)</span></td>
								</tr>
								<?php
								$no++;
								}
								?>
							</tbody>
						</table>
                    </div>
                </div> 
                </div>
        </div>
            <div class="col-lg-6">
                <div class="panel panel-border panel-danger">
                    <div class="panel-heading">
                        <h3 class="header-title"><center><i class="fa fa-trophy"></i>Top 5 Pengguna Dengan Penambahan User Terbanyak</center></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 table-responsive">
                    	<table class="table table-striped table-bordered table-hover m-0">
                    		<thead>
								<tr>
									<th>Peringkat</th>
									<th>Nama Pengguna</th>
									<th>Jumlah User Yang Di Daftarkan</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 1;
                                $query_list = mysqli_query($db, "select A.* from top_reff A inner join (SELECT username,max(jumlah) as maxRev FROM top_reff group by username) B on A.username=B.username and A.jumlah=B.maxRev ORDER BY jumlah DESC LIMIT 5"); // edit
								while ($data_hof = mysqli_fetch_assoc($query_list)) {
								if ($no == 1) {
									$label = "danger";
								} else if ($no == 2) {
									$label = "warning";
								} else if ($no == 3) {
									$label = "success";
								} else if ($no == 4) {
									$label = "primary";
								} else if ($no == 5) {
									$label = "default";
								}
							    ?>
								<tr>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $no; ?></span></td>
									<td><span class="badge badge-<?php echo $label; ?>"><?php echo $data_hof['username']; ?></span></td>
									<td> <span class="badge badge-<?php echo $label; ?>"> <?php echo number_format($data_hof['total'],0,',','.'); ?> User</span></td>
								</tr>
								<?php
								$no++;
								}
								?>
							</tbody>
						</table>
                    </div>
                </div> 
                </div>
                </div>
    </div>
</div>
</div>
						<!-- end row -->
<?php
	include("lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>