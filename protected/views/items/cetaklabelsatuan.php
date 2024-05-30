<style>
body{
    padding:2rem 1rem 1rem 3rem;
    margin:0 auto;
}

</style>
<script>
window.onload = function() {
    window.print();
    window.onafterprint = function() {
        window.close();
    };
};
</script>
<body  style="margin:0 auto" >
<link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

	<?php 
    // $barcode = "0690000000001";
    $jumlahcopy = 5;
	// foreach ($data as $key => $value) {
		# code...
	$count = 1;
    // var_dump($count);
	for ($i=0; $i < $count ; $i++) { 
		// $barcode = $_REQUEST[selected][$i];
		$md = ItemsSatuan::model()->find("barcode = '$barcode' ");
		$items = Items::model()->findByPk($md->item_id);
		for ($x=0; $x < $jumlahLabel ; $x++) { 
			# code...
		?>
			<div style="border: 1px solid black;width:210px;display:inline-block;margin-right:1.5rem" class="row" >
                <div class="row">
                    <div class="col-xs-12" style="margin-left:0.5rem">
                        <?php echo ucfirst(strtoupper($items->item_name))." - ".$md->nama_satuan;?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <h2 style="margin-top:1rem;margin-bottom:1rem;text-align:center"><?php echo "Rp. ".number_format(($md->harga));?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center" >
                        <img src="plugins/barcode/barcode.php?text=<?=$barcode?>&codetype=code128&print=true&size=20" />
                    </div>
                </div>
                <!-- <div class="barcode"><?php echo ucfirst(strtoupper($barcode));?></div> -->
                
                <div class="row">
                    <div class="col-xs-12" style="text-align:right">
                        <div class="waktu" style="font-size:1.2rem"><?php echo date("d/m/y") ?></div>
                    </div>
                </div>

				
			</div>
		<?php 
		}
	}
	
	?>
</body>