  	<?php 
	if (isset($_REQUEST['id'])){ 
        $nama_akun = AkuntansiAkun::model()->findByPk($_REQUEST['id'])->nama_akun;
    ?>
	<script>
    setTimeout(() => {
      $("#datatable_filter input").val("<?php echo $nama_akun ?>").trigger("keyup");
    }, 1000);
	</script>
<?php } ?>

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="#">Beranda</a></li>
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Categories/admin'); ?>">Mengelola Biaya</a></li>
</ol>

<h1>
<i class="fa fa-book"></i>
Akun
</h1>
<hr>
<div class="row">
	<div class="col-sm-8">
		<a class="btn btn-primary" href="<?php echo Yii::app()->controller->createUrl("create") ?>">
			<i class="fa fa-plus"></i> Tambah 
		</a>
    <a class="btn btn-primary" href="<?php echo Yii::app()->controller->createUrl("saldo") ?>">
			<i class="fa fa-pencil"></i> Kelola Saldo Awal 
		</a>


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
          <th>Kode Akun</th>
    
          <th>Nama </th>
          <th>Group</th>
          <th>Sub Group</th>

  		</tr>
       
          <!-- <th>Aksi</th> -->
        </thead>
        <tbody>

        	<?php 
        	$no = 1;
        	// echo "<pre>";
        	// print_r($rawData);
        	// echo "</pre>";
        	// // exit;
        	foreach ($rawData as $key => $value) { ?>
        	<tr>
            <td>
          
            <!-- Single button -->
<div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Aksi <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
 
    <li>
          <a href="<?php echo Yii::app()->createUrl("Akun/update", array("id"=>$value[id])) ?>">
          <i class="fa fa-pencil"></i> Ubah
          </a>
    </li>
    <li>
        <a class="hapus" href="<?php echo Yii::app()->createUrl("Akun/hapus", array("id"=>$value[id])) ?>">
                <i class="fa fa-times"></i> Hapus
              </a>
    </li>
    <!-- <li role="separator" class="divider"></li> -->
    <!-- <li><a href="#">Separated link</a></li> -->
  </ul>
</div>
            </td>
        		<!-- <td><?php echo $value['id']; ?></td> -->
            <td><?php echo $value['kode_akun'] ?></td>
        		<td><a href="<?php echo Yii::app()->createUrl("Akun/update", array("id"=>$value[id])) ?>"><?php echo $value['nama_akun'] ?></a></td>
            <td><?php echo $value['nama_group'] ?></td>
            <td><?php echo $value['nama_subgroup'] ?></td>

        	
        	</tr>
        	<?php } ?>
        </tbody>
    </table>

<?php 
// exit();
?>
 

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