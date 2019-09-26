
<h1>Rincian Customer #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nama',
		'alamat',
		'no_telepon',
	),
)); ?>


<?php
$sql  = "
	select * from sales s, sales_items si 
	where 
	si.sale_id = s.id  and 
	s.nama = '$model->nama'
	and branch = ".Yii::app()->user->branch()."
	order by s.date desc
  "; 
$model = Yii::app()->db->createCommand($sql)->queryAll();
?>

<hr>
<h3>Riwayat Transaksi</h3>

<table class="table">
	<thead>		
		<tr style="background: rgba(42, 63, 84,1);color:white">
			<td>No</td>
			<td>ID Penjualan</td>
			<td>Tanggal </td>
			<td>Total </td>
		</tr>

	</thead>
	<tbody>
		<?php 
		$no=1;
		foreach ($model as $m): ?>
		<tr>
			<td style=""><?php echo $no;?></td>
			<td style=""><?php echo $m[faktur_id]; ?></td>
			<td style=""><?php echo $m[date]; ?></td>
			<td style=""><?php echo "Rp. ".number_format($m['sale_total_cost']); ?></td>
		</tr>
			
	<?php 
	$total = $total +$m[sale_total_cost];
	$no++;
	endforeach; ?>
	<tr>
		<td colspan="8" style="text-align:right">
			<?php 
			echo "Total :".number_format("$total");
			?>
		</td>
	</tr>
	</tbody>
</table>