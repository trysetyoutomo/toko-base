<?php
$this->breadcrumbs=array(
	'Jenis Bebans'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List JenisBeban', 'url'=>array('index')),
	array('label'=>'Create JenisBeban', 'url'=>array('create')),
	array('label'=>'View JenisBeban', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage JenisBeban', 'url'=>array('admin')),
);
?>

<h1>Update JenisBeban <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>