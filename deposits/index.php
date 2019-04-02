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
	}
	$check_order_sosmed = mysqli_query($db, "SELECT SUM(price) AS total FROM orders WHERE user = '$sess_username'");
	$data_order_sosmed = mysqli_fetch_assoc($check_order_sosmed);
	$check_order_pulsa = mysqli_query($db, "SELECT SUM(price) AS total FROM orders_pulsa WHERE user = '$sess_username'");
	$data_order_pulsa = mysqli_fetch_assoc($check_order_pulsa);
	$total_belanja=$data_order_pulsa['total']+$data_order_sosmed['total'];
}


if (isset($_SESSION['user'])) {
    include("../lib/header.php");
?>
            <div class="col-md-6">
              <!-- small box -->
              <a href="<?php echo $cfg_baseurl; ?>deposits/pulsa">
                  <div class="widget widget-chart white-bg padding-0">
                <center><i class="fa fa-institution" style="font-size:80px;margin:10px;"></i></center>
                <center> <span class="small-box-footer" style="font-size:16px;">
                 Deposit via Pulsa <i class="fa fa-arrow-circle-right"></i>
                </span> </center>
              </div>
              </a>
            </div><!-- ./col -->
             <div class="col-md-6">
              <!-- small box -->
              <a href="<?php echo $cfg_baseurl; ?>deposits/redem">
                  <div class="widget widget-chart white-bg padding-0">
                <center><i class="fa fa-code" style="font-size:80px;margin:10px;"></i></center>
                <center> <span class="small-box-footer" style="font-size:16px;">
                 Deposit via Code <i class="fa fa-arrow-circle-right"></i>
                </span> </center>
              </div>
              </a>
            </div><!-- ./col -->
          <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <h5><i class="fa fa-usd"></i> Payment Method</h5>
                                </div>
                                    <div class="ibox-content">
									<div >
									
									<img src="https://vignette.wikia.nocookie.net/logopedia/images/e/e1/Alfamart.png/revision/latest/scale-to-width-down/2000?cb=20170507153647" high="100" width ="100">
									<img src="https://upload.wikimedia.org/wikipedia/id/2/28/Indomaret.png" high="100" width ="100">
									
									<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/PayPal_2014_logo.svg/1000px-PayPal_2014_logo.svg.png" high="100" width ="100">
									<img src="https://4.bp.blogspot.com/-MsTtnq6HtQI/Wf_yXAKGRcI/AAAAAAAABLM/RhN9xoejjLkvwsUCGwHUzU4t3mNFW6w4QCKgBGAs/s1600/unnamed%2B%25284%2529.png" high="150" width ="150">
									<img src="https://3.bp.blogspot.com/-ZK6W9UlA3lw/V15RGexr3yI/AAAAAAAAAJ4/nkyM9ebn_qg3_rQWyBZ1se5L_SSuuxcDACLcB/s640/Bank_Central_Asia.png" high="100" width ="100">
									<img src="https://i1.wp.com/terarah.com/wp-content/uploads/2017/12/Telkomsel-icon.png?fit=636%2C636&ssl=1" high="100" width ="100">
								
								</div>
								</div>
							</div>
						</div>	
<?php
include("../lib/footer.php");
} else {
    header("Location: ".$cfg_baseurl."auth/login");
}

?>