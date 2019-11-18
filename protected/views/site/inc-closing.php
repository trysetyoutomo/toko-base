<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'selisih-form',
    // additional javascript options for the dialog plugin
    'options' => array(
        'title' => 'Selisih ',
        'closeOnEscape'=> false,
        'autoOpen' => false,
        'modal' => true,
        'width' => 500,
        'height' => 250,
    ),
));
?>
<table cellpadding="20" border="0">
  <tr>
    <td>Total Omset Penjualan</td>
    <td><input type="number" name="total" id="total_omset_penjualan" readonly="" ></td>
  </tr>

   <tr>
    <td>Input Saldo Awal</td>
    <td><input type="number" name="total_awal" id="total_awal" readonly="" ></td>
  </tr>
  <tr><td colspan="2"><hr></td></tr>
   <tr>
    <td>Saldo Harus Ada</td>
    <td><input type="number" name="total_hrs_ada" id="total_hrs_ada" readonly="" ></td>
  </tr>


  <tr>
    <td>Input Saldo Akhir</td>
    <td><input readonly="" type="number" name="total" id="total_omset_fisik2"  class="number-only" ></td>
  </tr>


  <tr>
    <td>Selisih</td>
    <td><input type="number" readonly="" name="total" id="total_selisih" ></td>
  </tr> 
  <tr><td colspan="2"><hr></td></tr>
  <tr>
    <td>
      <input type="button" id="cetakrekap_kasir" class="btn btn-primary" value="Cetak Rekap & Keluar">
    </td>
  </tr>
  

</table>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id' => 'closing-form',
    // additional javascript options for the dialog plugin
    'options' => array(
        'title' => 'Penyesuaian ',
        'autoOpen' => false,
        'modal' => true,
        'width' => 400,
        'height' => 150,
    ),
));
?>
<table cellpadding="5" border="0">
  <tr>
    <td>Input Saldo Akhir</td>
    <td><input type="number" value="0" name="total" id="total_omset_fisik1"  ></td>
  </tr>
  <tr>
    <td>
      <input id="btn-lanjut1" type="button" class="btn btn-primary" value="Lanjut">
    </td>
  </tr>
  

</table>
<?php
$this->endWidget('zii.widgets.jui.CJuiDialog');
?>
<script type="text/javascript">
	   $(document).ready(function(){

        $('#cetakrekap_kasir').click(function(){
        // var tanggal = $('#Sales_date').val();
         var tanggal = '<?php echo date("Y-m-d"); ?>';
        
        if(tanggal==''){
            alert('Pilih tanggal terlebih dahulu');
            return false;
        }else{
            $.ajax({
                url:'<?=$this->createUrl('sales/cetakrekap')?>',
                data:'tanggal_rekap='+tanggal+"&uangmasuk="+$("#total_omset_fisik2").val(),
                success: function(data){
                    // alert(data);
                    var json = jQuery.parseJSON(data);
                    // $('#hasiljson').html(data);
                    print_rekap(json,false);
                    alert("Tekan OK untuk keluar");
                    window.location.assign("index.php?r=site/logout");

                    // console.log(data);
                    
                },
                error: function(data){
                    alert('error');
                }
            });
        }
    });


    $(document).on("click","#btn-lanjut1",function(){
        var n = $("#total_omset_fisik1").val();
        // if (n=="" || n=="0"  ){
        //  alert("Tidak oleh kosong");
        //  $("#total_omset_fisik1").focus();
        //  $("#total_omset_fisik1").val(0);
        //  exit;
        // }
        var c = confirm("Yakin ? angka tidak dapat di ubah lagi (1 hari 1 rekap)");
        if (!c){
            return false;
        }else{
            <?php 
            $username = Yii::app()->user->name;
            $user = Users::model()->find('username=:un',array(':un'=>$username));

            ?>
            var tanggal = '<?php echo date("Y-m-d"); ?>';
            var user_id = "<?php echo $user->id ?>";

            $.ajax({
                url : "<?php echo Yii::app()->createUrl('sales/GetOmsetByUser'); ?>",
                data : "date="+tanggal+"&user_id="+user_id,
                success:function(data){
                	var js = JSON.parse(data);
                	$("#total_awal").val(js.total_awal);
                    $("#total_omset_penjualan").val(js.cash);
                    var hrs_ada = parseInt(js.total_awal)+parseInt(js.cash);
                    $("#total_hrs_ada").val(hrs_ada);	
                    var z = $("#total_omset_fisik1").val();
                    $("#total_omset_fisik2").val(z);
                    var s = parseInt($("#total_omset_fisik2").val()) - parseInt(hrs_ada) ;
                    $("#total_selisih").val(s);





                }
            });
            $("#selisih-form").dialog("open");
        }
        // alert("susah");
    });

        });
</script>
