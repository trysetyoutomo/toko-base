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
<div class="h1 font-size-sm">
<i class="fa fa-book"></i> Laporan Penjualan Harian </div>
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

echo CHtml::beginForm($this->createUrl('sales/index'), 'get');
?>

<div class="row">
    <div class="col-md-2 col-xs-12">
        <label for="day">Tanggal Transaksi</label>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <?php
            $dropdownOptions = array('class' => 'form-control d-inline-block');
            echo CHtml::dropDownList('day', $day2, $day, $dropdownOptions);
            ?>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <?php echo CHtml::dropDownList('month', $month, $data, array('class' => 'form-control')); ?>
        </div>
    </div>
    <div class="col-md-1">
        <div class="form-group">
            <?php echo CHtml::dropDownList('year', $year, $arr_year, array('class' => 'form-control')); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-2 col-xs-12">
        <?php 
		if (Yii::app()->user->level() == "2")
			$nilai = Branch::model()->findAll("  hapus = 0 and store_id = '".Yii::app()->user->store_id()."' "); 
		else
			$nilai = Branch::model()->findAll(" hapus = 0 and id = '".Yii::app()->user->branch()."' "); 
		
		?>
        <label>Cabang</label>
</div>
<div class="col-md-10 col-xs-12">
        <select id="cabang" name="cabang" class="form-control">
		<?php  if (Yii::app()->user->level() == "2"){ ?>
			<option value="">Semua Cabang</option>
		<?php } ?> 
            <?php foreach ($nilai as $k): ?>
                <option <?php if ($k->id == $cabang) echo "selected" ?> value="<?php echo $k->id ?>">
                    <?php echo $k->branch_name ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="row " style="margin-top: 1rem; display:none">
	<div class="col-md-2 col-xs-12">
        <label for="day">Status Bayar</label>
    </div>
    <div class="col-md-10 col-xs-12">
        <select name="status" class="form-control">
            <option <?php if ($_REQUEST['status'] == 'semua') echo "selected" ?> value="semua">SEMUA</option>
            <option <?php if ($_REQUEST['status'] == 'Kredit') echo "selected" ?> value="Kredit">BELUM BAYAR / KURANG BAYAR</option>
            <option <?php if ($_REQUEST['status'] == 'Lunas') echo "selected" ?> value="Lunas">SUDAH BAYAR</option>
        </select>
    </div>
</div>

<hr>
<?php echo CHtml::submitButton('Cari',array('class'=>'btn btn-primary')); ?>
<input type="button" name="Cetak" value="Cetak" class="btn btn-primary"  onclick="$('#data-cetak').print()" />

<!-- <a href="<?php echo Yii::app()->createUrl('sales/cetakrekap&noprint=true') ?>" type="button" class="btn btn-primary"  name="btn-preview">
Preview Rekap
</a> -->

<?php //echo CHtml::button('Cetak Rekap',array('id'=>'cetakrekap','class'=>'btn btn-primary')); ?>
<?php //echo CHtml::button('Export to CSV',array('id'=>'export')); ?>
<?php //$this->endWidget(); ?>



<table id="datatable" class="table table-striped table-bordered">
	<thead></thead>
	<tbody></tbody>
	<tfoot>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
            <!-- <th colspan="5" style="text-align:right">Total:</th> -->
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
			<th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>

        </tr>
    </tfoot>
</table>

<?php 
// exit();
?>


<!-- Datatables -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/dataTables.editor.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/dataTables.select.min.js"></script>
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
<!-- moment js -->
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>


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
			"pageLength": "1000000",
            "lengthMenu": [[5, 10, 25, 50,100, 1000000], [5, 10, 25, 50,100, "Semua"]],
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
			"footerCallback": function (row, data, start, end, display) {
				var api = this.api(), data;

				var total_modal = api
					.column(4) // Replace 3 with the index of the column you want to calculate the total for
					.data()
					.reduce(function (a, b) {
						return parseInt(a) + parseInt(b); // Assuming the data is numeric; change the parsing logic as needed
					}, 0);

				// Calculate the sum of the data in the column
				var total_kotor = api
					.column(5) // Replace 3 with the index of the column you want to calculate the total for
					.data()
					.reduce(function (a, b) {
						return parseInt(a) + parseInt(b); // Assuming the data is numeric; change the parsing logic as needed
					}, 0);

				var total_pajak  = api
				.column(6) // Replace 3 with the index of the column you want to calculate the total for
				.data()
				.reduce(function (a, b) {
					return parseInt(a) + parseInt(b); // Assuming the data is numeric; change the parsing logic as needed
				}, 0);
				
				var total_service  = api
				.column(7) // Replace 3 with the index of the column you want to calculate the total for
				.data()
				.reduce(function (a, b) {
					return parseInt(a) + parseInt(b); // Assuming the data is numeric; change the parsing logic as needed
				}, 0);

				var total_diskon = api
					.column(8) // Replace 3 with the index of the column you want to calculate the total for
					.data()
					.reduce(function (a, b) {
						return parseInt(a) + parseInt(b); // Assuming the data is numeric; change the parsing logic as needed
					}, 0);

				var total_potongan = api
					.column(9) // Replace 3 with the index of the column you want to calculate the total for
					.data()
					.reduce(function (a, b) {
						return parseInt(a) + parseInt(b); // Assuming the data is numeric; change the parsing logic as needed
					}, 0);

				var total_pembulatan = api
					.column(10) // Replace 3 with the index of the column you want to calculate the total for
					.data()
					.reduce(function (a, b) {
						return parseInt(a) + parseInt(b); // Assuming the data is numeric; change the parsing logic as needed
					}, 0);

				var total_bersih = api
					.column(11) // Replace 3 with the index of the column you want to calculate the total for
					.data()
					.reduce(function (a, b) {
						return parseInt(a) + parseInt(b); // Assuming the data is numeric; change the parsing logic as needed
					}, 0);

				// Add the total to the footer cell
				$(api.column(4).footer()).html(numeral(total_modal).format('0,0')); // Replace 3 with the index of the column you're calculating the total for
				$(api.column(5).footer()).html(numeral(total_kotor).format('0,0')); // Replace 3 with the index of the column you're calculating the total for
				$(api.column(6).footer()).html(numeral(total_pajak).format('0,0')); // Replace 3 with the index of the column you're calculating the total for
				$(api.column(7).footer()).html(numeral(total_service).format('0,0')); // Replace 3 with the index of the column you're calculating the total for
				$(api.column(8).footer()).html(numeral(total_diskon).format('0,0')); // Replace 3 with the index of the column you're calculating the total for
				$(api.column(9).footer()).html(numeral(total_potongan).format('0,0')); // Replace 3 with the index of the column you're calculating the total for
				$(api.column(10).footer()).html(numeral(total_pembulatan).format('0,0')); // Replace 3 with the index of the column you're calculating the total for
				$(api.column(11).footer()).html(numeral(total_bersih).format('0,0')); // Replace 3 with the index of the column you're calculating the total for
			},

		//    "footerCallback": function(row, data, start, end, display) {
		// 		var total = 0;
		// 		var total_sisa = 0;
		// 		var api = this.api(),
		// 			intVal = function(i) {
		// 				return typeof i === 'string' ? i.replace(/[, Rs]|(\.\d{2})/g, "") * 1 : typeof i === 'number' ? i : 0;
		// 			},		
		// 			total = api.column(5).data().reduce(function(a, b) {
		// 				return intVal(a) + intVal(b);
        //             }, 0);
        //             total_sisa = api.column(6).data().reduce(function(a, b) {
		// 				return intVal(a) + intVal(b);
		// 			}, 0);
		// 			// alert(total);
		// 			// alert(total_sisa);

		// 		if (isNaN(total)) {
		// 			total = 0;
        //         }
                
                
		// 		if (isNaN(total_sisa)) {
		// 			total_sisa = 0;
		// 		}

		// 		if (data.length > 0) {
		// 			$(api.column(3).footer()).html('Total');
		// 			$(api.column(5).footer()).html(' ' + numeral(total).format('0,0') + '');
        //             $(api.column(6).footer()).html(' ' + numeral(total_sisa).format('0,0') + '');
        //             let total_all = total+total_sisa;
        //             $('tr:eq(1) td:eq(3)', api.table().footer()).html("Total Keseluruhan ");
        //             $('tr:eq(1) td:eq(5)', api.table().footer()).html(numeral(total_all).format("0,0"));
        //             $('tr:eq(1) td:eq(5)', api.table().footer()).html(numeral(total_all).format("0,0"));
		// 			// $(api.column(6).footer(1)).html(' ' + numeral(total_sisa).format('0,0') + '');
		// 		}
		// 	},
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
				"render": function(data, type, row) {
                    return moment(data).format('DD MMM YY hh:mm:ss');
                }
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
				render :  function(data){
					return numeral(data).format(0,0);
				},
				visible : <?=Yii::app()->user->getLevel()!=2 ? 'false' : 'true'?>
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
				title: "Total Pajak",	
				data:'tax',
				name:'tax',
				render :  function(data){
					return numeral(data).format(0,0);
				}
			},
			{
				title: "Total Service",
				data:'service',
				name:'service',
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
				title: "Total Pembulatan",
				data:'pembulatan',
				name:'pembulatan',
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
				title: "Metode <br> Pembayaran",
				data:'pembayaran_via',
				name:'pembayaran_via',
				render : function(data,type, row){
					// alert(row.cash);
					// alert(row.cash);
					let returnData = "";
					if (row.cash > 0){
						returnData += "<div class='badge badge-danger'>CASH</div> <br>"
					}
					if (row.edc_bca > 0){
						returnData += "<div class='badge badge-danger'>" + row.pembayaran_via +"</div> <br>"
						// returnData += row.pembayaran_via + " <br>"
					}
					return returnData;
				}
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
					return '<a target="_blank" href="index.php?r=sales/cetakfaktur_mini&id='+data+'" class="btn btn-primary">Cetak</a> &nbsp;'
					<?php //if (Yii::app()->user->getLevel() === '2'): ?>
						+'<a class=" btn-danger btn  hapus" href="index.php?r=sales/hapus&id='+data+'"> Hapus </a>';
					<?php //endif ?>
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


