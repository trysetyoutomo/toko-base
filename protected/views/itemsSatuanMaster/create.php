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

<?php 
// $form=$this->beginWidget('CActiveForm', array(
// 	'action'=>Yii::app()->createUrl("itemsSatuanMaster/create"),
// 	'id'=>'items-satuan-master-form',
// 	'enableAjaxValidation'=>false,
//)); ?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php // $this->endWidget(); ?>