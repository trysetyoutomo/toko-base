<div class="h1 font-size-sm">
	<i class="fa fa-chart-column"></i> Omzet Penjualan
</div>
<hr>

<?php
$data = array(
		1=>'Januari',
		2=>'Februari',
		3=>'Maret',
		4=>'April',
		5=>'Mei',
		6=>'Juni',
		7=>'Juli',
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
<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-3 text-left">
	<?php echo CHtml::dropDownList('month', $month, $data,['class'=>"form-control","prompt"=>"Semua Bulan"]); ?>
	</div>

	<div class="col-xs-12 col-sm-3 col-md-3 text-left">
	<?php echo CHtml::dropDownList('year', $year, $arr_year,['class'=>"form-control"]); ?>
	</div>


	<div class="col-xs-12 col-sm-3 col-md-3 text-left">
		<div class="form-group">
			<?php echo CHtml::submitButton('Cari', array('class' => 'btn btn-primary')); ?>
		</div>
	</div>
</div>
<?php 
echo CHtml::endForm();
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div id="container-chart">
	<canvas id="kanvasku" width="400" height="400"></canvas>
</div>

<script>
var labelku = new Array();
var dataku = new Array();
</script>
<?php
$data = "";
$label = "";
$i=0;
$array = array();
foreach ($databar as $key)
{
	$data = $data .""."'".$key['label']."'"."," ;
	$label = $label .""."'".$key['omzet']."'"."," ;
	$i++;
}
?>

<script>
		labelku = [<?=$data?>];
		dataku = [<?=$label?>];
        var barData = {
            labels : labelku,
            datasets : [
				{
					label: 'Omzet',
                    fillColor : "rgba(255, 0, 0, 0.8)",
                    strokeColor : "rgba(220,220,220,1)",
                    data : dataku
                },
            ]
            
        }

    // var barKu = new Chart(document.getElementById("kanvasku").getContext("2d")).Bar(barData);.
	
	var barKu = new Chart(document.getElementById("kanvasku").getContext("2d"), {
		type: 'bar',
		data: barData,
		options: {
			responsive: true,
			maintainAspectRatio: false
		}
	});
    
</script>
<br>
<br>
<br>
<br>
<?php 
// echo $data;
?>