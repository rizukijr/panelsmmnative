<?php
session_start();
require("mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
    }

        // Data Order Sosmed in Line Chart
    $check_order_today = mysqli_query($db, "SELECT * FROM orders WHERE date ='$date' and user = '$sess_username'");
    
    $oneday_ago = date('Y-m-d', strtotime("-1 day"));
    $check_order_oneday_ago = mysqli_query($db, "SELECT * FROM orders WHERE date ='$oneday_ago' and user = '$sess_username'");
    
    $twodays_ago = date('Y-m-d', strtotime("-2 day"));
    $check_order_twodays_ago = mysqli_query($db, "SELECT * FROM orders WHERE date ='$twodays_ago' and user = '$sess_username'");
    
    $threedays_ago = date('Y-m-d', strtotime("-3 day"));
    $check_order_threedays_ago = mysqli_query($db, "SELECT * FROM orders WHERE date ='$threedays_ago' and user = '$sess_username'");
    
    $fourdays_ago = date('Y-m-d', strtotime("-4 day"));
    $check_order_fourdays_ago = mysqli_query($db, "SELECT * FROM orders WHERE date ='$fourdays_ago' and user = '$sess_username'");
    
    $fivedays_ago = date('Y-m-d', strtotime("-5 day"));
    $check_order_fivedays_ago = mysqli_query($db, "SELECT * FROM orders WHERE date ='$fivedays_ago' and user = '$sess_username'");
    
    $sixdays_ago = date('Y-m-d', strtotime("-6 day"));
    $check_order_sixdays_ago = mysqli_query($db, "SELECT * FROM orders WHERE date ='$sixdays_ago' and user = '$sess_username'");
    
    // End Data
    
    // Data Order Pulsa in Line Chart
    $check_order_pulsa_today = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE date ='$date' and user = '$sess_username'");
    
    $oneday_ago = date('Y-m-d', strtotime("-1 day"));
    $check_order_pulsa_oneday_ago = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE date ='$oneday_ago' and user = '$sess_username'");
    
    $twodays_ago = date('Y-m-d', strtotime("-2 day"));
    $check_order_pulsa_twodays_ago = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE date ='$twodays_ago' and user = '$sess_username'");
    
    $threedays_ago = date('Y-m-d', strtotime("-3 day"));
    $check_order_pulsa_threedays_ago = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE date ='$threedays_ago' and user = '$sess_username'");
    
    $fourdays_ago = date('Y-m-d', strtotime("-4 day"));
    $check_order_pulsa_fourdays_ago = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE date ='$fourdays_ago' and user = '$sess_username'");
    
    $fivedays_ago = date('Y-m-d', strtotime("-5 day"));
    $check_order_pulsa_fivedays_ago = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE date ='$fivedays_ago' and user = '$sess_username'");
    
    $sixdays_ago = date('Y-m-d', strtotime("-6 day"));
    $check_order_pulsa_sixdays_ago = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE date ='$sixdays_ago' and user = '$sess_username'");
    
    // End Data

	$check_order = mysqli_query($db, "SELECT SUM(price) AS total FROM orders WHERE user = '$sess_username'");
	$data_order = mysqli_fetch_assoc($check_order);
	$check_orderpulsa = mysqli_query($db, "SELECT SUM(price) AS total FROM orders_pulsa WHERE user = '$sess_username'");
	$data_orderpulsa = mysqli_fetch_assoc($check_orderpulsa);
	$count_users = mysqli_num_rows(mysqli_query($db, "SELECT * FROM users"));
	
    $count_orders = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders WHERE user = '$sess_username'"));
    $count_orderspulsa = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders_pulsa WHERE user = '$sess_username'"));   
    $count_orders1 = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders"));
    
    // End Data
    
} else {
	if (isset($_POST['login'])) {
		$post_username = mysqli_real_escape_string($db, trim($_POST['username']));
		$post_password = mysqli_real_escape_string($db, trim($_POST['password']));
		if (empty($post_username) || empty($post_password)) {
			$msg_type = "error";
			$msg_content = "<b>Gagal:</b> Mohon mengisi semua input.";
		} else {
			$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			if (mysqli_num_rows($check_user) == 0) {
				$msg_type = "error";
				$msg_content = '<b>Gagal:</b> Username atau password salah.<script>swal("Error!", "Username atau password salah.", "error");</script>';
			} else {
				$data_user = mysqli_fetch_assoc($check_user);
				if ($post_password <> $data_user['password']) {
					$msg_type = "error";
					$msg_content = '<b>Gagal:</b> Username atau password salah.<script>swal("Error!", "Username atau password salah.", "error");</script>';
				} else if ($data_user['status'] == "Suspended") {
					$msg_type = "error";
					$msg_content = '<b>Gagal:</b> Akun Tidak Aktif.<script>swal("Error!", "Akun Suspended.", "error");</script>';
					
				} else {
					$_SESSION['user'] = $data_user;
					header("Location: ".$cfg_baseurl);
				}
			}
		}
	}
}

include("lib/header.php");
if (isset($_SESSION['user'])) {
?>
        <script>swal("Layanan Tercepat", "Instagram Followers CMP 1 [NO PARTIAL] [TERBAIK], Instagram Followers Auto 6 [Super Fast], Instagram Followers Auto 8 PROMO [MASUK CEPAT]");</script>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Halaman Utama</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                           Halaman Utama
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
                                        <div class="alert alert-info">
<marquee>
<?php
$qslider = mysqli_query($db, "SELECT * FROM orders WHERE date = '$date' ORDER BY id DESC LIMIT 20");
while($slider = mysqli_fetch_assoc($qslider)) {
	$slider_userstr = "-".strlen($slider['user']);
	$slider_usersensor = substr($slider['user'],$slider_userstr,-4);
	echo "<span style='margin-right: 30px;'><b>".$slider_usersensor."****</b> telah melakukan pembelian ".$slider['quantity']." ".$slider['service']."</span>";
}
?>
<?php
$qslider = mysqli_query($db, "SELECT * FROM orders_pulsa WHERE date = '$date' ORDER BY id DESC LIMIT 20");
while($slider = mysqli_fetch_assoc($qslider)) {
	$slider_userstr = "-".strlen($slider['user']);
	$slider_usersensor = substr($slider['user'],$slider_userstr,-4);
	echo "<span style='margin-right: 30px;'><b>".$slider_usersensor."****</b> telah melakukan pembelian ".$slider['price']." ".$slider['service']."</span>";
}
?>
</marquee>
                                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="panel panel-border panel-info">
                                    <div class="panel-heading"> 
                                        <h3 class="header-title"><i class="fa fa-line-chart"></i> Grafik Transaksi </h3> 
                                    </div> 
                                    <div class="panel-body">
        <div class="chart" id="line-chart" style="height: 325px;"></div>
<script>
  $(function () {
    "use strict";

    // LINE CHART
    var line = new Morris.Line({
      element: 'line-chart',
      resize: true,
      data: [
        {y: '<?php echo $date; ?>', x: <?php echo mysqli_num_rows($check_order_today); ?>, z: <?php echo mysqli_num_rows($check_order_pulsa_today); ?>},
        {y: '<?php echo $oneday_ago; ?>', x: <?php echo mysqli_num_rows($check_order_oneday_ago); ?>, z: <?php echo mysqli_num_rows($check_order_pulsa_oneday_ago); ?>},
        {y: '<?php echo $twodays_ago; ?>', x: <?php echo mysqli_num_rows($check_order_twodays_ago); ?>, z: <?php echo mysqli_num_rows($check_order_pulsa_twodays_ago); ?>},
        {y: '<?php echo $threedays_ago; ?>', x: <?php echo mysqli_num_rows($check_order_threedays_ago); ?>, z: <?php echo mysqli_num_rows($check_order_pulsa_threedays_ago); ?>},
        {y: '<?php echo $fourdays_ago; ?>', x: <?php echo mysqli_num_rows($check_order_fourdays_ago); ?>, z: <?php echo mysqli_num_rows($check_order_pulsa_fourdays_ago); ?>},
        {y: '<?php echo $fivedays_ago; ?>', x: <?php echo mysqli_num_rows($check_order_fivedays_ago); ?>, z: <?php echo mysqli_num_rows($check_order_pulsa_fivedays_ago); ?>},
        {y: '<?php echo $sixdays_ago; ?>', x: <?php echo mysqli_num_rows($check_order_sixdays_ago); ?>, z: <?php echo mysqli_num_rows($check_order_pulsa_sixdays_ago); ?>}
      ],
      xkey: 'y',
      ykeys: ['x','z'],
      labels: ['Pesanan Sosmed','Pesanan Pulsa'],
      lineColors: ['#3c8dbc','#ff1320'],
      hideHover: 'auto'
    });
  });
</script>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="card-box card widget-box-three">
                                    <div class="bg-icon pull-left">
										<i class="mdi mdi-cart"></i>
									</div>
                                    <div class="text-right">
                                        <p class="text-success m-t-5 text-uppercase font-600 font-secondary">Total Pembelian</p>
                                        <h2 class="m-b-10"><span data-plugin="">Rp <?php echo number_format($data_order['total']+$data_orderpulsa['total'],0,',','.'); ?></span></h2>
                                    </div>
                                </div>

                                <div class="card-box widget-box-three">
                                    <div class="bg-icon pull-left">
										<i class="fa fa-money"></i>
									</div>
                                    <div class="text-right">
                                        <p class="text-pink m-t-5 text-uppercase font-600 font-secondary">Sisa Saldo</p>
                                        <h2 class="m-b-10"><span data-plugin="">Rp <?php echo number_format($data_user['balance'],0,',','.'); ?></span></h2>
                                    </div>
                                </div>
                                <div class="card-box widget-box-three">
                                    <div class="bg-icon pull-left">
										<i class="mdi mdi-cart"></i>
									</div>
                                    <div class="text-right">
                                        <p class="text-pink m-t-5 text-uppercase font-600 font-secondary">Total Transaksi</p>
                                        <h2 class="m-b-10"><span data-plugin=""><?php echo $count_orders+$count_orderspulsa; ?> Transaksi</span></h2>
                                    </div>
                                </div>                                
                            </div>
                        </div>
						<div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-border panel-info">
                                    <div class="panel-heading">
                                        <h3 class="header-title"><i class="fa fa-newspaper-o"></i> Berita & Informasi</h3>
                                    </div>
                                    <div class="panel-body">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover m-0">
												<thead>
													<tr>
														<th>#</th>
														<th>Tanggal</th>
														<th>Isi</th>
													</tr>
												</thead>
												<tbody>
													<?php
													$check_news = mysqli_query($db, "SELECT * FROM news ORDER BY id DESC LIMIT 5");
													$no = 1;
													while ($data_news = mysqli_fetch_assoc($check_news)) {
													?>
													<tr>
														<th scope="row"><?php echo $no; ?></th>
														<td><?php echo $data_news['date']; ?></td>
														<td><?php echo $data_news['content']; ?></td>
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
						<!-- end row -->
<?php
} else {
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Masuk</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                           Masuk
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>

						<div class="row">
                            <div class="col-md-7">
                                <div class="panel panel-border panel-info">
                                    <div class="panel-heading">
                                        <h3 class="header-title"><i class="mdi mdi-import"></i> Masuk</h3>
                                    </div>
                                    <div class="panel-body">
										<?php 
										if ($msg_type == "error") {
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
												<label class="col-md-2 control-label">Password</label>
												<div class="col-md-10">
													<input type="password" id="password" name="password" class="form-control" placeholder="Password">
													<input type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'"> Lihat Password ?
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
													<button type="submit" name="login" class="btn btn-success waves-effect waves-light"><i class="mdi mdi-login"></i> Masuk</button>
													<button type="reset" class="btn btn-danger waves-effect waves-light"><i class="fa fa-history"></i> Ulangi</button>
											</div>
		                                </form>
										</div>
										<div class="pull-right">
											Belum punya akun? <a class="btn btn-primary waves-effect" href="<?php echo $cfg_baseurl; ?>register"><i class="mdi mdi-account-plus"></i> Daftar</a>
										</div>
									</div>
								</div>
								</div>
                            <div class="col-md-5">
                                <div class="panel panel-border panel-info">
                                    <div class="panel-heading">
                                        <h3 class="header-title"><i class="mdi mdi-information-outline"></i> Tentang Kami</h3>
                                    </div>
                                    <div class="panel-body">
                                    <p><?php echo $cfg_desc; ?></p>
									<ul>
										<li>Instant & Auto processing.</li>
										<li>Pendaftaran Gratis.</li>
										<li>Cheapest price.</li>
										<li>Layanan lengkap.</li>
										<li>24 Hours support.</li>
										<li>Deposit via Bank & Pulsa.</li>
									</ul><hr>                            
                                    <center><a href="//www.dmca.com/Protection/Status.aspx?ID=0e3f1e93-5f14-4ec9-a0c9-ea1f67e1ced2" title="DMCA.com Protection Status" class="dmca-badge" target="_top"> <img src="//images.dmca.com/Badges/dmca-badge-w200-5x1-06.png?ID=0e3f1e93-5f14-4ec9-a0c9-ea1f67e1ced2" alt="DMCA.com Protection Status"></a> <script src="//images.dmca.com/Badges/DMCABadgeHelper.min.js"> </script></center>									
                                    </div>
                                </div>
                            </div>                            
                        </div>

						
<?php
}
include("lib/footer.php");
?>