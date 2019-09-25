
<?php
/* @var $this BranchController */
/* @var $model Branch */

?>
<!-- <style type="text/css">
	#Branch-grid{
		width: 100%;
	}
</style>
 -->

<h1>
<i class="fa fa-book"></i>
Mengelola Tempat
</h1>
<hr>
<!-- <div class="row">
	<div class="col-sm-8">
		<a href="<?php echo Yii::app()->controller->createUrl("create") ?>">
		<button class="btn btn-primary">
			<i class="fa fa-plus"></i> Tambah Tempat
		</button>
		</a>
	</div>
</div> -->

   <table id="datatable" class="table table-striped table-bordered">
       <thead>
  		<tr>
           <th>Aksi</th>
          <th>No</th>
          <!-- <th>Nama</th> -->
          <th>Nama Tempat</th>
          <th>Alamat</th>
          <th>Telepon</th>
          <th>Slogan</th>

  		</tr>
       
          <!-- <th>Aksi</th> -->
        </thead>
        <tbody>

        	<?php 
        	$no = 1;
        	// echo "<pre>";
        	// print_r($model);
        	// echo "</pre>";
        	foreach ($rawData as $key => $value) { ?>
        	<tr>
            <td>
          
            <!-- Single button -->
<div class="btn-group">
  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Aksi <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
 <!--  <li>
      <a href="<?php echo Yii::app()->createUrl("BranchSatuan/admin", array("id"=>$value[id],"status"=>"ubah")) ?>" >
              <i class="fa fa-pencil"></i>
              Kelola Satuan
            </a>
        
  </li> -->

    <li>
      
          <a href="<?php echo Yii::app()->createUrl("Branch/update", array("id"=>$value[id],"status"=>"ubah")) ?>">
            <i class="fa fa-pencil"></i> Ubah
          </a>
    </li>

    <li>
      <?php 
      if ($value['is_utama']!="1"){
      ?>
        <a class="hapus" href="<?php echo Yii::app()->createUrl("Branch/hapus", array("id"=>$value[id])) ?>">
        <i class="fa fa-times"></i> Hapus
        </a>
      <?php } ?>
    </li>
    <!-- <li role="separator" class="divider"></li> -->
    <!-- <li><a href="#">Separated link</a></li> -->
  </ul>
</div>

            
              <!-- <a href=""></a> -->
            </td>
        		<td><?php echo $no++; ?></td>
        	<!-- 	<td>
            <a href="<?php //echo Yii::app()->createUrl("Branch/view", array("id"=>$value[id])) ?>">
            <?php echo $value['company'] ?>
            </a>
            </td> -->
            <td>
            	
            <a href="<?php echo Yii::app()->createUrl("Branch/update", array("id"=>$value[id])) ?>">
            <?php echo $value['branch_name'] ?>
            </a>
            </td>
              

            		<td><?php echo $value['address'] ?></td>
                <td><?php echo $value['telp'] ?></td>
            		<td><?php echo $value['slogan'] ?></td>
        		
</td>
        	
        	</tr>
        	<?php } ?>
        </tbody>
    </table>

<?php 
// exit();
?>
 <?php 
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