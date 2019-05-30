<br>
<br>
<br>
<h1>Melihat Customer #<?php echo $model->id; ?></h1>

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
	select * from sales s, sales_items si where si.sale_id = s.id  and s.nama = '$model->nama'
	order by s.date desc
  "; 
$model = Yii::app()->db->createCommand($sql)->queryAll();
?>



<table class="table">
	<thead>		
		<tr style="background:rgba(163, 0, 0,1) ;color:white">
			<td>No</td>
			<td>Sale ID</td>
			<td>Tanggal </td>
			<td>Item</td>
			<td>Harga </td>
			<td>Jumlah </td>
			<td>Total </td>
		</tr>

	</thead>
	<tbody>
		<?php 
		$no=1;
		foreach ($model as $m): ?>
		<tr>
			<td style=""><?php echo $no;?></td>
			<td style=""><?php echo $m[id]; ?></td>
			<td style=""><?php echo $m[date]; ?></td>
			<td style=""><?php echo "Rp. ".number_format($m[item_price]); ?></td>
			<td style=""><?php echo number_format($m[quantity_purchased]); ?></td>
			<td style=""><?php echo "Rp. ".number_format($m[item_price]); ?></td>
			<td style=""><?php echo "Rp. ".number_format($m[item_price]*$m[quantity_purchased]); ?></td>
		</tr>
			
	<?php 
	$total = $total +$m[total];
	$no++;
	endforeach; ?>
	<tr>
		<td colspan="8" style="text-align:right">
			<?php 
			echo number_format("$total");
			?>
		</td>
	</tr>
	</tbody>
</table>