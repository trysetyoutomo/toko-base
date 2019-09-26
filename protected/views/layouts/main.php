<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/jquery/dist/jquery.min.js"></script>

  <!-- Bootstrap -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/bootstrap/dist/js/bootstrap.min.js"></script>


	<?php //Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php Yii::app()->getClientScript()->registerCoreScript('jquery.ui'); ?>
<!--
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui/jquery-ui-timepicker-addon.js"></script>
-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
  <style type="text/css">
      .list-button .btn-nevy{
        width: 100%!important;
      }
      .container-fluid{
        width: 800px;
      }      
      #footer{
        position: absolute;
        bottom: 0px;
        width: 100%;

      }
      body{
        margin:10px;
      }
      .btn-nevy,.btn-primary{
        background: #2E4057 !important;
        color:white!important;
        margin-bottom: 5px!important;
      }
      .btn-danger{
        margin-bottom: 5px!important;
      }
  </style>
</head>
<?php  
$service =  Parameter::model()->findByPk(1); 
$service =  $service->service;

$parameter = Parameter::model()->findByPk(1);
?> 
<input type="text" value="<?=$service?>" id="parameter-service" style="display:none"/>
<input id="parameter-pajak" type="text" value="<?=$parameter->pajak?>" style="display:none"/>


<script>
    var sale_id;
//    var	 var_service = <?php	$test =  Yii::app()->file->set('service.txt');  echo $test->contents; ?>;
    var	 var_service = <?php echo $service; ?>
	//alert(var_service);
</script>
<body>
<?php //echo "user id : ".Yii::app()->user->name; ?>
<div id="header">
   

	<div id="header-in">



    <div class="admin-bar" style="float: left;width: 100px;position: absolute;left: 40px;text-align:center">
      <?php 
            $parameter = Parameter::model()->findByPk(1);
      ?>
                  <a class="auth-brand text-center" href="#">
                      <img style="width: 65px"  class="brand-img img"  src="<?php echo Yii::app()->request->baseUrl; ?>/logo/<?php echo $parameter->gambar ?>" alt="">
                  </a>
     <!-- <h4 class="text-center"><?php  echo SiteController::getConfig("company_name"); ?></h4> -->
    <span id="head-meja"></span><span id="head-meja-nilai"></span>

    </div>
		<div class="admin-bar" style="float: right;width: 250px;position: absolute;right: 20px;text-align:center">
            <ul>
                <li><a href="#" id="data-user"><?php echo Yii::app()->user->name; ?></a></li>
				<li><a href="<?php echo $this->createUrl('sales/index');?>">Laporan</a></li>
				 <!-- <li><a href="<?php echo $this->createUrl('site/waiter');?>">Halaman Waiter</a></li> -->
				 <!--waiterkasir-->
<!--                <li><a href="#">Setting</a></li> -->
				<?php if (!Yii::app()->user->isGuest) {?>
                <li><a href="<?php echo $this->createUrl('site/logout'); ?>">Keluar</a></li>		
				<?php } else { ?>
                <li><a href="<?php echo $this->createUrl('site/login');?>">Masuk</a></li>		
				<?php }?>
            </ul>
		</div>
        <div id="header-logo"></div-->
<!--		<div id="mainmenu">
           
        </div><!-- mainmenu -->
	</div>
</div>
<div id="navigasi">

</div>
<div id="mid-content">
    <div class="" id="page">
        
        <style>
         p,h1,h2,h3,h4,h5,a,td,th,label,body{
           font-family: 'Open Sans', sans-serif!important;
         }
        element.style {
        width: 100000px;
   		 }
       #footer nav ul li {
        display: block;
        float: left;
        font-size: 15px;
        padding: 7px;


       }

   		 #navigasi,#footer,.tb_kanan{
   		  background: #2E4057 !important;
   		  color:white;
   		 	
   		 }
   		 #sum_sale_total2{
   		 	font-size: 25px;
   		 }
   		 body{
   		 	background: white !important
   		 }
        </style>

        <!-- <div id="pos-content">
           
          </h3>
        </div>    
        </div>
            -->
      <div class="row main-itama" style="margin-top:30px;margin-left: 10px">



          <div class="col-sm-2 list-button">
            <div class="row">
            <div class="col-12">
              <b style="color:red">Tanggal Transaksi</b><br>
              <input readonly="" type="text" name="tanggal" id="tanggal" 
              value="<?php echo date("Y-m-d") ?>" style="padding: 1px;width: 100%">
            </div>

            <?php $list = CHtml::listData(CustomerType::model()->findAll(), 'id', 'customer_type');?>
            <div class="col-12">
              <b style="color:red">Tanggal Jatuh Tempo</b>
              <input type="text" name="tanggal" id="tanggal_jt"
              value="<?php //echo date("Y-m-d",strtotime()) ?>" style="padding: 1px;width: 100%">
            </div>
            <div class="col-12"> 
              <b style="color:red">Customer</b>
          
              <div class="col" class="col-sm6">
              <select style="width:100%;" class="select-pel" id="namapel"></select>
               <input style="display: none;" type="text" style="display:inline;padding:5px;width: 100%" class="umum-value" id="umum-value" placeholder="Nama pelanggan Umum" >
              </div>
              <div class="col-sm6" >
                
               <button type="button"  class="btn btn-nevy"  id="tambah-pelanggan-2">Tambah</button>
              </div>
            </div>

              <div style="display: none;">

              <?php echo CHtml::button('Bayar Nanti', array('id' => 'tombol_meja', 'onclick' => 'klikmeja()', 'class' => ' btn btn-nevy', 'style'=>'text-align:center')); ?>

              </div>

              <!-- <input type=button onClick="print_bayar()" value="Cetak2" class="btn btn-nevy"> -->
              <!-- <div class="row" class="list-button-action"> -->
                <hr>
                <div class="col-12">
                  <input style="width:100%!important" id="pay" type="button" value="Bayar" onclick='list_action(112);' class="btn btn-danger" >
                </div>

                <div class="col-12">
                  <input type=button onClick="editdiskongrid($('#discount').val())" value="Edit Diskon" class="btn btn-nevy" id="ditditvoc">
                </div>
                <div class="col-12">
                  <input  type="button" onClick="hapusGrid()" value="Hapus Semua" class="btn btn-nevy">
                  
                </div>
                <div class="col-12">
                  <?php 
                  if ($usaha=="Restauran"){ ?>
                  <input type=button onClick="cetakbill()" value="Cetak" class="btn btn-nevy">
                  <input  type="button" id="cetakrekap" value="Cetak Rekap" class="btn btn-nevy">
                  <input type=button onClick="cetakbillterakhir()" value="Struk Terakhir" class="btn btn-nevy"> 
                  <?php } ?>
                  <input  type="button" value="Item Baru" id="btn-item-baru" class="btn btn-nevy">
                </div>
              </div>
              <input style="display:none" type=button id="cetakdapur" onClick="cetakdapur()" value="Cetak Dapur" class="btn btn-nevy">
              <input style="display:none" type=button id="cetakbar" onClick="cetakbar()" value="Cetak Bar" class="btn btn-nevy">
              <!-- <input type=button  onClick="cetakbardapur()" value="Cetak Bar & Dapur" class="btn btn-nevy"> -->
              <?php 
              if ($usaha=="Restauran"){ ?>
              <input type=button onClick="cetakbill()" value="Cetak" class="btn btn-nevy">
              <input  type="button" id="cetakrekap" value="Cetak Rekap" class="btn btn-nevy">
              <input type=button onClick="cetakbillterakhir()" value="Struk Terakhir" class="btn btn-nevy"> 
              <?php } ?>
              <!-- <input  type="button" value="Item Baru" id="btn-item-baru" class="btn btn-nevy"> -->


          </div>
          <div class="col-sm-7" >
             <?php echo $content; ?>
          </div>
          <div class="col-sm-3" ">
                  <div style="margin-left:0px;">
                   <?php //echo "Jenis Customer : ".CHtml::dropDownList('custype', '0', $list, array('class' => 'myinput', 'onchange' => 'custype()', 'style'=>'margin-bottom:5px;width:100px;')); ?>
                   <input  type="text" id="vouchernominal" placeholder="Potongan ">
                </div>
                   <table class="">
                      <tr>
                         <td class="left">Sub Total:</td>
                         <td class="right">
                            <div id="sum_sub_total">0</div>
                         </td>
                      </tr>
                      <tr>
                         <td class="left">Discount :</td>
                         <td class="right">
                            <div id="sum_sale_discount">0<?php //echo CHtml::dropDownList('sum_sale_discount2', '5', array('5'=>'5%','10'=>'10%')); ?></div>
                         </td>
                      </tr>
                      <tr>
                         <td class="left">
                            Service (<script>document.write(var_service);</script>)% :
                         </td>
                         <td class="right">
                            <div id="sum_sale_service">0</div>
                         </td>
                      </tr>
                      <tr>
                         <td class="left">Tax (<?php echo Parameter::model()->findByPk(1)->pajak ?>)%:</td>
                         <td class="right">
                            <div id="sum_sale_tax">
                               0
                         </td>
                      </tr>
                    <tr>
                      <td class="left">Potongan :</td>
                      <td class="right"><div id="sum_sale_voucher">0</td>
                      </tr>
                          <tr>
                      <td class="left">Sisa Belum Bayar :</td>
                      <td class="right"><div id="sum_sale_bayar">0</td>
                      </tr>

                   </table>
                   <table class="tb_kanan kanan-footer">
                   <tr style="display:none">
                   <td class="left"><b>Total:</b></td>
                   <td class="right"><b><div id="sum_sale_total">0</div></b></td>
                   </tr>
                   <tr>
                   <td class="left"><b>Total:</b></td>
                   <td class="right"><b><div id="sum_sale_total2">0</div></b></td>
                   </tr>
                   </table>
          </div>
          <div style="clear:both"></div>
        </div>


        <div class="clear"></div>
    </div><!-- page -->
</div><!--mid-content-->
<div id="footer">
	<nav >
		<ul style="font-size: 5px;width: 100000000000px;float: left;">
    <li>Esc = Batal </li>
    <li>F1 = Bayar </li>
    <li> F2 = Pilih Item </li>
    <li> F3 = Data Sementara</li>
    <li> F4 = Hapus Semua</li>
    <li> F9 = Tutup & Simpan</li>
    <li>F7 = Cetak Struk</li>
    <li>F8 = Cetak Rekap </li>
		</ul>
	</nav>
    <!--Copyright &copy; <?php //echo date('Y'); ?> by My Company.<br/>-->
    <!--All Rights Reserved.<br/>-->
    <?php // echo Yii::powered(); ?>
</div><!-- footer -->
 <style type="text/css">
      #tambah-paket-baru{
        position: absolute;
        top: 0px;
        left: 0px;
        right: 0px;
        bottom: 0px;
        margin:auto;
        z-index: 1;
        width:820px;
        height: 500px;
        background:white;
        padding: 30px;
        border:2px solid black;
        overflow-y: auto;
        overflow-x: hidden;
        display: none;
      }
    </style>
    <div id="tambah-paket-baru">
      <?php 
      // $item = new Paket;
      // $kode_paket = ItemsController::GetKodePaket();
      // $item->id_paket = $kode_paket;


      // $this->renderPartial('application.views.items.createpaket',array(
      //     'namapaket'=>$namapaket,
      //     'hargapaket'=>$hargapaket,
      //     'array'=>$array,
      //     'from'=>"site" 
      //   ));
        ?>
    </div>
    <?php 
    include "js.php";
    ?>
</body>
</html>
