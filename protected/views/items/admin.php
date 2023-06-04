<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Beranda</a></li>
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a></li>
</ol>

<h1>
<i class="fa fa-book"></i>
Mengelola Item
</h1>
<hr>
<div class="row">
	<div class="col-sm-8">
		<a href="<?php echo Yii::app()->controller->createUrl("create") ?>">
		<button class="btn btn-primary">
			<i class="fa fa-plus"></i> Tambah Item
		</button>
		</a>
    <a  href="<?php echo Yii::app()->controller->createUrl("cetaklabel") ?>">
    <button class="btn btn-primary">
      <i class="fa fa-barcode"></i> Cetak Barcode Item
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
        </thead>
        <tbody>

      </tbody>
    </table>

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


    $('#datatable tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    });

	 // var table = $('#datatable').DataTable({
		// 	"pageLength": 20,
		// 	 "autoWidth": true
		// });

    //    table.columns().every( function () {
    //     var that = this;
 
    //     $( 'input', this.footer() ).on( 'keyup change', function () {
    //         if ( that.search() !== this.value ) {
    //             that
    //                 .search( this.value )
    //                 .draw();
    //         }
    //     } );
    // } );

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
            "processing": true,
            "serverSide": true,
            "lengthMenu": [[5, 10, 25, 50, 1000000], [5, 10, 25, 50, "Semua"]],
            "ajax" : "<?php echo Yii::app()->createUrl("Items/adminJSON") ?>",
            "fnDrawCallback": function( oSettings ) {
                   // $(".loader").hide();
           },

            "footerCallback": function ( row, data, start, end, display ) {
 
        },
            columns:

            [  
              {
              title: "Aksi",
              name:'aksi',
                "searchable":false                
              },
              {
                title: "Barcode ",
                name:'barcode',
                "searchable":true,                
              },
              {
                title: "Nama ",
                name:'item_name',
                "searchable":true,                
              },

              {
               title: "Kategori",
                name:'nama_kategori',
                "searchable":true,  
              }, 
              {
                title: "Sub Kategori",
                name:'motif',
                "searchable":true,  
              },
              {
                title: "Harga",
                name:'harga',
                "searchable":true,  
              },
              {
                title: "Harga Pokok",
                name:'harga',
                "searchable":true,  
              },

              {
              title: "Jenis Item",
              name:'nama_letak',
              "searchable":true,  
            }
          ]

          });
  }
          });
</script>