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
}

include("lib/header.php");
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Daftar Harga</h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                          Daftar Harga
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
                                        <h3 class="panel-title"><i class="mdi mdi-cards-outline"></i> Daftar Harga</h3>
                                    </div>
                                    <div class="panel-body">
                                <div class="table-responsive m-t-0">
<table id="datatable-responsive" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
														<th>ID</th>
														<th>Operator</th>
														<th>Layanan</th>
														<th>Harga</th>
														<th>Status</th>
													</tr>
												</thead>
												<tbody>
<?php
$query_order = "SELECT * FROM services_pulsa WHERE status = 'active'"; // edit
$records_per_page = 30; // edit

$starting_position = 0;
if(isset($_GET["page_no"])) {
	$starting_position = ($_GET["page_no"]-1) * $records_per_page;
}
$new_query = $query_order." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query);
// end paging config
												while ($data_service = mysqli_fetch_assoc($new_query)) {
												?>
													<tr class="odd gradeX">
														<td><?php echo $data_service['id']; ?></td>
														<td><?php echo $data_service['oprator']; ?></td>
														<td><?php echo $data_service['service']; ?></td>
														<td>Rp <?php echo number_format($data_service['price'],0,',','.'); ?></td>
														<td><?php echo $data_service['status']; ?></td>
													</tr>
												<?php
												}
												?>
												</tbody>
											</table>
                                    </div>

										<ul class="pagination">
											<?php
// start paging link
$self = $_SERVER['PHP_SELF'];
$query_order = mysqli_query($db, $query_order);
$total_records = mysqli_num_rows($query_order);
echo "<li class='disabled'><a href='#'>Total: ".$total_records."</a></li>";
if($total_records > 0) {
	$total_pages = ceil($total_records/$records_per_page);
	$current_page = 1;
	if(isset($_GET["page_no"])) {
		$current_page = $_GET["page_no"];
		if ($current_page < 1) {
			$current_page = 1;
		}
	}
	if($current_page > 1) {
		$previous = $current_page-1;
		echo "<li><a href='".$self."?page_no=1'>← First</a></li>";
		echo "<li><a href='".$self."?page_no=".$previous."'><i class='fa fa-angle-left'></i> Previous</a></li>";
	}
	// limit page
	$limit_page = $current_page+3;
	$limit_show_link = $total_pages-$limit_page;
	if ($limit_show_link < 0) {
		$limit_show_link2 = $limit_show_link*2;
		$limit_link = $limit_show_link - $limit_show_link2;
		$limit_link = 3 - $limit_link;
	} else {
		$limit_link = 3;
	}
	$limit_page = $current_page+$limit_link;
	// end limit page
	// start page
	if ($current_page == 1) {
		$start_page = 1;
	} else if ($current_page > 1) {
		if ($current_page < 4) {
			$min_page  = $current_page-1;
		} else {
			$min_page  = 3;
		}
		$start_page = $current_page-$min_page;
	} else {
		$start_page = $current_page;
	}
	// end start page
	for($i=$start_page; $i<=$limit_page; $i++) {
		if($i==$current_page) {
			echo "<li class='active'><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
		} else {
			echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
		}
	}
	if($current_page!=$total_pages) {
		$next = $current_page+1;
		echo "<li><a href='".$self."?page_no=".$next."'>Next <i class='fa fa-angle-right'></i></a></li>";
		echo "<li><a href='".$self."?page_no=".$total_pages."'>Last →</a></li>";
	}

// end paging link
											?>
										</ul>
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