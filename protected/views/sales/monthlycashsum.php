
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>
<style type="text/css">
	select{
		padding:7px;
	}
</style>
<h1>
<i class="fa fa-book"></i>
Rekap Pembayaran </h1>
<hr>
<?php
$data = array(
		1=>'Januari',
		2=>'Februari',
		3=>'Maret',
		4=>'April',
		5=>'Mei',
		6=>'Juni',
		7=>'July',
		8=>'Agustus',
		9=>'September',
		10=>'Oktober',
		11=>'November',
		12=>'Desember');
	
$curr_year = Date('Y');
for($x=$curr_year-5; $x<$curr_year+5;$x++){
	$arr_year[$x] = $x;
}
$arr_bank = array();
$banks = Bank::model()->findAll("aktif=1");
foreach ($banks as $key => $value) {
	$arr_bank[$value->nama] = $value->nama; 
}
$arr_bank["0"] = "CASH"; 

?>
<form method="POST" >
<input type="hidden" name="r" value="sales/Salescashmonthly">
<?php 
// echo CHtml::beginForm();
?>
<label>Bulan</label>
<?php 
echo CHtml::dropDownList('month', $month, $data);
?>&nbsp;<?php
echo CHtml::dropDownList('year', $year, $arr_year);
?>
&nbsp;
<label> Pembayaran</label>
&nbsp;
<?php
echo CHtml::dropDownList('pembayaran', $bank, $arr_bank,array("empty"=>"Pilih"));
?>&nbsp;<?php
// echo CHtml::button('Cari', array('submit' => array('sales/Salescashmonthly'),"class"=>'btn btn-primary' ));
// echo CHtml::endForm();

?>
<button type="submit" class="btn btn-primary">Cari</button>
</form>
<?php
// echo "<BR>";
echo "<BR>";

echo "<table class='table items' >";
echo "<thead>";
	echo "<tr>";
		echo "<th>No.</th>";
		echo "<th>Tanggal</th>";
		// echo "<th>Kartu</th>";
		echo "<th>Total</th>";
		echo "<th>Cash</th>";
		// echo "<th>Compliment</th>";
		echo "<th>Debit</th>";
		echo "<th>Kredit</th>";
		// echo "<th>Transfer Mandiri</th>";
		// echo "<th>Voucher</th>";
		// echo "<th>dll</th>";
	echo "</tr>";
echo "</thead>";
echo "<tbody>";
$x=1;
//$tot = 3;
foreach($tot as $a){
	echo "<tr>";
		echo "<td>".$x++."</td>";
		echo "<td>".date("d M Y",strtotime($a['tanggal']) )."</td>";
		// echo "<td>".$a['pembayaran_via	']."</td>";
		echo "<td>".number_format($a['grandtotal'])."</td>";
		echo "<td>".number_format($a['cash'])."</td>";
		// echo "<td>".number_format($a['compliment'])."</td>";
		echo "<td>".number_format($a['edc_bca'])."</td>";
		echo "<td>".number_format($a['edc_niaga'])."</td>";
		// echo "<td>".number_format($a['transfer'])."</td>";
		// echo "<td>".number_format($a['voucher'])."</td>";
		// echo "<td>".number_format($a['dll'])."</td>";
	echo "</tr>";
	$tcash +=$a['cash'];
	$b +=$a['compliment'];
	$tbca +=$a['edc_bca'];
	$tmandiri +=$a['edc_niaga'];
	$ttransfer +=$a['transfer'];
	$e +=$a['voucher'];
	$f +=$a['dll'];
	$gt +=$a['grandtotal'];
}
echo "</tbody>";
echo "<tfoot style='background-color:#ccc;'>";
	echo "<tr>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		// echo "<td>&nbsp;</td>";
		echo "<td>".number_format($gt)."</td>";
		echo "<td>".number_format($tcash)."</td>";
		echo "<td>".number_format($tbca)."</td>";
		echo "<td>".number_format($tmandiri)."</td>";
		// echo "<td>".number_format($ttransfer)."</td>";
		// echo "<td>".number_format($e)."</td>";
		// echo "<td>".number_format($f)."</td>";
	echo "</tr>";
echo "</tfoot>";
echo "</table>";
?>