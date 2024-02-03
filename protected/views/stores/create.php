<?php
$this->breadcrumbs=array(
	'Stores'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Stores', 'url'=>array('index')),
	array('label'=>'Manage Stores', 'url'=>array('admin')),
);
?>
<h1>Store Baru <?php echo $model->id; ?></h1>


<?php //echo $this->renderPartial('_form', array('model'=>$model,"u"=>$u,'message'=>$message)); ?>
<?php echo $this->renderPartial('_form_admin', array('model'=>$model,"u"=>$u,'message'=>$message)); ?>