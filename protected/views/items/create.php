<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a></li>
  <li class="breadcrumb-item active">Item Baru</li>
</ol>


<h1>
<!-- <a onclick="window.history.back()">
	<i class="fa fa-chevron-left"></i>
</a>
 -->
<i class="fa fa-plus"></i>
Item baru</h1>
<hr>
<?php echo $this->renderPartial('application.views.items._form', array('model'=>$model,'datasatuan'=>$datasatuan)); ?>
<?php echo $this->renderPartial('application.views.layouts.js'); ?>
<script type="text/javascript">
	    $(document).ready(function(){
	    	$("#Items_barcode").focus();
	    });
</script>