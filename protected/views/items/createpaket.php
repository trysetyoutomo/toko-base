<style type="text/css">
table#data tr td{
padding: 10px
} 	
</style>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>
<?php 
if ($_REQUEST['status']=="ubah"){ ?>
	<h1>Ubah Paket</h1>
<?php  }else{ ?>
	<h1>Paket baru</h1>
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

<a href="<?php echo Yii::app()->createUrl("paket/admin") ?>">
<button class="btn btn-primary">
	<i class="fa fa-cog"></i> Kelola Paket
</button>
</a>


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
			var nilai = parseInt($(this).attr("nilai")) ; 
			var jumlah = parseInt($(this).closest("tr").find("td .jumlah").val()) ;
			var harga = parseInt($(this).closest("tr").find("td .harga").val()) ;
			array_kode.push({"kode":nilai,"harga":harga,"jumlah":jumlah});
		});	

		$.ajax({
			type: 'GET',
			url: '<?php echo Yii::app()->createAbsoluteUrl("items/createpaket"); ?>',
			data: {"kode":array_kode,"nama":nama,"total":total,"status":status,'kode_paket':kode_paket},
			success:function(data){
				var json = JSON.parse(data);
				if (json.sukses==true){
					<?php if ($from=="admin"){ ?>
					window.location.assign("index.php?r=paket/admin");
					<?php } else if ($from=="site") {?>
					alert("Berhasil !");
					$("#tambah-paket-baru").hide();
					window.location.reload();
					// alert("Berhasil !");
					<?php } ?>
				}else{
					alert(JSON.stringify(json.err));
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
<!--
<p class="note">Fields with <span class="required">*</span> are required.</p>
-->
<table style="border:0px;width:700px;" cellpadding="20" id="data" border="0">
	<tr>
		
		<td>Kode Paket</td>
		<td>
			<?php
			if (empty($_REQUEST['id'])){
				$kode_paket =ItemsController::GetKodePaket();
			}else{
				$kode_paket =$_REQUEST['id']	;
			}
			?>
			<input readonly="" type="text" name="kode_paket" id="kode_paket" value="<?php echo $kode_paket ?>">
		</td>
	</tr>
	<tr>
		<td style="width: 100px;">Nama Paket</td>
		<td >
			<input type="hidden" id="id" value="<?php echo $_REQUEST["id"]?>" />
			<?php 
			if ($_REQUEST["status"]=="ubah"){
			echo "<body onload='getTgl()'>";
			echo Chtml::Textfield("paket[nama]","$namapaket",array("id"=>"namasimpan",'style'=>'width:100%')); 
			}
			else
			echo Chtml::Textfield("paket[nama]","",array("id"=>"namasimpan",'style'=>'width:100%')); 

			?>

		</td>
	</tr>
	<tr>
		<td>Harga Paket</td>
		<td>
			<input value="<?php echo $hargapaket ?>" type="text"  id="total" name="total">
		</td>
	</tr>
<tr>	
<?php// $ik = Kamar::model()->findByPk($model->id_kamar); ?>
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
function additem(){
	if ($('#nama_item').val()!=0 && $('#nama_item').val()!=null ){
	$.ajax({
		data : "id="+$('#nama_item').val(),
		url : "<?php echo Yii::app()->createAbsoluteUrl('items/getname') ?>",
		success : function(data){
			var da = JSON.parse(data);
			var harga = da.harga_jual;
			var name = da.nama;
			var ukuran = da.ukuran;
			var panjang = da.panjang;
			var ketebalan = da.ketebalan;


			var nama = $('#nama_item');
			// alert(nama.val());
			var stok = $('#stok');
			// alert(stok.val());
			var count = $('.pk[nilai="'+nama.val()+'"]').length;
			// if (count==0){

				$('#users tbody').append(
					"<tr class='baris'>" +
					// "<td></td>";
					"<td style='display:none' class='pk' nilai="+nama.val()+"  >" + nama.val() + "</td>" +
					"<td>" +name + "</td>" +
					// "<td><input class='kode' style='width:100%;padding:4px;' maxlength='15' type='text' value='0'/></td>" +
					"<td><input class='jumlah' style='width:30%;padding:4px;' maxlength='15' value='1' type='text'/></td>" +
					
					"<td><input class='harga' style='width:100%;padding:4px;' maxlength='15' value='"+harga+"' type='text'/></td>" +

						"<td>"+

						"<input readonly='' class='ukuran' style='width:100%;padding:4px;' maxlength='15' value='"+ukuran+"' type='text'/></td>" +
						"<td>"+
						
						"<input  readonly='' class='panjang' style='width:100%;padding:4px;' maxlength='15' value='"+panjang+"' type='text'/></td>" +
						"<td>"+
						
						"<input readonly='' class='ketebalan' style='width:100%;padding:4px;' maxlength='15' value='"+ketebalan+"' type='text'/></td>" +
			
					"<td class='hapus'>&nbsp;<i class='fa fa-times'></i > "+

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
<label for="nama" >Nama</label> 
			<?php 

			$dataa = CHtml::listdata(Items::model()->findAll("hapus = 0 "),'id','item_name'); 
			$array = array();
			foreach (Items::model()->findAll("hapus = 0 ") as $x) {
				$array[$x->id] = $x->barcode ." -  ".$x->item_name;
			}
			echo CHtml::dropDownList('nama_item', '1', $array,
				array(
					'class'=>'',
					// 'id'=>,
					'empty'=>'Pilih Item'
				) );?>		
			<button style="display: inline" type="button" onclick="additem()" class="btn btn-primary"><i class="fa fa-plus"></i></button>
    	
<table style="width:100%" id="users" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Nama Item</th>
			<th>Jumlah</th>
			<th>Harga</th>
			<th>Ukuran</th>
			<th>Panjang</th>
			<th>Ketebalan</th>
			<th>aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if ($_REQUEST['status']=="ubah"){ 
			$id = $_REQUEST['id'];
			foreach (PaketDetail::model()->findAll("paket_id = '$id' ") as $key => $value) {
				// echo "string";
				$items = Items::model()->findByPk($value->item_id);
			?>
			<tr class='baris'>
				<td style='display:none' class='pk' nilai='<?php echo $items->id ?>'  >   </td>
				<td><?php echo $items->item_name ?></td> 
				<td><input class='jumlah' style='width:20%;padding:4px;' maxlength='15' value='<?php echo $value->item_qty ?>' type='text'/>
	
				</td> 
				
				<td><input class='harga' style='width:100%;padding:4px;' maxlength='15' value='<?php echo $value->item_price ?>' type='text'/></td> 

					<td>

					<input readonly='' class='ukuran' style='width:100%;padding:4px;' maxlength='15' value='<?php echo $items->ukuran ?>' type='text'/></td> 
					<td>
					
					<input  readonly='' class='panjang' style='width:100%;padding:4px;' maxlength='15' value='<?php echo $items->panjang ?>' type='text'/></td> 
					<td>
					
					<input readonly='' class='ketebalan' style='width:100%;padding:4px;' maxlength='15' value='<?php echo $items->ketebalan ?>' type='text'/></td> 

				<td class='hapus'>&nbsp;<i class='fa fa-times'></i > 

				</td>  

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
<a href="<?php echo Yii::app()->createUrl('paket/admin') ?>"   class="btn btn-danger ">Batal</a>

</div>
<?php $this->endWidget(); ?>
</div>
</div>
