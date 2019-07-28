
<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>

<style type="text/css">
    #wrap-selisih{
        position: fixed;
        top: 20px;
        right: 20px;
        width: 300px;
        height: 300px;
        background-color: white;
        padding: 10px;

        border: 3px solid black;

    }
</style>
<div id="wrap-selisih">
    <h1>Selisih</h1>

    <h5>Total Sebelumnya : <label id="total-lama">0</label></h5>
    <h5>Total Pergantian : <label id="total-ganti">0</label></h5>
    <h5>Selisih          : <label id="total-selisih">0</label></h5>
</div>
<script type="text/javascript">
 var format = function(num){
        var str = num.toString().replace("$", ""), parts = false, output = [], i = 1, formatted = null;
        if(str.indexOf(".") > 0) {
            parts = str.split(".");
            str = parts[0];
        }
        str = str.split("").reverse();
        for(var j = 0, len = str.length; j < len; j++) {
            if(str[j] != ",") {
                output.push(str[j]);
                if(i%3 == 0 && j < (len - 1)) {
                    output.push(",");
                }
                i++;
            }
        }
        formatted = output.reverse().join("");
        return("Rp. " + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
    };  
    function kalkulasi(){

        var harga_lama = $("#PenukaranItems_item_harga_asal").val();
        var qty_lama = $("#PenukaranItems_item_qty_asal").val();

        var harga_baru = $("#harga_baru").val();
        var qty_baru = $("#PenukaranItems_item_qty_baru").val();
        var total_awal = parseInt(harga_lama)*parseInt(qty_lama);
        var total_baru = parseInt(harga_baru)*parseInt(qty_baru);
        $("#total-lama").html( format(total_awal) );
        $("#total-ganti").html(format(total_baru));
        $("#total-selisih").html(format(total_awal-total_baru));
    }

    $(document).ready(function(){
        $(document).on("change","#PenukaranItems_item_qty_baru",function(){
            kalkulasi();

        })
        $(document).on("change","#harga_baru",function(){
            kalkulasi();
            // $("#PenukaranItems_item_id_baru").trigger("change");
        });

        $("#PenukaranItems_item_id_baru").keypress(function(e){

            var value = $(this).val();
            if (e.keyCode==13 || e.which==13){
                $.ajax({
                // url : 'index.php?r=items/check',
                    url : 'index.php?r=items/check',

                    type : 'GET',
                    data : "id="+value,
                    success : function(data)
                    {
                         $("#harga_baru").html(" ");
                        var data = JSON.parse(data);
                        var price_detail = data.price_detail;
                        $.each( price_detail, function( key, value ) {
                            $("#harga_baru").append("<option value="+value.price+">"+value.price_type+" - "+value.price+"</option>");
                          // alert(value);
                        });
                        // alert(JSON.stringify(price_detail));

                        // alert(JSON.stringify(data.satuan_detail));
                        // $("#harga_baru").select2();
                        // kalkulasi();

                    },
                    error : function(data)
                    {

                    alert(JSON.stringify(data));
                    // $('#dialog_meja').load('index.php?r=site/table');
                    }
                });
                return false;
            }
        });
    	// $("#PenukaranItems_item_id_baru").change(function(){
    	// 	value = this.value;
     //        // alert(value);
     //        if (value==""){
     //            alert("tidak boleh kosong");
     //            return false;
     //        }

            // $.ajax({
            // // url : 'index.php?r=items/check',
            // url : 'index.php?r=items/getlistprice',

            // type : 'GET',
            // data : "id="+value,
            // success : function(data)
            // {
            //     // var data = jQuery.parseJSON(data);
            //     // alert(JSON.stringify(data));
            //     $("#harga_baru").html(data);
            //     $("#harga_baru").select2();
            //     kalkulasi();

            // },
            // error : function(data)
            // {
            // alert(JSON.stringify(data));
            // $('#dialog_meja').load('index.php?r=site/table');
            // }
            // });

    	// });
    //     $('select').select2();
    });
</script>
<h1>Penukaran Barang
</h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'sales-items-form',
    'enableAjaxValidation'=>false,
)); ?>

    <p class="note">.</p>

    <?php //echo $form->errorSummary($model); ?>
    <?php 
    $penukaran = new PenukaranItems; 
    ?>
    <div style="padding:10px;">

        <legend>Penjualan awal </legend>
         <div class="row">
         <input type="hidden" name="item_satuan_id" value="<?php echo $model->item_satuan_id ?>"> 
            <?php 
            // var_dump($model->item_id);
            // var_dump($model->item_satuan_id);
            // var_dump($penukaran->item_id_asal);
            // $sql = 
            $data = CHtml::listdata(Items::model()->findAll(),'id','item_name');
            ?>        
            <?php echo $form->labelEx($penukaran,'Nama Item'); ?>
            <?php  echo $form->dropDownList($penukaran,'item_id_asal', $data, 
            array(
                'options' => array($model->item_id=>array('selected'=>true)),
                'empty' => 'Pilih Item',
                'separator'=>'|',
                'disabled'=>'true'
                ))?> 
            <?php echo $form->error($penukaran,'item_id_asal'); ?>
        </div>
        <div class="row">
            <?php //echo $form->labelEx($penukaran,'Jumlah '); ?>
            Jumlah
            <?php echo $form->textField($penukaran,'item_qty_asal',
            array(
            'value'=>$model->quantity_purchased,
            'readonly'=>'true',
            )); ?>
            Harga 
            <?php echo $form->textField($penukaran,'item_harga_asal',
            array(
            'value'=>$model->item_price,
            'readonly'=>'true',
            )); ?>

            <!-- item id asal -->
            <?php echo $form->textField($penukaran,'item_id_asal',
            array(
            'value'=>$model->item_id,
            'style'=>'display:none',
            )); ?>



            <?php echo $form->error($penukaran,'item_qty_asal'); ?>
        </div>


        <legend>diganti menjadi</legend>
         <div class="row">
            <?php 
            // $data = CHtml::listdata(Items::model()->findAll("id!=$model->item_id"),'id','item_name');
            $data = CHtml::listdata(Items::model()->findAll("1=0"),'id','item_name');
            ?>        
            <?php echo $form->labelEx($penukaran,'Barcode Item'); ?>
            <?php  echo $form->textField($penukaran,'item_id_baru'); ?> 
              <?php 
                // ,
            // array(
            //     'empty' => 'Pilih Item',
            //     'separator'=>'|',
            //     'options' => array("'$model->item_id'"=>array('selected'=>true)),
            // )
              ?>
            <?php echo $form->error($model,'item_id_baru'); ?>
        </div>
        <div class="row">
            <?php // echo $form->labelEx($penukaran,'Jumlah '); ?>
            Jumlah
            <?php echo $form->textField($penukaran,'item_qty_baru',array('value'=>$model->quantity_purchased)); ?>
            

            <?php echo $form->textField($penukaran,'si_id',array('value'=>$_REQUEST[id],'style'=>'display:none')); ?>
            <?php echo $form->error($penukaran,'item_qty_baru'); ?>
           Harga 
            <select style="width: 200px;" name="harga_baru"  id="harga_baru" value="0" required></select>
            <!-- <input type="text" name="harga_baru"  id="harga_baru" value="0" required> -->
            <!-- <input type="text" > -->
        </div>

    </div>
  


    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array("class"=>"btn btn-primary")); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form