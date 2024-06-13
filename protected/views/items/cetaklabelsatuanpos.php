<style>
body{
    padding:0px!important;
    margin:0 auto;
}

.card-label{
    border: 1px solid black!important;
    width:190px!important;
    display:block;
    margin-bottom: 1rem;
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
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

	<?php 
            function printLine($text = '', $width = 40) {
                echo str_pad($text, $width, ' ', STR_PAD_BOTH) . "\n";
            }

            function printSeparator($char = '-', $width = 40) {
                echo str_repeat($char, $width) . "\n";
            }

            function printItem($item, $qty, $price, $width = 40) {
                $itemWidth = 20;
                $qtyWidth = 10;
                $priceWidth = 10;

                $item = str_pad($item, $itemWidth);
                $qty = str_pad($qty, $qtyWidth, ' ', STR_PAD_LEFT);
                $price = str_pad(number_format($price, 2), $priceWidth, ' ', STR_PAD_LEFT);

                echo $item . $qty . $price . "\n";
            }

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

        ?>
        <div class="text-center">
            <?php echo ucfirst(strtoupper($items->item_name))." - ".$md->nama_satuan;?>
        </div>
        <div class="text-center">
            <?php echo "Rp. ".number_format(($md->harga));?>
        </div>
        <br>

        <div class="text-center">
            <svg id="B_<?=$barcode?>"></svg>
            <script>
                JsBarcode("#B_<?=$barcode?>", "<?=$barcode?>");
            </script>
        </div>
        <br>
        <br>
        <!-- <img src="plugins/barcode/barcode.php?text=<?=$barcode?>&codetype=code128&print=true&size=40" style="width: 100%;height:160px";  /> -->
        <!-- <br> -->
        <div style="text-align:center"><?php echo date("d/M/y") ?></div>
        <div style="text-align:center">------------------------------------------------------</div>
                        <?php 
		}
        }
        
        ?>
</body>
<!-- <div class="barcode"><?php echo ucfirst(strtoupper($barcode));?></div> -->