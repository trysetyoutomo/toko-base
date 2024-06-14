	<!-- 
-->
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="jZebra/jzebra/jzebra.jar" width="0" height="0">
    <param name="printer" value="zebra">
</applet> 


<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>

<!-- Datatables -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/dataTables.select.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/jszip/dist/jszip.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/pdfmake/build/vfs_fonts.js"></script>
<!-- moment js -->
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>


<h1>
<i class="fa fa-book"></i> Laporan Transaksi Kasir 
</h1> 
<hr>
<?php 
	if (isset($_REQUEST[tanggal])){
			$date = $_REQUEST[tanggal];
		}else{
			$date = date("Y-m-d");
		}
?>
<form method="get">
	<input type="hidden" name="r" value="sales/rekap"  />
			<label style="margin-right:1rem"> Tanggal Transaksi </label>
			<input readonly type="text" value="<?php echo $date; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal" id="tanggal">
	<button type="submit" name="Cari" class="btn btn-primary" style="display:inline" >
		<i class="fa fa-search"></i>  Cari Data
	</button>
	<!-- <input type="button" name="kirim" value="Kirim Email" class="btn btn-primary btn-kirim-email" style="display:inline" /> -->
</form>
<br>
<?php 
if (isset($_REQUEST['tanggal'])){
?>
<table class="items table" id="datatable">
	<thead>
		
		<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >
			<td>No</td>
			<td>Cabang</td>
			<td>Petugas </td>
			<td>Saldo Cash Awal </td>
			<td>Total Cash Masuk</td>
			<td>Total Cashless</td>
			<td>Total Omset (Hari ini) </td>
			<!-- <td>Total Biaya Bank </td> -->
			<td>Total Potongan </td>
			<td>Total Pengeluaran </td>
			<!-- <td>Total Akhir </td> -->
			<td>Total Uang Cash harus Ada </td>
			<td>Total Uang Cash (Input Akhir)</td>
			<td>Selisih</td>
			<td>Cetak</td>
		</tr>
		

	</thead>
	<tbody>
	<?php 
		
$branch_id = Yii::app()->user->branch();
$store_id = Yii::app()->user->store_id();
$sql  = "
select 

branch_name,
nama_user,
sum(sale_total_cost) total_omset,
sum(sale_total_cost) total_omset,
sum(adt_cost) as total_biaya,
sum(voucher) as voucher,
sum(cash) as cash,
sum(edc_bca) as cashless,
total_fisik as total_fisik,
userid,
total_awal,
is_closed,
created_at
		
from 
(
SELECT
	sp.edc_bca,
	b.id bid,
	b.branch_name,
	se.created_at,
	se.is_closed,
	se.total_awal,
	sp.voucher voucher,
	sp.cash cash,
	adt_cost,
	u.username nama_user,
	s.inserter sia,
	se.id seid,
	u.id userid,
	se.total total_fisik,
	s.bayar,
	s.TABLE,
	inserter,
	s.id,
	sum(si.quantity_purchased) AS total_items,
	date as tanggal,
	sum(
		si.item_price * si.quantity_purchased
	) sale_sub_total,
	s.sale_tax,
	s.sale_service,
	s.sale_discount,
	sum(
		(
			(
				si.item_price * si.quantity_purchased
			) + (si.item_service) + (si.item_tax) - (
				si.item_discount * (
					si.item_price * si.quantity_purchased
				) / 100
			)
		)
	) + s.pembulatan sale_total_cost 
FROM
	sales s
INNER JOIN sales_items si ON s.id = si.sale_id
INNER JOIN users u ON s.inserter = u.id
INNER JOIN items i ON i.id = si.item_id
INNER JOIN sales_payment sp ON sp.id = s.id
INNER JOIN branch b ON b.id = s.branch
LEFT JOIN (
	SELECT DISTINCT
		tanggal,
		user_id,
		total,
		total_awal,
		id,
		is_closed,created_at
	FROM
		setor
) AS se ON se.tanggal = date(s.date)
AND se.user_id = s.inserter
WHERE
	
 date(se.tanggal) = '$date'
AND s. STATUS = 1
and b.store_id = '$store_id'
GROUP BY
	s.inserter,s.id
) AS A 

group by A.nama_user, A.bid
";
// echo $sql;
// exit;

		$model = Yii::app()->db->createCommand($sql)->queryAll();

		$query = "select u.username,b.branch_name,s.total_awal,s.created_at,s.updated_at from setor s
	INNER JOIN users u on s.user_id = u.id
	INNER JOIN branch b on u.branch_id = b.id
WHERE s.store_id = '$store_id' AND s.tanggal = '$date' ORDER BY b.branch_name ASC";

$model_hadir = Yii::app()->db->createCommand($query)->queryAll();


	?>
		<?php 
		
		if (isset($_REQUEST['id']) and isset($_REQUEST[tanggal]) ){
			$filter = "and si.item_id = $_REQUEST[id] and date(s.date)= '$_REQUEST[tanggal]' " ;
		}else{
			$filter = "and s.id = 999999999999999999999999999999999  " ;
		
		}
		// $date = date('Y-m-d');
			// $sql = "select *,s.id sid from sales_items si inner join sales s on s.id = si.sale_id 
			// inner join items i on i.id = si.item_id
			
			// where  s.status =  1   $filter  ";
			// $model = Yii::app()->db->createCommand($sql)->queryAll();
				$no = 1;
				$ttl = 0;
		if (count($model) > 0){
		foreach ($model  as $m ):
			$query_pengeluaran  = "select sum(total) as total_pengeluaran  from pengeluaran where 1=1 and date(tanggal)='$_REQUEST[tanggal]' and user='$m[nama_user]' order by tanggal desc";
			// echo $query_pengeluaran;
			// echo "<br>";
			$data_pengeluaran = Yii::app()->db->createCommand($query_pengeluaran)->queryRow();		
			// print_r($data_pengeluaran);
		?>
		<tr >
			<td><?php echo $no ?></td>
			<td><?php echo $m['branch_name']; ?>
			<td><div><?php echo $m['nama_user']; ?></div>
				<?php 
				if ($m['created_at']!="" && $m['is_closed']=="0"){
					echo "<div class='badge badge-success'>Sedang Bertugas</div>";
				}else if ($m['created_at']!="" && $m['is_closed']=="1"){
					echo "<div class='badge badge-danger'>Sudah Closing</div>";
				}else{
					echo "<div class='badge badge-danger'>Belum Mulai</div>";
				}
				?>
			</td>
			<td><?php echo number_format($m['total_awal']); ?></td>
			<td><?php echo number_format($m['cash']); ?></td>
			<?php 
			// $total_akhir = ($m['total_biaya']+$m['total_omset'])-$m['voucher'];
			$total_akhir = ($m['total_biaya']+$m['cash'])-$data_pengeluaran['total_pengeluaran'];
			$must = $total_akhir+$m['total_awal'];
			?>
			<td><?php echo number_format($m['cashless']); ?></td>
			<td style="text-align:left"><?php echo number_format($m['total_omset']); ?></td>
			<!-- <td style="text-align:left"><?php echo number_format($m['total_biaya']); ?></td> -->
			<td style="text-align:left"><?php echo number_format($m['voucher']); ?></td>
			<td style="text-align:left"><?php echo number_format($data_pengeluaran['total_pengeluaran']); ?></td>

			<!-- <td style="text-align:left"><?php echo number_format($total_akhir); ?></td> -->

			<td style="text-align:left"><?php echo number_format($must); ?></td>
			<td style="text-align:left;color:green;font-weight:bolder"><?php echo number_format($m['total_fisik']); ?></td>
			<td style="text-align:left"><?php echo number_format($m['total_fisik']-($total_akhir+$m['total_awal'])); ?></td>

<!-- 				
 -->
			<td>
				<!-- <button class="cetak btn-primary btn d-none" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
				<i class="fa fa-print" style="color:white!important"></i>
				Cetak Rekap</button>  removed at 23-10-2022 due to print is not use applet anymore since many issues -->
				<?php if (SiteController::getConfig("jenis_printer") == "Epson LX"){ ?>
					<a  href="<?php echo Yii::app()->createUrl("sales/cetakrekap&tanggal_rekap=$date&noprint=true&inserter=$m[userid]") ?>"
					class="btn-primary btn" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
					<i class="fa fa-print" style="color:white!important"></i>
					Cetak Rekap </a>
				<?php }else{ ?>
					<button class="cetak btn-primary btn" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
					<i class="fa fa-print" style="color:white!important"></i>
					Cetak Rekap</button>  
				<?php } ?>



				<?php 
				$filter = "user_id = '$m[userid]' and date(tanggal)='$date' ";
				$se = Setor::model()->find($filter);
				// echo $filter;

				if ($se){
					?>
					<a 
					href="<?php echo Yii::app()->createUrl("sales/hapusregister&tanggal_rekap=$date&noprint=true&inserter=$m[userid]") ?>"
					class="btn-danger btn btn-hapus-register" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
					<i class="fa fa-refresh" style="color:white!important"></i>
					Hapus Register </a>
					<?php if ($m['is_closed'] == 1):  ?>
					<a 
					href="<?php echo Yii::app()->createUrl("sales/reopenregister&tanggal_rekap=$date&noprint=true&inserter=$m[userid]") ?>"
					class="btn-warning btn btn-reopen-register" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
					<i class="fa fa-arrow-rotate-right" style="color:white!important"></i>
					Buka kembali  </a>
					<?php endif;?>
				<?php 
				}
				if ($m['is_closed'] == 0 && $m['created_at']!=""):
				?>
				<a href="<?php echo Yii::app()->createUrl("sales/tutupregister&tanggal_rekap=$date&noprint=true&inserter=$m[userid]&must=$must") ?>"
					class="btn-primary btn btn-tutup-register" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
					<i class="fa fa-check" style="color:white!important"></i>
					Tutup Transaksi Kasir 
				</a>
				<?php endif; ?>
 
 
 
				<!-- <a href="<?php 
				echo Yii::app()->createUrl("sales/rekapdetail",array("user"=>$m['userid'],"tanggal"=>$_REQUEST[tanggal])) ?>" class="btn-primary btn" inserter="<?php echo $m['userid']; ?>" tanggal="<?php echo $date ?>" >
				<i class="fa fa-print" style="color:white!important"></i>
				Cetak Rekap</a>
				<button 
				user-id='<?php echo $m['userid'] ?>'
				class="hapus btn-primary btn" seid="<?php echo $m['userid']; ?>" >Hapus</button>
 -->
			</td>
			
		</tr>
		<?php 

			
		$t_awal+=$m['total_awal'];
		$c+=$m['cash'];
		$cl+=$m['cashless'];
		$tv+=$m['voucher'];
		$ttl+=$m['total_omset'];
		$tf+=$m['total_fisik'];
		$tf_hrs+=$total_akhir+$m['total_awal'];
		$ts+=$m['total_fisik']-($total_akhir+$m['total_awal']);
		$tb+=$m['cashless'];
		$total_akhir_pengeluaran += $data_pengeluaran['total_pengeluaran'];

		$ta+=$total_akhir;

		$no++;
		endforeach; 
		}else{
			?>
			<!-- <tr>
				<td colspan="13">
					<p style="color:red;font-style:italic;text-align:center">Data tidak ditemukan</p>
				</td>
			</tr> -->
			<?php 
		}
		?>


		<?php 
		if (count($model) > 0){
		?>
		<tfoot>
			<tr>
				<td colspan="3">Total</td>
				<td><?php echo number_format($t_awal); ?></td>
				<td><?php echo number_format($c); ?></td>
				<td><?php echo number_format($tb); ?></td>
				<td><?php echo number_format($ttl); ?></td>
				<td><?php echo number_format($tv); ?></td>
				<td><?php echo number_format($total_akhir_pengeluaran); ?></td>
				<!-- <td><?php echo number_format($ta); ?></td> -->
				<td><?php echo number_format($tf_hrs); ?></td>
				<td><?php echo number_format($tf); ?></td>
				<td><?php echo number_format($ts); ?></td>
				<td></td>
				
			</tr>
		</tfoot>
		<?php } ?>
		
	</tbody>
</table>

<h1>Petugas Hadir</h1>
<table class="items table" id="datatable">
	<thead>
		
		<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >
			<td>No</td>
			<td>Cabang</td>
			<td>Setor Awal </td>
			<td>Jam Masuk</td>
			<td>Jam Pulang</td>
		</tr>
		

	</thead>
	<tbody>
	<?php
			$nomor =1; 
			foreach ($model_hadir  as $hadir ):
		?>
		
			<tr>
			<td><?php echo $nomor ?></td>
			<td><?php echo $hadir['branch_name']; ?></td>
			<td><?php echo number_format($hadir['total_awal']); ?></td>
			<td><?php echo $hadir['created_at']; ?></td>
			<td><?php echo $hadir['updated_at']; ?></td>
			</tr>
			<?php 
				$nomor++;
			endforeach;?>
	</tbody>
</table>



<?php } ?>
<script>

function reloadDT(){

	if ($.fn.DataTable.isDataTable('#datatable')) {
    // Destroy DataTable
    	$('#datatable').DataTable().destroy();
	}

	$("#datatable").DataTable({
		"processing": true,
		"responsive": true,
		"autoWidth": true,
		"columnDefs": [
			{ "width": "0%", "targets": 0, "visible":false }, 
			{ "width": "8%", "targets": 1 },  
			{ "width": "8%", "targets": 2 },
			{ "width": "8%", "targets": 3 },
			{ "width": "8%", "targets": 4 },
			{ "width": "8%", "targets": 5 },
			{ "width": "8%", "targets": 6 },
			{ "width": "8%", "targets": 7 },
			{ "width": "8%", "targets": 8 },
			{ "width": "8%", "targets": 9 },
			{ "width": "8%", "targets": 10 },
			{ "width": "8%", "targets": 11 }
  		]
	});
}
$(document).ready(function(e){
	reloadDT();

	//  $('#datatable').DataTable();

	 $('.tanggal').datepicker({ 
		dateFormat: 'yy-mm-dd',
		changeMonth:true,
		changeYear:true,
		maxDate:new Date,

	});

	$('.btn-tutup-register').click(function(e){
		var c = confirm("Dengan menutup register, kasir dianggap sudah setor sesuai dengan *Total Uang Fisik yang harus ada*, Lanjutkan ? ");
		if (!c){
			return false;
		}
	});

	$('.btn-hapus-register').click(function(e){
		var c = confirm(" saldo awal dan saldo akhir transaksi kasir ini pada tanggal dipilih akan dihapus, Yakin ? ");
		if (!c) return false;
	});
	$('.btn-kirim-email').click(function(){
		$.ajax({
		url:'<?php echo Yii::app()->createUrl("site/KirimEMail") ?>',
		// data:'tanggal_rekap='+tanggal+"&inserter="+inserter,
		success: function(data){
			alert(data);
			

		},
		error: function(data){
		alert('error');
		}
		});

	});


	$('.hapus').click(function(){
		var c = confirm("Yakin ?");

		var id = $(this).attr("user-id");
		var tanggal = $("#tanggal").val();

	

		if (!c){return false};

		var href = "index.php?r=setor/HapusByTanggal&id="+id+"&tanggal="+tanggal;
		window.location.assign(href);
		

		});
	$('.cetak').click(function(){
		var tanggal = $(this).attr("tanggal");
		var inserter = $(this).attr("inserter");
		
		if(tanggal==''){
			alert('Pilih tanggal terlebih dahulu');
			return false;
		}else{
			$.ajax({
				url:'<?php echo Yii::app()->createUrl("sales/cetakrekap") ?>',
				data:'tanggal_rekap='+tanggal+"&inserter="+inserter,
				success: function(data){
					// alert(data);
					var json = jQuery.parseJSON(data);
					// $('#hasiljson').html(data);
					print_rekap(json,false);
					// console.log(data);
					
				},
				error: function(data){
					alert('error');
				}
			});
		}
	});
});

// Function to handle orientation change
function handleOrientationChange() {
  if (window.orientation === 0 || window.orientation === 180) {
    // Portrait orientation
    console.log("Switched to portrait orientation");
    // Your portrait orientation handling code here
  } else {
    // Landscape orientation
    console.log("Switched to landscape orientation");
	setTimeout(() => {
		reloadDT();
	}, 1000);
    // Your landscape orientation handling code here
  }
}

// Call handler initially
handleOrientationChange();

// Add listener for orientation change
window.addEventListener("orientationchange", handleOrientationChange, false);
</script>