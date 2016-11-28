<?php
include('header.php');
include "config/koneksi.php";
include "datediff.php";
error_reporting(E_ALL ^ (E_NOTICE));
function format_angka($angka) {
    $frmt = number_format($angka, 2, ',', '.');
    return $frmt;
}
$sid = $_GET['id'];
$jum_page= 10;
if($_REQUEST['page']==0|| empty($_REQUEST['page'])){
$mulai = 0;
$page = 1;
}
else{
	$mulai = ($jum_page * $_REQUEST['page'])- $jum_page ;
	$page = $_REQUEST['page'];
}
$jum_data = mysql_num_rows(mysql_query("SELECT * FROM barang where id_kategori='$sid'"));
$jum_halaman = ceil($jum_data / $jum_page);
$next = $page+1;
$back = $page-1;
?>  

<div id="content">                
    <div class="container-fluid outer">
        <div class="row-fluid">                        
            <div class="span12 inner">                           
                <div class="row-fluid">
                    <div class="span12">
                        <div class="box">
                            <header>
                                <div class="icons"><i class="icon-book icon-large"></i></div>
                                <h5 style="weight:bold">Data Inventaris dan Aset Kategori <?php
                                    switch ($_GET['id']) {
                                        case 1 : echo ("Hardware");
                                            break;
                                        case 2 : echo ("Software");
                                            break;
                                        case 3 : echo ("Peralatan Kantor");
                                            break;
                                        case 4 : echo ("Bangunan");
                                            break;
                                        case 5 : echo ("Kendaraan");
                                            break;
                                    }
                                    ?></h5>
                            </header>
                            <div id="collapse4" class="body">
									<ul class="breadcrumb">
										<li><a href="menu.php">Home</a> <span class="divider"><i class="icon-chevron-right"></i></span></li>
										<li class="active">List Data</li>
									</ul>
									<div class="modal-body" style="max-width:100%">
										<div class="accordion-group">
                                        <table width="100%">
										<tr>
											<td width="70%">
												<a href="tambahdata.php"><button class="btn btn-success"><i class="icon icon-plus"></i> Tambah</button></a><br>
											</td>
											<form name="cari" method="post" action="cari_kategori.php?id=<?php echo $sid;?>">
											<td ><input type="text" name="cari_jml" id="cari_jml"></td>
											<td valign="top"><button class="btn btn-primary" name="submit_cari" id="submit_cari"><i class="icon icon-search"></i> Cari</button></td>
											</form>
										</tr>
										</table>
										<table id="" class="table table-bordered table-condensed table-hover table-striped">
											<thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>ID Aset</th>
                                                    <th>Nama Aset</th>
                                                    <th>Tanggal Pengadaan</th>
                                                    <th>Status</th>
													<th>PIC</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
												
                                                $nomor = ($jum_page * $_GET['page']-9); 
                                                /* MEMBUAT VARIABEL PERHITUNGAN JUMLAH */
                                                $jml_perolehan = 0;
                                                /* menampilkan database */
                                                $tampil = mysql_query("SELECT * FROM barang b LEFT JOIN kategori k ON  b.id_kategori = k.id_kategori
												   WHERE k.id_kategori='$_GET[id]' order by id_aset ASC LIMIT $mulai,$jum_page");
												
                                                while ($data = mysql_fetch_array($tampil)) {
                                                    ?>
                                                    <tr>
                                                        <td width="4%" align="center"><?php echo $nomor; ?></td>
                                                        <td width="10%" align="center"><?php echo $data['id_aset']; ?></td>
                                                        <td width="24%"><a href="detil_barang.php?id=<?php echo $data['id_barang']; ?>&&kat=<?php echo $sid;?>"><?php echo $data['nama_barang']; ?></a></td>
                                                        <td width="14%" align="center"><?php echo $data['tanggal_pengadaan']; ?></td>
                                                        <td width="10%" align="center"><?php echo $data['status']; ?></td>
														<td width="18%"><?php echo $data['p_i_c']; ?></td>
                                                        <td align="center" width="10%">
															<a href="detil_barang.php?id=<?php echo $data['id_barang']; ?>&&kat=<?php echo $sid;?>"><img src="images/data-view.png"></a>
															<a href="update.php?id_kat=<?php echo $data['id_kategori'];?>&&id=<?php echo $data['id_barang']; ?>" title='Edit Data'><img src="images/data-edit.png"></a>
                                                            <a href="aksiForm.php?act=hapus&id=<?php echo $data['id_barang']; ?>" target="_self" alt="Delete Data" onClick="return 	confirm('Anda yakin akan menghapus data?')"><img src="images/data-delete.png"></a></td>
                                                    </tr>
													
    <?php
    $nomor++;
}
?>
                                            </tbody>
                                        </table>
									</div>
                                    </div>
										<table class="tableluar breadcrumb" style="width:100%">
										<tr>
											<td valign="middle" align="left" width="75%">
											
											<?php

											$sql="select * from barang where id_kategori='$sid' and nama_barang like '%$_POST[cari_jml]%'";
											$query=mysql_query($sql);
											$jumlah=mysql_num_rows($query);
											$data=mysql_fetch_array($query);
											?>
											Jumlah data saat ini adalah : <? echo $jumlah; ?></div>
											</td>
											<td colspan='2' align="left" valign="middle">
											<? echo "Page $page / $jum_halaman"; ?>
											</td>
											
											<td align="right" valign="middle">
											
											<?php
											if ($page>1){
												echo "<a href='$PHP_SELF?id=$sid&&page=$back' ><i class='icon icon-chevron-left'></i><i class='icon icon-chevron-left'></i> Prev</a> ";
											}else{
											echo "<i class='icon icon-chevron-left' style='color:#9E9E9E'></i><i class='icon icon-chevron-left' style='color:#9E9E9E'></i> <font color='#9E9E9E'>Prev</font></a> ";
											}
											echo "|&nbsp;";
											if ($page<$jum_halaman){
												echo "<a href='$PHP_SELF?id=$sid&&page=$next' >Next <i class='icon icon-chevron-right'></i><i class='icon icon-chevron-right'></i></a>";
											}else{
											echo "<font color='#9E9E9E'>Next</font> <i class='icon icon-chevron-right' style='color:#9E9E9E'></i><i class='icon icon-chevron-right' style='color:#9E9E9E'></i></a>";
											}?>
											
											</td>
										</tr>
										</table>
										</div>
									</div>
                                <form name="form" id="block-validate" action="import_kategori.php" method="POST" enctype="multipart/form-data">
                                <div class="form-actions no-margin-bottom">
                                    Silakan Pilih File Excel: <input name="userfile" type="file">
                                    <input type="submit" value="Import Data" class="btn">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
              
            </div>              
        </div>                   
    </div>               
</div>
<div id="push"></div>
<?php include('footer.php'); ?>