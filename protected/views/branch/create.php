<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Beranda</a></li>
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Branch/admin') ?>">Tempat</a></li>
  <li class="breadcrumb-item active">Tempat baru</li>
</ol>
<h1>Tempat Baru</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>