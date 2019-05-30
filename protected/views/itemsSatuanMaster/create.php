<?php
$this->breadcrumbs=array(
	'Items Satuan Masters'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ItemsSatuanMaster', 'url'=>array('index')),
	array('label'=>'Manage ItemsSatuanMaster', 'url'=>array('admin')),
);
?>

<h1>Satuan Baru</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>