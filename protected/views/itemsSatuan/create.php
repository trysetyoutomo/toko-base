<ol class="breadcrumb">
  <li class="breadcrumb-item">
  <a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a>
  </li>
  <li class="breadcrumb-item ">
  	<a href="<?php echo Yii::app()->createUrl('ItemsSatuan/admin'); ?>">Mengelola Satuan</a>
  </li>
  <li class="breadcrumb-item active">Satuan Baru</li>
</ol>


<h1>
<i class="fa fa-plus"></i>
Tambah Satuan</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>