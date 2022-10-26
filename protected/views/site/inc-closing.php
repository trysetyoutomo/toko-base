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
        'height' => "auto",
    ),
));
?>
<table cellpadding="20" border="0" id="table-closing">
  <tr>
    <td>Total Uang Cash Masuk <small class="d-block text-info">Transaksi penjualan dengan pembayaran cash</small></td>
    <td><input type="number" name="total" id="total_omset_penjualan" readonly="" class="form-control"></td>
  </tr>

   <tr>
    <td>Input Saldo Awal <small class="d-block text-info">Saldo awal pada saat Membuka register</small></td>
    <td><input type="number" name="total_awal" id="total_awal" readonly="" class="form-control" ></td>
  </tr>
  </tr>
     <tr>
    <td>Potongan Diskon/Voucher <small class="d-block text-info">Nilai potongan diskon atau voucher</small></td>
    <td><input type="number" name="total_potongan" id="total_potongan" readonly="" class="form-control" ></td>
  </tr>
  </tr> 
     <tr>
    <td>Pengeluaran <small class="d-block text-info">Total nilai pengeluaran per user hari ini </small></td>
    <td><input type="number" name="total_pengeluaran" id="total_pengeluaran" readonly="" class="form-control" ></td>
  </tr>



  <tr><td colspan="2"><hr></td></tr>
   <tr>
    <td>Saldo Harus Ada <small class="d-block text-info">Saldo yang harus dipegang kasir saat ini</small></td>
    <td><input type="number" name="total_hrs_ada" id="total_hrs_ada" readonly=""  class="form-control"></td>



  <tr>
    <td style="font-weight:bolder">Input Saldo Akhir <small class="d-block text-info">Saldo akhir yang diinputkan kasir</small></td>
    <td><input readonly="" type="number" name="total" id="total_omset_fisik2"  class="number-only form-control" ></td>
  </tr>


  <tr>
    <td>Selisih <small class="d-block text-danger">Selisih antara saldo akhir yang diinput dengan saldo yang harus ada</small></td>
    <td><input type="number" readonly="" name="total" id="total_selisih" class="form-control"></td>
  </tr> 
  <tr><td colspan="2"><hr></td></tr>
  <tr>
    <td>
      <input type="button" id="cetakrekap_kasir" class="btn btn-primary" value="Simpan Data & Keluar Akun">
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
        'height' => 250,
    ),
));
?>
<table cellpadding="5" border="0" id="table-closing-input-first">
  <tr>
    <td>Input Saldo Akhir</td>
    <td><input type="number" value="0" name="total" id="total_omset_fisik1" class="form-control" ></td>
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
<style>
  #table-closing tr td, #table-closing-input-first tr td{
    padding:5px;
  }
</style>
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
                beforeSend: function(){
                  $(".preloader-it").show();
                },
                success: function(data){
                    // alert(data);
                    var json = jQuery.parseJSON(data);
                    // $('#hasiljson').html(data);
                    // print_rekap(json,false);
                    alert("Data berhasil disimpan!, sistem akan logout secara otomatis");
                    setTimeout(function() {
                      $(".preloader-it").hide();
                      window.location.assign("index.php?r=site/logout");
                    }, 1000);

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
                	// set nilai pengeluaran
                  $("#total_pengeluaran").val(js.pengeluaran);
                	$("#total_potongan").val(js.potongan);
                	$("#total_awal").val(js.total_awal);
                    $("#total_omset_penjualan").val(js.cash);
                    var hrs_ada = parseInt(js.total_awal)+parseInt(js.cash)-parseInt(js.potongan)-parseInt(js.pengeluaran);
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
