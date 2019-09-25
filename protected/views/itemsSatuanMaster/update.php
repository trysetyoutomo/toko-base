<?php
$this->breadcrumbs=array(
	'Items Satuan Masters'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

// $this->menu=array(
// 	array('label'=>'List ItemsSatuanMaster', 'url'=>array('index')),
// 	array('label'=>'Create ItemsSatuanMaster', 'url'=>array('create')),
// 	array('label'=>'View ItemsSatuanMaster', 'url'=>array('view', 'id'=>$model->id)),
// 	array('label'=>'Manage ItemsSatuanMaster', 'url'=>array('admin')),
// );
?>

<h1>Ubah Satuan #<?php echo $model->nama_satuan; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'form'=>$form)); ?>
