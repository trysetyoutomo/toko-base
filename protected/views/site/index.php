
<?php 
$usaha = SiteController::getConfig("jenis_usaha");
?>

<!-- Toast -->
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toast/src/main/javascript/jquery.toastmessage.js"></script>

<link rel="Stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/toast/src/main/resources/css/jquery.toastmessage.css" />

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/extjs4/resources/css/ext-all.css" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/extjs4/ext-all.js"></script>
<title>Halaman Penjualan</title>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/app/SalesItems/model.js"></script>
<?php
   // $url = Yii::getPathOfAlias('webroot');
   // include("'$url/js/app/SalesItems/main.php'");
   $this->renderPartial('main');
   ?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/app/SalesItems/_form.js"></script>
<!-- <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/include/dist/plugins/jqplot.barRenderer.min.js"></script> -->
<!-- select 2-->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.css" rel="stylesheet"/>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/select2/select2.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/grid-pos.css" />
<!-- Bootstrap -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
              
<script>
   function print() {
       document.jzebra.append("A37,503,0,1,2,3,N,PRINTED USING JZEBRA\n");
       // ZPLII
       // document.jzebra.append("^XA^FO50,50^ADN,36,20^FDPRINTED USING JZEBRA^FS^XZ");
       document.jzebra.print();
   }
</script>
<?php 
// include "js.php";
?>
<!-- <div class="content-pos">
<div> -->
   <style type="text/css">
      body{
        overflow-x: hidden;
      }
      #vouchernominal{
      width: 100%;
      padding:5px;
      }
      #full-screen{
      display: none;
      width: 100%;
      height: 100%;
      z-index: 999;
      background-color: rgba(0,0,0,0.4);
      position: fixed;
      top: 0px;
      bottom: 0px;
      left: 0px;
      right: 0px;
      margin: auto;
      }
      #wrapper-item-search{
      display: none;
      background-color: white;
      width: 350px;
      height: 140px;
      border: 2px solid black;
      position: fixed;
      top: 0px;
      bottom: 0px;
      left: 0px;
      right: 0px;
      margin: auto;
      z-index: 1000;
      padding: 10px;
      }
      #wrapper-item-search h1{
      font-size:20px;
      font-weight: bolder;
      }
      #wrapper-item-search .close{
      position: absolute;
      top: 10px;
      right: 10px;
      cursor: pointer;
      }
      #s2id_e1{
        width: 100%;
      }
      #head tr td {
     padding: 5px;
     border: 0px solid black;
     }
     #head tr td *{
     text-align: left;
     }
     #pos-content{
     height: 500px;
     }
     .content-pos {
     min-height: 450px;
     }
     #content{
      padding: 0px!important;
      margin: 0px!important;

     }
</style>
   <?php 
    $this->renderPartial("application.views.items.inc-pencarian-items");
   ?>
   <script>
      $(document).ready(function(){
       //    $("#tambah-satuan-form,#tambah-kategori-form").dialog({
       //     height: 200,
       //     width: 700
       //   });
       //   $("#tambah-satuan-form").dialog("close");
       //   $("#tambah-kategori-form").dialog("close");

         
    
       // $(document).on("click",".tambah-satuan",function(e){
       //  e.preventDefault();

       //   var i = $(".tambah-satuan").index(this);
       //   $("#tambah-satuan-form").dialog("open");
       // });



       //  $(document).on("click",".tambah-kategori",function(e){
       //  e.preventDefault();
       //    // alert("123");
       //   var i = $(".tambah-kategori").index(this);
       //   $("#tambah-kategori-form").dialog("open");
       // });



        $('#cetakrekap').click(function(){
          var c = confirm("Yakin Cetak Rekap Hari ini ? ");
          if (!c){
            // alert
            return;
          }
          var tanggal = $("#tanggal").val();
          <?php 
          $u = Yii::app()->user->name;
          $userid = Users::model()->find("username = '$u' ")->id;
          ?>
          var inserter = '<?php echo $userid ?>'; 
          
          if(tanggal==''){
            alert('Pilih tanggal terlebih dahulu');
            return false;
          }else{
            $.ajax({
              url:'<?php echo Yii::app()->createUrl("sales/cetakrekap") ?>',
              data:'tanggal_rekap='+tanggal+"&inserter="+inserter,
              success: function(data){
                // alert(data);
                var json = jQuery.parseJSON(data);
                // $('#hasiljson').html(data);
                print_rekap(json,false);
                // console.log(data);
                
              },
              error: function(data){
                alert('error');
              }
            });
          }
        });
        $(document).on("keypress","#text1",function(e){
          if (e.keyCode=="13"){
            // getSaleID();
            $("#btnbayar").focus();
            // alert("123");
          }
        });
          // setInterval(function(e){
          //     // alert("123");
          //     if (!$("#full-screen").is(':visible')){
          //         $("#input_items").focus();
          //     }


          $("#input_items").focus();
          // },5000);
          // $("#qty").click(function(){
          //     $(this).select();
          // });
         
          $("#cowork").click(function(){
              // var subtotal = $("#sum_sub_total").html();
              var service = $("#sum_sale_service").html();
              var tax = $("#sum_sale_tax").html();
              // var voucher = $("#sum_sale_voucher").html();
              var total = $("#sum_sale_total").html();
              var jumlaha = total - service - tax;
              $("#sum_sale_service").html(0);
              $("#sum_sale_tax").html(0);
              $("#sum_sale_total").html(jumlaha);
              // alert(service);
              // alert(tax);
              // alert(total);
          });
          $("#ditditvoc").click(function(){
              var subtotal = $("#sum_sub_total").html();
              var total = $("#sum_sale_total").html();
              var vouchernominal = $("#vouchernominal").val();
              if (vouchernominal == ""){
                  vouchernominal = 0;
              }
              var setelahvouchernominal = subtotal - vouchernominal;
              var nilai_tax = "<?php echo Parameter::model()->findByPk('1')->pajak ?>";
              var nilai_service = "<?php echo Parameter::model()->findByPk('1')->service ?>";
              var real_tax = nilai_tax / 100;
              var real_service = nilai_service / 100;
              var vtax = setelahvouchernominal * real_tax;
              var vservice = setelahvouchernominal * real_service;
              var tampil = setelahvouchernominal + vtax + vservice;
              $("#sum_sale_voucher").html(vouchernominal);
              $("#sum_sale_total").html(tampil);
            kalkulasi1();
          });
        $("#vouchernominal").keyup(function(){
              var subtotal = $("#sum_sub_total").html();
              var total = $("#sum_sale_total").html();
              var vouchernominal = $("#vouchernominal").val();
              if (vouchernominal == ""){
                  vouchernominal = 0;
              }
              var setelahvouchernominal = subtotal - vouchernominal;
              var nilai_tax = "<?php echo Parameter::model()->findByPk('1')->pajak ?>";
              var nilai_service = "<?php echo Parameter::model()->findByPk('1')->service ?>";
              var real_tax = nilai_tax / 100;
              var real_service = nilai_service / 100;
              var vtax = setelahvouchernominal * real_tax;
              var vservice = setelahvouchernominal * real_service;
              var tampil = setelahvouchernominal + vtax + vservice;
              $("#sum_sale_voucher").html(vouchernominal);
              $("#sum_sale_total").html(tampil);
            kalkulasi1();
          });
      });

      function hapusGrid(){
          liveSearchPanel_SalesItems.store.removeAll();
          $("#namapel").val("umum").trigger("change");

          var i = 0;
          liveSearchPanel_SalesItems.store.each(function (rec) {
           i=i+1;
          });

          // alert(i);
          var nilai = parseInt($("#head-meja-nilai").html());
          // alert(nilai);
          if (!isNaN(nilai)){
            if (parseInt(i)==0){
              alert("Data Berhasil di hapus");
              hapusData(nilai);
            }else{
              // alert("ga ok");
            }
          }

          kalkulasi1();

      }
      function clearItems(){
        $("#Items_category_id").val(0);
        $("#Items_letak_id").val(0);
        $("#Items_motif").val(0);
        $("#Items_satuan_id").val(0);
        $("#Items_modal").val(0);

        $("#Items_item_name").removeAttr("value");
        $("#Items_description").val("-");
        $("#Items_modal").removeAttr("value");
        $("#Items_total_cost").removeAttr("value");
        $("#Items_stok_minimum").removeAttr("value");
        $("#Items_discount").removeAttr("value");
        $("#is_generate").attr("checked","true");

        $("#head-meja").html("");
        $("#head-meja-nilai").html("");
      }
      function hutang(){
        var id = prompt("Silahkan Masukan No. Faktur", "");
        if (id!=""){
           $.ajax({
                    type: 'GET',
                    url: '<?php echo Yii::app()->createAbsoluteUrl("sales/hutang"); ?>',
                    data:'id='+id,
                    success:function(data){
                    if (data=='kosong')
                        alert('tidak ada ID faktur '+id);
                        // alert('id telah sukses dibayar');
                    else if (data =='already')
                        alert(id+' tersebut sudah dibayar!!');
                    else{
                        // alert(data);
                        var sales = jQuery.parseJSON(data);
                        print_bayar(sales);
                        }
                    },
                    dataType:'html'
                });
        }else{
            alert('ID faktur kosong');
        }
      }

   </script>
   <style type="text/css">
      .namapel{
      margin-top: 20px;
      height: 30px;
      width: 150px;
      border-radius: 8px;
      }
   </style>
   <?php
      // $connection=new CDbConnection('mysql:host=localhost;dbname=postech','root','');
      //        $connection->active=true; // open connection

      //        $que="select i.id,o.status, i.item_name from outlet o , items i where o.kode_outlet = i.kode_outlet ";
      //        $command=$connection->createCommand($que);
      //        $reader=$command->query();

      //        $model=Items::model()->with('outlet')->findAll(
      //        array('select'=>'kode_outlet,item_name',));

      //                 // $data = array();
      //                 // foreach ($model as $item)
      //                 // {
      //                     // $temp = array();
      //                     // $data[$item->id] = $item->outlet->status." ".$item->item_name;
      //                 // }

      //                $data = array();
      //                 foreach ($reader as $item)
      //                 {
      //                     $temp = array();
      //                     // $data[$item->id] = $item->outlet->status." ".$item->item_name;
      //                     $data[$item['id']] = $item['status']." ".$item['item_name'];

      //                 }
                      // return $data;
                    // echo "<pre>";
                    // print_r($data);
                    // echo "</pre>";
      ?>
<!-- </div> -->

<div class="conten t-pos-grid">
   <div class="inputtab" >
      <form id="sales-hb" >
         <table id="head" border="1" cellpadding="2"  >
          
            <tr style="display: none;" >
               <td>Jenis Harga</td>
               <td>
                  <select style="width:150px" id="costumer_type" class="myinput">
                     <?php foreach (CustomerType::model()->findAll() as $ct) { ?>
                     <option value="<?php echo $ct->id ?>"><?php echo $ct->customer_type ?></option>
                     <?php } ?>
                  </select>
               </td>
            </tr>
         </table>
         
         <table>
            <tr>
               <td>
                  <input placeholder="Item" type="text" id="input_items" style="padding:10px;width:90%"  >
               </td>
               <td>
                  Diskon
                  <?php echo CHtml::dropDownList('discount', '0', Chtml::listdata(Diskon::model()->findAll(),'diskon','diskon'),array('class' => 'myinput')); ?> %
                  Jumlah
                  <input maxlength="2" class="myinput" type="text" value="1" name="qty" id="qty">
                   <input style="display: inline;" type="button" value="Tambah" onClick="add_item($('#input_items').val())" class="mybutton">

                  <?php //echo CHtml::textField('qty', '1', array('maxlength' => '2','class' => 'myinput', 'onkeypress' => 'return runScript(event,"add_item($(''))")')); ?>
               </td>
               <td>
               </td>
            </tr>
         </table>
         <?php //echo CHtml::textField('discount', '0', array('class' => 'myinput', 'onkeypress' => 'return runScript(event,"add_item")')); ?>
        
      </form>
   </div>
   <script>
      function runScript(e,obj) {
          if (e.keyCode == 13) {
              //                    alert('endter');
              $('#'+obj).focus();
              if (obj=="ftem")
              {
                  add_item();
              }
          }
      }
      function send()
      {

          var data=$("#sales-hb").serialize();


          $.ajax({
              type: 'POST',
              url: '<?php echo Yii::app()->createAbsoluteUrl("SalesHb/save"); ?>',
              data:data,
              success:function(data){
                  alert(data);
              },

              dataType:'html'
          });

      }
      function reloadItems()
      {



          $.ajax({
              type: 'POST',
              url: '<?php echo Yii::app()->createAbsoluteUrl("Items/getAllItems"); ?>',
              // data:data,
              success:function(data){
                  // alert(data);
                  $("#e1").html(data);
              },

              dataType:'html'
          });

      }


      function estimate(num)
      {
          // return 0 ;

          if (num < 10000)
          {
              num = 10000;
              return num;
          }
          else if (num<= 20000)
          {
              return 20000;
          }
          else if (num <= 100000)
          {
              return 100000;
          }
          else if (num <= 150000)
          {
              return 150000;
          }
          else if (num <= 200000)
          {
              return 200000;
          }
          else if (num <= 250000)
          {
              return 250000;
          }
          else if (num <= 300000)
          {
              return 300000;
          }
          else
          {
              return num;
          }

      }
      function runScript(e,obj) {
          if (e.keyCode == 13) {
              //                    alert('endter');
              $('#'+obj).focus();
              if (obj=="add_item")
              {
                  add_item();
              }
          }
      }
      function send()
      {

          var data=$("#sales-hb").serialize();


          $.ajax({
              type: 'POST',
              url: '<?php echo Yii::app()->createAbsoluteUrl("SalesHb/save"); ?>',
              data:data,
              success:function(data){
                  alert(data);
              },

              dataType:'html'
          });

      }

      function custype(){
      var customer_type = $('#custype').val();
      // alert(customer_type);

      // var chk = $("#chkService").val();
      //ambil data dari summary kanan
      var subtotal = $('#sum_sub_total').html();
      var discount =$('#sum_sale_discount').html();
      var tax =$('#sum_sale_tax').html();
      var service=0;
      var total_cost=$('#sum_sale_total').html();

      //travel tidak pake service
      if(customer_type==2){
      // alert('checked');
      // $('#sum_sub_total').html(0);
      // $('#sum_sale_discount').html(0);
      $('#sum_sale_service').html(0);
      // $('#sum_sale_tax').html(0);
      $('#sum_sale_total').html(total_cost-service);
      }else{
      // alert('unchecked');
      service  =    var_service * (subtotal-discount)/ 100;
      $('#sum_sale_service').html(service);
      // $('#sum_sale_tax').html(0);
      $('#sum_sale_total').html(parseInt(total_cost)+parseInt(service));
      }

      // alert(chk);
      }
   </script>
   <div id="sales_items"></div>
</div>
<script type="text/javascript">
   function klikmeja(){
       // $('#dialog_meja').load('index.php?r=site/table');
       $("#dialog_meja").dialog("open");

   }
</script>
<!-- tombol -->
<!-- <div class="content-pos-kanan"></div> -->
<!-- untuk div tax, subtotal, total -->
<script>

  function numberWithCommas(x) {
      return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

//     function hold_bill(meja,mode,data)
//     {
//       alert(" can't hold bill ");
// //         if (mode===0)
// //             bayar(0,meja);
// //         else
// //         {
// //             load_bill(meja,data);
// //             sale_id = data;
// //             none;none;none;("Meja ("+meja+")");
// //             $('#dialog_meja').dialog('close');
// // //            alert(sale_id);
// //         }none;none;none;
// //         $('#dialog_meja').load('index.php?r=site/table');
//     }
    function show_meja(val)
    {
        $("#tombol_meja").attr('value', val);
    // $('#dialog_meja').load('index.php?r=site/table');
    }
  
  function cekisigrid(){
    var inc = 0;
     liveSearchPanel_SalesItems.store.each(function (rec) {
      inc=inc+1;
    });
    // alert(inc);
    return inc;
  }
  
  function hold_bill_press(e){
    // alert(data);
    //cek isi grid
    
    
    
    var key;
    if (window.event)
      key = window.event.keyCode;     //IE
    else
      key = e.which;     //firefox
    
      //jika enter di tekan
        var meja = $("#meja").val().toString();
        var nilai = meja.split("-");
        no_meja = nilai[0];
        // alert(no_meja);
        meja_cetak = no_meja;
        mode = nilai[1];
        data = nilai[2];
      if (key == 13) {
        
        var number_meja= $("#tombol_meja").attr('value');
        number_meja =  number_meja.replace(/[^0-9]+/g, '');
        // alert(no_meja);
        // return false;
        if (mode==0)
        {
          var inc = 0;
           liveSearchPanel_SalesItems.store.each(function (rec) {
            inc=inc+1;
          });
          
          if(inc == 0){
            alert('Tidak ada data, Silahkan isi Items');
            return false;
          }
          //cek meja kosong atau isi
          if(number_meja != ''){
            // alert('no meja tidak kosong\npindah meja dilakukan');
            var r=confirm("pindah meja ?")
            if (r==true)
            {
              $.ajax({
                url:'<?php echo $this->createUrl('sales/pindahmeja');?>',
                data:'meja='+no_meja+"&meja_now="+parseInt($("#head-meja-nilai").html()),
                success: function(){
                  $("#head-meja-nilai").html("");
                  // alert(data);
                  // bayar(0,no_meja);
                  show_meja("Bayar Nanti");
                  
                  $("#dialog_bayar").dialog("close");
                  liveSearchPanel_SalesItems.store.removeAll();
                  $('#sum_sub_total').html(0);
                  $('#sum_sale_discount').html(0);
                  $('#sum_sale_service').html(0);
                  $('#sum_sale_tax').html(0);
                  $('#sum_sale_total').html(0);
                  $('#sum_sale_bayar').html(0);
                  $('#pembayaran').val(0);
                  $('#payment').val(0);
                  $("#e1").select2("close");
                  kalkulasi1();
                  $("#head-meja").html("");

                  
                },
                error: function(data){
                  alert(data);
                }
              });
            }else{
              return false;
              // alert("Meja sedang di buka  !");
            }
              $('#dialog_meja').load('index.php?r=site/table');
              $("#dialog_meja").dialog('close');
          }else{
            // alert("Meja sedang di buka  !");
            var harus =  parseInt($("#sum_sale_total").html()) ;
            var angkabayar = parseInt($("#total_hutang").val()) ;

            // if ($("#tanggal_jt").val()!=""){  
              if(angkabayar<=0 || angkabayar==""){
                alert("Silahkan Masukan Uang pembayaran ");
              }else{
                if (angkabayar>=harus ){
                  alert("Untuk Melunasi, Silahkan Melakukan pembayaran Secara langsung");
                }else{
                  // alert("masuk 2");
                  bayar(0,no_meja);
                  $('#dialog_meja').load('index.php?r=site/table');
                  $("#dialog_meja").dialog('close');
                    $("#head-meja").html("");
					alert("Data berhasil disimpan di Meja "+no_meja+", Silahkan tekan OK untuk melanjutkan");

                }
              }
            
            // }else{
            //   alert("Jatuh Tempo Wajib di isi");
            // }
              // show_meja("Meja");
          }
        }
        else if (number_meja == no_meja)
        {
          // alert("meja sama");
          alert("Slot sedang di buka");
          // alert("mode update holdbill");
          // bayar(0,no_meja,data);
          // show_meja("Meja");
        }
        else
        {
           bukaData(no_meja);
      } 
        
      
      
    }
    <?php //if (Yii::app()->user->getLevel()==2){ ?>
    else if (key == 32){
      var tanya = confirm('yakin hapus pada data sementara? ?');
    if (tanya==true){
       hapusData(no_meja);
    }
    
    
    }
    <?php // } ?>
    
    
    // alert("asdasd");
  }
  function hapusData(no_meja){
       $.ajax({
      url:'<?php echo $this->createUrl('sales/del');?>',
      data:'id='+no_meja,
      success: function(){
        // alert('sukses hapus');
        // bayar(0,no_meja);
          show_meja("Bayar Nanti");
          $('#dialog_meja').dialog('close');
          location.reload();
      // hapusGrid();
        
      },
      error: function(data){
        alert("Terjadi Kesalahan ");
        location.reload();
      }
      });
  }

  function bukaData(no_meja){
  $("#head-meja").html(" | Meja  Aktif : ");
  $("#head-meja-nilai").html(no_meja);
    alert("Membuka Data Sementara No "+no_meja);
    $("#input_items").val("");
    $("#input_items").focus();

    // alert(meja);
    // alert(data);
    load_bill(meja,data);
    // $.ajax({
    // url:'<?php echo $this->createUrl('sales/sessid')?>',
    // data:'id='+data,
    // success:function(data){
    // // alert(data);
    // // alert('triana');

    // // Koding Triana
    // $.ajax({
    // url:'<?php echo $this->createUrl('sales/artimeja') ?>',
    // data : {
    // no_meja : no_meja,
    // },
    // success:function(data){
    // $.ajax({
    // url:'<?php echo $this->createUrl('sales/artimeja') ?>',
    // data : {
    // no_meja : no_meja,
    // },
    // success:function(data){
    // // alert(data);
    // if (data == '4'){
    // // alert('');
    // owner();
    // }
    // },
    // error:function(){
    // alert('Error');
    // }
    // });
    // //##tutup
    // },
    // error:function(){
    // alert('Error');
    // }
    // });


    // // alert('success'+data);
    // },
    // error: function(){
    // // alert('gagal'+data);
    // }

    // });
    show_meja("Data Semantara ("+no_meja+")");
      $("#dialog_meja").dialog("close");

  }
</script>
<script type="text/javascript">
   $(document).ready(function(){
      $(document).on("click",".pilih-no-meja",function(e){
        var meja = $(this).attr("data-meja");
        var sale_id = $(this).attr("data-id");

        load_bill(meja,sale_id);
        $.ajax({
          url:'<?php echo $this->createUrl('sales/sessid')?>',
          data:'id='+sale_id,
          success:function(data){
            var meja2 = "Bayar Nanti ("+meja+")";
            $("#tombol_meja").attr('value', meja2);


          }
        });

        $("#dialog_meja").dialog("close");

        });
      // #tanggal,
        $('#tanggal_jt').datepicker({
           dateFormat: 'yy-mm-dd',
           changeYear: true,
           changeMonth: true
       });
        $(document).on("change","#namapel",function(e){
            var nilai= $(this).val();
            getDiskonMember(nilai);
        });

        $(document).on("click","#tambah-pelanggan-2",function(e){
           e.preventDefault();
           
           // $("#tambah-paket-baru").dialog("open");
             $("#tambah-pelanggan-form").dialog("open");
             $("#Customer_nama").focus();
        });
        // $( "#tambah-paket-baru" ).draggable();
         $(document).on("click","#btn-paket-baru",function(e){
           e.preventDefault();
            $("#tambah-paket-baru").show();
        });

      $(document).on("click","#btn-item-baru",function(e){
           e.preventDefault();
           clearItems();
           
           $("#tambah-item-baru").dialog("open");
            // $("#tambah-item-baru").show();
            // alert("123");
        });




        function getDiskonMember(name){
            $.ajax({
               type: 'GET',
               url: '<?php echo Yii::app()->createAbsoluteUrl("Customer/getDiskonMember2"); ?>',
               // dataType : "json",
               data : "name="+name,
               success:function(data){
                // alert(data);
                var data = JSON.parse(data);
      				var nilai = data.sisa_hutang;
  		    		$("#sum_sale_bayar").html(nilai);
                // alert(data.nilai_diskon);
                if (parseInt(data.nilai_diskon)>0){
                    var c = confirm("Terdapat Diskon untuk member ini, terapkan diskon "+data.nilai_diskon+"% ?");
                    if (c==true){
                      editdiskongrid(data.nilai_diskon);
                      kalkulasi1();
                    } 
                }
                  // $("#namapel").html(data).trigger("change");
                  // $("#namapel").val("umum").trigger("change");
               },
               error:function(data){
                console.log(data);
                   // alert(JSON.stringify(data));
               },

               dataType:'html'
           });
        }
        function getCustomer(selected=""){
           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::app()->createAbsoluteUrl("Customer/GetCustomer"); ?>',

               success:function(data){
              
                  $("#namapel").html(data).trigger("change");
                  if (selected!=""){
                    $("#namapel").val(selected).trigger("change");
                  }
               },
               error:function(data){
                   // alert(data);
                   alert(JSON.stringify(data));
               },

               dataType:'html'
           });
        }
        getCustomer();
        
     


        //   $(document).on("submit","#items-satuan-master-form",function(e){
        //    e.preventDefault();
        //     var data = $(this).serializeArray();
        //     // alert(data);
        //     data.push({ name: "isajax", value: "true" });
        //     // alert(data);

        //    $.ajax({
        //        type: 'POST',
        //        url: '<?php echo Yii::app()->createAbsoluteUrl("ItemsSatuanMaster/create"); ?>',
        //        data:data,
        //        success:function(data){
        //            // alert(data);
        //            if (data=="sukses"){
        //             getSatuan();
        //                 $(" #tambah-satuan-form").dialog("close");
        //            }
        //            else
        //                alert(data);
        //        },
        //        error:function(data){
        //            // alert(data);
        //            alert(JSON.stringify(data));
        //        },

        //        dataType:'html'
        //    });
        // });

        $(document).on("submit","#customer-form",function(e){
           e.preventDefault();
            var data = $(this).serializeArray();
            // alert(data);
            data.push({ name: "isajax", value: "true" });
            // alert(data);

           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::app()->createAbsoluteUrl("Customer/create"); ?>',
               data:data,
               success:function(data){
                   // alert(data);
                   if (data=="sukses"){
                        var nama = $("#Customer_nama").val();
                        // $("#Customer_nama")
                        // alert(nama);
                        // $("#namapel").val(nama).trigger("change");
                       getCustomer(nama);
                        // $*""
                        // alert("nama");
                        $("#reset-btn").trigger("click");
                        $("#tambah-pelanggan-form").dialog("close");
                   }
                   else
                       alert(data);
               },
               error:function(data){
                   // alert(data);
                   alert(JSON.stringify(data));
               },

               dataType:'html'
           });
        });

        $(document).on("submit","#items-form",function(e){
           e.preventDefault();
            var data = $(this).serializeArray();
            // alert(data);
            data.push({ name: "isajax", value: "true" });
            // alert(data);

           $.ajax({
               type: 'POST',
               url: '<?php echo Yii::app()->createAbsoluteUrl("Items/create"); ?>',
               data:data,
               success:function(data){
                   // alert(data);
                   if (data=="sukses"){
                        reloadItems();
                        clearItems();
                        $("#tambah-item-baru").dialog("close");
                        generateBarcodeAction();
                        // $("#items-form").trigger("reset");
                        // var nama = $("#Customer_nama").val();
                        // $("#Customer_nama")
                        // alert(nama);
                        // $("#namapel").val(nama).trigger("change");
                       // getCustomer(nama);
                       //  // $*""
                       //  alert("nama");
                       //  $("#reset-btn").trigger("click");
                   }
                   else{
                       alert(data);
                   }
               },
               error:function(data){
                   // alert(data);
                   alert(JSON.stringify(data));
               },

               dataType:'html'
           });
        });


   });

    function travel(){
                var customer_type = $('#custype').val();
                // alert(customer_type);

                // var chk = $("#chkService").val();
                //ambil data dari summary kanan
                var subtotal = $('#sum_sub_total').html();
                var discount =$('#sum_sale_discount').html();
                var tax =$('#sum_sale_tax').html();
                var service=$('#sum_sale_service').html();
                var total_cost=$('#sum_sale_total').html();

                $('#sum_sale_service').html(0);
                $('#sum_sale_tax').html(0);
                $('#sum_sale_total').html(parseInt(subtotal)-parseInt(discount));

                //mulai
        var data_detail = [];
        var inc = 0;
        liveSearchPanel_SalesItems.store.each(function (rec) {
            //        var temp = new Array(10,10);
            //        temp['item_price'].push(rec.get('item_total_cost'));
            //        data_detail[0]['quantity_purchased']=rec.get('quantity_purchased');
            data_detail[inc] = {
                "item_id":rec.get('item_id'),
                "quantity_purchased":rec.get('quantity_purchased'),
                "item_tax":rec.get('item_tax'),
                "item_name":rec.get('item_name'),
                "item_discount":rec.get('item_discount'),
                   "item_price":rec.get('item_price'),
                "permintaan":rec.get('permintaan'),
                   "item_total_cost":rec.get('item_total_cost')
           };
           inc=inc+1;
        console.log(data_detail);
        });
        //remove isi grid
        liveSearchPanel_SalesItems.store.removeAll();

            for (i = 0; i < data_detail.length; i++) {
            // alert(data_detail[i].name);
            // var hargatotal = data_detail[i].quantity_purchased * data_detail[i].item_price;
            // var potongan = (hargatotal*ediskon)/100;
            // var itcost = hargatotal-potongan+data_detail[i].item_tax;

            var r = Ext.create('SalesItems', {
                   item_id:  data_detail[i].item_id,
                   quantity_purchased:data_detail[i].quantity_purchased,
                   item_tax: 0,
                   item_name: data_detail[i].item_name,
                   item_price:data_detail[i].item_price,
                   // item_discount: val.item_discount,
                   item_discount: data_detail[i].item_discount,
                   item_total_cost: data_detail[i].item_price * data_detail[i].quantity_purchased
            });
            // alert(i);
            liveSearchPanel_SalesItems.store.insert(0, r);
              kalkulasi1();
        }
   }
   //Koding Triana##buka
       function owner(){
        var customer_type = $('#custype').val();
           var subtotal = $('#sum_sub_total').html();
           var discount =$('#sum_sale_discount').html();
           var tax =$('#sum_sale_tax').html();
           var service=$('#sum_sale_service').html();
           var total_cost=$('#sum_sale_total').html();
           $('#sum_sale_tax').html(0);
           $('#sum_sale_total').html(parseInt(subtotal)+parseInt(service));
           // kalkulasi1();

           var data_detail = [];
           var inc = 0;
           liveSearchPanel_SalesItems.store.each(function (rec) {
               //        var temp = new Array(10,10);
               //        temp['item_price'].push(rec.get('item_total_cost'));
               //        data_detail[0]['quantity_purchased']=rec.get('quantity_purchased');
               data_detail[inc] = {
                   "item_id":rec.get('item_id'),
                   "quantity_purchased":rec.get('quantity_purchased'),
                   "item_tax":rec.get('item_tax'),
                   "item_name":rec.get('item_name'),
                   "item_discount":rec.get('item_discount'),
                   "item_service":rec.get('item_service'),
                   "item_price":rec.get('item_price'),
                   "permintaan":rec.get('permintaan'),
                   "item_total_cost":rec.get('item_total_cost')
           };
           inc=inc+1;
           console.log(data_detail);
           });
           //remove isi grid
           liveSearchPanel_SalesItems.store.removeAll();

               for (i = 0; i < data_detail.length; i++) {
               // alert(data_detail[i].name);
               // var hargatotal = data_detail[i].quantity_purchased * data_detail[i].item_price;
               // var potongan = (hargatotal*ediskon)/100;
               // var itcost = hargatotal-potongan+data_detail[i].item_tax;

               var r = Ext.create('SalesItems', {
                   item_id:  data_detail[i].item_id,
                   quantity_purchased:data_detail[i].quantity_purchased,
                   item_tax: 0,
                   item_name: data_detail[i].item_name,
                   item_price:data_detail[i].item_price,
                   item_service: data_detail[i].item_service,
                   item_discount: data_detail[i].item_discount,
                   item_total_cost: data_detail[i].item_price * data_detail[i].quantity_purchased + data_detail[i].item_service
               });
               // alert(i);
               liveSearchPanel_SalesItems.store.insert(0, r);
              // kalkulasi1();
           }

       }
       //##tutup

</script>
<script>
   function add_item_new(item_id){ // add with barcode
        $.ajax({
           url : 'index.php?r=items/checkbarcode',
           data : 'id='+item_id,
           success : function(data)

           {


               // alert(data);
               if ($("#qty").val()==0 || $("#qty").val()=="" ){
                   $().toastmessage('showToast', {
                       text : "Item tidak boleh 0",
                       sticky : false,
                       type     : 'error'
                   });
                   $("#input_items").val("");
                   $("#input_items").focus();

                   exit;
               }
               if (data=="error"){
                // alert("123 ok");
                   $().toastmessage('showToast', {
                       text : "Data dengan kode "+id+" tidak ditemukan",
                       sticky : false,
                       type     : 'warning'
                   });

                   $("#input_items").val("");
                   $("#wrapper-item-search").show();
                   $("#full-screen").show();
                   $("#e1").focus();
                   $("#e1").select2("open");
                   exit;
               }
               var obj = jQuery.parseJSON(data);
               var total = obj.total_cost * $("#qty").val();
               // alert(obj.discount);

               if ($("#costumer_type").val()==1) {
                   obj.total_cost  = obj.total_cost;
               }else if ($("#costumer_type").val()==2) {
                   obj.total_cost  = obj.price_distributor;
               }else if ($("#costumer_type").val()==3) {
                   obj.total_cost  = obj.price_reseller;
               }


               if (obj.discount==0)
                   var dc =  $("#discount").val();
               else{
                   var message = "Item "+obj.item_name+" mendapatkan discount "+obj.discount+" %";
                   $().toastmessage('showToast', {
                       text : message,
                       sticky : false,
                       type     : 'success'
                   });
                   var dc =  obj.discount;
               }


               var discount = total * dc / 100;
               // }else{
            //        var discount = total * obj.discount  / 100;
               // }
               // var tax = $("#qty").val()*obj.tax_percent;

               // alert(persen_svc);
               var tax = $("#qty").val()*obj.total_cost*persen;
               var service = $("#qty").val()*obj.total_cost*persen_svc;


               // var taxdisc = tax*10/100 ;
               // var totaltax = tax - taxdisc;
               // var total_cost = total-discount+totaltax;
               var total_cost = total-discount+tax;
               id_inc = id_inc + 1;

               liveSearchPanel_SalesItems.store.each(function (rec) {
                   if (rec.get('item_name')==obj.item_name){
                           var grid = liveSearchPanel_SalesItems;
                           var row = grid.store.indexOf(rec);
                           var models = grid.getStore().getRange();
                           models[row].set("quantity_purchased",(models[row].get("quantity_purchased")+parseInt($("#qty").val())));
                           models[row].set("item_tax",(models[row].get("item_price")*persen)*models[row].get("quantity_purchased"));
                           models[row].set("item_service",(models[row].get("item_price")*persen_svc)*models[row].get("quantity_purchased"));
                           models[row].set("item_total_cost",models[row].get("item_tax")+models[row].get("item_service")+(models[row].get("item_price")*models[row].get("quantity_purchased")));kalkulasi1();
                           $("#input_items").val("");
                           $("#input_items").focus();
                           exit;
                   }
                   // alert(models);

               });
               // alert(liveSearchPanel_SalesItems.getStore().getRange());

               // alert(obj.id);
               var r = Ext.create('SalesItems', {
                   id : id_inc,
                   item_id:  obj.id,
                   quantity_purchased: $("#qty").val(),
                   // item_tax: obj.tax_percent*$("#qty").val(),
                   item_service: service,
                   item_tax: tax,
                   lokasi: obj.lokasi,
                   item_name: obj.item_name,
                   item_price:obj.total_cost,
                   item_discount: dc,
                   item_total_cost:total_cost

               });

               // alert(JSON.stringify(r));
               liveSearchPanel_SalesItems.store.insert(0, r);
               var sum = 0;
               discount = 0;
               tax = 0;
               var subtotal = 0;
               kalkulasi1();

               // $("#e1").select2("open");
               //  $('#ext_sales_items-body').animate({
               //   scrollTop: 10000
               // }, 1000);

               $("#input_items").val("");
               $("#input_items").focus();

           },
           error : function(data)
           {
             // alert(data);

           }
       });
   }
       $(document).ready(function() {

           $("#input_items").focus();

           $("#input_items").keypress(function(e){
            // e.preventDefault();
               if (e.keyCode == 13){
                // alert("123");
                   var item_id = $("#input_items").val();
                   // alert(item_id);
                   add_item(item_id);
               }
           });

           $(document).on("click",".close",function(e){
                $("#wrapper-item-search").hide();
                $("#full-screen").hide();
           });

           // $(document).on("keyup",".select2-input",function(e){

           //  // alert("456");
           //  // alert(e.which);
           // });
           $(document).on("change","#e1",function(e){
            // alert("123");
            console.log("ditemukan");
               var item_id = $("#e1").val();
               // alert(item_id);
               if (item_id!=''){
                // alert("123");
                   add_item(item_id);
                   $("#e1").select2("open");
                   $('#e1').val(0);
               }else{
                    $().toastmessage('showToast', {
                       text : "Data tidak ditemukan",
                       sticky : false,
                       type     : 'warning'
                   });
               }
           });
           // ('body').keypress(function(event){
           $("#e1").select2({
              closeOnSelect : true,
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              minimumInputLength: 2,
           });
           // $("#bank-kredit,#bank-debit").select2({
              // closeOnSelect : true,
              // escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              // minimumInputLength: 2,
           // });

           $(".select-pel").select2({
               closeOnSelect : true
           });


           $('#bank-kredit').on("change",function(){
              $("#bank-debit").val(0);
           });
           $('#bank-debit').on("change",function(){
              $("#bank-kredit").val(0);
           });
           $('#edcniaga').on("click",function(){
              $("#bank-debit").val(0);
           });
            $('#edcbca').on("click",function(){
              $("#bank-kredit").val(0);
           });

           $('.select-pel').on("change",function(){
               if (this.value=='umum'){
                   // $('#umum-value').css("opacity","1");
                   $("#umum-value").css("opacity",1);
                   $('#umum-value').removeAttr("readonly");
                   $('#umum-value').focus();
               }else{
                   $('#umum-value').attr("readonly","1");
                   $('#input_items').focus();
                   $("#umum-value").css("opacity",0);

               }
           });

           $('#costumer_type').on("change",function(){
                liveSearchPanel_SalesItems.store.removeAll();
                kalkulasi1();
           });
           $('#qty').on("keypress",function(e){
            // e.preventDefault();
            if (e.keyCode=="13"){
                add_item($("#input_items").val());
              // alert("123");
            }
           });
           // $('#e1').on("change",function(){
               // $("#e1").select2('close',function(){
               //     $("#qty").focus();
               // });
           // });

        // $("#cash").on("keyup",function(){
            // var bayar = $("#cash").val();
            // $("#cash").val(bayar.replace(/[^\d,]+/g, ''));
            // bayar = $("#cash").val()+$("#edc").val()+$("#voucher").val()+$("#compliment").val()+$("#dll").val();
            // // bayar = bayar.replace(/[^\d,]+/g, '');
            // var sum_sale_total = $("#sum_sale_total").html();
            // var kembalian = bayar-sum_sale_total;
            // $("#kembalian").val(kembalian);
            // // alert('asdasd');
        // });

       });
        function changebayar(){
               // var my = $('.myinput').val();
               // if (my.length==''){
               //     $('.myinput').val(0);
               // }
               // alert(my);
            var bayar = $("#cash").val();
            $("#cash").val(bayar.replace(/[^\d,]+/g, ''));
            bayar = parseInt($("#cash").val())+parseInt($("#edcbca").val())+parseInt($("#edcniaga").val())+parseInt($("#creditbca").val())+parseInt($("#creditmandiri").val())+parseInt($("#voucher").val())+parseInt($("#compliment").val())+parseInt($("#dll").val());
            // bayar = bayar.replace(/[^\d,]+/g, '');
            var sum_sale_total = $("#sum_sale_total").html();
            var kembalian = bayar-sum_sale_total;
            $("#kembalian").val(kembalian);
            $("#cash").val(parseInt($("#sum_sale_total").html())-parseInt($("#edcbca").val())-parseInt($("#edcniaga").val())-parseInt($("#creditbca").val())-parseInt($("#creditmandiri").val())-parseInt($("#voucher").val())-parseInt($("#compliment").val())-parseInt($("#dll").val()));
            // $("#voucher").val("haha");


            // alert('asdasd');
        };

</script>
<script>
   $('#sum_sale_discount2').change(function(){
    var disc = $('#sum_sale_discount2').val();
    alert(disc);
   });

      function test(){
         var service=$('#sum_sale_service').html();
         alert('service : '+service);
      }
      //script buat disable tombol
      function disable(event) {
          switch (event.which){
              //116 itu key code nya F5
              case 112: event.preventDefault(); break;
              case 113: event.preventDefault(); break;
              case 114: event.preventDefault(); break;
              case 115: event.preventDefault(); break;
              case 116: event.preventDefault(); break;
              case 117: event.preventDefault(); break;
              case 118: event.preventDefault(); break;
              case 119: event.preventDefault(); break;
              case 120: event.preventDefault(); break;
              case 121: event.preventDefault(); break;
          }
      };
      // disable F5
      $(document).bind("keydown", disable);
      // $(document).on("keypress,keydown","body",function(e){
      $('body').keyup(function(e){
        // alert(e.which);
        if(e.which === 114 || e.which === 112) {
         return false;   
        }
      });
      $('body').keyup(function(event){

      // });
      // $('body').keypress(function(event){
          //message gan, buat info kode2 tombol doank
          var message = '<BR>ada tombol yg di pencet gan!, keyCode = ' + event.keyCode + ' which = ' + event.which;
          // alert(event.which);
          // alert(event.keyCode);
          //cek kalo keycodenya > 0 berarti ada tombol f1 - f12 + enter (kode 13) yg agan pencet
          if (event.keyCode>=0 || event.charCode>=0 || event.which>=0 ){
            // alert("123");
                // event.preventDefault();
              message = message + '<BR>F1 - F12 / enter pressed';
              list_action(event.keyCode);
          }else{
            // alert("456");
              list_action_other(event.which);
              message = message + '<BR>key other than F1 - F12 pressed';
          }

          //print pesan
          $('#msg-keypress').html(message)

      });


      function kalkulasi1()
      {
          var sum = 0;
          var discount = 0;
          var tax = 0;
          var svc = 0;
          var subtotal = 0;
    var voucher = 0;
    voucher = $("#vouchernominal").val();
    var bayar = parseInt($("#sum_sale_bayar").html());
          liveSearchPanel_SalesItems.store.each(function (rec) {
              sum += rec.get('item_total_cost');
              discount += rec.get('item_discount') * (rec.get('item_price')*rec.get('quantity_purchased')) /100 ;

              // tax += rec.get('item_tax')*rec.get('quantity_purchased');
              tax += rec.get('item_tax');
              svc += rec.get('item_service');
              subtotal += (rec.get('item_price')*rec.get('quantity_purchased'));
          });

    // tax = (subtotal-discount)/10;

          $('#sum_sub_total').html(Math.round(subtotal));
          $('#sum_sale_discount').html(Math.round(discount));
          $('#sum_sale_tax').html(Math.round(tax));
          service = svc;
           // service  =    var_service * (subtotal-discount)/ 100;
          //service  = 0;


          $('#sum_sale_service').html(Math.round(service));
          var total_akhir =Math.ceil(subtotal-discount-voucher+service+tax+bayar);
          $('#sum_sale_total').html(total_akhir);
          $('#sum_sale_total2').html( format(total_akhir.toString()) );


          $('#total_bayar').html(Math.ceil(subtotal-discount-voucher+tax+service-bayar));



      }
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


    function list_action(act)
      {
    var sum_sale_total = $("#sum_sale_total").html();
    var kembalian = estimate($("#total_bayar").html())-sum_sale_total;
    // alert(act);
          switch(act)
          {
              case 112 :
                          // alert($('#sum_sale_total').html());
                          // if ( parseInt($('#sum_sale_total').html()) !=0 ){
                          // alert(JSON.stringify(data_detail));
          $("#e1").select2("close"); $("#pembayaran").val(estimate($("#total_bayar").html()));
          $("#dialog_bayar").dialog("open");
          $("#pembayaran").focus();
          $("#kembalian").val(kembalian);
          $('#cash').val(sum_sale_total);
          $('#edcbca').val(0);
          $('#edcniaga').val(0);
          $('#creditbca').val(0);
          $('#creditmandiri').val(0);
          $('#compliment').val(0);
          $('#voucher').val(0);
          $('#dll').val(0);
          changebayar();
          $('#total_bayar').html($('#sum_sale_total').html());
                    // }
                          // else{
                          //     alert('Silahkan isi item terlebih dahulu .. ');
                          // }

                          break;

              case 27 :
                  $("#full-screen").hide();
                  $("#wrapper-item-search").hide();
                  $("#input_items").focus();

              break;
              case 113 :

                  $("#full-screen").show();
                  $("#wrapper-item-search").show();
                  // $("#e1").select2("close");
                  $("#e1").select2("open");

                  // alert('123');
                  break;
            case 115 :
            hapusGrid();
            break;
            case 114 :
              // e.preventDefault();
              // $('#dialog_meja').load('index.php?r=site/table');

              $.ajax({
                url : 'index.php?r=site/table',
                success : function(d){
                  $("#dialog_meja").html(d);
                  $("#dialog_meja").dialog("open");

                  $("#e1").select2("close");
                  // $("#meja").focus();
                  // $('#meja option').eq(0).focus();
                  // var selectedValues = new Array();
                  // selectedValues[0] = "1";
                  // selectedValues[1] = "2";
                  $("#meja option[nilai='1']").prop("selected", true);
                  // $('#meja').val(selectedValues);

                  // $('#meja option[nilai="1"]').attr("selected",true);
                  // $("#meja opti")
                  // alert("123");

                  }
              });
             

                break;
              case 115 :  $("#e1").selectedt2("close"); $("#e1").select2("close"); liveSearchPanel_SalesItems.getView().focus(); liveSearchPanel_SalesItems.getView().focus(); liveSearchPanel_SalesItems.getSelectionModel().select(0);
                  break;
              case 116 : baru(); kalkulasi1(); break;
              case 118 :
                         if ( parseInt($('#sum_sale_total').html()) !=0 ){
                          cetakbill();
                          }else{
                             alert('Silahkan isi item terlebih dahulu ..');
                          }
                          break;
              case 119 : 
              $("#cetakrekap").trigger("click");
              // hutang();

               break;
        case 120 :
            var number_meja= $("#tombol_meja").attr('value');
            number_meja =  number_meja.replace(/[^0-9]+/g, '');
            if (number_meja!=''){

                    $.ajax({
                        url:'<?php echo $this->createUrl('sales/getsaleid2')?>',
                        // data:'id='+data,
                        success:function(data){
                            // alert('success :'+data);
                            bayar(0,number_meja,data);
                            alert("Meja Nomor "+number_meja+" telah berhasil di update!");
                            $("#head-meja").html("");



                        },
                        error: function(){
                            // alert('gagal'+data);
                        }

                    });
            }
            else
                alert('Belum Membuka Data Sementara');
            // // alert(number_meja);

         break;
          }

      }
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


   function list_action_other(act)
   {
   switch(act)
   {
    case 109 : $('#payment option[value="2"]').attr("selected",true); break;
    case 99 : $('#payment option[value="1"]').attr("selected",true); break;
    //            case 113 : alert('f1'); break;
    //            case 114 : alert('f1'); break;
    //            case 115 : alert('f1'); break;
   }
   }

   // function editdiskon(){
   // //ambil nilai dari combo diskon
   //   if ( parseInt($('#sum_sale_total').html()) !=0 ){

   // var diskon = $('#discount').val();

   // var subtotal = $('#sum_sub_total').html();
   //    var discount = (subtotal/10);
   //    var tax = $('#sum_sale_tax').html();
   //    var service = 0;
   //    var total_cost = parseInt(subtotal) - parseInt(discount) + parseInt(tax) + parseInt(service);

   // $('#sum_sub_total').html(subtotal);
   // $('#sum_sale_discount').html(discount);
   // $('#sum_sale_service').html(service);
   // $('#sum_sale_tax').html(tax);
   // $('#sum_sale_total').html(total_cost);
   //    }else{
   //        alert('Silahkan isi menu');
   //    }
   // }

   function hanyacetak(status,table,sale_id)
   {
   //alert(sale_id);
   // return;
   var subtotal = $('#sum_sub_total').html();
   var discount =$('#sum_sale_discount').html();
   var tax =$('#sum_sale_tax').html();
   var service=0;
   var total_cost=$('#sum_sale_total').html();
   var payment=$('#pembayaran').val();
   var paidwith=$('#payment').val();
   data = {
   sale_id : sale_id,
   subtotal : subtotal,
   discount : discount,
   tax : tax,
   service : service,
   total_cost : total_cost,
   payment : payment,
   paidwith : paidwith,
   status : status,
   table : table
   };
   var data_detail = [];
   var inc = 0;
   liveSearchPanel_SalesItems.store.each(function (rec) {
   //        var temp = new Array(10,10);
   //        temp['item_price'].push(rec.get('item_total_cost'));
   //        data_detail[0]['quantity_purchased']=rec.get('quantity_purchased');
   data_detail[inc] = {
    "item_id":rec.get('item_id'),
    "quantity_purchased":rec.get('quantity_purchased'),
    "item_tax":rec.get('item_tax'),
    "item_discount":rec.get('item_discount'),
    "item_price":rec.get('item_price'),
    "item_total_cost":rec.get('item_total_cost')
   };
   inc=inc+1;
   });
   //    console.log(data_detail);

   $.ajax({
   url : 'index.php?r=sales/hanyacetak',
   data : {
      data:data,
      data_detail:data_detail
   },
   success : function(data)
   {
      var sales = jQuery.parseJSON(data);
      if (sales.sale_id!="")
      {
          print_bayar(sales);
          //$.each(sales.detail, function(i,dani) {
          // alert(dani.quantity + " " + dani.nama_item);
          // var total_cetak = dani.logo + dani.alamat;
          //});


          $("#dialog_bayar").dialog("close");

          liveSearchPanel_SalesItems.store.removeAll();
          $('#sum_sub_total').html(0);
          $('#sum_sale_discount').html(0);
          $('#sum_sale_tax').html(0);

          $('#sum_sale_total').html(0);
          $('#pembayaran').val(0);
          $('#payment').val(0);
          $("#e1").select2("close");
          $('#dialog_meja').load('index.php?r=site/table');
          //print_bayar(data);
          // show_meja('Meja');
      }


   },
   error : function(data)
   {
      alert(data);
      $('#dialog_meja').load('index.php?r=site/table');
   }
   });
   }

</script>
<!--div id="msg-keypress"></div-->
<?php
   $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
       'id' => 'dialog_bayar2',
       // additional javascript options for the dialog plugin
       'options' => array(
           'title' => 'Pembayaran Uang Cash',
           'autoOpen' => false,
           'modal' => true,
        'width' => 400,
       ),
   ));

   $this->renderPartial('payment_next');

   $this->endWidget('zii.widgets.jui.CJuiDialog');
   ?>
<?php
   $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
       'id' => 'tambah-pelanggan-form',
       // additional javascript options for the dialog plugin
       'options' => array(
           'title' => 'Tambah pelanggan',
           'autoOpen' => false,
           'modal' => true,
           'width' => 400,
       ),
   ));
   echo "Pelanggan Baru";
   $model = new Customer;
   $this->renderPartial('application.views.customer._form',array("model"=>$model));

   $this->endWidget('zii.widgets.jui.CJuiDialog');
   ?>

   <?php
  //  $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
  //      'id' => 'tambah-paket-baru',
  //      // additional javascript options for the dialog plugin
  //      'options' => array(
  //          'title' => 'Tambah paket',
  //          'autoOpen' => false,
  //          'modal' => true,
  //          'width' => 400,
  //      ),
  // ));
  
   // $this->renderPartial('application.views.items.createpaket');

   // $this->endWidget('zii.widgets.jui.CJuiDialog');

   $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
       'id' => 'tambah-item-baru',
       // additional javascript options for the dialog plugin
       'options' => array(
           'title' => 'Tambah Item',
           'autoOpen' => false,
           'modal' => true,
           'width' => 700,
       ),
  ));
    $model = new Items;
   $this->renderPartial('application.views.items._form',array("model"=>$model));

   $this->endWidget('zii.widgets.jui.CJuiDialog');


   $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
       'id' => 'dialog_bayar',
       // additional javascript options for the dialog plugin
       'options' => array(
           'title' => 'Metode Pembayaran',
           'autoOpen' => false,
           'modal' => true,
        'width' => 600,

       ),
   ));

   $this->renderPartial('payment');

   $this->endWidget('zii.widgets.jui.CJuiDialog');

   ?>
<?php if (Yii::app()->user->getLevel()==2){ ?>
<?php
   $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
       'id' => 'dialog_meja',
       // additional javascript options for the dialog plugin
       'options' => array(
           // 'title' => 'Sementara (Space = hapus  |  Enter = simpan/update/pindah meja)',
           'title' => 'Data Sementara',
           'autoOpen' => false,
           'modal' => true,
           'width' => 205,
           'height' => 665
       ),
   ));
   $this->renderPartial('table');
   $this->endWidget('zii.widgets.jui.CJuiDialog');
   ?>
<?php } else{ ?>
<?php
   $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
       'id' => 'dialog_meja',
       // additional javascript options for the dialog plugin
       'options' => array(
           'title' => 'Data Sementara',
           'autoOpen' => false,
           'modal' => true,
           'width' => 700,
           'height' => 500
       ),
   ));
   $this->renderPartial('table');
   $this->endWidget('zii.widgets.jui.CJuiDialog');
   ?>
<?php } ?>
<?php
   $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
       'id' => 'dialog_menu',
       // additional javascript options for the dialog plugin
       'options' => array(
           'title' => 'Menu',
           'autoOpen' => false,
           'modal' => true,
           'width' => 1000,
           'height' => 400,
       ),
   ));
   ?>
<div><?php $this->renderPartial('menu'); ?> </div>
<?php
   //echo "ramdnai";

   $this->endWidget('zii.widgets.jui.CJuiDialog');
   ?>

<!--<input type=button onClick="opencash()" value="Buka Drawer" class="mybutton">-->
<!--input type=button onClick="cekisigrid()" value="cek isi grid" class="mybutton"-->
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="jZebra/jzebra/jzebra.jar" width="0" height="0">
   <param name="printer" value="zebra">
</applet>
<!--div id="msg-keypress">here press</div-->

 