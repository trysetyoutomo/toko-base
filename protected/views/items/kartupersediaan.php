
<?php 
$cabang = Yii::app()->user->branch();

//riwayat
// $sql = ItemsController::sqlAverage($_REQUEST['id'],$_REQUEST['satuan_id'],$cabang);
$sql = ItemsController::sqlAverage($_REQUEST['id'],"",$cabang);
// where bmd.kode = $model->id
$model = Yii::app()->db->createCommand($sql)->queryAll();


?>
  <?php 
    $item = Items::model()->findByPk($_REQUEST['id']);
    $item_satuan = ItemsSatuan::model()->findByPk($_REQUEST['satuan_id']);
    
    // echo " - ";
    // echo $item_satuan->nama_satuan;
    ?>
<h1>
<!-- <i class="fa fa-book"></i> -->
    Kartu Persediaan #<?php echo $item->item_name;?>
</h1>
<h2>
  
</h2>
<hr>
<table class="items table" id="data-log">
<thead>
    <th rowspan="2">No</th>
    <th rowspan="2">Tanggal Masuk</th>
    <th style="text-align:center" colspan="4">Barang Masuk</th>
    <th style="text-align:center" colspan="4">Barang Keluar</th>
    <th style="text-align:center" colspan="3">Saldo</th>
</thead>
<thead> 
    <th  rowspan="2"></th>
    <th  rowspan="2"></th>
 
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Total Modal</th>
     <th>Keterangan</th>

    
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Total Modal</th>
    <th>Keterangan</th>

    
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Total Modal</th>
</thead>
<tbody>
<?php 
$no = 1;
$qty_awal = 0;
$saldo_awal = 0;
$rc = count($model);
foreach ($model as $key => $value) { 
    ?>

    <tr 

    
    >
        <td><?php echo $no ?></td>
        <td><?php echo $value[tanggal] ?></td>

            <!-- <td><?php echo $value[keterangan] ?></td> -->
    
        <?php if ( $value[jenis]=="masuk" ): 
        $qty_awal += $value[jumlah];
        $saldo_awal += round($value[jumlah] * $value[harga]);
        ?>
            <td  style="text-align:right"><?php echo number_format($value[harga]) ?></td>
            <td  style="text-align:right"><?php echo $value[jumlah] ?></td>
            <td  style="text-align:right"><?php echo number_format(round($value[harga]*$value[jumlah]) ) ?></td>
            <td><?php echo $value[keterangan] ?></td>
        <?php else: ?>
            
             
                <td></td>
                <td></td>
                <td></td>
                <td></td>
        <?php endif;?>


        <?php   if ($value['jenis']=="keluar"): 
        // var_dump($saldo_awal);
        $qty_awal = $qty_awal -  $value['jumlah'];
        $saldo_awal = $saldo_awal -  round($value[harga]*$value[jumlah]);
        // if ($saldo_awal<=0){
        //     $harga = 0;
        // }else{
            $harga = $value['harga'];
        // }
        // var_dump($harga);

        ?>
        
        <td  style="text-align:right"><?php echo number_format($harga) ?></td>
        <td  style="text-align:right"><?php echo $value['jumlah'] ?></td>
        <td  style="text-align:right"><?php echo number_format(round($value['harga']*$value['jumlah']) ) ?></td>
        <td><?php echo $value['keterangan'] ?></td>
        <?php else: ?>
              <td></td>
              
                <td></td>
                <td></td>
                <td></td>
        <?php endif;?>


        <!-- saldo -->
        <td <?php if ($rc == $no){echo "style='background-color:#A52A2A;color:white;text-align:right'";}?>  style="text-align:right">
        <?php 
            if ($qty_awal == 0){
                $saldo_awal = 0;
                $qty_awal = 0;
            }

            if ($saldo_awal>0 || $qty_awal>0){
         ?>
        <?php 
        echo number_format( round($saldo_awal/$qty_awal) ) ;
        // var_dump(123);

        ?>
            

        </td>
        <?php 
        }else{
            echo "0";
        }
        ?>
        <td <?php if ($rc == $no){echo "style='background-color:#A52A2A;color:white;text-align:right'";}?>  style="text-align:right"><?php echo $qty_awal ?></td>
        <td <?php if ($rc == $no){echo "style='background-color:#A52A2A;color:white;text-align:right'";}?>  style="">
        <?php echo number_format($saldo_awal ) ?></td>
    







    </tr>
<?php 

// $tj2 += $value['jumlah'];
// $tm2 += $value[harga]*$value[jumlah];
    $no++;
} ?>
</tbody>



</table>
<?php 
    // echo number_format($saldo_awal);
    // echo number_format($saldo_awal/$qty_awal);

// echo number_format(floor(1599990) );
?>

<div class="row">
    <div class="col-sm-8">
    <!--    
        <a href="<?php echo Yii::app()->createUrl("items/barangmasuk",array("id"=>$_REQUEST[id])) ?>">
        <button class="btn btn-primary">
            <i class="fa fa-pencil"></i> Tambah Stok Barang
        </button>
        </a>
      <a class="btn btn-primary" href="<?php echo Yii::app()->createUrl("items/barangmasuk",array("id"=>$_REQUEST[id])) ?>">
            <i class="fa fa-print"></i> Cetak Kartu Persediaan
        </a>
 -->
        
    </div>
</div>
<br>
<br>
<br>