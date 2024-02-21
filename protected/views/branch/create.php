<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Beranda</a></li>
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Branch/admin') ?>">Cabang</a></li>
  <li class="breadcrumb-item active">Cabang baru</li>
</ol>
<h1>Cabang Baru</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>