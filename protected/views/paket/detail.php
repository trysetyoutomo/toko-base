
<?php
/* @var $this ItemsController */
/* @var $model Items */



?>
<style type="text/css">
	#items-grid{
		width: 100%;
	}
</style>
<script type="text/javascript">
	// $('#items-grid').yiiGridView({'ajaxUpdate':['items-grid'],'ajaxVar':'ajax','pagerClass':'pager','loadingClass':'grid-view-loading','filterClass':'filters','tableClass':'items','selectableRows':1,'pageVar':'page'});

</script>

<h1>Rincian Paket</h1>
<hr>
<div class="row">
	<div class="col-sm-8">
	</div>
	<div class="col-sm-4">
		<form action="">
		<input type="hidden" name="r" value="paket/admin">
		Cari Nama <input type="text" name="cari" value="<?php echo $_REQUEST['cari'] ?>">
		<input type="submit"  value="cari" class="btn btn-primary">
		</form>
	</div>
</div>
<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php //$this->renderPartial('_search',array('model'=>$model,)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'items-grid',
	// 'dataProvider'=>$model->search(),
	'dataProvider'=>$model,
	// 'filter'=>$model,
   // 'filter'=>$filtersForm,
	'columns'=>array(
		array(
			'header'=>'No.',
			'value'=>'($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) + array_search($data,$this->grid->dataProvider->getData())+1',
		),
		array(
			'name'=>'nama_paket',
			'header'=>'Nama Paket',
		),
		array(
			'name'=>'item_name',
			'header'=>'Nama Item',
		),
		array(
			'name'=>'item_qty',
			'header'=>'Jumlah',
		),
		array(
			'name'=>'item_price',
			'header'=>'Harga',
		),
		// array(
		// 	'name'=>'ukuran',
		// 	'header'=>'Ukuran',
		// ),
		// array(
		// 	'name'=>'panjang',
		// 	'header'=>'Panjang',
		// ),
		// // 'item_number',
		// // 'description',
		// // array(
		// // 	'name'=>'category_id',
		// // 	'header'=>'kategori',
		// // ),
		// array(
		// 	'name'=>'Kategori',
		// 	'header'=>' Kategori ',
		// 	'type'=>'raw',
		// 	'value'=>'Categories::model()->findByPk($data[category_id])->category',

		// ),
		// array(
		// 	'name'=>'Sub Kategori',
		// 	'header'=>'Sub Kategori ',
		// 	'type'=>'raw',
		// 	'value'=>'Motif::model()->findByPk($data[motif])->nama',

		// ),
		// array(
		// 	// 'name'=>'modal',
		// 	'value'=>'ItemsController::getAverage($data[id],"stok")',
		// 	'header'=>'Harga Modal',
		// 	'type'=>'number',
		// ),
		// array(
		// 	'name'=>'price_distributor',
		// 	'header'=>'Harga Distributor ',
		// 	'type'=>'number',
		// ),
		// array(
		// 	'name'=>'price_reseller',
		// 	'header'=>'Harga Reseller ',
		// 	'type'=>'number',
		// ),
		// array(
		// 	'name'=>'total_cost',
		// 	'header'=>'Harga Konsumen ',
		// 	'type'=>'number',
		// ),
		// // array(
		// // 	'name'=>'unit_price',
		// // 	'header'=>'Harga Jual (tanpa Pajak)',
		// // ),
		// array(
		// 	'name'=>'',
		// 	'header'=>'Total Stok',
		// 	// 'value'=>'ItemsController::getStok($data[id])',
		// 	'type'=>'number',
		// 	'value'=>'ItemsController::getStok($data[id])',
		// 	'class'=>'ext.gridcolumns.TotalColumn',
		// 	'footer'=>true,
		// ),
		// // array(
		// // 	'name'=>'stok_minimum',
		// // 	'header'=>'Stok Minimum',
		// // ),
		// // array(
		// // 	'name'=>'discount',
		// // 	'header'=>'Discount',
		// // ),
		// array(
		// 	'name'=>'barcode',
		// 	'header'=>'barcode',
		// ),
		// array(
		// 	'name'=>'modal',
		// 	'header'=>'Total Modal',
		// 	// 'value'=>'item',
		// 	'value'=>'round(ItemsController::getAverage($data[id])*ItemsController::getStok($data[id])) ',
		// 	// 'value'=>'$data[modal]*$data[stok]',
		// 	'type'=>'number',
		// 	'class'=>'ext.gridcolumns.TotalColumn',
		// 	'footer'=>true,
		// ),
		// array(
		// 'name'=>'category_id',
		// 'value'=>'$data->categories->category',
		// 'filter' => CHtml::listData(Categories::model()->findall(), 'id', 'category'),
		// ),
		// 'unit_price',
		// array(
		// 	'name'=>'lokasi',
		// 	'header'=>'Lokasi',
		// 	// 'value'=>'$data->lokasi ? 1 : "dapur" : "bar"',
		// 	'value'=>'$data[lokasi]==2 ? \'Dapur\':\'Bar\'',

			
		// 	// 'value'=>'$data->outlet->nama_outlet',
		// 	// 'filter' => CHtml::listData(Outlet::model()->findall(), 'nama_outlet', 'nama_outlet'),
		
		// ),
		// array(
		// 	'name'=>'lokasi',
		// 	'header'=>'Lokasi makanan',
			
		// 	'value'=>'$data[lokasi] == "1" ? "Bar" : "Dapur" ',
		// 	'filter' =>array('1'=>'Bar','2'=>'Dapur') ,
		
		// ),
		/*
		'tax_percent',
		'total_cost',
		'discount',
		'image',
		'status',
		*/
		// array(
		// 'type'=>'raw',
		// 'header'=>'hapus',
		// 'value'=>'CHtml::link("",array("Items/hapus","id"=>$data[id]),array("style"=>"text-decoration:none","class"=>"fa fa-times"))',
		
		// ),
			array(
			'class'=>'CButtonColumn',
			// 'visible'=>Yii::app()->user->getIdAdmin()==1,
			'template' => '{ubah}{rinci}{hapus}',
			'buttons' =>array(
			'view'=>array(
					'label'=> 'view',
					'url'=>'Yii::app()->createUrl("items/view", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			),
			'ubah'=>array(

				'label'=> ' ',
				'options'=>array(
					'class'=>'fa fa-pencil',
				),
					// 'label'=> 'Update',
					'url'=>'Yii::app()->createUrl("items/update", array("id"=>$data[id],"status"=>"ubah"))',      //A PHP expression for generating the URL of the button.

			),
			'rinci'=>array(

				'label'=> ' ',
				'options'=>array(
					'class'=>'fa fa-eye',
				),
					// 'label'=> 'Update',
					'url'=>'Yii::app()->createUrl("items/view", array("id"=>$data[id],"status"=>"ubah"))',      //A PHP expression for generating the URL of the button.

			),
			'hapus'=>array(
				'label'=> '',
				'options'=>array(
					'class'=>'fa fa-times',
				),
				'class'=>'fa fa-times',
				'url'=>'Yii::app()->createUrl("Items/hapus", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			),
			// 'view'=>array(
			// 	'label'=> '',
			// 	'url'=>'Yii::app()->createUrl("Items/view", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

			// ),

			

					
			
			),


		),
	),
)); ?>
