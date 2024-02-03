<?php
$this->breadcrumbs=array(
	'Stores'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Stores', 'url'=>array('index')),
	array('label'=>'Create Stores', 'url'=>array('create')),
	array('label'=>'View Stores', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Stores', 'url'=>array('admin')),
);
?>

<h1>Update Stores <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form_admin', array('model'=>$model,"u"=>$u,'message'=>$message)); ?>