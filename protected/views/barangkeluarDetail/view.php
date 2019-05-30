<?php
$this->breadcrumbs=array(
	'Barangkeluar Details'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List BarangkeluarDetail', 'url'=>array('index')),
	array('label'=>'Create BarangkeluarDetail', 'url'=>array('create')),
	array('label'=>'Update BarangkeluarDetail', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete BarangkeluarDetail', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage BarangkeluarDetail', 'url'=>array('admin')),
);
?>

<h1>View BarangkeluarDetail #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'kode',
		'jumlah',
		'head_id',
		'harga',
	),
)); ?>
