<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>

<style type="text/css">
	select{
		padding: 7px;
	}
</style>
<?php 
	$level = Yii::app()->user->getLevel();
	// echo $level;
?>
<br>
		
<h1>
<i class="fa fa-book"></i>
Rekap Penjualan Harian </h1>
<br>
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

echo CHtml::beginForm();
?>
<label>
	Bulan 
</label>
<?php 
echo CHtml::dropDownList('month', $month, $data);
echo CHtml::dropDownList('year', $year, $arr_year);
//echo CHtml::button('Cari', array('submit' => array('sales/Salesmonthly'),'class'=>'btn btn-primary' ) );
?>
&nbsp;
<button class="btn btn-primary" type="submit">Cari</button>
<button type="button" class="btn btn-primary" onclick="$('#table-rekap-harian').print()">Cetak</button>

<?php 
echo CHtml::endForm();

echo "<BR>";
echo "<div class='grid-view'>";
echo "<table class='items table' id='table-rekap-harian'>";
echo "<thead>";
	echo "<tr>";
		echo "<th>No.</th>";
		echo "<th>Tanggal</th>";
		if ($level==2){		
			echo "<th>Total Modal</th>";
		}
		echo "<th>Total Kotor</th>";
		echo "<th>Total  Diskon</th>";
		echo "<th>Total  Biaya Layanan</th>";
		echo "<th>Voucher</th>";
		echo "<th>Total  Pajak </th>";
		echo "<th>Total Bersih</th>";
	echo "</tr>";
echo "</thead>";
echo "<tbody>";
$c=1;
foreach($tot as $a){
	echo "<tr>";
		echo "<td>".$c++."</td>";
		echo "<td>".date("d M Y",strtotime($a['date']) )."</td>";
		if ($level==2){		
			echo "<td>".number_format($a['sale_sub_modal'])."</td>";
		}
		

		echo "<td>".number_format($a['sale_sub_total'])."</td>";
		echo "<td>".number_format($a['sale_discount'])."</td>";
		echo "<td>".number_format($a['service'])."</td>";
		echo "<td>".number_format($a['voucher'])."</td>";
		echo "<td>".number_format($a['tax'])."</td>";
		echo "<td>".number_format($a['sale_total_cost'])."</td>";
	echo "</tr>";
	$t_modal +=$a['sale_sub_modal'];
	$t_discount +=$a['sale_discount'];
	$t_kotor +=$a['sale_sub_total'];
	$t_service +=$a['service'];
	$t_voucher +=$a['voucher'];
	$t_tax +=$a['tax'];
	$t_total+=$a['sale_total_cost'];
}
echo "</tbody>";
echo "<tfoot style='background-color:#ccc;'>";
	echo "<tr>";
		echo "<td>&nbsp;</td>";
		echo "<td>&nbsp;</td>";
		echo "<td>".number_format($t_modal)."</td>";
		echo "<td>".number_format($t_kotor)."</td>";
		echo "<td>".number_format($t_discount)."</td>";
		echo "<td>".number_format($t_service)."</td>";
		echo "<td>".number_format($t_voucher)."</td>";
		echo "<td>".number_format($t_tax)."</td>";
		echo "<td>".number_format($t_total)."</td>";
		// echo "<td>".number_format($ttv)."</td>";
	echo "</tr>";
echo "</tfoot>";
echo "</table>";
echo "</div>";
?>