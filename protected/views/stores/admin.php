<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#">Beranda</a></li>
<li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Stores/admin'); ?>">Mengelola Member</a></li>
</ol>

<h1>
<i class="fa fa-book"></i>
Stores
</h1>
<hr>
<div class="row">
<div class="col-sm-8">
<a href="<?php echo Yii::app()->controller->createUrl("create") ?>">
<button class="btn btn-primary">
	<i class="fa fa-plus"></i> Tambah 
</button>
</a>

</div>
</div>

<table id="datatable" class="table table-striped table-bordered">
<thead>
<tr>
	<th>Aksi</th>
	<th>Nama Usaha</th>
	<th>Jenis usaha</th>
	<th>Telepon</th>
	<th>Alamat</th>
	<th>Tanggal dibuat</th>
</tr>
</thead>
<tbody>
	<?php 
	$no = 1;
	foreach ($rawData as $key => $value) { ?>
	<tr>
	<td>          
<div class="btn-group">
<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
Aksi <span class="caret"></span>
</button>
<ul class="dropdown-menu">

<li>

	<a href="<?php echo Yii::app()->createUrl("Stores/update", array("id"=>$value[id],"status"=>"ubah")) ?>">
	<i class="fa fa-pencil"></i> Ubah
	</a>
</li>
<li style="display:none">
	<a href="<?php echo Yii::app()->createUrl("Stores/view", array("id"=>$value[id],"status"=>"ubah")) ?>">
	<i class="fa fa-eye"></i> Lihat
	</a>
</li>
<li>
<a class="hapus" href="<?php echo Yii::app()->createUrl("stores/hapus", array("id"=>$value[id])) ?>">
		<i class="fa fa-times"></i> Hapus
		</a>
</li>
</ul>
</div> 
	</td>
		<td>
			<a href="<?php echo Yii::app()->createUrl("Stores/update", array("id"=>$value['id'])) ?>">
			<?php echo $value['name']; ?>
		</a>
		</td>
		<td><?php echo $value['store_type']; ?></td>
		<td><?php echo $value['phone']; ?></td>
		<td><?php echo $value['address1']; ?></td>        	
		<td><?php echo date("d M Y h:i", strtotime($value['created_at'])); ?></td>        	
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