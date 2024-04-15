<?php 
// echo "<pre>";
// print_r($databar);
// echo "</pre>";
// exit;
?>
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
	<i class="fa fa-wallet"></i> Rekap Pembayaran
</div>
<hr>

<?php
$form = $this->beginWidget('CActiveForm', array(
	// 'action' => Yii::app()->createUrl('sales/grafik'),
	'method' => 'get',
	'htmlOptions' => array('class' => 'form-inline'),
));
?>
<div class="row" style="margin-bottom:1rem">
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

			
			<!-- <div class="col-xs-12 col-sm-3 col-sm-2 text-left">
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
			-->
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

<table class="items table" id="datatable">
	<thead>
		
		<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >
			<td>Metode Pembayaran</td>
			<td>Total</td>
		</tr>
	</thead>
	<tbody>
			<?php foreach ($databar  as $m ):?>
				<tr>
					<td><?=($m['bank'])?></td>
					<td><?=number_format($m['total'])?></td>
				</tr>
				<?php 
				$total+=$m['total'];
				?>
			<?php endforeach?>		
	</tbody>
		<tfoot style="font-weight: bold;">
			<tr>
				<td>Total</td>
				<td><?=number_format($total)?></td>
			</tr>
		</tfoot>
</table>

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
			{ "width": "8%", "targets": 1 },  
			{ "width": "8%", "targets": 2 },
			{ "width": "8%", "targets": 3 },
		]
	});
	}
	$(document).ready(function(e){
		reloadDT();
	})
</script>