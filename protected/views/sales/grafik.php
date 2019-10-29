<script type="text/javascript">
	$(document).ready(function(e){
	 $('.tanggal').datepicker({ dateFormat: 'yy-mm-dd',changeMonth:true,changeYear:true,});
	});
</script>

<center>
<?php $form=$this->beginWidget('CActiveForm',array(
	'action'=>Yii::app()->createUrl('sales/grafik'),
	'method'=>'get',
)); ?>
<div class="row">
	<input name="mode" type ="hidden" value="<?php echo $mode; ?>" />
	<span>Tanggal</span>
		<input type="text" value="<?php echo $tgl ?>" style="display:inline;padding:5px" name="Sales[date]" id="Sales_date" class="tanggal">

	<?php
	
	?>
	&nbsp;sampai dengan &nbsp;
	<input type="text" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="Sales[tgl]" id="Sales_tgl" class="tanggal">
<?php 
echo "&nbsp;&nbsp;&nbsp;";
?>
<!-- 
-->
Pilih Kategori
<select name="kategori">
	<optgroup>Pilih Kategori</optgroup>
	<option value="semua">Semua</option>

	<?php 
	$store_id = Yii::app()->user->store_id();
	foreach (Categories::model()->findAll("status=0 and store_id = '$store_id'") as $c) {
	?>
	<option <?php if ($_REQUEST[kategori]==$c[id]) echo "selected"; ?> value="<?php echo $c[id] ?>"><?php echo $c[category] ?></option>
	<?php 
	}?>
</select>
Limit 
<input name="limit" type="text" value="<?php 
if (isset($_REQUEST[limit]))
	echo $_REQUEST[limit]; 
else
	echo '100';
?>" required  >

<!-- <select name="motif">
<optgroup>Pilih Motif</optgroup>
	<?php foreach (Motif::model()->findAll() as $c) {
	?>
	<option value="<?php echo $c->id ?>"><?php echo $c->nama ?></option>
	<?php 
	}?>
</select> -->
<?php 
echo CHtml::submitButton('Cari',array("class"=>"btn btn-primary")); 
?>
</div>
<?php $this->endWidget(); ?>
</center>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/chart/Chart.js"></script>
<canvas id="kanvasku" height="500" width="900%"  ></canvas>


<?php
$data = "";
$label = "";
$i=0;
if ($mode=='bersih'){
	foreach ($databar as $key=>$value)
	{
		$data = $data .""."'".$databar[$i][n]."'"."," ;
		$label = $label .""."'".$databar[$i][b]."'"."," ;
		$i++;
	}
}else if ($mode=='top')  {
	foreach ($databar as $key=>$value)
	{
		$na = str_replace("'","", $databar[$i][nama]);
		$data = $data .""."'".$na."'"."," ;
		$label = $label .""."'".intval($databar[$i][jumlah])."'"."," ;
		$i++;
	}
}
$label = rtrim($label,",");
$data = rtrim($data,",");


?>
<script type="text/javascript">
	var labelku = new Array();
	var dataku = new Array();

	labelku = [<?php echo $data; ?>];
	dataku = [<?php echo $label; ?>];


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