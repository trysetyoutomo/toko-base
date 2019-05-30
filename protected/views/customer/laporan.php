<div id="hasil"></div>
<style type="text/css">
	.dalem{
		width:100%;
	}
</style>
<!--
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/app/SalesItems/main.js"></script>
-->
<?php 
$this->renderPartial('application.views.site.main');
?>
<script type="text/javascript">
    function print() {
        document.jzebra.append("A37,503,0,1,2,3,N,PRINTED USING JZEBRA\n");
		// document.jzebra.appendPDF(window.location.href + "/../sample.pdf");
		// alert(window.location.href + "/../sample.pdf")
		// document.jzebra.printPS();

        // ZPLII
        // document.jzebra.append("^XA^FO50,50^ADN,36,20^FDPRINTED USING JZEBRA^FS^XZ");  
        document.jzebra.print();
    }
	function nilai(data){
		alert("nilai : " + data);
		return false;
	}
	
	
	
	function cetakRekap(){
		var tanggal = $('#Sales_date').val();
		
		if(tanggal==''){
			alert('Pilih tanggal terlebih dahulu');
			return false;
		}
		
		// alert(tanggal);
		// $.ajax({
			// alert('asdasd');
			// url:<?=$this->createUrl('sales/cetakrekap')?>,
			// data:'tanggal_rekap='+tanggal,
			// success: function(data){
				// alert(data);
			// },
			// error: function(data){
				// alert('error');
			// }
		// });
	}
</script>
<?php
/* @var $this SalesController */
/* @var $dataProvider CActiveDataProvider */

// $this->breadcrumbs=array(
	// 'Sales',
// );

// $this->menu=array(
	// array('label'=>'Create Sales', 'url'=>array('create')),
	// array('label'=>'Manage Sales', 'url'=>array('admin')),
// );
?>
<fieldset>
	<legend>
		
<h1>Laporan </h1>
<br>
	</legend>

<?php
 // $this->widget('zii.widgets.CListView', array(
	// 'dataProvider'=>$dataProvider,
	// 'itemView'=>'_view',
// )); 
?>
<?php 
if (isset($_REQUEST['tgl'])){
	$tgl = $_REQUEST['tgl'];
	$tgl2 = $_REQUEST['tgl2'];
	$filter = " where date(tanggal) >= '$tgl' and date(tanggal)<='$tgl2' ";

}else{
	$tgl = date('Y-m-d');
	$tgl2 = date('Y-m-d');
	$filter = "";

}
?>
<?php $form=$this->beginWidget('CActiveForm',array(
	'action'=>Yii::app()->createUrl('items/laporan_pengeluaran'),
	'method'=>'get',
)); ?>
<!-- <div class="row"> -->

tanggal
<?php
	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'tgl',
		'attribute'=>'date',
//		'model'=>$model,
		// additional javascript options for the date picker plugin
		'options'=>array(
			'dateFormat'=>'yy-mm-dd',
			'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
			'showOn'=>'button', // 'focus', 'button', 'both'
			'buttonText'=>Yii::t('ui','Select form calendar'),
			'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
			'buttonImageOnly'=>true,
		),
		'value'=>$tgl,
		'htmlOptions'=>array(
			// 'style'=>'height:20px;'
			'style'=>'height:20px;;width:80px;display:inline-block'
		),
	));
	
// $this->renderPartial('summary',array('summary'=>$summary));

?>
sampai Tanggal
<?php
	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'tgl2',
		'attribute'=>'date',
//		'model'=>$model,
		// additional javascript options for the date picker plugin
		'options'=>array(
			'dateFormat'=>'yy-mm-dd',
			'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
			'showOn'=>'button', // 'focus', 'button', 'both'
			'buttonText'=>Yii::t('ui','Select form calendar'),
			'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
			'buttonImageOnly'=>true,
		),
		'value'=>$tgl2,
		'htmlOptions'=>array(
			// 'style'=>'height:20px;'
			'style'=>'height:20px;;width:80px;display:inline-block'
		),
	));
	
// $this->renderPartial('summary',array('summary'=>$summary));

?>
	<!-- </div> -->
			<?php echo CHtml::submitButton('Cari'); ?>
			<?php //echo CHtml::button('Export to CSV',array('id'=>'export')); ?>
<?php $this->endWidget(); ?>

<?php

	
	$username = Yii::app()->user->name;
	$user = Users::model()->find('username=:un',array(':un'=>$username));
	$idk = $user->level; 
	$a = true;
	if($idk < 5)
	$a = true;
	else
	$a = false;
?>

<?php


$sql  = "select * from pengeluaran $filter order by tanggal desc";
// echo "$sql";
$model = Yii::app()->db->createCommand($sql)->queryAll();

?>
<style type="text/css">
	.table tr td{
		border: 1px solid rgb(163, 0, 0,1);
	}
</style>
<br>
<table class="table">
	<thead>
		
		<tr style="background:rgba(163, 0, 0,1) ;color:white">
			<td>No</td>
			<td>ID Keluar</td>
			<td>Tanggal</td>
			<td>Petugas</td>
			<td>Jenis Pengeluaran </td>
			<td>Keterangan</td>
			<td>Total</td>
			<td>Aksi</td>
			<!-- <td>Aksi</td> -->
		</tr>

	</thead>
	<tbody>
		<?php 
		$no=1;
		foreach ($model as $m): ?>
		<tr>
			<td style="width:5%"><?php echo $no;?></td>
			<td style="width:50%"><?php echo $m[id]; ?></td>
			<td style="width:50%"><?php echo $m[tanggal]; ?></td>
			<td style="width:50%"><?php echo $m[user]; ?></td>
			<td style="width:30%"><?php echo $m[jenis_pengeluaran]; ?></td>
			<td style="width:30%"><?php echo $m[keterangan]; ?></td>
			<td style="width:30%" align="right"><?php echo number_format($m[total]); ?></td>
			<td>
			<a href="<?php echo Yii::app()->controller->createUrl('pengeluaranhapus',array("id"=>$m[id])) ?>" class="hapus">
			<img style="width:15px;" src="img/delete.ico">
			</a></td>
			<!-- <tr>
				<td colspan="7">
					<table style="font-size:12px;width:500px;">

						<tr>
							<td>No</td>
							<td>Nama Item</td>
							<td>Qty</td>
							
						</tr>
						<?php 
						$sql = "SELECT 
						s.id, i.item_name, si.jumlah qty
						FROM
						barangkeluar s, barangkeluar_detail si, items i
						WHERE 
						si.head_id = s.id 
						AND
						i.id = si.kode

						AND s.id = '$m[id]'";
						$no2=1;
						foreach (Yii::app()->db->createCommand($sql)->queryAll() as $q ) {
						?>
						<tr>
							<td style="width:5%"><?php echo $no2; ?></td>
							<td style="width:20%"><?php echo $q[item_name]  ?></td>
							<td style="width:10%"><?php echo $q[qty]  ?></td>
							
						</tr>
						<?php 
						$no2++;
						} ?>
					</table>
				
				</td>
			</tr> -->
		</tr>
	<?php 
	$total = $total +$m[total];
	$no++;
	endforeach; ?>
	<tr>
		<td colspan="8" style="text-align:right">
			<?php 
			echo number_format("$total");
			?>
		</td>
	</tr>
	</tbody>
</table>
<?php 

//  $this->widget('zii.widgets.grid.CGridView', array(
// 	'id'=>'sales-grid',
// 	'dataProvider'=>$dataProvider,
// 	// 'filter'=>$model->search(),
// 	'columns'=>array(
// 		array(
// 			'header'=>'No.',
// 			'value'=>'($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) + array_search($data,$this->grid->dataProvider->getData())+1',
// 		),
// 		array(
// 		'name'=>'id',
// 		'header'=>'ID'
// 		),
// 		array(
// 		'name'=>'date',
// 		'header'=>'Tanggal'
// 		),
// 		// 'date',
// 		array(
// 		'name'=>'total_items',
// 		'header'=>'Total Menu',
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 		),	
// // 		array(
// // 			'name'=>'untung',
// // 			'header'=>'total Keuntungan',
// // 			'type'=>'number',
// // 			// 'htmlOptions'=>array('style'=>'text-align:right'),
// // 			'class'=>'ext.gridcolumns.TotalColumn',
// // //			'value'=>'$data->nilai',
// // 			'visible'=>$a,
// // 			'type'=>'number',
// // 			'footer'=>true,
// // 			'htmlOptions'=>array('style'=>'text-align:right'),

// // 		),	
// 		array(
// 			'name'=>'sale_sub_modal',
// 			'header'=>'total modal',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'visible'=>$a,
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 		array(
// 			'name'=>'sale_sub_total',
// 			'header'=>'total bersih',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 		array(
// 			'name'=>'sale_tax',
// 			'header'=>'total pajak',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// //			'value'=>'$data->nilai',
// 			'class'=>'ext.gridcolumns.TotalColumn',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 		array(
// 			'name'=>'sale_service',
// 			'header'=>'total service',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 		array(
// 			'name'=>'sale_discount',
// 			'header'=>'total diskon',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),
		
// 		array(
// 			'name'=>'sale_total_cost',
// 			'header'=>'total kotor',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),
// 		array(
// 			'name'=>'inserter',
// 			'header'=>'Kasir',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// 			// 'value'=>'$data->user->username',
// 			'type'=>'text',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),
// 		// array(
// 		// 	'name'=>'table',
// 		// 	'header'=>'Meja',
// 		// ),
// 		// array(
// 		// 	'name'=>'waiter',
// 		// 	'header'=>'waiter',
// 		// )
// 		// 'table'
// 		// ,
// 		array(
// 		'name'=>'bayar',

// 		'header'=>'bayar',
// 		'type'=>'number',		
// 		)
// 		,
// 		//'comment',
// 		// 'status',
// 		array(
// 		'type'=>'raw',
// 		'header'=>'Rincian Menu',
// 		'value'=>'CHtml::link("Detail",array("sales/detailitems","id"=>$data[id]),array("style"=>"text-decoration:none"))',
		
// 		),
// 		// array(
// 		// 	"name"=>"comment",
// 		// 	"header"=>"Description",
// 		// ),
// 		array
// 		(
// 			'name'=>'print',
// 			'header'=>'Cetak',
// 			'type'=>'raw',
// 			// 'value'=>'CHtml::ajaxButton("Cetak", array("sales/CetakReport","id"=>$data->id),array())',
// 			'value'=>'CHtml::ajaxButton("Cetak", array("sales/CetakReport"),array(
// 																					"data"=>array("id"=>$data[id]),
// 																					"success"=>"function(data){
// 																						// alert(\'cek\');
// 																						var sales = jQuery.parseJSON(data);
// 																						if (sales.sale_id!=\'\')
// 																						{
// 																							print_bayar(sales);
// 																						}
// 																					}",
// 																					"error"=>"function(data){alert(\'data\')}"
// 																				))',
// 		),	
// 		array(
// 		'class'=>'CButtonColumn',
// 		'template'=>'{delete}',
// 		'visible'=>$a,
// 		'buttons' => array(
			
// 				'delete' => array(
// 							'url'=>'Yii::app()->createUrl("Sales/delete", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
				
// 				),
// 			),
		
// 		), 		
// 	),
//)); ?>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'dialog_export',
    // additional javascript options for the dialog plugin
    'options' => array(
        'title' => 'Meja',
        'autoOpen' => false,
        'modal' => true,
        'width' => 250,
        'height' => 80,
    ),
));

echo "data sales berhasil di export";
//echo "ramdnai";

$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script>
$(document).ready(function(){
	$('.hapus').click(function(e){
		// e.preventDefault();
		if (!confirm("Yakin  ?")){
			return false
		}else{
			return true;
		}
	});

	$('.hapus-detail').click(function(e){
		// e.preventDefault();
		if (!confirm("Menghapus  , akan mempengaruhi stok, Yakin hapus  ?")){
		// if (!confirm("Yakin hapus ?")){
			return false
		}else{
			return true;
		}
	});
	


});
</script>
<div id="hasil">
</div>


</fieldset>
