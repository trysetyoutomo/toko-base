<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a></li>
  <li class="breadcrumb-item "> 
  	<a href="<?php echo Yii::app()->createUrl("Items/view&id=$model->id"); ?>">
  	<?php echo $model->item_name ?>
  	</a>
  		
  	</li>
  <li class="breadcrumb-item active">Update</li>
</ol>

<h1>
<i class="fa fa-edit"></i>
Ubah Items #<?php echo $model->id; ?></h1>
<hr>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<div class="row">
	<div class="col-sm-12">
			<?php 
//$this->renderPartial("kartupersediaan");
?>
	</div>
</div>
