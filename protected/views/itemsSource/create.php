<!-- animation css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>

<?php 

$produkName = Items::model()->findByPk($_REQUEST['id'])->item_name;
?>
<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="<?php echo Yii::app()->createUrl('Items/admin'); ?>">Mengelola Items</a></li>
  <li class="breadcrumb-item active">
  	
  	 <a style="text-decoration: underline;" href="<?php echo Yii::app()->controller->createUrl("items/view",array("id"=>$_REQUEST[id])) ?>">
	<?php 
	echo $produkName
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
<?php 
$item = Items::model()->findByPk($_REQUEST['id']);
$itemsSatuanUtama = ItemsSatuan::model()->find("item_id = '$_REQUEST[id]' and is_default = 1 ");
// echo  $itemsSatuanUtama->harga_beli;


if ($_REQUEST['status']=="ubah"){ ?>
	<h1> <i class="fa fa-book"></i> Bahan Baku <b><?php echo $item->item_name ?></b> </h1>
	<?php  }else{ ?>
		<h1> <i class="fa fa-book"></i> Bahan Baku <b><?php echo $item->item_name ?></b> </h1>


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

function initSelect2(){
	$("#nama_item").select2({
		closeOnSelect : true,
		escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
		minimumInputLength: 2,
		minimumResultsForSearch : 5
	});
}
$(document).ready(function(e){
	initSelect2();
	$(document).on('click', '.hapus', function(e) {
		var index = $('.hapus').index(this);
		$('.baris').eq(index).remove();
		getTotal();
	});

	$(document).on('input', '.jumlah', function(e) {
		let hargasatuan = numeral($(this).closest(".baris").find(".hargasatuan").html()).value();
		// alert(hargasatuan);
		let jumlah = $(this).val();
		let hpp = Math.round(parseFloat(hargasatuan) * parseFloat(jumlah));
		hpp =  numeral(hpp).format('0,0')
		$(this).closest(".baris").find(".harga").html(hpp);
		getTotal();
	});

	<?php  if ($_REQUEST['status']=="ubah") { ?>
		<?php } ?>
		setTimeout(() => {
			$(".jumlah").trigger("input");
		}, 500);

	

});

function getTotal(){
	let hargaHPPsaatIni = <?php echo $itemsSatuanUtama->harga_beli ?>; 
	let totalHarga = 0;
	$('.harga').each(function(e){
		totalHarga += numeral($(this).html()).value();
	});

	$("#total-estimasi").html(numeral(totalHarga).format("0,0"));
	if (totalHarga != hargaHPPsaatIni){
		$("#wrapper-ubah-harga").show();
	}else{
		$("#wrapper-ubah-harga").hide();
	}
}

function validasi(){
	var nama = $("#nama").val();
	var menu = $("#menu").val();
	var total = $("#total").val();
	// alert(menu);
	var pesan ="";
}

function simpan(){
	var pesan = "";
	var ubah_harga = $("#ubah-harga").prop("checked");
	var harga_estimasi = numeral($("#total-estimasi").html()).value();
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
			var satuan = $(this).closest("tr").find(".satuan").val();
			array_kode.push({"kode":nilai,"harga":harga,"jumlah":jumlah,"satuan":satuan});
		});	
		$.ajax({
		type: 'GET',
			url: '<?php echo Yii::app()->createAbsoluteUrl("itemsSource/create"); ?>',
			data: {"item_id":"<?php echo $_REQUEST['id'] ?>","kode":array_kode,"nama":nama,"total":total,"status":status,'kode_paket':kode_paket,"ubah_harga":ubah_harga,"harga_estimasi":harga_estimasi},
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

function resetSelect(){
	$("#nama_item").val("");
	initSelect2();
}
// function GetSatuanByID(id){
	// var th = this;
	// alert(th.index(this));
	// var menu = $('#menu').val();
	$(document).on('change', '#nama_item', function(e) {
		additem();
	})
	$(document).on('change', '.satuan', function(e) {
		var th = $(this);
		$.ajax({
			type: 'GET',
			url: '<?php echo Yii::app()->createAbsoluteUrl("ItemsSatuan/GetSatuanByID"); ?>',
			data:'id='+th.val(),
			success:function(data){
				var hrg = data.harga_beli;
				let  satuan  = data.satuan; // satuan ke gramasi utama
				// th.index()
				hrg = numeral(hrg).format("0,0");
				th.closest("tr").find(".hargasatuan").html(hrg);
				$(".jumlah").trigger("input");
			},
			dataType:'json'
		});
	});
function empty(e) {
  switch (e) {
    case "":
    case 0:
    case "0":
    case null:
    case false:
    case undefined:
      return true;
    default:
      return false;
  }
}
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
				
				var select = "<select  style='width:100%' class='satuan form-control'>"+string+"</select>";
				// var listCodes =


				let listCodes = [];
				$('.baris').each(function(){
					let barcode = $(this).find(".pk").attr("nilai");
					listCodes.push(barcode);
					// let singleCode = "";
					// if (!empty(barcode)){
					// 	singleCode = barcode.split("##")[0];
					// }
				});

				if (listCodes.includes(nama.val())){
					// alert('123');
					$("[nilai='"+ nama.val() +"']").closest(".baris").addClass("animate__animated animate__flash");
					
					setTimeout(() => {
						$("[nilai='"+ nama.val() +"']").closest(".baris").removeClass("animate__animated animate__flash");
						resetSelect();
					}, 1000);

					return;
				}


				// if (kode == )


				$('#users tbody').append(
					"<tr class='baris'>" +
					// "<td></td>";
					"<td style='display:none' class='pk' nilai="+nama.val()+"  >" + nama.val() + "</td>" +
					"<td>" +kode + "</td>" +
					"<td>" +name + "</td>" +
					"<td><input class='jumlah form-control' style='padding:4px;' maxlength='15'  value='1' type='number'/></td>" +
					"<td>"+select+"</td>" +
					"<td align='right' class='hargasatuan'>" + numeral(harga_beli).format("0,0") + "</td>" +
					"<td align='right' class='harga'>0</td>" +
					"<td >&nbsp;<i class='fa fa-times hapus'></i > "+

					"</td> " +

					"</tr>"
				);
				$(".jumlah").trigger("input");
				getTotal();
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
					'style'=>'width:450px',
					// 'id'=>,
					'empty'=>'Pilih Item'
				) );?>		
			<button style="display: inline" type="button" onclick="additem()" class="btn btn-primary"><i class="fa fa-plus"></i></button>
    	
<table style="width:100%" id="users" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Barcode</th>
			<th>Nama Bahan </th>
			<th>Total Bahan</th>
			<th>Satuan Bahan</th>
			<th>Harga Satuan Bahan</th>
			<th>Estimasi HPP</th>
			<th>aksi</th>
		</tr>
	</thead>
	<tbody>
		<?php 

			$dd = itemsSource::model()->findAll("item_menu = '$_REQUEST[id]' order by id asc ");
			// item satuan utama pada menu ini 
			
		// if ($_REQUEST['status']=="ubah"){ 
			// echo "kkkk";
			$id = $_REQUEST['id'];
			$totalestimasi = 0;
			// echo "ok";
			foreach ($dd as $key => $value) {
				$itemsSatuan = ItemsSatuan::model()->find("id = '$value->satuan' ");
				$items = Items::model()->findByPk($value->item_id);	

			?>
			<tr class='baris'>
				<td style='display:none' class='pk' nilai='<?php echo $itemsSatuan->barcode."##".$value->satuan ?>'  >   </td>
				<td>
					<?php echo $itemsSatuan->barcode ?>
				</td>
				<td>
				<a href="<?php echo Yii::app()->createUrl("Items/view",array("id"=>$value->item_id)) ?>">
				<?php echo $items->item_name ?>
				</a>
					

				</td> 
				<td><input class='jumlah form-control' style='padding:4px;' step='.01' value='<?php echo $value->jumlah ?>' type='number' maxlength="3"/>
	
				</td> 
				
				<td>
					<select  class="satuan form-control" style="width: 100%">
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
				<td class="hargasatuan" style="text-align:right"><?php echo  number_format($itemsSatuan->harga_beli) ?></td>
				<td class="harga" style="text-align:right"><?php echo $value->harga ?></td>
				<td><i class='fa fa-times hapus'></i> </td>  

			</tr>

		<?php
		// $totalestimasi += 
			} 
		// }
	
		?>
	</tbody>
	<tfoot>
		<td colspan="5" style="text-align:right">Total Estimasi <b>Menu/Produk</b></td>
		<td style="text-align: right;"><span class='badge badge-success' style="background-color: green;" id="total-estimasi"></span>
		</td>
		<td style="width: 100px;">
			<div id="wrapper-ubah-harga" style="display: none;">
				<label><input style="width: auto;" type="checkbox" id="ubah-harga" name="ubah-harga" />&nbsp;Ubah Harga Pokok <br/> <small>(harga estimasi berbeda dengan harga saat ini, centang untuk merubah harga saat ini)</small></label>
			</div>
		</td>
	</tfoot>
</table>

</div>
<div class="mws-button-row">
<?php if ($_REQUEST["status"]=="ubah"){ ?>
<input type="button" onclick="simpan()" value="Simpan" class="btn btn-primary">
<?php }else{ ?>
<input type="button" onclick="simpan()" value="Simpan" class="	btn btn-primary">
<?php } ?>

<?php if ($from=="admin") ?>
<!-- <a href="<?php echo Yii::app()->createUrl('paket/admin') ?>"   class="btn btn-danger ">Batal</a> -->

</div>
<?php $this->endWidget(); ?>
</div>
</div>
