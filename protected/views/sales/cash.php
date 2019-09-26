
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>

<style type="text/css">
	.table tr td{
		border: 0px solid white!important;
	}
	select{
		padding:7px;
	}
</style>
<h1>Laporan Pembayaran </h1>
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


echo CHtml::beginForm();
echo CHtml::dropDownList('month', $month, $data);
echo CHtml::dropDownList('year', $year, $arr_year);
?>
&nbsp;
<?php
// echo CHtml::submitButton('Cari', array('submit' => array('sales/cashReport'),"class"=>'btn btn-primary' ));
echo CHtml::submitButton("Cari", array('id' => 'btSubmit', 'class' => 'btn btn-primary', 'name' => 'files', 'title' => 'Save the updates to these files'));

echo CHtml::Button("CETAK", array('class' => 'btn btn-primary','onclick'=>'$("#sales-grid").print()'));


echo CHtml::endForm();
?>

<div class="row">
	<?php 	
	// $this->renderPartial('summarycash',array('summary'=>$cashsum));
	?>
</div>



<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'sales-grid',
	'dataProvider'=>$datacash,
	'columns'=>array(
		array(
			'header'=>'No.',
			'value'=>'($this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize) + array_search($data,$this->grid->dataProvider->getData())+1',
		),
		// 'id',
		// 'date',
		array(
			'name'=>'faktur_id',
			'header'=>'ID Penjualan',
			//'value'=>$data->date
			// 'value'=>'date("Y-m-d",strtotime($data->date))',
			// 'value'=>'date('d M Y', strtotime($model['date'])'
		),
		array(
			'name'=>'date',
			'header'=>'Tanggal',
			'value'=>$data->date
			// 'value'=>'date("Y-m-d",strtotime($data->date))',
			// 'value'=>'date('d M Y', strtotime($model['date'])'
		),
		array(
			'name'=>'pembayaran_via',
			'header'=>'Kartu'
			// 'value'=>$data->date
			// 'value'=>'date("Y-m-d",strtotime($data->date))',
			// 'value'=>'date('d M Y', strtotime($model['date'])'
		),
		// 'total_cost',
		array(
			'name'=>'total',
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			// 'type'=>'number',
			'footer'=>true,
			// 'htmlOptions'=>array('style'=>'text-align:right'),

		),
		// 'compliment',
		array(
			'name'=>'cash',
			'header'=>'KAS',
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			// 'type'=>'number',
			'footer'=>true,
			// 'htmlOptions'=>array('style'=>'text-align:right'),

		),
// 		array(
// 			'name'=>'compliment',
// 			'header'=>'Komplimen',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			// 'type'=>'number',
// 			'footer'=>true,
// 			// 'htmlOptions'=>array('style'=>'text-align:right'),

// 		),		
		array(
			'name'=>'edc_bca',
			'header'=>'Debit',
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			// 'type'=>'number',
			'footer'=>true,
			// 'htmlOptions'=>array('style'=>'text-align:right'),

		),

		array(
			'name'=>'edc_niaga',
			'header'=>'Kredit',
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			// 'type'=>'number',
			'footer'=>true,
			// 'htmlOptions'=>array('style'=>'text-align:right'),

		),

// 		array(
// 			'name'=>'credit_bca', // TRANSFER MANDIRI
// 			'header'=>'Transfer Mandiri',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			// 'type'=>'number',
// 			'footer'=>true,
// 			'visible'=>true,

// 			// 'htmlOptions'=>array('style'=>'text-align:right'),

// 		),array(
// 			'name'=>'credit_mandiri',
// 			'header'=>'Kredit Mandiri',
// 			'type'=>'number',
// 			'htmlOptions'=>array('style'=>'text-align:right'),
// 			'class'=>'ext.gridcolumns.TotalColumn',
// //			'value'=>'$data->nilai',
// 			// 'type'=>'number',
// 			'visible'=>false,

// 			'footer'=>true,
// 			// 'htmlOptions'=>array('style'=>'text-align:right'),

		// ),
		array(
			'name'=>'voucher',
			'type'=>'number',
			'visible'=>false,

			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			// 'type'=>'number',
			'footer'=>true,
			// 'htmlOptions'=>array('style'=>'text-align:right'),

		),array(
			'name'=>'dll',
			'header'=>'Pending',
			'visible'=>false,
			'type'=>'number',
			'htmlOptions'=>array('style'=>'text-align:right'),
			'class'=>'ext.gridcolumns.TotalColumn',
//			'value'=>'$data->nilai',
			// 'type'=>'number',
			'footer'=>true,
			// 'htmlOptions'=>array('style'=>'text-align:right'),

		),		
	),
)); ?>
