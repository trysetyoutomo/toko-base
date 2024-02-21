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
	<tr style="display: none;">
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



<table id="datatable" class="table table-striped table-bordered"></table>

<?php 
// exit();
?>


<!-- Datatables -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/dataTables.editor.min.js"></script>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/dataTables.select.min.js"></script>

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
    reloadItems();
	
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

	var filterInputs = "";
     $('#datatable thead th').each( function () {
         var title = $(this).text();
	 	// let listTitle = ["Kasir","Sub Kategori"];
	 	// if (listTitle.includes(title)){
				filterInputs += '<th><input class="form-control" type="text" placeholder="Cari  '+title+'" /></th>';
			
		// }else{
		// 	 filterInputs += '<th></th>';
		// }
     });


	// $("#datatable thead").append("<tr>"+filterInputs+"</tr>");
	


    // $('#datatable tfoot th').each( function () {
    //     var title = $(this).text();
    //     $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    // });

	 // var table = $('#datatable').DataTable({
		// 	"pageLength": 20,
		// 	 "autoWidth": true
		// });

	myTable.columns().every( function () {
        var that = this;
		console.log(that.search() );
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
			if (this.value.length > 4){
				myTable.columns(0).search(table.columns(0).search() + ' ' +  this.value).draw();
				// if ( that.search() !== this.value ) {
				// 	that.search( this.value ).draw();
				// }
            }
        } );
    } );

    var myTable;
    var myTableMember;
    var editor;



  // $('#datatable').on( 'click', 'tbody td:not(:first-child)', function (e) {
  //   editor.inline( this );
  // } );


   function reloadItems(){
    if (myTable){
            myTable.destroy();
          }
          // $(".loader").show();
          myTable =  $('#datatable').DataTable({
            "searching": false,
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[5, 10, 25, 50, 1000000], [5, 10, 25, 50, "Semua"]],
			"ajax": {
				"url" : "<?php echo Yii::app()->createUrl("sales/adminJSON") ?>",
				"dataSrc": "data",
				"data" : {
					"day" : $("#day").val(),
					"month" : $("#month").val(),
					"year" : $("#year").val(),
					"cabang" : $("#cabang").val(),
					"status" : $("#status").val()
				}
			},
            "fnDrawCallback": function( oSettings ) {
           },

            "footerCallback": function ( row, data, start, end, display ) {
 
	        },
            columns:
            [  
			{
				title: "Faktur ID",
				data:'faktur_id',
				name:'faktur_id',
			},
			{
				title: "Tgl Transaksi",
				data:'date',
				name:'date',
			},
			{
				title: 'Tgl jatuh Tempo',
				data:'tanggal_jt',
				name:'tanggal_jt',
			},
			{
				title: "Nama pembeli",
				data:'nama',
				name:'nama',
			},
			{
				title: "Total modal",
				data:'sale_sub_modal',
				name:'sale_sub_modal',
				hidden : <?=Yii::app()->user->getLevel()!=2 ? 'true' : 'false'?>
			},
			{
				title: "Total Kotor",
				data:'sale_sub_total',
				name:'sale_sub_total',
				render :  function(data){
					return numeral(data).format(0,0);
				}
			},
			{
				title: "Total diskon",
				data:'sale_discount',
				name:'sale_discount',
				render :  function(data){
					return numeral(data).format(0,0);
				}
			},
			{
				title: "Total Potongan",
				data:'voucher',
				name:'voucher',
				render :  function(data){
					return numeral(data).format(0,0);
				}
			},
			{
				title: "Total Bersih",
				data:'sale_total_cost',
				name:'sale_total_cost',
				render :  function(data){
					return numeral(data).format(0,0);
				}
			},
			{
				title: "Kasir",
				data:'inserter',
				name:'inserter',
				searchable:true,
			},
			{
				title: "bayar",
				data:'bayar',
				name:'bayar',
				render :  function(data){
					return numeral(data).format(0,0);
				}
			},
			{
				title: "Kartu",
				data:'pembayaran_via',
				name:'pembayaran_via',
			},
			{
				title: "Rincian",
				data: "id",
				render : function(data,row){
					return '<a href="index.php?r=sales/detailitems&id='+data+'">Detail</a>';
				}
			},
			{
				title: "Aksi",
				data: "id",
				render : function(data,row){
					return '<a target="_blank" href="index.php?r=sales/cetakfaktur_mini&id='+data+'" class="btn btn-primary">Cetak</a> &nbsp; <a class=" btn-danger btn  hapus" href="index.php?r=sales/hapus&id='+data+'"> Hapus </a>';
				}
			}
          ]

          });
  }
          });
</script>

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


