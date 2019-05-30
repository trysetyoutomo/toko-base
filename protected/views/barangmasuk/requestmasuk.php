<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>

<div id="hasil"></div>
<style type="text/css">
	.dalem{
		width:100%;
	}
	.show{
		display: block;
	}
	thead tr td{
		font-weight: bolder;
	}
</style>
<!--
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/app/SalesItems/main.js"></script>
-->
<?php 
$this->renderPartial('application.views.site.main');
?>


		
<h1>
<i class="fa fa-list"></i>
Laporan Barang Keluar</h1>
<br>
	</legend>

<?php
 // $this->widget('zii.widgets.CListView', array(
	// 'dataProvider'=>$dataProvider,
	// 'itemView'=>'_view',
// )); 
?>
<?php 
if (isset($_REQUEST['tgl'])){
	$tgl = $_REQUEST['tgl'];
	$tgl2 = $_REQUEST['tgl2'];
	$filter = " where date(tanggal) >= '$tgl1' and date(tanggal)<='$tgl2' ";

}else{
	$tgl = date('Y-m-d');
	$tgl2 = date('Y-m-d');
	$filter = "";

}
?>
<?php $form=$this->beginWidget('CActiveForm',array(
	'action'=>Yii::app()->createUrl('items/laporanrusak'),
	'method'=>'get',
)); ?>

<label>Tanggal 1</label>
<input name="Sales[date]" type="date" value="<?php echo $tgl; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<label>Tanggal 2</label>
<input name="Sales[date2]" type="date" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">


<?php echo CHtml::submitButton('Cari',array("class"=>"btn btn-primary")); ?>
<?php //echo CHtml::button('Export to CSV',array('id'=>'export')); ?>
<?php $this->endWidget(); ?>

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

<?php

$branch_id = Yii::app()->user->branch();

$sql  = "select * from barangkeluar 
where branch_id = '$branch_id'

$filter 

order by tanggal asc";

$model = Yii::app()->db->createCommand($sql)->queryAll();

?>
<style type="text/css">
	.table tr td{
		border: 1px solid rgb(163, 0, 0,1);
	}
</style>
<br>
<table class="table">
	<thead>
		
		<tr>
			<td>No</td>
			<td>Kode</td>
			<td>Tanggal</td>
			<td>Petugas</td>
			<td>Jenis Keluar</td>
			<td>Keluar Ke</td>
			<td>Keterangan</td>
			<td>Aksi</td>
			<!-- <td>Aksi</td> -->
		</tr>

	</thead>
	<tbody>
		<?php 
		$no=1;
		foreach ($model as $m): ?>
		<tr>
			<td ><?php echo $no;?></td>
			<td ><?php echo $m['kode_trx']; ?></td>
			<td ><?php echo date("d M Y H:i",strtotime($m['tanggal']) ); ?></td>
			<td ><?php echo $m['user']; ?></td>
			<td ><?php echo $m['jenis_keluar']; ?></td>
			<td ><?php echo Branch::model()->findByPk($m['branch_id'])->branch_name; ?></td>
			<td ><?php echo $m['keterangan']; ?></td>
			<td>

			<a href="<?php echo Yii::app()->createUrl('Barangkeluar/update',array("id"=>$m[id])) ?>" class="hapus">
				<i class="fa fa-check "></i>
			</a>

			</td>
			<tr class="data-rincian"   >
				<td colspan="8">
					<table style="font-size:12px;" class="table">

						<tr>
							<td>No</td>
							<td>Nama Item</td>
							<td>Satuan Item</td>
							<td>Qty</td>
							<td>Harga</td>
							<td>Aksi</td>
							
						</tr>
						<?php 
						$sql = "SELECT 
						si.id sid, i.item_name, si.jumlah qty,i.id iid, iss.harga, iss.nama_satuan
						FROM
						barangkeluar s, barangkeluar_detail si, items i, items_satuan iss 
						WHERE 
						si.head_id = s.id 
						and 
						iss.item_id = i.id
						AND
						i.id = si.kode

						AND s.id = '$m[id]'";
						$no2=1;
						foreach (Yii::app()->db->createCommand($sql)->queryAll() as $q ) {
						?>
						<tr>
							<td ><?php echo $no2; ?></td>
							<td >
							<a href="<?php echo Yii::app()->controller->createUrl('items/view',array("id"=>$q['iid'])) ?>" >
								<?php echo $q[item_name]  ?>
							</a>

							</td>
							<td>
								<?php 
								echo $q['nama_satuan'];
								?>
							</td>
							<td style="width:10%"><?php echo $q[qty]  ?></td>
							<td style="width:10%"><?php echo number_format($q[harga])  ?></td>
							<td>
							123
						<!-- 	<a href="<?php echo Yii::app()->createUrl('barangkeluardetail/update',array("id"=>$q['sid'])) ?>" class="hapus-detail" href="#">
								<i class="fa fa-pencil"></i>
							
							</a>
								&nbsp;
							<a href="<?php echo Yii::app()->createUrl('barangkeluardetail/delete',array("id"=>$q['sid'])) ?>" class=" fa fa-times " ></a> -->
				
							</td>
							
						</tr>
						<?php 
						$no2++;
						} ?>
					</table>
					<hr style="color: red;border:1px solid red">
				

				
				</td>
			</tr>
		</tr>
	<?php 
	$no++;
	endforeach; ?>
	</tbody>
</table>

<script>
$(document).ready(function(){
	// alert("123");
	$('.btn-detail').click(function(){
		// alert("123");
		var i = $(".btn-detail").index(this);
		$(".data-rincian").eq(i).toggleClass("show");


	});
	 $('.tanggal').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true

    });
$('.hapus.fa-times').click(function(e){
		// e.preventDefault();
		if (!confirm("Menghapus  , akan mempengaruhi stok, Yakin hapus  ?")){
			return false
		}else{
			return true;
		}
	});

	// $('.hapus-detail').click(function(e){
	// 	// e.preventDefault();
	// 	if (!confirm("Menghapus  , akan mempengaruhi stok, Yakin hapus  ?")){
	// 	// if (!confirm("Yakin hapus ?")){
	// 		return false
	// 	}else{
	// 		return true;
	// 	}
	// });
	


});
</script>
<div id="hasil">
</div>


