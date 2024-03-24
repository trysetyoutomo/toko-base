<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Beranda</a></li>
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Branch/admin'); ?>">Mengelola Cabang</a></li>
  <li class="breadcrumb-item active"><?php echo $model->branch_name ?></a></li>
</ol>

<h1>Ubah Cabang #<?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>