<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a></li>
  <li class="breadcrumb-item active"><a href="<?php echo Yii::app()->createUrl("Items/view&id=$_REQUEST[id]"); ?>"><?php echo Items::model()->findByPk($_REQUEST['id'])->item_name; ?></a></li>
  <li class="breadcrumb-item active">Kelola Satuan</li>
</ol>

<h1>
<!-- <a onclick="window.history.back()">
<i class="fa fa-chevron-left"></i>
</a>
 -->
Mengelola Satuan
<a style="text-decoration: underline;" href="<?php echo Yii::app()->controller->createUrl("items/view",array("id"=>$_REQUEST[id])) ?>">
<?php 
echo Items::model()->findByPk($_REQUEST['id'])->item_name;
?>
</a>
</h1>
<hr>
<div class="row">
	<div class="col-sm-8">
		<a href="<?php echo Yii::app()->controller->createUrl("create",array("id"=>$_REQUEST[id])) ?>">
		<button class="btn btn-primary">
			<i class="fa fa-plus"></i> Tambah
		</button>
		</a>

		<!-- <a href="<?php echo Yii::app()->createUrl("items/kosongkanstok") ?>">
		<button class="btn btn-primary">
			<i class="fa fa-trash"></i> Kosongkan Stok
	</button>
		</a> -->
	</div>
	<!-- <div class="col-sm-4">
		<form action="">
		<input type="hidden" name="r" value="items/admin">
		Cari Nama <input type="text" name="cari" value="<?php echo $_REQUEST['cari'] ?>">
		<input type="submit"  value="cari" class="btn btn-primary">
		</form>
	</div> -->
</div>

   <table id="datatable" class="table table-striped table-bordered">
       <thead>
  		<tr>
           <th>Aksi</th>
          <th>Barcode</th>
          <th>Nama</th>
          <th>Nilai</th>
          <th>Harga Jual</th>
          <th>Harga Modal</th>
          <!-- <th>Stok Minimum</th> -->
          <th>Utama</th>
        
       
  		</tr>
       
          <!-- <th>Aksi</th> -->
        </thead>
        <tbody>

        	<?php 
        	$no = 1;
        	foreach ($rawData as $key => $value) { ?>
        	<tr>
            <td>
             <a href="<?php echo Yii::app()->createUrl("ItemsSatuan/kartu", array("id"=>$_REQUEST[id],"satuan_id"=>$value[id])) ?>">
                <i class="fa fa-list"></i>
              </a>
              &nbsp;
           
              <a href="<?php echo Yii::app()->createUrl("ItemsSatuan/update", array("id"=>$value[id],"status"=>"ubah")) ?>">
                <i class="fa fa-pencil"></i>
              </a>
              &nbsp;
              <a href="<?php echo Yii::app()->createUrl("ItemsSatuan/delete", array("id"=>$value[id])) ?>">
                <i class="fa fa-times"></i>
              </a> 
              &nbsp;
              <a href="<?php echo Yii::app()->createUrl("ItemsSatuanPrice/create", array("id"=>$value[id])) ?>">
                <!-- <i class="fa fa-dollar-sign"></i> -->
                Rp
              </a>
              <!-- <a href=""></a> -->
            </td>
        		<td><?php echo $value['barcode']; ?></td>
        		<td><?php echo $value['nama_satuan'] ?></td>
             <td><?php echo $value['satuan'] ?></td>
            
            <td>
            <a href="<?php echo Yii::app()->createUrl("ItemsSatuanPrice/create", array("id"=>$value[id])) ?>">
              <?php echo number_format($value['harga']) ?>
            </a>
                

              </td>

            <td><?php echo number_format($value['harga_beli']) ?></td>
            <!-- <td><?php echo $value['stok_minimum'] ?></td> -->
        		<td><?php 
            if ($value['is_default']=="1"){
              echo "Ya";
            }else{
              echo "Tidak";
            }
             ?></td>
        	
        	
        	</tr>
        	<?php } ?>
        </tbody>
    </table>

<?php 
// exit();
?>
 <?php 
//$this->widget('zii.widgets.grid.CGridView', array(
// 	'id'=>'items-grid',
// 	// 'dataProvider'=>$model->search(),
// 	'dataProvider'=>$model,
// 	// 'filter'=>$model,
//    // 'filter'=>$filtersForm,
// 	'columns'=>array(
// 		array(
// 			'header'=>'No.',
// 			'value'=>'($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) + array_search($data,$this->grid->dataProvider->getData())+1',
// 		),
// 		array(
// 			'name'=>'item_name',
// 			'header'=>'nama Item',
// 		),
// 		array(
// 			'name'=>'ketebalan',
// 			'header'=>'Ketebalan',
// 		),
// 		array(
// 			'name'=>'ukuran',
// 			'header'=>'Ukuran',
// 		),
// 		array(
// 			'name'=>'panjang',
// 			'header'=>'Panjang',
// 		),
// 		// 'item_number',
// 		// 'description',
// 		// array(
// 		// 	'name'=>'category_id',
// 		// 	'header'=>'kategori',
// 		// ),
// 		array(
// 			'name'=>'Kategori',
// 			'header'=>' Kategori ',
// 			'type'=>'raw',
// 			'value'=>'Categories::model()->findByPk($data[category_id])->category',

// 		),
// 		array(
// 			'name'=>'Sub Kategori',
// 			'header'=>'Sub Kategori ',
// 			'type'=>'raw',
// 			'value'=>'Motif::model()->findByPk($data[motif])->nama',

// 		),
// 		array(
// 			// 'name'=>'modal',
// 			'value'=>'ItemsController::getAverage($data[id],"stok")',
// 			'header'=>'Harga Modal',
// 			'type'=>'number',
// 		),
// 		array(
// 			'name'=>'price_distributor',
// 			'header'=>'Harga Distributor ',
// 			'type'=>'number',
// 		),
// 		array(
// 			'name'=>'price_reseller',
// 			'header'=>'Harga Reseller ',
// 			'type'=>'number',
// 		),
// 		array(
// 			'name'=>'total_cost',
// 			'header'=>'Harga Konsumen ',
// 			'type'=>'number',
// 		),
// 		// array(
// 		// 	'name'=>'unit_price',
// 		// 	'header'=>'Harga Jual (tanpa Pajak)',
// 		// ),
// 		array(
// 			'name'=>'',
// 			'header'=>'Total Stok',
// 			// 'value'=>'ItemsController::getStok($data[id])',
// 			'type'=>'number',
// 			'value'=>'ItemsController::getStok($data[id])',
// 			'class'=>'ext.gridcolumns.TotalColumn',
// 			'footer'=>true,
// 		),
// 		// array(
// 		// 	'name'=>'stok_minimum',
// 		// 	'header'=>'Stok Minimum',
// 		// ),
// 		// array(
// 		// 	'name'=>'discount',
// 		// 	'header'=>'Discount',
// 		// ),
// 		array(
// 			'name'=>'barcode',
// 			'header'=>'barcode',
// 		),
// 		array(
// 			'name'=>'modal',
// 			'header'=>'Total Modal',
// 			// 'value'=>'item',
// 			'value'=>'round(ItemsController::getAverage($data[id])*ItemsController::getStok($data[id])) ',
// 			// 'value'=>'$data[modal]*$data[stok]',
// 			'type'=>'number',
// 			'class'=>'ext.gridcolumns.TotalColumn',
// 			'footer'=>true,
// 		),
// 		// array(
// 		// 'name'=>'category_id',
// 		// 'value'=>'$data->categories->category',
// 		// 'filter' => CHtml::listData(Categories::model()->findall(), 'id', 'category'),
// 		// ),
// 		// 'unit_price',
// 		// array(
// 		// 	'name'=>'lokasi',
// 		// 	'header'=>'Lokasi',
// 		// 	// 'value'=>'$data->lokasi ? 1 : "dapur" : "bar"',
// 		// 	'value'=>'$data[lokasi]==2 ? \'Dapur\':\'Bar\'',

			
// 		// 	// 'value'=>'$data->outlet->nama_outlet',
// 		// 	// 'filter' => CHtml::listData(Outlet::model()->findall(), 'nama_outlet', 'nama_outlet'),
		
// 		// ),
// 		// array(
// 		// 	'name'=>'lokasi',
// 		// 	'header'=>'Lokasi makanan',
			
// 		// 	'value'=>'$data[lokasi] == "1" ? "Bar" : "Dapur" ',
// 		// 	'filter' =>array('1'=>'Bar','2'=>'Dapur') ,
		
// 		// ),
// 		/*
// 		'tax_percent',
// 		'total_cost',
// 		'discount',
// 		'image',
// 		'status',
// 		*/
// 		// array(
// 		// 'type'=>'raw',
// 		// 'header'=>'hapus',
// 		// 'value'=>'CHtml::link("",array("Items/hapus","id"=>$data[id]),array("style"=>"text-decoration:none","class"=>"fa fa-times"))',
		
// 		// ),
// 			array(
// 			'class'=>'CButtonColumn',
// 			// 'visible'=>Yii::app()->user->getIdAdmin()==1,
// 			'template' => '{ubah}{rinci}{hapus}',
// 			'buttons' =>array(
// 			'view'=>array(
// 					'label'=> 'view',
// 					'url'=>'Yii::app()->createUrl("items/view", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

// 			),
// 			'ubah'=>array(

// 				'label'=> ' ',
// 				'options'=>array(
// 					'class'=>'fa fa-pencil',
// 				),
// 					// 'label'=> 'Update',
// 					'url'=>'Yii::app()->createUrl("items/update", array("id"=>$data[id],"status"=>"ubah"))',      //A PHP expression for generating the URL of the button.

// 			),
// 			'rinci'=>array(

// 				'label'=> ' ',
// 				'options'=>array(
// 					'class'=>'fa fa-eye',
// 				),
// 					// 'label'=> 'Update',
// 					'url'=>'Yii::app()->createUrl("items/view", array("id"=>$data[id],"status"=>"ubah"))',      //A PHP expression for generating the URL of the button.

// 			),
// 			'hapus'=>array(
// 				'label'=> '',
// 				'options'=>array(
// 					'class'=>'fa fa-times',
// 				),
// 				'class'=>'fa fa-times',
// 				'url'=>'Yii::app()->createUrl("Items/hapus", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

// 			),
// 			// 'view'=>array(
// 			// 	'label'=> '',
// 			// 	'url'=>'Yii::app()->createUrl("Items/view", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.

// 			// ),

			

					
			
// 			),


// 		),
// 	),
// )); ?>

<!-- Datatables -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<!--
-->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/jszip/dist/jszip.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/pdfmake/build/vfs_fonts.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#datatable').dataTable({
			"pageLength": 100,
			 "autoWidth": true
		});
	});
</script>