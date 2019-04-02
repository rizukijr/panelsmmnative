<?php
session_start();
require("../mainconfig.php");

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	}

	include("../lib/header.php");
	$msg_type = "nothing";
	

	if (isset($_POST['order'])) {
		$post_service = $_POST['service'];
		$post_quantity = $_POST['quantity'];
		$post_link = trim($_POST['link']);

		$check_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_service' AND status = 'Active'");
		$data_service = mysqli_fetch_assoc($check_service);
		
		$check_orders = mysqli_query($db, "SELECT * FROM orders WHERE link = '$post_link' AND status = 'Pending'");
		$data_orders = mysqli_fetch_assoc($check_orders);
		

		$rate = $data_service['price'] / 1000;
		$price = $rate*$post_quantity;
		$oid = random_number(3).random_number(4);
		$service = $data_service['service'];
		$provider = $data_service['provider'];
		$pid = $data_service['pid'];
		$post_category = $data_service['category'];


		$check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = '$provider'");
		$data_provider = mysqli_fetch_assoc($check_provider);

		if (empty($post_service) || empty($post_link) || empty($post_quantity)) {
			$msg_type = "error";
			$msg_content = "<b>Gagal:</b> Mohon mengisi input.";
		} else if (mysqli_num_rows($check_service) == 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal:</b> Layanan tidak ditemukan.";
		} else if (mysqli_num_rows($check_provider) == 0) {
			$msg_type = "error";
			$msg_content = "<b>Gagal:</b> Server Maintenance.";
		} else if ($post_quantity < $data_service['min']) {
			$msg_type = "error";
			$msg_content = "<b>Gagal:</b> Jumlah minimal adalah ".$data_service['min'].".";
		} else if ($post_quantity > $data_service['max']) {
			$msg_type = "error";
			$msg_content = "<b>Gagal:</b> Jumlah maksimal adalah ".$data_service['max'].".";
		} else if ($data_user['balance'] < $price) {
			$msg_type = "error";
			$msg_content = "<b>Gagal:</b> Saldo Anda tidak mencukupi untuk melakukan pembelian ini.";
		} else if (mysqli_num_rows($check_orders) == 1) {
		    $msg_type = "error";
		    $msg_content = "<b>Gagal:</b> Terdapat Orderan sama berstatus pending.";
		} else {

			// api data
			$api_link = $data_provider['link'];
			$api_key = $data_provider['api_key'];
			// end api data

			if ($provider == "MP-SOSMED") {
				// contoh api
                function connect($end_point, $post) {
                	$_post = array();
                	if (is_array($post)) {
                		foreach ($post as $name => $value) {
                			$_post[] = $name.'='.urlencode($value);
                		}
                	}
                	$ch = curl_init($end_point);
                	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                	curl_setopt($ch, CURLOPT_POST, 1);
                	curl_setopt($ch, CURLOPT_HEADER, 0);
                	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                	if (is_array($post)) {
                		curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
                	}
                	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
                	$result = curl_exec($ch);
                	if (curl_errno($ch) != 0 && empty($result)) {
                		$result = false;
                	}
                	curl_close($ch);
                	return $result;
                }
                $api_url = $api_link; // api url
                $post_data = array(
                	'api_key' => $api_key, // api key Anda
                	'action' => 'order',
                	'service' => $pid, // id layanan
                	'data' => $post_link,
                	'quantity' => $post_quantity
                );
                $api = json_decode(connect($api_url, $post_data));
                $poid = $api->data->id;
			} else {
				die("System Error!");
			}

			if (empty($poid)) {
				$msg_type = "error";
				$msg_content = "<b>Error:</b> ".$api->data->msg.".";
			} else {
			    $top_layanan = mysqli_query($db, "SELECT * FROM top_layanan WHERE layanan = '$service'");
			    $data_layanan = mysqli_fetch_assoc($top_layanan);
			    $check_top = mysqli_query($db, "SELECT * FROM top_users WHERE username = '$sess_username'");
			    $data_top = mysqli_fetch_assoc($check_top);			
				$update_user = mysqli_query($db, "UPDATE users SET balance = balance-$price WHERE username = '$sess_username'");
				if ($update_user == TRUE) {
				    $insert_order = mysqli_query($db, "INSERT INTO balance_history (id, username, action, quantity, msg, date, time) VALUES ('', '$sess_username', 'Cut Balance', '$price', 'Pemesanan Sosial Media Dengan Order ID $oid', '$date', '$time')");
					$insert_order = mysqli_query($db, "INSERT INTO orders (oid, poid, user, service, link, quantity, price, status, date, provider, place_from) VALUES ('$oid', '$poid', '$sess_username', '$service', '$post_link', '$post_quantity', '$price', 'Pending', '$date', '$provider', 'WEB')");
					if ($insert_order == TRUE) {
					if (mysqli_num_rows($check_top) == 0) {
				        $insert_topup = mysqli_query($db, "INSERT INTO top_users (method, username, jumlah, total) VALUES ('Order', '$sess_username', '$price', '1')");
				    } else {
				        $insert_topup = mysqli_query($db, "UPDATE top_users SET jumlah = ".$data_top['jumlah']."+$price, total = ".$data_top['total']."+1 WHERE username = '$sess_username' AND method = 'Order'");
				    }
					if (mysqli_num_rows($top_layanan) == 0) {
				        $insert_topup = mysqli_query($db, "INSERT INTO top_layanan (method, layanan, jumlah, total) VALUES ('Layanan', '$service', '$price', '1')");
				    } else {
				        $insert_topup = mysqli_query($db, "UPDATE top_layanan SET jumlah = ".$data_top['jumlah']."+$price, total = ".$data_top['total']."+1 WHERE layanan = '$service' AND method = 'Layanan'");
				    }
						$msg_type = "success";
						$msg_content = "<b>Pesanan telah diterima.</b><br /><b>Layanan:</b> $service<br /><b>Link:</b> $post_link<br /><b>Jumlah:</b> ".number_format($post_quantity,0,',','.')."<br /><b>Biaya:</b> Rp ".number_format($price,0,',','.');

				} else {
					$msg_type = "error";
					$msg_content = "<b>Gagal:</b> Error system (1).";
				}
			}
		}
	}
}
	
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Pemesanan Baru</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                           Pemesanan Baru
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
<div class="row">
<div class="col-lg-12">
<div class="alert alert-warning">
<h4 class="text-uppercase">
<i class="mdi mdi-bullhorn"></i> <b class="text-uppercase">Penting!</b></h3>
Halo <?php echo $sess_username; ?>, sebelum membuat pesanan disarankan untuk membaca <b>Informasi</b> terlebih dahulu, jika Anda masuk menggunakan PC maka <b>Informasi</b> terletak disebelah kanan form pesanan, jika Anda masuk menggunakan <i>smartphone / mobile phone</i> maka <b>Informasi</b> terletak dibagian bawah form pesanan.
<br />
Terimakasih.
</div>
</div>
</div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="panel panel-border panel-info">
                                    <div class="panel-heading">
                                        <h3 class="header-title"><i class="mdi mdi-cart"></i> Pemesanan Baru</h3>
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
												<label class="col-md-2 control-label">Kategori</label>
												<div class="col-md-10">
													<select class="form-control" id="category" name="category">
														<option value="0">Pilih salah satu...</option>
														<?php
														$check_cat = mysqli_query($db, "SELECT * FROM service_cat ORDER BY name ASC");
														while ($data_cat = mysqli_fetch_assoc($check_cat)) {
														?>
														<option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
														<?php
														}
														?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Layanan</label>
												<div class="col-md-10">
													<select class="form-control" name="service" id="service">
														<option value="0">Pilih kategori...</option>
													</select>
												</div>
											</div>
											<div id="note">
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Link/Target</label>
												<div class="col-md-10">
													<input type="text" name="link" class="form-control" placeholder="Jangan Menggunakan @">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Jumlah</label>
												<div class="col-md-10">
													<input type="number" name="quantity" class="form-control" placeholder="Jumlah" onkeyup="get_total(this.value).value;">
												</div>
											</div>
											
											<input type="hidden" id="rate" value="0">
											<div class="form-group">
												<label class="col-md-2 control-label">Total Harga</label>
												<div class="col-md-10">
													<input type="number" class="form-control" id="total" readonly>
												</div>
											</div>
											<div class="form-group">
												<div class="col-md-offset-2 col-md-10">
											<button type="submit" class="btn btn-info waves-effect w-md waves-light" name="order">Buat Pesanan</button>
											<button type="reset" class="btn btn-default waves-effect w-md waves-light">Ulangi</button>
											    </div>
											</div>    
										</form>
									</div>
								</div>
							</div>
                            <div class="col-md-5">
                                <div class="panel panel-border panel-warning">
                                    <div class="panel-heading">
                                        <h3 class="header-title"><i class="mdi mdi-information-outline"></i> Peraturan Pemesanan</h3>
                                    </div>
                                    <div class="panel-body">
										<ul>
											<li>Pastikan username / link data yang di input benar dan valid,</li>
											<li>Pastikan akun target tidak berstatus private,</li>
											<li>Jangan input data yang sama dengan orderan sebelum nya apabila orderan sebelum nya belum Completed,</li>
											<li>Apabila orderan tidak mengalami perubahan status, silahkan kontak admin untuk di tangani,</li>
                                            <li>Tidak ada pengembalian dana untuk kesalahan pengguna.</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!-- end row -->
						<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript">
$(document).ready(function() {
	$("#category").change(function() {
		var category = $("#category").val();
		$.ajax({
			url: '<?php echo $cfg_baseurl; ?>inc/order_service.php',
			data: 'category=' + category,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#service").html(msg);
			}
		});
	});
	$("#service").change(function() {
		var service = $("#service").val();
		$.ajax({
			url: '<?php echo $cfg_baseurl; ?>inc/order_note.php',
			data: 'service=' + service,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#note").html(msg);
			}
		});
		$.ajax({
			url: '<?php echo $cfg_baseurl; ?>inc/order_rate.php',
			data: 'service=' + service,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#rate").val(msg);
			}
		});
	});
});

function get_total(quantity) {
	var rate = $("#rate").val();
	var result = eval(quantity) * rate;
	$('#total').val(result);
}
	</script>
<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>