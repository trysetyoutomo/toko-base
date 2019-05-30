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
		font-size: 11px;
		width: 30%;
		height: 120px;
		border: 1px solid black;
		float: left;
		margin:10px;
		padding: 10px;
		margin:0 auto;

	}
	#wrapper .price{
		font-size: 25px;
		font-weight: bolder;
		margin-left: 25px;
		margin-top: 10px;
	}
	#wrapper .barcode{
		margin-top: 5px
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
			<?php
			echo ucfirst(strtoupper($items->item_name))." - ".$md->nama_satuan;
			?>
			<div class="price">
			<?php 
			echo "Rp. ".number_format(($md->harga));
			?>
			</div>
			<div class="barcode"><?php echo ucfirst(strtoupper($barcode));?></div>
			<div class="waktu"><?php echo date("d-m-y") ?></div>

				
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
<label>
	Jumlah Copy
	<input type="text" name="jumlah-copy" value="1">
	<input type="hidden" name="cetak" value="1">
	<input type="hidden" name="r" value="items/cetaklabel">
	<button type="submit" name="submit" value="Cetak" class="btn btn-primary">Cetak</button>
</label>
<br>
<table width="600px" border="1" class="nice" cellpadding="20">
	<thead style="background: blue;color:white">
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

<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(e){
      $(document).on("click","#cek-all",function(e){
      	// alert("123");
      	if ($(this).is(':checked')) {
      		// alert("23");
      		$(".selected").each(function(i,v){
      			$(this).attr("checked",true);

      		})
      	}else{
      		// alert("456");
      		$(".selected").each(function(i,v){
      			$(this).removeAttr("checked");

      		});
      	}
      })
	});
</script>

