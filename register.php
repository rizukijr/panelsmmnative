<?php
session_start();
require("mainconfig.php");
include_once("lib/header.php");
?>
            <div class="content-page">
                <div class="content">
                    <div class="container">
                        <div class="row">
							<div class="col-xs-12">
								<div class="page-title-box">
                                    <h4 class="page-title">Harga Pendaftaran </h4>
                                    <ol class="breadcrumb p-0 m-0">
                                        <li>
                                            <a href="#"><?php echo $cfg_webname; ?></a>
                                        </li>
                                        <li class="active">
                                          Harga Pendaftaran
                                        </li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>
							</div>
						</div>

                       <div class="table-responsive">
                                <table class="table table-striped table-bordered table-vcenter">
                                <thead>
                                                        <tr>
                                                            <th>Level</th>
                                                            <th>Harga Daftar</th>
                                                            <th>Pesan Layanan</th>
                                                            <th>API Layanan</th>
                                                            <th>Tambah User</th>
                                                            <th>Bonus Bulanan</th>
                                                            <th>Saldo Pertama</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>Member</strong></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp 20.000</strong></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="No" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="No" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp 10.000</strong></a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Agen</strong></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp 40.000</strong></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp.15.000</strong></a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Resseler</strong></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp 100.000</strong></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp.50.000</strong></a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Admin</strong></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp 200.000</strong></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="Yes" class="btn btn-effect-ripple btn-sm btn-success"><i class="fa fa-check"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp.100.000</strong></a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                            </div>
                            <h4 class="page-title">Nikmati Bonus Perbulanmu </h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-vcenter">
                                <thead>
                                                        <tr>
                                                            <th>Level</th>
                                                            <th>Reseller</th>
                                                            <th>Agen</th>
                                                            <th>Member</th>
                                                            <th>Dibayar Setiap</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Member</td>
                                                            <td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="No" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
															<td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="No" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
															<td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="No" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
															<td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="No" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Agen</td>
															<td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="No" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
															<td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="No" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp.1500/Member</strong></a></td>
															<td align="center"><a href="javascript:void(0)" class="label label-success"><strong>perbulan</strong></a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Reseller</td>
															<td align="center"><a href="javascript:void(0)" data-toggle="tooltip" title="No" class="btn btn-effect-ripple btn-sm btn-danger"><i class="fa fa-minus"></i></a></td>
															<td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp.4000/Agen</strong></a></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp.1500/Member</strong></a></td>
															<td align="center"><a href="javascript:void(0)" class="label label-success"><strong>perbulan</strong></a></td>
                                                        </tr>
                                                        <tr>
															<td>Admin</td>
															<td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp.7000/Reseller</strong></a></td>
															<td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp.4000/Agen</strong></a></td>
                                                            <td align="center"><a href="javascript:void(0)" class="label label-success"><strong>Rp.1500/Member</strong></a></td>
															<td align="center"><a href="javascript:void(0)" class="label label-success"><strong>perbulan</strong></a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                            </div>
                                        <div class="pull-right">
											Hubungi Kami Untuk Daftar! <a class="btn btn-primary waves-effect" href="<?php echo $cfg_baseurl; ?>list_staff"><i class="mdi mdi-account-plus"></i> Lets Go</a>
										</div>
						
<?php
include("lib/footer.php");
?>