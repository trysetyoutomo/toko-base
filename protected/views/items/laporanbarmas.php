<applet name="jzebra" code="jzebra.PrintApplet.class" archive="jZebra/jzebra/jzebra.jar" width="0" height="0">
    <param name="printer" value="zebra">
</applet> 

<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.css">
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-custom/jquery-ui.min.js"></script>

<div id="hasil"></div>
<style type="text/css">
	.dalem{
		width:100%;
	}
	.rincian tr td{
		padding: 5px;
	}
	 tr td{
		padding: 5px;
	}
</style>

<?php 
$this->renderPartial('application.views.site.main');
?>

<script type="text/javascript">
	$(document).ready(function(e){
		<?php 
		if ($_REQUEST['tipemasuk']=="0"){
			?>
			$("#cabang").show();
			<?php 
		}else{
			?>
			$("#supplier").show();
			<?php 
		}
		?>
		$(document).on("change","#tipemasuk",function(e){
			if ($(this).val()=="0"){
				$("#cabang").show();
				$("#supplier").hide();
			}else{
				$("#cabang").hide();
				$("#supplier").show();
			}
		});
	});
</script>
		
<h1>
<i class="fa fa-book"></i>
Laporan Pembelian</h1>
<hr>
<br>

<?php 
$filter = "";
if (isset($_REQUEST['tgl1'])){
	$tgl1 = $_REQUEST['tgl1'];
	$tgl2 = $_REQUEST['tgl2'];
	$filter .= " and date(tanggal) >= '$tgl1' and date(tanggal)<='$tgl2' ";

}else{
	// $filter = " ";
	$tgl1 = date('Y-m-d');
	$tgl2 = date('Y-m-d');

}
// var_dump($tgl1);
// var_dump($tgl2);
$isbayar = $_REQUEST['isbayar'];
if (isset($isbayar) && $isbayar!="semua"){
	if ($isbayar==1){	
		$filter .= " and  barangmasuk.bayar  >= barangmasuk.grand ";
	}else if ($isbayar==0){
		$filter .= " and  barangmasuk.bayar  < barangmasuk.grand ";
	}
}

$tanggal_jt = $_REQUEST['tanggal_jt'];
$tanggal_jt2 = $_REQUEST['tanggal_jt2'];
if (isset($tanggal_jt) && !empty($tanggal_jt) && isset($tanggal_jt2) && !empty($tanggal_jt2) ){
	$filter .= " and  date(barangmasuk.tanggal_jt) between '$tanggal_jt' and '$tanggal_jt2' ";
}

$faktur = $_REQUEST['faktur'];
if (isset($faktur) && !empty($faktur)){
	$filter .= " and  barangmasuk.faktur = '$faktur' ";
}

$kode_trx = $_REQUEST['kode_trx'];
if (isset($kode_trx) && !empty($kode_trx)){
	$filter .= " and  barangmasuk.kode_trx = '$kode_trx' ";
}

$status_aktif = $_REQUEST['tipemasuk'];
$cabang = $_REQUEST['cabang'];
$supplier = $_REQUEST['supplier'];
if ($status_aktif==""){
	$status_aktif = "0";
}
if (isset($status_aktif) && $status_aktif!="" ){
	// var_dump($status_aktif);
	if ($status_aktif=="1"){
		$filter .= " and  barangmasuk.status_aktif = '$status_aktif' and sumber = '$supplier' ";
	}
	 if ($status_aktif=="0"){
		$filter .= " and  barangmasuk.status_aktif = '0' and barangmasuk.sumber='$cabang'   ";
	}
}
// echo "<pre>";
// print_r($_REQUEST);
// echo "</pre>";
// echo $filter;
?>
<?php $form=$this->beginWidget('CActiveForm',array(
	'action'=>Yii::app()->createUrl('items/laporanmasuk'),
	'method'=>'get',
)); ?>
<table cellpadding="10" id="filter">
<tr>
<td>
	
<label>Tanggal Transaksi </label>
</td>	
<td>	
<input name="tgl1" type="text" value="<?php echo $tgl1; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<label>sampai  </label>
<input name="tgl2" type="text" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">

</td>	
</tr>

<tr style="display: none;">
<td>
	
<label>Status</label>
</td>
<td>
<select name="isbayar" style="display:inline;padding:2px"  >
	<!-- <option>Pilih Status</optgroup> -->
	<option <?php if($_REQUEST[isbayar]=='semua') echo "selected" ?> value="semua">SEMUA</option>
	<option <?php if($_REQUEST[isbayar]=='0') echo "selected" ?> value="0">BELUM LUNAS</option>
	<option <?php if($_REQUEST[isbayar]=='1') echo "selected" ?> value="1">LUNAS</option>
</select>
</td>
</tr>
<tr>
	<td>
		<label>Tanggal Jatuh Tempo</label>
	</td>
	<td>
		<input name="tanggal_jt" type="text" value="<?php echo $_REQUEST['tanggal_jt']; ?>" style="display:inline;padding:5px" name="tanggal_jt" class="tanggal">
		<label>sampai  </label>
			<input name="tanggal_jt2" type="text" value="<?php echo $_REQUEST['tanggal_jt2']; ?>" style="display:inline;padding:5px" name="tanggal_jt2" class="tanggal">

	</td>
</tr>
<tr>
	<td>
		<label>No Faktur</label>
	</td>
	<td>
			<input name="faktur" type="text" value="<?php echo $_REQUEST['faktur']; ?>" style="display:inline;padding:5px" name="faktur">

	</td>
</tr>
<tr>
	<td>
		<label>Kode TRX</label>
	</td>
	<td>
			<input  type="text" value="<?php echo $_REQUEST['kode_trx']; ?>" style="display:inline;padding:5px" name="kode_trx">

	</td>
</tr>
<tr>
	<td>
		<label>Sumber Masuk</label>
	</td>
	<td>
<select name="tipemasuk" id="tipemasuk" style="display:inline;padding:2px"  >
	<!-- <option>Pilih </option> -->
		
		<option <?php if($_REQUEST[tipemasuk]=='1') echo "selected" ?> value="1">SUPPLIER</option>
		<!-- <option <?php if($_REQUEST[tipemasuk]=='0') echo "selected" ?> value="0">CABANG</option> -->
</select>	
<select id="cabang" name="cabang" style="display:none;padding:2px">
		<?php 
		 // $branch = Yii::app()->user->branch();

		$data = Branch::model()->findAll("hapus=0");
		foreach ($data as $key => $value) { ?>
			<option 
			<?php if($_REQUEST[cabang]==$value->id ) echo "selected" ?>

			value="<?php echo $value->id ?>"
			>
			<?php echo $value->branch_name ?></option>
	 	<?php } ?>
</select>

<select id="supplier" name="supplier" style="display:none;padding:2px">
		<?php 
		 // $branch = Yii::app()->user->branch();
		$store_id = Yii::app()->user->store_id();
		
		$data = Supplier::model()->findAll("store_id = '{$store_id}' ");
		foreach ($data as $key => $value) { ?>
			<option 
			<?php if($_REQUEST[supplier]==$value->id ) echo "selected" ?>

			value="<?php echo $value->nama ?>"
			>
			<?php echo $value->nama ?></option>
	 	<?php } ?>
</select>
	</td>
</tr>
</table>



<!-- <div class="row"> -->

	<!-- </div> -->
	<!-- <input type="text" name="" > -->
			<?php echo CHtml::submitButton('Cari',array("class"=>"btn btn-primary")); ?>
			<?php //echo CHtml::button('Export to CSV',array('id'=>'export')); ?>
<?php $this->endWidget(); ?>

<?php
		
	// $username = Yii::app()->user->name;
	// $user = Users::model()->find('username=:un',array(':un'=>$username));
	// $idk = $user->level; 
	// $a = true;
	// if($idk < 5)
	// $a = true;
	// else
	// $a = false;
?>

<?php

$branch_id = Yii::app()->user->branch();
$sql  = "select barangmasuk.* , branch_name
from barangmasuk
left join branch b on b.id = barangmasuk.branch_id
inner join barangmasuk_detail bmd on bmd.head_id = barangmasuk.id

where b.id = '$branch_id' and barangmasuk.status_masuk = 1
 $filter 
group by barangmasuk.id
 order by tanggal desc

 ";

// echo $sql;
$model = Yii::app()->db->createCommand($sql)->queryAll();

?>
<style type="text/css">
	.table tr td{
		border: 1px solid rgb(163, 0, 0,1);
	}
</style>
<br>
<?php 
if (isset($_REQUEST['tipemasuk'])){
if (count($model)>0)
	echo "<h2>Hasil Pencarian : </h2>";
else
	echo "<h2 style='color:red'>Data tidak ditemukan : </h2>";
?>
<table class="table items">
	<thead>
		
	<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >

			<td>No</td>
			<td>Kode Transaksi</td>
			<!-- <td>Status Bayar</td> -->
			<td>Petugas</td>
			<td>Faktur</td>
			<td>Tanggal Transaksi</td>
			<td> Jatuh Tempo</td>
			<!-- <td>Keterangan</td> -->
			<td>Sumber</td>
			<!-- <td>Masuk ke</td> -->
			<td>Sub Total</td>
			<td>Diskon</td>
			<td>Grand Total</td>
			<td>Bayar</td>
			<td>Hutang</td>
			<td>Aksi</td>
		</tr>

	</thead>
	<tbody>
		<?php 
		$no=1;
		foreach ($model as $m): ?>
		<tr>
			<td ><?php echo $no;?></td>
			<td ><?php echo $m[kode_trx]; ?></td>
		<!-- 	<td ><?php 
			if($m['isbayar']=="0")
				echo "BELUM BAYAR";
			else
				echo "SUDAH BAYAR";

			 ?></td> -->
			<td ><?php echo $m[user]; ?></td>
			<td ><?php echo $m[faktur];?></td>
			<td style="font-size:10px;" ><?php echo date("d M Y H:i",strtotime($m[tanggal]) ); ?></td>
			<td ><?php echo $m[tanggal_jt]; ?></td>
			<!-- <td ><?php echo $m[keterangan]; ?></td> -->

			<td ><?php 
			if ($m['status_aktif']=="0"){	
				echo Branch::model()->findByPk($m[sumber])->branch_name;
			}else{
				echo $m[sumber];
			}
				// echo $m[sumber];

			 ?></td>
			<td ><?php echo number_format($m[subtotal]); ?></td>
			<td ><?php echo number_format($m[diskon]); ?></td>
			<td ><?php echo number_format($m[grand]); ?></td>
			<td ><?php echo number_format($m[bayar]); ?></td>

			<td ><?php 
			$sisa  = $m[grand]-$m[bayar];
			if ($sisa>0){	
				$sisa = $sisa;
				echo number_format($sisa); 
			}else{
				$sisa = 0;
				echo number_format(0); 
			}

			?></td>
			<td>
			<a href="<?php echo Yii::app()->controller->createUrl('hapusdetail',array("id"=>$m[id])) ?>" class="hapus">
			<i class="fa fa-times"></i>
			<!-- <img style="width:15px;" src="img/delete.ico"> -->
			</a>
			<a class="cetak" data-id='<?php echo $m['id'] ?>'>
				<i class="fa fa-print"></i>
			</a>
			<!--
			<a target="_blank" href="<?php echo Yii::app()->controller->createUrl('detailmasuk',array("id"=>$m[id])) ?>" >
			<i class="fa fa fa-print"></i>
			</a>
			-->
			<!-- <img style="width:15px;" src="img/delete.ico"> -->

			<!-- <i class="fa fa-list btn-detail"></i> -->
			
			<!-- <a href="<?php echo Yii::app()->createUrl('Barangmasuk/update',array("id"=>$m[id])) ?>" class="hapus">
				<i class="fa fa-edit "></i>
			</a> -->

			</td>
		</tr>
			<tr  >
				<td colspan="13"    >
					<button class="btn btn-primary show-detail">
					<i class="fa fa-eye"></i>
					Tampilkan Items</button>
					<table class="data-rincian" style="display: none;font-size:12px;width: 100%" >

						<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >

							<td>No</td>
							<td>Nama Item</td>
							<td>Jumlah</td>
							<td>Harga</td>
							<td>Total</td>
							<!-- <td>Satuan</td> -->
							<td>Aksi</td>
							
						</tr>
						<?php 
						$grandtotal = 0;
						// $sisa = 0;
						$sql = "SELECT 
						supplier_id,s.id, i.item_name, si.jumlah qty, si.id sid, si.harga, i.id iid,
						iss.nama_satuan nama_satuan, jumlah_satuan
						FROM
						barangmasuk s, barangmasuk_detail si, items i,items_satuan iss
						WHERE 
						iss.item_id = i.id 
						and
						si.head_id = s.id 
						and 
						iss.id = si.satuan
						AND
						i.id = si.kode


						AND s.id = '$m[id]'

						";
						$no2=1;
						// $sisaa = 0;
						foreach (Yii::app()->db->createCommand($sql)->queryAll() as $q ) {
						?>
						<tr>
							<td style="width:5%"><?php echo $no2; ?></td>
							<td style="width:20%">
							<a href="<?php echo Yii::app()->controller->createUrl('items/view',array("id"=>$q['iid'])) ?>" >
							<?php echo $q[item_name]." - ".$q['nama_satuan']  ?>
							</a>

							</td>
							<td style="width:10%"><?php 

							// echo $q[qty];
							// $satuanlist = ItemsController::getSatuanItems($q['iid'],$q['qty']);
							// echo $q['qty'];

							// echo "<br>";
							// $string = "Hello! 123 test this? 456. done? 100%";
							// $int = intval(preg_replace('/[^0-9]+/', '', $satuanlist), 10);
							$int =  $q['jumlah_satuan'];
							echo $int;
			

							  ?></td>
							<td style="width:10%">
								<?php echo number_format($q[harga]); ?>
							</td>
							<td><?php echo number_format($q[harga]*$int) ?></td>
							<!-- <td><?php echo $q['nama_satuan']?></td> -->
							<!-- <td><?php //echo Supplier::model()->findByPk($q[supplier_id])->nama  ?></td> -->
							<td style="width:10%">
								<a href="<?php echo Yii::app()->controller->createUrl('masukhapusdetail',array("id"=>$q['sid'])) ?>" class="hapus-detail" href="#">
										<i class="fa fa-times"></i>
									
								</a>
								<br>
							<a href="<?php echo Yii::app()->controller->createUrl('masukubahdetail',array("id"=>$q['sid'])) ?>" class="ubah-detail fa fa-edit " href="#"></a>
							</td>
							
						</tr>
						<?php 
						$no2++;

							$grandtotal += $q['harga']*$int;
						} ?>
					</table>
					<!-- <hr style="color: red;border:1px solid red"> -->
				
					<?php 
					// var_dump($sisa);
					?>
					
				</td>
			</tr>
		
	<?php 
	$no++;
	$total_qty +=$int;
	
	$sisaa += $sisa;

	endforeach; 

	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="12">
					Grand Total : <?php  echo number_format($grandtotal); ?>
					<br>
					Hutang : <?php  echo number_format($sisaa); ?>
		 	</td>
			<td></td>
		</tr>
	</tfoot>
</table>
<?php 
}
?>

<script>
$(document).ready(function(){
	 $('.tanggal').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true

    });
	$('.show-detail').click(function(){
		// alert("123");
		var i = $(".show-detail").index(this);
		$(".data-rincian").eq(i).toggleClass("show");


	});


	$('#export').click(function(){
		var tanggal = $('#Sales_date').val();
		$.ajax({
			url:'<?=$this->createUrl('sales/export')?>',
			data:'tanggal='+tanggal,
			success: function(data){
				$("#dialog_export").dialog("open");
				$("#hasil").html(data);
				// alert(data);
			},
			error: function(data){
				$("#hasil").html(data);
				// alert(data);
				// alert('data gagal di export');
			}
		});
	});
	$('.cetak').click(function(){
		var id = $(this).attr("data-id");
		
			$.ajax({
				url:'<?php echo Yii::app()->createUrl("sales/CetakMasuk") ?>',
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

	

	$('.hapus.fa-times').click(function(e){
		// e.preventDefault();
		if (!confirm("Menghapus barang , akan mempengaruhi stok, Yakin hapus  ?")){
			return false
		}else{
			return true;
		}
	});

	$('.hapus-detail').click(function(e){
		// e.preventDefault();
		if (!confirm("Menghapus barang , akan mempengaruhi stok, Yakin hapus  ?")){
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


