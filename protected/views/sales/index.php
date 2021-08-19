<?php 
?>
<style type="text/css">
	@media print{
		#data-cetak table tr td,#data-cetak table tr th{
			border:1px solid black;
		}
	}
	select{
		padding:7px;
	}
	#sales-filter tr td{
		padding: 5px;
	}	
</style>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>



<?php 
$this->renderPartial('application.views.site.main');
?>
<div id="hasil"></div>
<h1>
<i class="fa fa-book"></i> Laporan Penjualan Harian </h1>
<hr>

<?php
$data = array(
		1=>'Januari',
		2=>'Februari',
		3=>'Maret',
		4=>'April',
		5=>'Mei',
		6=>'Juni',
		7=>'July',
		8=>'Agustus',
		9=>'September',
		10=>'Oktober',
		11=>'November',
		12=>'Desember');
	
$curr_year = Date('Y');
for($x=$curr_year-5; $x<$curr_year+5;$x++){
	$arr_year[$x] = $x;
}


$day = array();
for($x=1; $x<=31;$x++){
	$day[$x] = $x;
}

echo CHtml::beginForm();
?>
<table cellpadding="20" id="sales-filter">
	<tr>
		<td>
			<label>Transaksi</label>
			
		</td>
		<td>
			
<?php 
echo CHtml::dropDownList('day', $day2, $day);
echo CHtml::dropDownList('month', $month, $data);
echo CHtml::dropDownList('year', $year, $arr_year);
//echo CHtml::button('Cari', array('submit' => array('sales/Salesmonthly'),'class'=>'btn btn-primary' ) );
?>

		</td>
	</tr>
	<tr >
		<td>
<?php  $nilai = Branch::model()->findAll("store_id = '".Yii::app()->user->store_id()."' ");?>
<label>
	Tempat
</label>
			
		</td>
		<td>
			<?php 

			?>
<select id="cabang" name="cabang" class="form-control" style="display: inline;">

<?php foreach($nilai as $k): ?>
	<option <?php if ($k->id==Yii::app()->user->store_id()) echo "selected" ?> value="<?php echo $k->id ?>">
	<?php echo $k->branch_name ?>
		
	</option>
<?php endforeach; ?>
	
</select>
		</td>

	</tr>
	<tr>
		<td>
			<label>
	Status Bayar
</label>


		</td>
		<td>
			<select name="status" class="form-control" style="display: inline;" >
	<!-- <optgroup>Pilih Status</optgroup> -->
	<option <?php if($_REQUEST[status]=='semua') echo "selected" ?> value="semua">SEMUA</option>
	<option <?php if($_REQUEST[status]=='Kredit') echo "selected" ?> value="Kredit">BELUM BAYAR / KURANG BAYAR</option>
	<option <?php if($_REQUEST[status]=='Lunas') echo "selected" ?> value="Lunas">SUDAH BAYAR</option>
</select>
		</td>
	</tr>
</table>
<hr>
<?php echo CHtml::submitButton('Cari',array('class'=>'btn btn-primary')); ?>
<input type="button" name="Cetak" value="Cetak" class="btn btn-primary"  onclick="$('#data-cetak').print()" />

<!-- <a href="<?php echo Yii::app()->createUrl('sales/cetakrekap&noprint=true') ?>" type="button" class="btn btn-primary"  name="btn-preview">
Preview Rekap
</a> -->
<?php //echo CHtml::button('Cetak Rekap',array('id'=>'cetakrekap','class'=>'btn btn-primary')); ?>
<?php //echo CHtml::button('Export to CSV',array('id'=>'export')); ?>
<?php //$this->endWidget(); ?>

<?php
	function getCustomer($data)
	{
		if($data == 1){
			return "Pelanggan";
		}
	}
	
	function getBarista($data)
	{
		if($data == 1){
			return "Pasir Kaliki";
		}else if($data == 2){
			return "Baltos";
		}else if($data == 3){
			return "City Link";
		}else if($data == 4){
			return "BTC";
		}
		// $cabang = Branch::model()->find('branch_name=:bn',array(':bn'=>$data));
		// return $cabang->id;
	}
	
	function getPaid($data)
	{
		if($data == 1){
			return "Cash";
		}else if($data == 3){
			return "BCA";
		}else if($data == 4){
			return "Mandiri";
		}else if($data == 5){
			return "CIMB Niaga";
		}else if($data == 12){
			return "Compl";
		}else if($data == 99){
			return "Voucher";
		}

		
	}
	
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
$usaha = SiteController::getConfig("jenis_usaha");
$jenis_printer = SiteController::getConfig("ukuran_kertas");

// echo $jenis_printer;
// exit;
// if ($usaha=="Toko"){
$array_cetak = array();
if ($jenis_printer=="80mm" || $jenis_printer=="58mm"){
	$array_cetak = array(
		'type'=>'raw',
		'name'=>'aa',
		'header'=>'Cetak',
		'value'=>function($data){

			// "abv"
			// return $data['id'];
			return '<div value="'.$data['id'].'" class="btn btn-primary btn-cetak-ulang ">Cetak</div>';
		}
		
	);
}else{ // jika mini
	$array_cetak = 	array(
			'name'=>'print',
			'header'=>'Cetak',
			'type'=>'raw',
			'value'=>function($data){

		return '<a href="'.Yii::app()->createUrl("sales/cetakfaktur&id=$data[id]").'" class="btn btn-primary">Cetak</a>';
		}
			// 'value'=>'"<a href='' class="btn btn-primary">Cetak</a>"'
			// 'value'=>'CHtml::ajaxButton("Cetak", array("sales/CetakReport","id"=>$data->id),array())',
			// 'value'=>'"CHtml::ajaxButton("Cetak ", array("sales/CetakReport"),array(
	// 		"data"=>array("id"=>$data[id]),
	// 		"success"=>"function(data){
	// 			// alert(\'cek\');
	// 			var sales = jQuery.parseJSON(data);
	// 			if (sales.sale_id!=\'\')
	// 			{
	// 				print_bayar(sales);
	// 			}
	// 		}",
	// 		"error"=>"function(data){alert(\'data\')}"
	// 	),array("class"=>"btn btn-primary")
	// )"',
	 );
}
// var_dump($array_cetak);
// echo "<pre>";
// print_r($array_cetak);
// echo "</pre>";
// echo $array_cetak;
$columnaja = array(
		array(
			'header'=>'No.',
			'value'=>'($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) + array_search($data,$this->grid->dataProvider->getData())+1',
		),
		array(
		'name'=>'faktur_id',
		'header'=>'ID Penjualan'
		),
		array(
		'name'=>'date',
		'header'=>'Tgl Transaksi'
		),
		array(
		'name'=>'tanggal_jt',
		'header'=>'Tgl jatuh Tempo'
		),
		array(
		'name'=>'nama',
		'header'=>'Nama pembeli'
		),
		// 'date',
// 		array(
// 		'name'=>'Total_items',
// 		'header'=>'Total Item',
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 		),	
	
		array(
			'name'=>'sale_sub_modal',
			'header'=>'Total modal',
			'visible'=>Yii::app()->user->getLevel()==2,

			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			// 'visible'=>$a,
			'type'=>'number',
			'footer'=>true,
			'htmlOptions'=>array('style'=>'text-align:right'),

		),	
		array(
			'name'=>'sale_sub_total',
			'header'=>'Total Kotor',
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			'type'=>'number',
			'footer'=>true,
			'visible'=>Yii::app()->user->getLevel()==2,

			'htmlOptions'=>array('style'=>'text-align:right'),

		),	
// 			array(
// 			'name'=>'untung',
// 			'header'=>'Total Keuntungan',
// 			'type'=>'number',
// 			// 'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'visible'=>$a,
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 		array(
// 			'name'=>'sale_tax',
// 			'header'=>'Total pajak',
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
// 			'header'=>'Total service',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
		array(
			'name'=>'sale_discount',
			'header'=>'Total diskon',
			'type'=>'number',
			'visible'=>Yii::app()->user->getLevel()==2,
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			'type'=>'number',
			'footer'=>true,
			'htmlOptions'=>array('style'=>'text-align:right'),
		),
		array(
			'name'=>'voucher',
			'header'=>'Total Potongan',
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			'type'=>'number',
			'footer'=>true,
					'visible'=>Yii::app()->user->getLevel()==2,

			'htmlOptions'=>array('style'=>'text-align:right'),

		),

		
		array(
			'name'=>'sale_total_cost',
			'header'=>'Total Bersih',
			'type'=>'number',
			'visible'=>Yii::app()->user->getLevel()==2,

			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			'type'=>'number',
			'footer'=>true,
			'htmlOptions'=>array('style'=>'text-align:right'),

		),
		array(
			'name'=>'inserter',
			'header'=>'Kasir',
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
			// 'value'=>'$data->user->username',
			'type'=>'text',
			'footer'=>true,
			'htmlOptions'=>array('style'=>'text-align:right'),

		),
		// array(
		// 	'name'=>'table',
		// 	'header'=>'Meja',
		// ),
		// array(
		// 	'name'=>'waiter',
		// 	'header'=>'waiter',
		// )
		// 'table'
		// ,
		array(
			'name'=>'bayar',
			'class'=>'ext.gridcolumns.TotalColumn',
			'header'=>'bayar',
			'type'=>'number',		
			'footer'=>true,

		),
		array(
			'name'=>'pembayaran_via',
			'class'=>'ext.gridcolumns.TotalColumn',
			'header'=>'Kartu',
			// 'type'=>'number',		
			'footer'=>true,

		),
		// array(
		// 	'name'=>'sb',
		// 	'type'=>'raw',
		// 	'header'=>'status bayar',
		// 	// 'visible'=>false,
		// 	// 'value'=> "$data[bayar]==0 ? 'Belum Lunas' : 'Lunas'",		
		// ),
		//'comment',
		// 'status',
		array(
		'type'=>'raw',
		'header'=>'Rincian',
		'value'=>'CHtml::link("Detail",array("Sales/detailitems","id"=>$data[id]),array("style"=>"text-decoration:none"))',
		
		),
		// array(
		// 'type'=>'raw',
		// 'header'=>'Cetak',
		// 'value'=>'CHtml::link("Cetak",array("Sales/cetakfaktur","id"=>$data[id]),array("class"=>"btn btn-primary ","style"=>"text-decoration:none"))',
		
		// ),
		// array(
		// 	"name"=>"comment",
		// 	"header"=>"Description",
		// ),
		array(
		'type'=>'raw',
		'header'=>'hapus',
		'visible'=>Yii::app()->user->getLevel()==2,
		'value'=>'CHtml::link("",array("Sales/hapus","id"=>$data[id]),array("style"=>"text-decoration:none","class"=>"fa fa-times hapus "))',
		
		),
		// 	array
		// (
		// 	'name'=>'print',
		// 	'header'=>'Cetak Faktur',
		// 	'type'=>'raw',
		// 	'value'=>"CHtml::link('Cetak Faktur', array('sales/cetakfaktur&id=$data[id]'), array('class' => 'btn btn-primary'));"

		// 	// 'value'=>"<a href='123'>123</a>",

	 // ),	
		// array(
		// 'class'=>'CButtonColumn',
		// 'template'=>'',
		// // 'visible'=>$a,
		// 'buttons' => array(
		// // 'delete' => array(
		// // 	'url'=>'Yii::app()->createUrl("Sales/delete", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
		// // ),
		// 		'bayar' => array(
		// 			'url'=>'Yii::app()->createUrl("Sales/bayarhutang", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
		// 			'imageUrl'=>Yii::app()->request->baseUrl."/img/pay.png",
		// 			'class'=>'bayarhutang',
		// 			'options'=>array(
		// 				'class'=>'bayarhutang'
		// 			),
		// 			'visible'=>'$data[sb]=="Kredit"',
		// 		),
		// 	),
		
		// ), 		
	);
	array_push($columnaja, $array_cetak);
	?>
<div id="data-cetak">
	
<?php
 $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sales-grid',
	'dataProvider'=>$dataProvider,
	// 'filter'=>$model->search(),
	'columns'=>$columnaja
)); ?>
</div>
<?php
// $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
//     'id' => 'dialog_export',
//     // additional javascript options for the dialog plugin
//     'options' => array(
//         'title' => 'Meja',
//         'autoOpen' => false,
//         'modal' => true,
//         'width' => 250,
//         'height' => 80,
//     ),
// ));

// echo "data sales berhasil di export";
// //echo "ramdnai";

// $this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script>
$(document).ready(function(){
	// $(".fa-times").click(function(e){
	// 	// e.preventDefault();
	// 	if (!confirm("Yakin ? ")){
	// 		return false;
	// 	}

	// });
	 $('.tanggal').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true

    });

	$('.bayarhutang').click(function(e){
		if (!confirm("yakin  ? ")){
			return false;
		}

		// e.preventDefault();
		
		// alert('123');
		// var tanggal = $('#Sales_date').val();
		// $.ajax({
		// 	url:'<? //$this->createUrl('sales/export')?>',
		// 	data:'tanggal='+tanggal,
		// 	success: function(data){
		// 		$("#dialog_export").dialog("open");
		// 		$("#hasil").html(data);
		// 		// alert(data);
		// 	},
		// 	error: function(data){
		// 		$("#hasil").html(data);
		// 		// alert(data);
		// 		// alert('data gagal di export');
		// 	}
		// });
	});
	

	$('.btn-cetak-ulang').click(function(e){
		e.preventDefault();
		var value = $(this).attr("value");
		// alert(va)
		$.ajax({
			url:'<?=$this->createUrl('sales/CetakReport')?>',
			data:'id='+value,
			success: function(data){
				var json = jQuery.parseJSON(data);
				print_bayar(json);
				// alert(JSON.stringify(json));
				// $('#hasiljson').html(data);
				// print_rekap(json);
				// console.log(data);
				
			},
			error: function(data){
				alert('error');
			}
		});
	});
	$('#cetakrekap').click(function(){
		var tanggal = $('#Sales_date').val();
		var tanggal2 = $('#Sales_date2').val();
		
		if(tanggal==''){
			alert('Pilih tanggal terlebih dahulu');
			return false;
		}else{
			$.ajax({
				url:'<?=$this->createUrl('sales/cetakrekap')?>',
				data:'tanggal_rekap='+tanggal+'&tanggal_rekap2='+tanggal2,
				success: function(data){
					var json = jQuery.parseJSON(data);
					// alert(JSON.stringify(json));
					// $('#hasiljson').html(data);
					print_rekap(json);
					// console.log(data);
					
				},
				error: function(data){
					alert('error');
				}
			});
		}
	});
});
</script>
<div id="hasil">
</div>
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="jZebra/jzebra/jzebra.jar" width="0" height="0">
    <param name="printer" value="zebra">
</applet>


<!-- Modal -->
<div class="modal fade" id="modal-bukti-bayar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="min-width:700px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Bukti Pembayaran</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body body-bukti">

      </div>
      <div style="clear:both"></div>
      <div class="modal-footer d-none">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button> -->
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>