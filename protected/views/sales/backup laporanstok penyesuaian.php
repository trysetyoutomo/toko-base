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
</style>
<!--
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/app/SalesItems/main.js"></script>
-->


<h1>
<i class="fa fa-book"></i>

Penyesuaian Stok</h1>
<br>
<div class="col-sm-8">
	<form action="">
	<input type="hidden" name="r" value="sales/laporanstok">
	<label>
	Cari Nama 	</label>
	<input type="text" name="cari" value="<?php echo $_REQUEST['cari'] ?>">
	<input type="submit"  value="cari" class="btn btn-primary">
	</form>
</div>

<?php 
$filter = " and 1=1 and i.hapus = 0 ";
if (isset($_REQUEST['tgl'])){
	$tgl = $_REQUEST['tgl'];
	$tgl2 = $_REQUEST['tgl2'];
	$filter .= " and  date(tanggal) >= '$tgl' and date(tanggal)<='$tgl2' ";

}else{
	$tgl = date('Y-m-d');
	$tgl2 = date('Y-m-d');
	$filter .= "";

}
?>
<?php $form=$this->beginWidget('CActiveForm',array(
	'action'=>Yii::app()->createUrl('sales/laporanstok'),
	'method'=>'get',
)); ?>

<div style="display: none;">
	
<label>Tanggal 1</label>
<input name="Sales[date]" type="date" value="<?php echo $tgl; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<label>Tanggal 2</label>
<input name="Sales[date2]" type="date" value="<?php echo $tgl2; ?>" style="display:inline;padding:5px" name="tanggal" class="tanggal">
<input type="text" name="key" placeholder="Kata Kunci" value="<?php echo $_REQUEST[key] ?>" >





<?php echo CHtml::submitButton('Cari',array('class'=>'btn btn-primary')); ?>
<?php $this->endWidget(); ?>

</div>
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

// if (isset($_REQUEST['key'])){
// 	$key = $_REQUEST['key'];
// 	$filter.= " and item_name like  '%$key%' ";
// }


if (isset($_REQUEST['key'])){
	$key = $_REQUEST['key'];
	$filter.= " and item_name like  '%$key%' ";
}

if (isset($_REQUEST['cari'])){
	$key = $_REQUEST['cari'];
	$filter.= " and item_name like  '%$key%' ";
}
// $sql  = "select 
// m.nama nama_subkategori,
// concat(i.item_name,' - ',iss.nama_satuan,'') as item_name,
// c.category as nama_kategori,
// i.hapus hapus, iss.id as satuan_id, i.id id, i.barcode barcode

//  from items i inner join items_satuan iss
//   on iss.item_id = i.id and i.is_stockable = 1 
//   left join categories as c on c.id = i.category_id
//   left join motif m on m.category_id = c.id and m.id = i.motif

//   $filter 
//   and (iss.is_default = 1 )
//   group by iss.id
//   order by c.category, m.id  asc

//   ";
$sql = ItemsController::queryStok($filter);
  // echo $sql;
$model = Yii::app()->db->createCommand($sql)->queryAll();

?>
<style type="text/css">
	.table tr td{
		border: 1px solid rgb(163, 0, 0,1);
	}
</style>
<br>
<table class="table items " style="width: 70%">
	<thead>
		
	<tr style="color:white;font-weight: bolder;background-color: rgba(42, 63, 84,1)" >

			<td>No</td>
			<td>Kategori</td>
			<td>Sub Kategori</td>
			<td>Nama Barang</td>
			<td>Letak</td>
<!-- 			
			<td>Panjang</td>
			<td>Ketebalan</td> -->
			<td>Stok Saat ini</td>
			<td>Stok Sebenarnya</td>
			
			<!-- <td>Aksi</td> -->
			<!-- <td>Aksi</td> -->
		</tr>

	</thead>
	<tbody>
		<?php 
		$no=1;
		foreach ($model as $m): ?>
		<tr>
			<td style="width:5%"><?php echo $no;?></td>
			<td ><?php echo $m['nama_kategori']; ?></td>
			<td ><?php echo $m['nama_subkategori']; ?></td>
			<td >
				<a target="_blank" 
	href="<?php echo Yii::app()->createUrl("ItemsSatuan/kartu",array("id"=>$m['id'],'satuan_id'=>$m['satuan_id'])) ?>">
				<?php echo $m['item_name']; ?>
				</a>
			</td>
			<td>
				<?php 
				
					echo $m['nama_letak']; 
				

				?>
			</td>
		
		

			<?php 
			$stok = ItemsController::getStok($m['id'],$m['satuan_id'],Yii::app()->user->branch());
			$harga = round(ItemsController::getAverage($m[id],$m['satuan_id'],Yii::app()->user->branch())) ;
			?>
			<td  class="set-stok-old" ><?php echo $stok; ?></td>
			<td   align="center">
				<input type="text" name="stok_real" value="<?php echo $stok; ?>" 
				class="stok_real" style="width: 70px">
				<button harga='<?php echo $harga; ?>'  stok-before='<?php echo $stok; ?>'  class="set-stok btn btn-primary" 
				item-id="<?php echo $m[id]; ?>"
				satuan-id="<?php echo $m[satuan_id]; ?>"

				 >Atur Stok</button>
			</td>
			<!-- <td  align="right"><?php echo number_format($m[total]); ?></td> -->
			<td style="display: none;">
			<a href="<?php echo Yii::app()->controller->createUrl('pengeluaranhapus',array("id"=>$m[id])) ?>" class="hapus">
				<i class="fa fa-times"></i>
			</a>
			<a href="<?php echo Yii::app()->controller->createUrl('items/ubah_pengeluaran',array("id"=>$m[id])) ?>" class="update">
				<i class="fa fa-pencil"></i>
			</a>
			</td>
		
		</tr>
	<?php 
	$total = $total +$m[total];
	$no++;
	endforeach; ?>
	<!-- <tr>
		<td colspan="8" style="text-align:right">
			<?php 
			echo number_format("$total");
			?>
		</td>
	</tr> -->
	</tbody>
</table>
<script>
$(document).ready(function(){
	$('#cetak-all-stok').click(function(){
		var id = $("#cabang").val();
		
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
	 $('.tanggal').datepicker(
        { 
            dateFormat: 'yy-mm-dd',
            changeYear: true,
            changeMonth: true

    });
	$('.set-stok').click(function(e){
		var i = $(".set-stok").index(this);
		// alert(i);
	   var before = parseFloat($(this).attr("stok-before")) ;
	   var skrg =  parseFloat($(".stok_real").eq(i).val() );
	   // var satuan_id =  parseInt($(".satuan_id").eq(i).val() );
	   var harga =  parseFloat($(this).attr("harga"));
	   var satuan_id =  $(this).attr("satuan-id");
	   var id = $(this).attr("item-id");
	   // var harga = $(this).attr("item-id");
	   // alert(before);
	   // alert(skrg);
	   // alert(id);
	   if (before!=skrg){
	   		// alert(skrg);
	   		// alert(before);
	   		if (skrg>before){	
			   var harga = parseFloat(prompt("Masukan Harga Beli (Harga Beli Terakhir)",harga,harga) );
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
	          data : "before="+before+"&skrg="+skrg+"&id="+id+"&satuan_id="+satuan_id+"&harga="+harga,
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


<!-- <button id="cetak-all-stok" class="btn btn-primary" name="cetak-all-stok">
	<i class="fa fa-print"></i>
		Cetak
</button>
 -->