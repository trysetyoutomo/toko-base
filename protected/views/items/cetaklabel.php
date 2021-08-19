
<!-- jquery -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/jquery/dist/jquery.min.js"></script>
<!-- Datatables -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/dataTables.editor.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/datatables.net/js/dataTables.select.min.js"></script>
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
<br>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
	  body {
        height: 842px;
        width: 695px;
        /* to centre page on screen*/
        margin-left: auto;
        margin-right: auto;
    }
	#wrapper{
		padding: 10px;
	}
	#wrapper .item{
		font-weight:bolder;
		font-size: 11px;
		width: 30%;
		height: 110px;	
		border: 1px solid black;
		float: left;
		margin:10px;
		padding: 5px;
		margin:0 auto;
		text-align:center;

	}
	#wrapper .price{
		font-size: 25px;
		font-weight: bolder;
		/* margin-left: 25px; */
		/* margin-top: 10px; */
	}
	#wrapper .barcode{
		/* margin-top: 5px */
	}
	table tr td{
		padding: 10px;
	}
</style>
<?php 

$q = Items::model()->queryDataItems("");
$q = "SELECT * FROM ($q) as data order by nama asc";
$data = Yii::app()->db->createCommand($q)->queryAll();

if (isset($_REQUEST['cetak'])){
// echo "<pre>";
// print_r($_REQUEST);
// echo "</pre>";
?>
<body onload="print()">
	

<div id="wrapper">
<?php

// echo $q

?>
	<?php 
	// foreach ($data as $key => $value) {
		# code...
	$count = count($_REQUEST['selected']);
	for ($i=0; $i < $count ; $i++) { 
		$barcode = $_REQUEST[selected][$i];
		$md = ItemsSatuan::model()->find("barcode = '$barcode' ");
		$items = Items::model()->findByPk($md->item_id);
		for ($x=0; $x<$_REQUEST['jumlah-copy'] ; $x++) { 
			# code...
		?>
			<div class="item">
			<div class="item_name" style="height:35px">
				<?php echo ucfirst(strtoupper($items->item_name))." - ".$md->nama_satuan;?>
			</div>
			<div class="price">
			<img src="plugins/barcode/barcode.php?text=<?=$barcode?>&codetype=code128&print=true&size=30" />
			<?php 
			// echo "Rp. ".number_format(($md->harga));
			?>
			</div>
			<!-- <div class="barcode"><?php echo ucfirst(strtoupper($barcode));?></div> -->
			<!-- <div class="waktu"><?php echo date("d-m-y") ?></div> -->

				
			</div>
		<?php 
		}
	}
	
	?>
</div>
</body>
<?php 
}else{
?>
<style type="text/css">
	.nice{
		border-collapse: collapse;
	}
</style>
<form method="post">
<h1>Cetak Barcode</h1>
<hr>
<label>
	Jumlah Copy
	<input type="text" name="jumlah-copy" value="1">
	<input type="hidden" name="cetak" value="1">
	<input type="hidden" name="r" value="items/cetaklabel">
	<button type="submit" name="submit" value="Cetak" class="btn btn-primary">Cetak</button>
</label>
<br>
<hr>
<table width="100%" class="nice table table-bordered" cellpadding="20" id="datatable">
	<thead >
	<tr>
		<td>
			<input type="checkbox" name="cek-all" id="cek-all">
		</td>
		<td style="width:40px;">No</td>
		<td  style="width:50px;">Barcode</td>
		<td>Produk</td>
	</tr>
	</thead>
	<tbody>
		
	<?php 
	$no=1;
	foreach ($data as $key => $value) {
		echo "<tr>";
		echo "<td><input value='$value[barcode]' type='checkbox' name='selected[]'  class='selected' /></td>";
		echo "<td>$no</td>";
		echo "<td>$value[barcode]</td>";
		echo "<td>$value[nama]</td>";
		echo "</tr>";
		$no++;
	}
	?>
	</tbody>
</table>
</form>
<?php } ?>

<script type="text/javascript">
	$(document).ready(function(e){
	$("#datatable").DataTable({
		"language": {
			"search": "Scan Barcode "
		},
		"lengthMenu": [[1000000], ["Semua"]],
		//  initComplete(a,b,c){
		// 	   $("#datatable_filter input").val("");
		// 		$("#datatable_filter input").focus();
		// }
	});
      $(document).on("click","#cek-all",function(e){
      	// alert("123");
      	if ($(this).is(':checked')) {
      		// alert("23");
      		$(".selected").each(function(i,v){
      			$(this).prop("checked",true);

      		})
      	}else{
      		// alert($(".selected").length); 
      		$(".selected").each(function(i,v){
      			$(this).prop("checked",false);
      			// $(this).removeProp("checked");
      			// $(this).removeAttr("checked");

      		});
      	}
      })
	});
</script>

