<style>
body{
    padding:0px!important;
    margin:0 auto;
}

.card-label{
    border: 1px solid black!important;
    width:190px!important;
    display:inline-block;
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
    $jumlahcopy = 5;
	$count = 1;
	for ($i=0; $i < $count ; $i++) { 
		$md = ItemsSatuan::model()->find("barcode = '$barcode' ");
		$items = Items::model()->findByPk($md->item_id);
		for ($x=0; $x < $jumlahLabel ; $x++) { 
		?>
			<div  class="card-label" >
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
                        <img src="plugins/barcode/barcode.php?text=<?=$barcode?>&codetype=code128&print=true&size=30" />
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