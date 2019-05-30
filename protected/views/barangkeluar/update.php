<?php
$this->breadcrumbs=array(
	'Barangkeluars'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Barangkeluar', 'url'=>array('index')),
	array('label'=>'Create Barangkeluar', 'url'=>array('create')),
	array('label'=>'View Barangkeluar', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Barangkeluar', 'url'=>array('admin')),
);
?>

<h1>
<i class="fa fa-book"></i>
Update Transaksi </h1>
<h2>
	Barang Keluar <?php echo $model->kode_trx; ?>
</h2>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>