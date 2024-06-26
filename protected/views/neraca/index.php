
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/xlsx/xlsx.full.min.js"></script>

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
<i class="fa fa-balance-scale "></i>
Laporan Neraca  </h1>

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

?>
<form method="POST">
<input type="hidden" name="r" value="sales/labarugi">
<div style="display:none">
	<!-- <label>Tanggal</label> -->
	<!-- <input name="Sales[date]" type="text" value="<?php echo $tgl; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal"> -->
</div>
<label> Periode  </label>
<select id="bulan" name="bulan">
	<?php  
	$thisMonth = date("m") == 1 ? 12 : date("m") -1 ;
	for ($i=1; $i<=12; $i++) {
         $month = date('F', mktime(0,0,0,$i, 1, date('Y')));
         $x = date('m', mktime(0,0,0,$i, 1, date('Y')));
		 if (isset($_POST['bulan'])){
			if (intval($_POST['bulan']) === intval($x)){
				$selected = "selected";
			}else{
				$selected = "";
			}
		}else{
			if (intval($thisMonth) === intval($x)){
				$selected = "selected";
			}else{
				$selected = "";
			}
		}
         echo "<option $selected value='$x'>".ucfirst($month). '</option>';
     }
	 ?>
</select>
<select id="tahun" name="tahun">
<?php 
	for($i = 2022 ; $i <= date('Y'); $i++){
		if (isset($_POST['tahun'])){
			if ($i == $_POST['tahun']){
				$selected = "selected";
			}else{
				$selected = "";
			}
		}else{
			if ($i == date('Y')){
				$selected = "selected";
			}else{
				$selected = "";
			}
		}
		echo "<option $selected value='$i'>$i</option>";
	}
?>
</select>

<!-- <input name="Sales[date2]" type="text" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal"> -->
<input type="submit" name="submit" value="Lihat" class="btn btn-primary">
<input onclick="$('#data-cetak').print()" type="button" name="cetak" value="cetak" class="btn btn-primary">
<input type="button" value="Cetak Excel" class="no-print btn btn-primary" onclick="htmlTableToExcel('xlsx')" />

</form>
<br/>
<div class="well">
<table class="table items " id="data-cetak" style="width:500px;">
<tr class="print">
	<td colspan="3">
		<h1>Laporan Laba Rugi</h1>
		<h5>Periode <?php echo $tgl ?> sampai dengan <?php echo $tgl2 ?> </h5>
		<!-- <hr style="border:1px solid black"> -->

	</td>
</tr>
<tr>
	<td colspan="3" style="text-align: center;">
</tr>


<?php 
if (isset($_REQUEST['bulan'])):
$bulan = $_REQUEST['bulan'];
$tahun = $_REQUEST['tahun'];

$branch = Yii::app()->user->branch();
$filter = "  and month(tanggal_posting)<='$bulan' and year(tanggal_posting) <='$tahun' ";
$sql = NeracaController::queryNeraca($branch,$filter," ORDER by ag.id asc");
$model = Yii::app()->db->createCommand($sql)->queryAll();      
if (count($model) <= 0){
	echo "<p class='text-center text-warning' style='margin-top:5rem'>Tidak ada data ditemukan</p>";
}else{

// echo "<pre>";
// print_r($model);
// echo "</pre>";
foreach ($model as $value):
	if ($value['debit'] == 0){
		$modifiedData[$value['nama_group']][$value['nama_subgroup']][$value['nama_akun']] = in_array($value['ag_id'],[1,5,6]) ? $value['kredit'] *-1 : $value['kredit'] ;
	}else{
		$modifiedData[$value['nama_group']][$value['nama_subgroup']][$value['nama_akun']] = $value['debit'];
	}
endforeach;

// echo "<pre>";
// print_r($modifiedData);
// echo "</pre>";

?>
<?php 

function getAccounts($modifiedData,$filter,$totalNitip){
	$html = "";

	foreach ($modifiedData as $key=>$value):
		$totalAll = 0;
		if (in_array($key,$filter)) : 
			$html .= '
				<tr>
					<td colspan="2"  style="text-align: left;font-weight:bolder;padding-left:1rem">'.$key.'</td>
				</tr>
				';
			foreach ($value as $k=>$v):
				$totalSubGroup = 0;
				$html .= '
				<tr>
					<td colspan="2"  style="text-align: left;font-weight:bolder;padding-left:3rem">'.$k.'</td>
				</tr>'
				;
				foreach ($v as $k1=>$v1):
					$totalSubGroup+=$v1;
					$html .= '
						<tr>
							<td  style="text-align: left;padding-left:5rem">'.$k1.'</td>
							<td style="text-align: right;">'.number_format($v1).'</td>
						</tr>'
						;
				endforeach;
				$html .= '
				<tr>
					<td  style="text-align: left;padding-left:3rem;font-weight:bolder">TOTAL '.$k.'</td>
					<td style="text-align: right;font-weight:bolder">'.number_format($totalSubGroup).'</td>
				</tr>'
				;
				$totalAll += $totalSubGroup;
			endforeach;

		$html .= '
		<tr>
			<td  style="text-align: left;padding-left:1rem;font-weight:bolder">TOTAL '.$key.'</td>
			<td style="text-align: right;font-weight:bolder">'.number_format($totalAll).'</td>
		</tr>';
		$totalNitip = $totalAll;
		$$key += $totalSubGroup;
		endif; // enf of check key
	endforeach;

	if (isset($KEWAJIBAN) && isset($MODAL)){
		
		$html .= '
		<tr>
		<td  style="text-align: left;padding-left:1rem;font-weight:bolder">KEWAJIBAN & MODAL</td>
		<td style="text-align: right;font-weight:bolder">'.number_format($KEWAJIBAN + $MODAL).'</td>
		</tr>'
		;
		$totalNitip = $KEWAJIBAN + $MODAL;
	}
		

	return ['html'=>$html,'totalNitip'=>$totalNitip];
}

$totalAktiva = 0;
$totalPasiva = 0;
$totalModal = 0;
$table1 = getAccounts($modifiedData,['AKTIVA'],$totalPendapatanPokok);
echo $table1['html'];
$table2 = getAccounts($modifiedData,['KEWAJIBAN','MODAL'],$totalBiaya);
echo $table2['html'];
// $table3 = getAccounts($modifiedData,['MODAL'],$totalModal);
// echo $table3['html'];
}

// echo '
// <tr>
// 	<td  style="text-align: left;padding-left:1rem;font-weight:bolder">LABA/RUGI</td>
// 	<td style="text-align: right;">'.number_format($table1['totalNitip'] - $table2['totalNitip']).'</td>
// </tr>'
// ;
else:
	echo "<p class='text-center text-warning' style='margin-top:5rem'>Tidak ada data ditemukan</p>";
endif;
?>






	

</table>
</div>
