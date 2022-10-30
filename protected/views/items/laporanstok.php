<?php 
if (!isset($cabangpusat)){
	$cabangpusat = 0;
}
?>
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="jZebra/jzebra/jzebra.jar" width="0" height="0">
    <param name="printer" value="zebra">
</applet> 
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>

<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>

<div id="hasil"></div>
<style type="text/css">
	.dalem{
		width:100%;
	}
	thead tr td{
		font-weight: bolder;
	}
	table tr td{
		padding: 5px;
	}
	 @media print {
       table{
		width:100%;
	   }
	   #data-cetak{
		width:80%!important;
	   }
	   #datatable_wrapper{
		overflow:visible;
	   }
 	}
</style>
<?php 
$this->renderPartial('application.views.site.main');
?>

<h1><i class="fa fa-book"></i>
	Laporan Stok
</h1>
<h4>
	Stok items tidak sesuai ?? sesuaikan <a href="<?php echo Yii::app()->createUrl("sales/laporanstok") ?>">disini</a>
</h4>
<br>

<!-- <input type="button" name="Cetak" value="Cetak" class="btn btn-primary"  onclick="$('#datatable').print()" /> -->

<div id="data-cetak">
<table  id="datatable" class="table" ></table>
</div>
<?php 
$usaha = SiteController::getConfig("jenis_usaha");
if ($usaha!="Toko"){
?>
<button style="display: none;" id="cetak-all-stok" class="btn btn-primary" name="cetak-all-stok">
	<i class="fa fa-print"></i>
		Cetak
</button>
<?php  } ?>

<script>
$(document).ready(function(){
	$('#cetak-all-stok').click(function(){
		var id = $("#cabang").val();
		
			$.ajax({
				url:'<?php echo Yii::app()->createUrl("sales/CetakStok") ?>',
				data:'id='+id,
				success: function(data){
					// alert(data);
					var json = jQuery.parseJSON(data);
					// $('#hasiljson').html(data);
					print_keluar(json);
					// console.log(data);
					
				},
				error: function(data){
					alert('error');
				}
			});
		
	});
	 $('.tanggal').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true

    });
	$('.set-stok').click(function(e){
		var i = $(".set-stok").index(this);
		// alert(i);
	   var before = parseInt($(this).attr("stok-before")) ;
	   var skrg =  parseInt($(".stok_real").eq(i).val() );
	   var harga =  parseInt($(this).attr("harga"));
	   var id = $(this).attr("item-id");
	   // var harga = $(this).attr("item-id");
	   // alert(before);
	   // alert(skrg);
	   // alert(id);
	   if (before!=skrg){
	   		// alert(skrg);
	   		// alert(before);
	   		if (skrg>before){	
			   var harga = parseInt(prompt("Masukan Harga Beli (Harga Beli Terakhir)",harga,harga) );
			   if (harga==0 || isNaN(harga) || harga==null){
				   	alert("Tidak Boleh Kosong");
				   	exit;
			   }
	   		}else{
	   			harga = 0;
	   		}
	   		// alet("auasdasdas");

	   		// alert(harga);
		   $.ajax({
	          url: "<?php echo Yii::app()->createUrl("items/setstok")?>",
	          // cache: false,
	          data : "before="+before+"&skrg="+skrg+"&id="+id+"&harga="+harga,
	          success: function(msg){
	            var data = JSON.parse(msg);
	            if (data.success==true){
	            	$(".set-stok-old").eq(i).html(skrg);
	            	window.location.reload();
	            	// alert("Berhasil Update Data");
	            }
	        	// alert(JSON.stringify(data));
	           },
	           error : function(d){
	           	alert(d);
	           	// var data = JSON.parse(d);
	           	// ale
	           	// alert(JSON.st)
	           }
	       });
	   }else{
	   	alert(" Tidak ada perubahan ");
	   }

	});
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
    reloadItems();


    $('#datatable tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    });

	 // var table = $('#datatable').DataTable({
		// 	"pageLength": 20,
		// 	 "autoWidth": true
		// });

       myTable.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );

       var myTable;
    var myTableMember;
   function reloadItems(){
    if (myTable){
            myTable.destroy();
          }
          // $(".loader").show();
          myTable =  $('#datatable').DataTable({
            "processing": true,
			 dom: '<"top"i>Brt<"bottom"lp><"clear">',
			 buttons: [
				'copyHtml5',
				'excelHtml5',
				'csvHtml5',
				'pdfHtml5'
        	],
			//  buttons: [ 'copy', 'csv', 'excel', 'pdf', 'print'],	
            "lengthMenu": [[10, 25, 50, 1000000], [10, 25, 50, "Semua"]],
            "serverSide": true,
            // "ajax" : "<?php echo Yii::app()->createUrl("Items/laporanstokJSON") ?>",
			"ajax": {
				"url": "<?php echo Yii::app()->createUrl("Items/laporanstokJSON") ?>",
				"type": "GET",
				"data": function ( d ) {
				return $.extend( {}, d, {
					"cabangpusat": <?=$cabangpusat?>
				} );
				}
			},
            "fnDrawCallback": function( oSettings ) {
                   // $(".loader").hide();
           },

            "footerCallback": function ( row, data, start, end, display ) {
 
        },
            columns:

            [  
              {
                title: "No ",
                name:'nomor',
                "searchable":false,                
              },
              {
                title: "Kategori ",
                name:'nama_kategori',
                "searchable":true,                
              },

              {
               title: "Sub Kategori",
                name:'nama_sub_kategori',
                "searchable":true,  
              }, 
              //  {
              //  title: "Nama Letak",
              //   name:'nama_letak',
              //   "searchable":true,  
              // }, 
              {
                title: "Nama Item",
                name:'item_name',
                "searchable":true,  
              },
              {
                title: "Stok",
                name:'stok',
                "searchable":false,  
              }
          ]

          });
  }
          });
</script>