<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
    <?php Yii::app()->getClientScript()->registerCoreScript('jquery.ui'); ?>
<!--
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui/jquery-ui-timepicker-addon.js"></script>
-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
  <style type="text/css">
      .container-fluid{
        width: 90%;
       }
       .content-pos-grid{
        width: 75%!important;
       }
       .content-pos-kanan{
        width: 25%!important;
       }
       /*x-box-item {
        top: 15px!important;
       }*/
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
		<div class="admin-bar">
            <ul>
                <li><a href="#" id="data-user"><?php echo Yii::app()->user->name; ?></a></li>
				<li><a href="<?php echo $this->createUrl('sales/index');?>">Laporan</a></li>
				 <!-- <li><a href="<?php echo $this->createUrl('site/waiter');?>">Halaman Waiter</a></li> -->
				 <!--waiterkasir-->
<!--                <li><a href="#">Setting</a></li> -->
				<?php if (!Yii::app()->user->isGuest) {?>
                <li><a href="<?php echo $this->createUrl('site/logout'); ?>">Logout</a></li>		
				<?php } else { ?>
                <li><a href="<?php echo $this->createUrl('site/login');?>">Login</a></li>		
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
    <div class="container-fluid" id="page">
        
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

        <div id="pos-content">
            <div class="title-content"><h3>
            <?php 
            echo SiteController::getConfig("company_name");
            ?>
            <span id="head-meja"></span>
            <span id="head-meja-nilai"></span>
          </h3>
        </div>    
            <?php echo $content; ?>
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
