<br>
<br>
<center>
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
// echo "Bulan : ";
// echo CHtml::dropDownList('month', $month, $data);
echo "Tahun : ";
echo CHtml::dropDownList('year', $year, $arr_year);
echo " &nbsp;&nbsp;&nbsp; ";
// echo CHtml::button('Cari', array('submit' => array('sales/grafikpenjualan')));
?>
<input type="submit" name="cari" value="Cari" class="btn btn-primary"> 
<?php 
echo CHtml::endForm();
?>
</center>


</center>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/chart/Chart.js"></script>
<canvas id="kanvasku" height="500" width="900%"  ></canvas>

<?php 
// echo "123";
// echo "<pre>";
// print_r($databar);
// echo "</pre>";
// echo $databar[0]["nama_outlet"];
// $z=0;
// // foreach ($databar as $key=>$value)
// {
// echo $databar[$z]["nama_outlet"] . " nilai :".$databar[$z]["persentase_hasil"]."<br>";
// $z++;
// }

// $dataku = 
?> 
<script>
var labelku = new Array();
var dataku = new Array();
</script>
<?php
$data = "";
$label = "";
$i=0;
$array = array();
// for ($i=1; $i <=12 ;$i++) {
// 	if ($databar['MONTH']==$i){
// 		echo $i."123";
// 	}
// 	// array_push($array, )
// }
foreach ($databar as $key)
{
	$data = $data .""."'".$key['month_name']."'"."," ;
	$label = $label .""."'".$key['stt']."'"."," ;
	$i++;
}
// echo $data."<br>";
// echo $label;


?>

<script>

			
		labelku = [<?=$data?>];
		dataku = [<?=$label?>];


        var barData = {
            labels : labelku,
            datasets : [
                {
                    fillColor : "rgba(255, 0, 0, 0.8)",
                    strokeColor : "rgba(220,220,220,1)",
                    data : dataku
                },
            ]
            
        }

    var barKu = new Chart(document.getElementById("kanvasku").getContext("2d")).Bar(barData);
    
</script>
<br>
<br>
<br>
<br>
<?php 
// echo $data;
?>