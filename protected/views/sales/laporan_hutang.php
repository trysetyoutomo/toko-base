
<style type="text/css">
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
<i class="fa fa-book"></i> Laporan Piutang </h1>

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

echo CHtml::beginForm('', 'get');
?>
<hr>
<table cellpadding="20" id="sales-filter">
	<tr style="display: none;">
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
	<tr>
		<td>
<?php  $nilai = Branch::model()->findAll("store_id = '".Yii::app()->user->store_id()."' ");?>
<label >
	Tempat
</label>
			
		</td>
		<td>
<select id="cabang" name="cabang" class="form-control" style="display: inline;">

<?php foreach($nilai as $k): ?>
	<option <?php if ($k->id==Yii::app()->user->store_id()) echo "selected" ?> value="<?php echo $k->id ?>">
	<?php echo $k->branch_name ?>
		
	</option>
<?php endforeach; ?>
	
</select>
		</td>

	</tr>
	<tr style="display: none;">
		<td>
			<label>
	Status Bayar
</label>


		</td>
		<td>
			<select name="status" >
	<!-- <optgroup>Pilih Status</optgroup> -->
	<!-- <option <?php if($_REQUEST[status]=='semua') echo "selected" ?> value="semua">SEMUA</option> -->
	<option <?php if($_REQUEST[status]=='Kredit') echo "selected" ?> value="Kredit">BELUM BAYAR / KURANG BAYAR</option>
	<!-- <option <?php if($_REQUEST[status]=='Lunas') echo "selected" ?> value="Lunas">SUDAH BAYAR</option> -->
</select>
		</td>
	</tr>
	<tr>
		<td>
			<label>Konsumen</label>
		</td>
		<td>
	<?php  $nilai = Customer::model()->findAll(" store_id = ".Yii::app()->user->store_id()." ");?>

<select id="customer" name="customer" class="tobe-select2 " style="display: inline;">
<option>Pilih Konsumen</option>
<?php foreach($nilai as $k): ?>
	<option <?php if ($k->nama==$_REQUEST['customer']) echo "selected" ?> value="<?php echo $k->nama ?>">
	<?php echo $k->nama ?>
		
	</option>
<?php endforeach; ?>
	
</select>
		</td>
	</tr>

	<tr>
		<td>
			<label>No Referensi</label>
		</td>
		<td>
			<input value="<?=$refno?>" class="form-control" name="refno" id="refno" placeholder="No Referensi" />
		</td>
	</tr>
</table>
	

<?php
// 	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
// 		'name'=>'Sales[date2]',
// 		'attribute'=>'date',
// //		'model'=>$model,
// 		// additional javascript options for the date picker plugin
// 		'options'=>array(
// 			'dateFormat'=>'yy-mm-dd',
// 			'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
// 			'showOn'=>'button', // 'focus', 'button', 'both'
// 			'buttonText'=>Yii::t('ui','Select form calendar'),
// 			'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
// 			'buttonImageOnly'=>true,
// 		),
// 		'value'=>$tgl2,
// 		'htmlOptions'=>array(
// 			// 'style'=>'height:20px;'
// 			'style'=>'height:20px;;width:80px;display:inline-block'
// 		),
// 	));


	
// $this->renderPartial('summary',array('summary'=>$summary));

?>
<!-- Status -->
	


<?php 

?>




	<!-- </div> -->
			<div style="margin-top:10px;">
			<?php echo CHtml::submitButton('Cari',array('class'=>'btn btn-primary')); ?>
			<input type="button" name="Cetak" value="Cetak" class="btn btn-primary"  onclick="$('#data-cetak').print()" />
			</div>

			<?php //echo CHtml::button('Cetak Rekap',array('id'=>'cetakrekap','class'=>'btn btn-primary')); ?>
			<?php //echo CHtml::button('Export to CSV',array('id'=>'export')); ?>
<?php //$this->endWidget(); ?>

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
<div id="data-cetak">
<?php 
if (isset($_REQUEST['customer'])){
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sales-grid',
	'dataProvider'=>$dataProvider,
	// 'filter'=>$model->search(),
	'columns'=>array(
		array(
			'header'=>'No.',
			'value'=>'($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) + array_search($data,$this->grid->dataProvider->getData())+1',
		),
		array(
		'name'=>'id',
		'header'=>'ID'
		),
		array(
		'name'=>'date',
		'header'=>'Tanggal'
		),
		array(
		'name'=>'refno',
		'header'=>'No Referensi'
		),
		array(
		'name'=>'nama',
		'header'=>'Nama pembeli'
		),
		// 'date',
// 		array(
// 		'name'=>'total_items',
// 		'header'=>'Total Item',
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 		),	
	
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
// 			'header'=>'total Kotor',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			'type'=>'number',
// 			'footer'=>true,
// 			'htmlOptions'=>array('style'=>'text-align:right'),

// 		),	
// 			array(
// 			'name'=>'untung',
// 			'header'=>'total Keuntungan',
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
		array(
			'name'=>'sale_discount',
			'header'=>'total diskon',
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			'type'=>'number',
			'footer'=>true,
			'htmlOptions'=>array('style'=>'text-align:right'),
		),
		array(
			'name'=>'voucher',
			'header'=>'total Voucher',
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			'type'=>'number',
			'footer'=>true,
			'htmlOptions'=>array('style'=>'text-align:right'),

		),

		
		array(
			'name'=>'sale_total_cost',
			'header'=>'Total Penjualan',
			'type'=>'number',
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

			'header'=>'bayar',
			'type'=>'number',		
		),
		array(
			'name'=>'sisa',

			'header'=>'Sisa Belum Bayar',
			'type'=>'number',		
			'value'=>'$data[sale_total_cost]-$data[bayar]',	
			'footer'=>true,
			'htmlOptions'=>array('style'=>'text-align:right'),	
			'class'=>'ext.gridcolumns.TotalColumn'
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
		'header'=>'Rincian Menu',
		'value'=>'CHtml::link("Detail",array("sales/detailitems","id"=>$data[id]),array("style"=>"text-decoration:none"))',
		
		),
		// array(
		// 	"name"=>"comment",
		// 	"header"=>"Description",
		// ),
		array(
		'type'=>'raw',
		'header'=>'hapus',
		'value'=>'CHtml::link("",array("Sales/hapus","id"=>$data[id]),array("style"=>"text-decoration:none","class"=>"fa fa-times"))',
		
		),
		array
		(
			'name'=>'print',
			'header'=>'Lunasi',
			'type'=>'raw',
				'value'=>'CHtml::Button("Lunasi",array("class"=>"btn btn-primary btn-lunasi")
		)'
			// 'value'=>'CHtml::ajaxButton("Cetak", array("sales/CetakReport","id"=>$data->id),array())',
		
	 ),	

		// array
		// (
		// 	'name'=>'print',
		// 	'header'=>'Cetak',
		// 	'type'=>'raw',
		// 	// 'value'=>'CHtml::ajaxButton("Cetak", array("sales/CetakReport","id"=>$data->id),array())',
		// 	'value'=>'CHtml::ajaxButton("Cetak ", array("sales/CetakReport"),array(
		// 	"data"=>array("id"=>$data[id]),
		// 	"success"=>"function(data){
		// 		// alert(\'cek\');
		// 		var sales = jQuery.parseJSON(data);
		// 		if (sales.sale_id!=\'\')
		// 		{
		// 			print_bayar(sales);
		// 		}
		// 	}",
		// 	"error"=>"function(data){alert(\'data\')}"
		// ),array("class"=>"btn btn-primary")
		// )',
	 // ),	

		// array(
		// 'type'=>'raw',
		// 'header'=>'Cetak',
		// 'value'=>'CHtml::link("Cetak Faktur Mini",array("Sales/cetakfaktur_mini","id"=>$data[id]),array("style"=>"text-decoration:none","class"=>"btn btn-primary"))',
		
		// ),
		// 	array
		// (
		// 	'name'=>'print',
		// 	'header'=>'Cetak Faktur',
		// 	'type'=>'raw',
		// 	'value'=>"CHtml::link('Cetak Faktur', array('sales/cetakfaktur&id=$data[id]'), array('class' => 'btn btn-primary'));"

		// 	// 'value'=>"<a href='123'>123</a>",

	 // ),	
		array(
		'class'=>'CButtonColumn',
		'template'=>'',
		// 'visible'=>$a,
		'buttons' => array(
		// 'delete' => array(
		// 	'url'=>'Yii::app()->createUrl("Sales/delete", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
		// ),
				'bayar' => array(
					'url'=>'Yii::app()->createUrl("Sales/bayarhutang", array("id"=>$data[id]))',      //A PHP expression for generating the URL of the button.
					'imageUrl'=>Yii::app()->request->baseUrl."/img/pay.png",
					'class'=>'bayarhutang',
					'options'=>array(
						'class'=>'bayarhutang'
					),
					'visible'=>'$data[sb]=="Kredit"',
				),
			),
		
		), 		
	),
)); 
}
?>
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
<div id="dialog" title="Pelunasan">
<p>
<?php 
$this->renderPartial("bayar_sales");
?>
</p>
</div>
<script>
$(document).ready(function(){
	 $( "#dialog" ).dialog({
		autoOpen: false,
		height: 350,
		width: 400,
		modal: true
	 });
	$(document).on("keyup","#total-bayar",function(e){
		// alert("123");
		if (!isNaN($(this).val())){
			var bayar = parseInt($(this).val());
			var tagihan = parseInt($("#total-tagihan").val());
			var sisa = parseInt(tagihan-bayar);
			$("#sisa-bayar").val(sisa);
		}else{
			$("#sisa-bayar").val(0);
		}
	});
	$(document).on("click",".btn-lunasikan",function(e){
		var tagihan = parseInt($("#total-tagihan").val());

		$("#total-bayar").val(tagihan).trigger("keyup");

	});
	$(document).on("click","#simpan-bayar",function(e){
		var total_bayar = parseInt($("#total-bayar").val());
		var total_tagihan = parseInt($("#total-tagihan").val());
		if (total_bayar=="0" || total_bayar==""){
			alert("Tidak Boleh Kosong");
			return;
		}

		if (total_bayar>total_tagihan){
			$("#total-bayar").val(0).trigger("keyup");
			alert("Total Bayar tidak bisa melebihi total hutang");
			return;
		}


		var c = confirm("Yakin ?? ");
		if (!c){
			return;
		}
		var id = $("#no-tagihan").val();
		var tanggal_bayar = $("#tanggal-bayar").val();
		var total_bayar = $("#total-bayar").val();
		var pembayaran_via = $("#pembayaran_via").val();
		var data = {
			id : id ,
			tanggal_bayar : tanggal_bayar,
			total_bayar : total_bayar,
			pembayaran_via : pembayaran_via
		}
		$.ajax({
			url:'<?php echo $this->createUrl('sales/bayartagihan')?>',
			data:data,
			success: function(data){
				// alert(data);
				var json = JSON.parse(data);
				if (json.status=="200"){
				    $("#dialog").dialog("close");
				    location.reload();
				}else{
					alert("Gagal!");
				    $("#dialog").dialog("close");
				}
				// $("#no-tagihan").val(id);
				// $("#total-tagihan").val(json.sale_total_cost);

			},
			error: function(data){
				alert('error');
			}
		});

	});
	$(document).on("click",".btn-lunasi",function(e){
		// alert("123");
		var id = $(this).closest("tr").find("td").eq(1).html();
		$.ajax({
				url:'<?php echo $this->createUrl('sales/getsaledata')?>',
				data:'id='+id,
				success: function(data){
					var json = JSON.parse(data);
					$("#no-tagihan").val(id);
					var total = parseInt(json.sale_total_cost) - parseInt(json.bayar);
					$("#total-tagihan").val(total);
					$("#total-bayar").focus();
			
				},
				error: function(data){
					alert('error');
				}
			});


	    $("#dialog").dialog("open");

		// $("#dialog").dialog( "open" );
		// e.preventDefault();
		// if (!confirm("Yakin ? ")){
		// 	return false;
		// }

	});
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

