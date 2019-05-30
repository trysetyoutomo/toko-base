
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a></li>
  <li class="breadcrumb-item active"><a href="<?php echo Yii::app()->createUrl("Items/view&id=$_REQUEST[id]"); ?>"><?php echo Items::model()->findByPk($_REQUEST['id'])->item_name; ?></a></li>
  <li class="breadcrumb-item active">Kelola Harga</li>
</ol>

<h1>Kelola Harga Pada Satuan</h1>
<h5>
	<?php 
	$itemSatuan = ItemsSatuan::model()->findByPk($_REQUEST['id']);
	$nama_satuan = $itemSatuan->nama_satuan;
	$id = $itemSatuan->item_id;
	// echo $id;
	$name = Items::model()->findByPk($id)->item_name;

	echo $name. " - ". $nama_satuan;
	?>
</h5>
<hr>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>