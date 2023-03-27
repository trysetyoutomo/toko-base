<style>
.tab1 {
    tab-size: 2;
}

.tab2 {
    tab-size: 4;
}

.tab4 {
    tab-size: 8;
}

</style>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>
<script type="text/javascript">
	function htmlTableToExcel(type){
		var data = document.getElementById('data-cetak');
		var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
		XLSX.write(excelFile, { bookType: type, bookSST: true, type: 'base64' });
		const urlParams = new URLSearchParams(window.location.search);
		const tgl1 = urlParams.get('tgl1')
		const tgl2 = urlParams.get('tgl2')
		XLSX.writeFile(excelFile, 'Laporan_Laba_Rugi_<?=$_REQUEST['customer']?>.'+type);
	}
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
if (isset($_REQUEST['Sales']['date'])){
	$tgl = $_REQUEST['Sales']['date'];
	$tgl2 = $_REQUEST['Sales']['date2'];
	$filter = " and date(tanggal) >= '$tgl' and date(tanggal)<='$tgl2' ";

}else{
	$tgl = date('Y-m-')."01";
	$tgl2 = date('Y-m-d');
	$filter = "";

} 
?>
<script type="text/javascript">
	function htmlTableToExcel(type){
		var data = document.getElementById('data-cetak');
		var excelFile = XLSX.utils.table_to_book(data, {sheet: "sheet1"});
		XLSX.write(excelFile, { bookType: type, bookSST: true, type: 'base64' });
		const urlParams = new URLSearchParams(window.location.search);
		const tgl1 = urlParams.get('tgl1')
		const tgl2 = urlParams.get('tgl2')
		XLSX.writeFile(excelFile, 'Laporan_Laba_Rugi_<?=$tgl1 . " - ". $tgl2?>.'+type);
	}
</script>
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
<form method="POST">
<input type="hidden" name="r" value="sales/labarugi">
<label>Tanggal</label>
<input name="Sales[date]" type="text" value="<?php echo $tgl; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<label>sampai</label>
<input name="Sales[date2]" type="text" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<input type="submit" name="submit" value="cari" class="btn btn-primary">
<input onclick="$('#data-cetak').print()" type="button" name="cetak" value="cetak" class="btn btn-primary">
<input type="button" value="Cetak Excel" class="no-print btn btn-primary" onclick="htmlTableToExcel('xlsx')" />

</form>
<?php 
$branch = Yii::app()->user->branch();
$filter = "  and date(tanggal_posting)<='$tgl2' ";
$sql = NeracaController::queryNeraca($branch,$filter);
$model = Yii::app()->db->createCommand($sql)->queryAll();      
?>


<!-- foreach -->
<pre>
<?php 
    print_r($model);
?>
<span style="margin-left:0rem;font-weight:bolder">AKTIVA </span>
<span style="margin-left:1rem;font-weight:bolder">KAS & BANK</span>
<?php
$total1 = 0; 
foreach ($model as $value):
    if ( in_array($value['nama_subgroup_detail_id'],[1])){

        echo '<div style="margin-left:2rem">'.$value['nama_akun'].'   '. number_format($value['debit']-$value['kredit']).'</div>';
        $total1 += $value['debit'] - $value['kredit'];
    }
    
endforeach;
?>
<span style="margin-left:1rem">TOTAL KAS & BANK <?php echo number_format($total1) ?></span>
<span style="margin-left:1rem;font-weight:bolder">PIUTANG</span>
<?php 
$total2 = 0; 
foreach ($model as $value):
    if ( in_array($value['nama_subgroup_detail_id'],[17])){
        echo '<div style="margin-left:3rem">'.$value['nama_akun'].'   '. number_format($value['debit']).'</div>';
        $total2 += $value['debit'];
    }
endforeach;
?>
<span style="margin-left:2rem">TOTAL PIUTANG  <?php echo number_format($total2) ?></span>
<span style="margin-left:1rem">TOTAL AKTIVA LANCAR <?php echo number_format($total1 + $total2) ?></span>
<span style="margin-left:1rem;font-weight:bolder">PERSEDIAAN</span>
<?php 
$total3 = 0; 
foreach ($model as $value):
    if ( in_array($value['subgroup_id'],[1])){
        echo '<div style="margin-left:3rem">'.$value['nama_akun'].'   '. number_format($value['debit']).'</div>';
        $total3 += $value['debit']; 
    }
endforeach;
?>
<span style="margin-left:1rem;font-weight:bolder">TOTAL PERSEDIAAN <?php echo number_format($total3) ?></span>
<span style="margin-left:0rem;font-weight:bolder">TOTAL AKTIVA <?php echo number_format( $total1 + $total2 + $total3) ?></span>


<span style="margin-left:0rem;font-weight:bolder">KEWAJIBAN </span>
<span style="margin-left:1rem;">KEWAJIBAN LANCAR</span>
<?php 
$total4 = 0; 
foreach ($model as $value):
    if ( in_array($value['subgroup_id'],[4])){
        echo '<div style="margin-left:2rem">'.$value['nama_akun'].'   '. number_format($value['kredit']).'</div>';
        $total4 += $value['kredit']; 
    }
endforeach;
?>
<span style="margin-left:1rem;">TOTAL KEWAJIBAN LANCAR <?php echo number_format($total4) ?></span>
<span style="margin-left:0rem;font-weight:bolder">MODAL </span>
<?php 
$total5 = 0; 
foreach ($model as $value):
    if ( in_array($value['subgroup_id'],[8])){
        echo '<div style="margin-left:1rem">'.$value['nama_akun'].'   '. number_format($value['kredit']).'</div>';
        $total4 += $value['kredit']; 
    }
endforeach;
?>
<span style="margin-left:0rem;font-weight:bolder">TOTAL MODAL <?php echo number_format($total5) ?></span>
<span style="margin-left:0rem;font-weight:bolder">TOTAL KEWAJIBAN & MODAL <?php echo number_format($total4 + $total5) ?></span>



</pre>


<table class="table items" id="data-cetak" style="width:500px">
<tr class="print">
	<td colspan="3">
		<h1>Laporan Laba Rugi</h1>
		<h5>Periode <?php echo $tgl ?> sampai dengan <?php echo $tgl2 ?> </h5>
		<!-- <hr style="border:1px solid black"> -->

	</td>
</tr>
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
	<td colspan="2"  style="text-align: right;">
		<h4>
		<?php 
		$total = ($omset_agen + $kotor) - $total_e;
			if ($total>0)
				echo "<b style='color:green'>".number_format($total)."</b>";
			else
				echo "<b style='color:red'>".number_format($total)."</b>";

		?>
	</h4>

	</td>

	

</table>
