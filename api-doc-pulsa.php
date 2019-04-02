<?php
// Recoded by Gh0Code
// Hargailah orang lain jika Anda ingin dihargai
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
}

include("lib/header.php");
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Api Documentation</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                           Api Documentation
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card-box">
                                    <h4 class="text-dark  header-title m-t-0">Method <font color="red">add</font> (Place Order)</h4>
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Parameters</th>
													<th>Description</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>URL</td>
													<td><?php echo $cfg_baseurl; ?>api/order</td>
												</tr>
												<tr>
													<td>key</td>
													<td>Your API key</td>
												</tr>
												<tr>
													<td>action</td>
													<td>order</td>
												</tr>
												<tr>
													<td>service</td>
													<td>Service ID <a href="<?php echo $cfg_baseurl; ?>price_list">Check at price list</a></td>
												</tr>
												<tr>
													<td>target</td>
													<td>target to page</td>
												</tr>
												<tr>
													<td>quantity</td>
													<td>Needed quantity</td>
												</tr>
												<tr>
													<td>Example PHP Code</td>
													<td><a href="<?php echo $cfg_baseurl; ?>api/api_order.txt" target="blank">Example</a></td>
												</tr>
											</tbody>
										</table>
									</div>
<b>Example Response</b><br />
<pre>
IF ORDER SUCCESS

{
  "data": {
          "id": "12345"
          }
}

IF ORDER FAIL

{
  "error": "Incorrect request"
}
</pre>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-md-6">
                                <div class="card-box">
                                    <h4 class="text-dark  header-title m-t-0">Method <font color="red">status</font> (Get Status)</h4>
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Parameters</th>
													<th>Description</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>URL</td>
													<td><?php echo $cfg_baseurl; ?>api/status</td>
												</tr>
												<tr>
													<td>key</td>
													<td>Your API key</td>
												</tr>
												<tr>
													<td>action</td>
													<td>status</td>
												</tr>
												<tr>
													<td>id</td>
													<td>Your order id</td>
												</tr>
												<tr>
													<td>Example PHP Code</td>
													<td><a href="<?php echo $cfg_baseurl; ?>api/api_status.txt" target="blank">Example</a></td>
												</tr>
											</tbody>
										</table>
									</div>
<b>Example Response</b><br />
<pre>
IF CHECK STATUS SUCCESS

{
  "data": {
          "target":"bajol.ijo1927",
          "start_count":"123",
          "status":"Success",
          "remains":"0"
          }
}

IF CHECK STATUS FAIL

{
  "error": "Incorrect request"
}
</pre>
                                </div>
                            </div>
                            <!-- end col -->
                            <div class="col-md-12">
                                <div class="card-box">
                                    <h4 class="text-dark  header-title m-t-0">Method <font color="red">service</font> (Get Service)</h4>
									<div class="table-responsive">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Parameters</th>
													<th>Description</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>URL</td>
													<td><?php echo $cfg_baseurl; ?>api/service</td>
												</tr>
												<tr>
													<td>key</td>
													<td>Your API key</td>
												</tr>
												<tr>
													<td>action</td>
													<td>service</td>
												</tr>
												<tr>
													<td>Example PHP Code</td>
													<td><a href="<?php echo $cfg_baseurl; ?>api/api_service.txt" target="blank">Example</a></td>
												</tr>
											</tbody>
										</table>
									</div>
<b>Example Response</b><br />
<pre>
IF GET SERVICE SUCCESS

{
  "result": {
          "sid": "1"
          "category": "IGF"
          "service": "Instagram Followers Indonesia GM 1"
          "min": "100"
          "max": "2000"
          "price": "60000"
          "status": "Active"
          "provider": "MARTPEDIA"
          }
}

IF GET SERVICE FAIL

{
  "error": "Incorrect request"
}
</pre>
                                </div>
                            </div>
                            <!-- end col -->
                        </div>
                        <!-- end row -->


                    </div>
                    <!-- end container -->
                </div>
                <!-- end content -->

                <footer class="footer">
                    <div class="pull-left">2018 ~ <a href="javascript:void(0);"><?php echo $cfg_webname; ?></a></div>
                    <div class="pull-right"><span class="hide-phone">Create With <i class="fa fa-heart"></i> by <a href="http://facebook.com/adminfatur">Gh0Code</a></span></div>
                </footer>
<?php
include("lib/footer.php");
?>