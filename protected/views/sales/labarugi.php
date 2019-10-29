
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
	// $(".fa-times").click(function(e){
	// 	// e.preventDefault();
	// 	if (!confirm("Yakin ? ")){
	// 		return false;
	// 	}

	// });
	 $('.tanggal').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true

    });

});
</script>
<style type="text/css">
	select{
		padding:7px;
	}
	.table tr td{
		border: 0px solid white!important;
	}
	</style>
<style type="text/css">
	.print{
		display: none;
	}
	@media print {
		.print{display: block;}
	}
</style>


<h1>
<i class="fa fa-book"></i>
Laporan Laba Rugi </h1>

<?php 
if (isset($_REQUEST['tgl'])){
	$tgl = $_REQUEST['tgl'];
	$tgl2 = $_REQUEST['tgl2'];
	$filter = " and date(tanggal) >= '$tgl' and date(tanggal)<='$tgl2' ";

}else{
	$tgl = date('Y-m-')."01";
	$tgl2 = date('Y-m-d');
	$filter = "";

}
?>
<br>
	<?php
// $data = array(
// 		1=>'Januari',
// 		2=>'Februari',
// 		3=>'Maret',
// 		4=>'April',
// 		5=>'Mei',
// 		6=>'Juni',
// 		7=>'July',
// 		8=>'Agustus',
// 		9=>'September',
// 		10=>'Oktober',
// 		11=>'November',
// 		12=>'Desember');
	
// $curr_year = Date('Y');
// for($x=$curr_year-5; $x<$curr_year+5;$x++){
// 	$arr_year[$x] = $x;
// }

// echo CHtml::beginForm();
?>
<form>
<input type="hidden" name="r" value="sales/labarugi">
<label>Tanggal 1</label>
<input name="Sales[date]" type="text" value="<?php echo $tgl; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<label>sampai</label>
<input name="Sales[date2]" type="text" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<input type="submit" name="submit" value="cari" class="btn btn-primary">
<input onclick="$('#data-cetak').print()" type="button" name="cetak" value="cetak" class="btn btn-primary">
</form>
<?php 
	
// echo CHtml::button('Cari', array('submit' => array('sales/labarugi'),'class'=>'btn btn-primary' ) );
?>

<table class="table items" id="data-cetak" style="width:500px">
<tr class="print">
	<td colspan="3">
		<h1>Laporan Laba Rugi</h1>
		<h5>Periode <?php echo $tgl ?> sampai dengan <?php echo $tgl2 ?> </h5>
		<!-- <hr style="border:1px solid black"> -->

	</td>
</tr>
<tr>
	<td colspan="3" style="text-align: center;">
<?php 
// $omset =  SalesController::GetOmset($month,$year,"omset");
// $modal =  SalesController::GetOmset($month,$year,"modal");
$sql_agen  = "
select sum(nominal) as total
from 
(
SELECT
	'1' AS item_id,
	'0' AS nomor,
IF
	( customer_id = 0, 'DEPOSIT', concat( 'DEPOSIT_AGEN', '
	', c.nama ) ) AS item_name,
IF
	( customer_id = 0, 'DEPOSIT', 'DEPOSIT_AGEN' ) AS nama_provider,
	nominal,
	'admin',
	'1',
	created_at AS tanggal 
FROM
	deposit
	LEFT JOIN customer c ON c.id = deposit.customer_id 
WHERE

	customer_id != '0' 
 
ORDER BY
	deposit.created_at asc
) as tbl
where tbl.tanggal  between '$tgl' and '$tgl2'
";
$data_agen = Yii::app()->db->createCommand($sql_agen)->queryRow();



// echo $sql_agen;



$table = SalesController::sqlSales();
	$sql = "SELECT ID AS id,
				tanggal_jt,
				sb,
				bayar,
				inserter,
				date,
				untung,
				sum(sale_sub_total) sale_sub_total ,
				sum(sale_sub_modal) sale_sub_modal,
				sum(voucher) voucher,
				sum(tax) tax,
				sum(service) service,
				sum(sale_discount) sale_discount,
				sum(sale_total_cost) sale_total_cost,
				sum(total_items) total_items FROM ($table) as A where  

				date(A.date) between '$tgl' and '$tgl2'  ";
	$tot = Yii::app()->db->createCommand($sql)->queryRow();



$omset = $tot['sale_sub_total'];
$modal = $tot['sale_sub_modal'];
$omset_agen = $data_agen['total'];

$kotor = $omset_agen - $omset - $modal;
?>
<tr>
	<td colspan="3">
		<b>Pendapatan Agen Pulsa</b>
	</td>
</tr>
<tr>
	<td  style="text-align: left;">Total Laba Kotor</td>
		<td style="text-align: right;"><?php echo number_format($data_agen['total']) ?></td>
	</tr>

<tr>
	<td colspan="3">
		<b>Pendapatan Penjualan Barang</b>
	</td>
</tr>
	<tr>
		<td  style="text-align: left;">Omzet</td>
		<!-- <td style="width:10px;">:</td> -->
		<td style="text-align: right;"><?php echo number_format($omset) ?></td>
	</tr>
	<tr>
		<td style="text-align: left;">Modal Penjualan</td>
		<!-- <td style="width:10px;">:</td> -->
			<td style="text-align: right;"><?php echo number_format($modal) ?></td>
	</tr>
	<tr>
		<td style="text-align: left;">Total Laba Kotor</td>
		<!-- <td style="width:10px;">:</td> -->
		<td style="text-align: right;color:red"><?php echo number_format($kotor); ?></td>
	</tr>
<tr>
	<td colspan="3">
		<b>Pengeluaran</b>
	</td>
</tr>
<?php 
	$branch = Yii::app()->user->branch();
	$sql = "SELECT  sum( total ) total,jb.nama nama
	FROM `pengeluaran` p
	right JOIN jenis_beban jb ON jb.nama = p.jenis_pengeluaran 
	and month(tanggal)=$month and year(tanggal)=$year
	where p.branch_id = '$branch'
	GROUP BY jb.nama

	";
	$model = Yii::app()->db->createCommand($sql)->queryAll();

	// print_r($model);

	 ?>
	<?php 
	$total_e = 0;
	foreach ($model as $key => $value) { ?>
		<tr>
		 <td style="text-transform:lowercase;"><?php echo $value['nama'] ?></td>
		 <!-- <td>:</td> -->
		 <td style="text-align: right;"><?php echo number_format($value['total']) ?></td>
	</tr>
	<?php 
	$total_e += $value["total"];
	} ?>
	<tr>
		<td>Total Pengeluaran</td>
		<td colspan="2" style="text-align: right;color:red">(<?php echo number_format($total_e); ?>)</td>
	</tr>
<tr>
	<td colspan="3">
		<b>Laba/Rugi</b>
	</td>
</tr>
	<td style="text-align: left;">
		Laba / Rugi 
	</td>
	<td colspan="2"  style="text-align: right;color:red">
		<?php 
			echo number_format($kotor - $total_e);

		?>
	</td>

	

</table>
