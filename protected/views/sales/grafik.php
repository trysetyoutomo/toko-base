<style>
	#container-chart {
		width: 100%;
	}
</style>

<script type="text/javascript">
	$(document).ready(function(e) {
		$('.tanggal').datepicker({
			dateFormat: 'yy-mm-dd',
			changeMonth: true,
			changeYear: true,
		});
	});

</script>

<?php $store_id = Yii::app()->user->store_id(); ?>
<div class="h1 font-size-sm">
	<i class="fa fa-chart-column"></i> Top Penjualan terbaik
</div>
<hr>

<?php
$form = $this->beginWidget('CActiveForm', array(
	'action' => Yii::app()->createUrl('sales/grafik'),
	'method' => 'get',
	'htmlOptions' => array('class' => 'form-inline'),
));
?>
<div class="row">
	<?php echo CHtml::hiddenField('mode', $mode); ?>

	<div class="col-xs-12">
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-sm-2 text-left">
				<div class="form-group">
					<?php echo CHtml::label('Tanggal', 'Sales_date'); ?>
					<?php echo CHtml::textField('Sales[date]', $tgl, array('class' => 'form-control tanggal', 'style' => 'display:inline;padding:5px')); ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-sm-2 text-left">
				<div class="form-group">
					<?php echo CHtml::label('Sampai', 'Sales_tgl'); ?>
					<?php echo CHtml::textField('Sales[tgl]', $tgl2, array('class' => 'form-control tanggal', 'style' => 'display:inline;padding:5px')); ?>
				</div>
			</div>

			
			<div class="col-xs-12 col-sm-3 col-sm-2 text-left">
				<div class="form-group">
					<?php echo CHtml::label('Pilih Kelompok', 'kelompok'); ?>
					<select name="kelompok" class="form-control">
						<option  <?=$_REQUEST['kelompok'] === 'produk' ? 'selected': ''?> value="produk">Produk</option>
						<option  <?=$_REQUEST['kelompok'] === 'cabang' ? 'selected': ''?> value="cabang">Cabang</option>

					</select>
				</div>
			</div>

			<div class="col-xs-12 col-sm-3 col-sm-2 text-left">
				<div class="form-group">
					<?php echo CHtml::label('Pilih Jenis Grafik', 'jenis_chart'); ?>
					<select name="jenis_chart" class="form-control">
						<option <?=$_REQUEST['jenis_chart'] === 'bar' ? 'selected': ''?> value="bar">Bar Chart</option>
						<option <?=$_REQUEST['jenis_chart'] === 'pie' ? 'selected': ''?> value="pie">Pie Chart</option>

					</select>
				</div>
			</div>

			<div class="col-xs-12 col-sm-3 col-sm-2 text-left">
				<div class="form-group">
					<?php echo CHtml::label('Pilih Kategori', 'kategori'); ?>
					<select name="kategori" class="form-control">
						<optgroup label="Pilih Kategori"></optgroup>
						<option value="semua">Semua</option>
						<?php
						foreach (Categories::model()->findAll("status=0 and store_id = '$store_id'") as $c) {
							$selected = ($_REQUEST['kategori'] == $c['id']) ? 'selected' : '';
							echo '<option value="' . $c['id'] . '" ' . $selected . '>' . $c['category'] . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-sm-2 text-left " style="display: none;">
				<div class="form-group">
					<?php echo CHtml::label('Limit', 'limit'); ?>
					<?php echo CHtml::textField('limit', isset($_REQUEST['limit']) ? $_REQUEST['limit'] : '10', array('class' => 'form-control', 'required' => 'required')); ?>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-sm-2 text-left">
				<div class="form-group">
					<?php echo CHtml::submitButton('Cari', array('class' => 'btn btn-primary')); ?>
				</div>
			</div>
		</div>
	</div>
</div>



<?php $this->endWidget(); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<div id="container-chart" style="margin-top:2rem">
	<canvas id="kanvasku" width="400" height="400"></canvas>
</div>


<?php
$data = "";
$label = "";
$i = 0;
if ($mode == 'bersih') {
	foreach ($databar as $key => $value) {
		$data = $data . "" . "'" . $databar[$i][n] . "'" . ",";
		$label = $label . "" . "'" . $databar[$i][b] . "'" . ",";
		$i++;
	}
} else if ($mode == 'top') {
	foreach ($databar as $key => $value) {
		$na = str_replace("'", "", $databar[$i][nama]);
		$data = $data . "" . "'" . $na . "'" . ",";
		$label = $label . "" . "'" . intval($databar[$i][jumlah]) . "'" . ",";
		$i++;
	}
}
$label = rtrim($label, ",");
$data = rtrim($data, ",");


?>
<script type="text/javascript">
	var labelku = new Array();
	var dataku = new Array();

	labelku = [<?php echo $data; ?>];
	dataku = [<?php echo $label; ?>];


	var barData = {
		labels: labelku,
		datasets: [{
			label: 'Terjual (QTY)',
			fillColor: "rgba(255, 0, 0, 0.8)",
			strokeColor: "rgba(220,220,220,1)",
			data: dataku
		}, ]

	}


	var barKu = new Chart(document.getElementById("kanvasku").getContext("2d"), {
		type: '<?=isset($_REQUEST['jenis_chart']) ? $_REQUEST['jenis_chart'] : 'bar'?>',
		data: barData,
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
              // Configure the datalabels plugin
              datalabels: {
                  color: '#fff',
                  formatter: (value, ctx) => {
                      let sum = 0;
                      let dataArr = ctx.chart.data.datasets[0].data;
                      dataArr.map(data => {
                          sum += parseFloat(data);
                      });
                      let percentage = (value*100 / sum).toFixed(2)+"%";
                      return percentage;
                  },
                  anchor: 'end',
                  align: 'start',
                  offset: 10,
              }
          },
		},
		plugins: [ChartDataLabels]
	});
</script>
