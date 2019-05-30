<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a></li>
 
  <li class="breadcrumb-item active"> 
	<a href="<?php echo Yii::app()->createUrl("Items/view&id=$model->item_id"); ?>">
  	<?php echo Items::model()->findByPk($model->item_id)->item_name ?>
  	</a>
  		

  	</li>

  <li class="breadcrumb-item active"> <?php echo $model->nama_satuan ?></li>
  <li class="breadcrumb-item active">Lihat</li>
</ol>

<h1>Ubah Satuan <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>