<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a></li>
  <li class="breadcrumb-item active">
  	
  	 <a style="text-decoration: underline;" href="<?php echo Yii::app()->controller->createUrl("items/view",array("id"=>$_REQUEST[id])) ?>">
	<?php 
	echo Items::model()->findByPk($_REQUEST['id'])->item_name;
	?>
	</a>
  </li>
  <li class="breadcrumb-item active">Bahan Baku </li>
</ol>

<style type="text/css">
table#data tr td{
padding: 10px
} 	
</style>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>
<?php 
$item = Items::model()->findByPk($_REQUEST['id']);

if ($_REQUEST['status']=="ubah"){ ?>
	<h2> <i class="fa fa-book"></i> Ubah Bahan Baku <?php echo $item->item_name ?> </h1>
<?php  }else{ ?>
	<h1> <i class="fa fa-book"></i>
Bahan Baku

	</h2>

<?php  } ?>
<hr>
<?php if ($from=="site"): ?>
<style type="text/css">
	.close-btn{
		position: absolute;
		right: 20px;
		top: 20px;
	}
</style>
<script type="text/javascript">
	$(document).on('click', '.close-btn', function(e) {
		$("#tambah-paket-baru").hide();
	});
</script>
<div class="close-btn">
	<i class="fa fa-times fa-2x"></i>
</div>
<?php endif; ?>


<script type="text/javascript">
$(document).ready(function(e){

	$("#nama_item").select2();
	$(document).on('click', '.hapus', function(e) {
		var index = $('.hapus').index(this);
		$('.baris').eq(index).remove();
	});

});

function validasi(){
	var nama = $("#nama").val();
	var menu = $("#menu").val();
	var total = $("#total").val();
	// alert(menu);
	var pesan ="";
}

function simpan(){
	var pesan = "";
	var nama = $("#namasimpan").val();
	var menu = $("#menu").val();
	var kode_paket = $("#kode_paket").val();
	// alert(menu);

	var status = "";
	<?php  if ($_REQUEST['status']=="ubah") { ?>
	status = "ubah";
	<?php }else{ ?>
	status = "simpan";
	<?php } ?>

	var total = $("#total").val();
	if (nama == "")
	pesan += "nama kosong \n";
	// if (length(menu)==0)
	// pesan += "menu kosong \n";
	if (total =="")
	pesan += "total kosong \n";

	if (pesan!=""){
		alert(pesan);
	return false
	}else{
		
	if ($(".baris").length==0){
		alert("Silahkan isi Item untuk Paket yang dibuat");
		return false;
	} 
		var array_kode = [];
		$('.pk').each(function (index, value){
			var nilai = $(this).attr("nilai") ; 
			var jumlah = $(this).closest("tr").find("td .jumlah").val() ;
			jumlah = jumlah.replace(/,/g, '.');
			jumlah = parseFloat(jumlah);
			jumlah = Math.round(jumlah * 100) / 100;

			var harga = parseInt($(this).closest("tr").find(".harga").html()) ;
			// alert(harga);
			// var satuan = $(this).closest("tr").html();
			var satuan = $(this).closest("tr").find(".satuan").val();
			// alert(satuan);
			array_kode.push({"kode":nilai,"harga":harga,"jumlah":jumlah,"satuan":satuan});
		});	
		$.ajax({
			type: 'GET',
			url: '<?php echo Yii::app()->createAbsoluteUrl("itemsSource/create"); ?>',
			data: {"item_id":"<?php echo $_REQUEST['id'] ?>","kode":array_kode,"nama":nama,"total":total,"status":status,'kode_paket':kode_paket},
			success:function(data){
				var json = JSON.parse(data);
				if (json.sukses==true){
					<?php if ($from=="admin"){ ?>
					window.location.assign("index.php?r=items/view&id=<?php echo $_REQUEST['id'] ?>");
					<?php } else if ($from=="site") {?>
					alert("Berhasil !");
					$("#tambah-paket-baru").hide();
					// window.location.reload();
					// alert("Berhasil !");
					<?php } ?>
				}else{
					alert("Terjadi kesalahan : "+JSON.stringify(json.err));
				}
			},
			error: function(err){
				alert(JSON.stringify(err));
			},
			dataType:'html'
		});
	}
}
function rubah(){
var nama = $("#namaubah").val();
var menu = $("#menu").val();
var total = $("#total").val();
var id = $("#id").val();
var pesan = "";
if (nama == "")
pesan += "nama kosong \n";
// if (length(menu)==0)
// pesan += "menu kosong \n";
if (total =="")
pesan += "total kosong \n";

if (pesan!=""){
alert(pesan);
return false
}else{
$.ajax({
type: 'GET',
url: '<?php echo Yii::app()->createAbsoluteUrl("items/createpaket"); ?>',
data:'id='+id+'&nama='+nama+'&menu='+menu+'&total='+total,
success:function(data){
alert('sukses');
window.location.assign("index.php?r=items/adminpaket");
},
dataType:'html'
});
}
}

</script>
<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="mws-panel grid_8">

<div class="mws-panel-body no-padding">
<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'items-form',
'enableAjaxValidation'=>false,
'htmlOptions' => array(
'class' => 'mws-form',
'onsubmit' => 'return validasi()',
'method' => 'GET',

)
)); ?>
<?php
$nilai = Categories::model()->findAll();
$data = CHtml::listData($nilai,'id','category');

$nilai2 = Outlet::model()->findAll();
$data2 = CHtml::listData($nilai2,'kode_outlet','nama_outlet');


?>
<div class="mws-form-inline">

<td colspan="2">
<script>
function getTgl(){
	var menu = $('#menu').val();
	$.ajax({
		type: 'GET',
		url: '<?php echo Yii::app()->createAbsoluteUrl("sales/getharga"); ?>',
		data:'id='+menu,
		success:function(data){
			$("#total").val(data);
		},
		dataType:'html'
	});
}
// function GetSatuanByID(id){
	// var th = this;
	// alert(th.index(this));
	// var menu = $('#menu').val();
	$(document).on('change', '.satuan', function(e) {
		var th = $(this);
		$.ajax({
			type: 'GET',
			url: '<?php echo Yii::app()->createAbsoluteUrl("ItemsSatuan/GetSatuanByID"); ?>',
			data:'id='+th.val(),
			success:function(data){
				var hrg = data.harga_beli;
				// th.index()
				// alert(hrg);
				th.closest("tr").find(".harga").html(hrg);
				// alert(data);

				// $("#total").val(data);
			},
			dataType:'json'
		});
	});
function additem(){
	if ($('#nama_item').val()!=0 && $('#nama_item').val()!=null ){
	$.ajax({
		data : "id="+$('#nama_item').val(),
		url : "<?php echo Yii::app()->createAbsoluteUrl('items/check') ?>",
		success : function(data){
			// alert(data);	
			var da = JSON.parse(data);
			var harga = da.harga_jual;
			var name = da.item_name;
			var ukuran = da.ukuran;
			var panjang = da.panjang;
			var ketebalan = da.ketebalan;
			var harga_beli = da.harga_beli;
			var kode = da.kode;


			var nama = $('#nama_item');
			// alert(nama.val());
			var stok = $('#stok');
			// alert(stok.val());
			var count = $('.pk[nilai="'+nama.val()+'"]').length;
			// if (count==0){

				var string = " ";
				// alert(JSON.stringify(da.satuan_detail));
				$.each(da.satuan_detail,function(i,v){
				// 	if (v.isdefault==1){

						// string+= "<option selected value='"+da.satuan_id+"'>"+da.nama_satuan+"</option>";
				if (v.id==da.nama_satuan_id){
					var selected = "selected";
				}else{
					var selected = " ";
				}
						string+= "<option "+selected+"  value='"+v.id+"'>"+v.nama_satuan+"</option>";
				// 	}else{
				// 		string+= "<option value='"+v.id+"'>"+v.nama_satuan+"</option>";
				// 	}
				});
				
				var select = "<select  style='width:100%' class='satuan'>"+string+"</select>";

				$('#users tbody').append(
					"<tr class='baris'>" +
					// "<td></td>";
					"<td style='display:none' class='pk' nilai="+nama.val()+"  >" + nama.val() + "</td>" +
					"<td>" +kode + "</td>" +
					"<td>" +name + "</td>" +
					"<td><input class='jumlah' style='padding:4px;' maxlength='15'  value='1' type='number'/></td>" +
					
					"<td>"+select+"</td>" +
					"<td align='right' class='harga'>" +harga_beli + "</td>" +
					"<td >&nbsp;<i class='fa fa-times hapus'></i > "+

					"</td> " +

					"</tr>"
				);
		    $("#nama").select2("open");

		}
	});
	}else{
		alert('tidak boleh kosong');
		$('#stok').val(1);
	}
}
</script>
<?php 
$data = Items::model()->findAll("hapus = 0");
$li = CHtml::listData($data,'id','item_name');
// echo "<pre>";
// print_r($li);
// echo "</pre>";

?>
<label for="nama" >Nama Item </label> 
			<?php 

			$dataa = CHtml::listdata(Items::model()->findAll("hapus = 0  and   is_bahan = 1 "),'id','item_name'); 
			$array = array();
			foreach (Items::model()->findAll("hapus = 0 and id<> '$_REQUEST[id]' and is_bahan=1 ") as $x) {
				$array[$x->id] = $x->barcode ."-".$x->item_name;
			}
			echo CHtml::dropDownList('nama_item', '1', Items::model()->data_items("BAHAN"),
				array(
					'class'=>'',
					// 'id'=>,
					'empty'=>'Pilih Item'
				) );?>		
			<button style="display: inline" type="button" onclick="additem()" class="btn btn-primary"><i class="fa fa-plus"></i></button>
    	
<table style="width:100%" id="users" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Barcode</th>
			<th>Nama Item</th>
			<th>Jumlah</th>
			<th>Satuan</th>
			<th>Harga Satuan</th>
			<th>aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$dd = itemsSource::model()->findAll("item_menu = '$_REQUEST[id]' order by id asc ");
		if ($_REQUEST['status']=="ubah"){ 
			// echo "kkkk";
			$id = $_REQUEST['id'];
			// echo "ok";
			foreach ($dd as $key => $value) {
				$itemsSatuan = ItemsSatuan::model()->find("id = '$value->satuan' ");
				$items = Items::model()->findByPk($value->item_id);
			?>
			<tr class='baris'>
				<td style='display:none' class='pk' nilai='<?php echo $itemsSatuan->barcode."-".$value->satuan ?>'  >   </td>
				<td>
					<?php echo $itemsSatuan->barcode ?>
				</td>
				<td>
				<a href="<?php echo Yii::app()->createUrl("Items/view",array("id"=>$value->item_id)) ?>">
				<?php echo $items->item_name ?>
				</a>
					

				</td> 
				<td><input class='jumlah' style='padding:4px;' step='.01' value='<?php echo $value->jumlah ?>' type='number'/>
	
				</td> 
				
				<td>
					<select  class="satuan" style="width: 100%">
						<?php 
						foreach (ItemsSatuan::model()->findAll("item_id = '$value->item_id' ") as $key2 => $value2) {
							if ($value->satuan==$value2->id){	
								$select = "selected";
							}else{
								$select = " ";
							}
							echo "<option $select value='$value2->id'>$value2->nama_satuan</option>";

						}
						?>
					</select>
				</td> 
				<td class="harga" align="right">
					<?php echo $value->harga ?>
				</td>
				<td><i class='fa fa-times hapus'></i> </td>  

			</tr>

		<?php
			} 
		}
	
		?>
	</tbody>
</table>

</div>
<div class="mws-button-row">
<?php if ($_REQUEST["status"]=="ubah"){ ?>
<input type="button" onclick="simpan()" value="Rubah" class="btn btn-primary">
<?php }else{ ?>
<input type="button" onclick="simpan()" value="Simpan" class="	btn btn-primary">
<?php } ?>

<?php if ($from=="admin") ?>
<!-- <a href="<?php echo Yii::app()->createUrl('paket/admin') ?>"   class="btn btn-danger ">Batal</a> -->

</div>
<?php $this->endWidget(); ?>
</div>
</div>
