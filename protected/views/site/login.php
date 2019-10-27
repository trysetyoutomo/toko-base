<!DOCTYPE html>
<!-- 
Template Name: Brunette - Responsive Bootstrap 4 Admin Dashboard Template
Author: Hencework
Contact: https://hencework.ticksy.com/

License: You must have a valid license purchased only from templatemonster to legally use the template for your project.
-->
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<title>35 POS</title>
<meta name="description" content="A responsive bootstrap 4 admin dashboard template by hencework" />

<!-- Favicon -->
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" href="favicon.ico" type="image/x-icon">

<!-- Toggles CSS -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/burnette/vendors/jquery-toggles/css/toggles.css" rel="stylesheet" type="text/css">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/burnette/vendors/jquery-toggles/css/themes/toggles-light.css" rel="stylesheet" type="text/css">

<!-- font google -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

<!-- Custom CSS -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/burnette/dist/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
     p,h1,h2,h3,h4,h5,a,td,th,label,body{
       font-family: 'Open Sans', sans-serif!important;
     }

	.custom-checkbox .custom-control-input:checked ~ .custom-control-label::before{
		background: #2A3F54;
	}
</style>
<script>
  function initFreshChat() {
    window.fcWidget.init({
      token: "9008a611-28ae-4050-b54b-1f41c63c7b40",
      host: "https://wchat.freshchat.com"
    });
  }
  function initialize(i,t){var e;i.getElementById(t)?initFreshChat():((e=i.createElement("script")).id=t,e.async=!0,e.src="https://wchat.freshchat.com/js/widget.js",e.onload=initFreshChat,i.head.appendChild(e))}function initiateCall(){initialize(document,"freshchat-js-sdk")}window.addEventListener?window.addEventListener("load",initiateCall,!1):window.attachEvent("load",initiateCall,!1);
</script>
</head>
<body>
<!-- Preloader -->
<div class="preloader-it">
<div class="loader-pendulums"></div>
</div>
<!-- /Preloader -->

<!-- HK Wrapper -->
<div class="hk-wrapper">

<!-- Main Content -->
<div class="hk-pg-wrapper hk-auth-wrapper">
	<header class="d-flex justify-content-end align-items-center">
		<div class="btn-group btn-group-sm">
			<!-- <a href="tel:08986044235" class="btn btn-outline-secondary">Hubungi Saya</a> -->
			<!-- <a href="#" class="btn btn-outline-secondary">About Us</a> -->
		</div>
	</header>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12 pa-0">
				<div class="auth-form-wrap pt-xl-0 pt-70">
					<div class="auth-form w-xl-30 w-lg-55 w-sm-75 w-100">
						<?php 
						  $parameter = Parameter::model()->findByPk(1);
						?>
						<a class="auth-brand text-center d-block mb-20" href="#">
						    <img style="width: 100px"  class="brand-img img"  src="<?php echo Yii::app()->request->baseUrl; ?>/logo/<?php echo $parameter->gambar ?>" alt="">
						</a>

<?php $form=$this->beginWidget('CActiveForm', array(
'id'=>'login-form',
'enableClientValidation'=>true,
'clientOptions'=>array(
'validateOnSubmit'=>true,
),
)); ?>

							<h1 class="display-4 text-center mb-10">Selamat Datang</h1>
							<p class="text-center mb-30">Gunakan username atau password untuk masuk ke sistem.</p> 
							<div class="form-group">
								<!-- <input class="form-control" placeholder="Email" type="email"> -->
								<?php echo $form->textField($model,'username',array("class"=>"form-control","placeholder"=>"username")); ?>
								<?php echo $form->error($model,'username'); ?>	
							</div>
							<div class="form-group">
								<div class="input-group">
								<?php echo $form->passwordField($model,'password',
								array("class"=>"form-control","placeholder"=>"Password","style"=>"width:100%")); ?>
							
								<div class="input-group-append">
										<!-- <span class="input-group-text"><span class="feather-icon"><i data-feather="eye-off"></i></span></span> -->
									</div>
								</div>
								<?php echo $form->error($model,'password'); ?>	

							</div>
							<div class="custom-control custom-checkbox mb-25">
								<input class="custom-control-input" id="same-address" type="checkbox" checked>
								<label class="custom-control-label font-14" for="same-address">Tetap masuk</label>
							</div>
							<!-- <p class="font-14 text-center mt-15">Belum Mendaftarkan <b>Usaha</b> anda ?</p> -->
							<!-- <div class="option-sep">Silahkan daftar</div> -->
							<div class="form-row">

				<div class="col-sm-12 mb-20">
				<button  class="btn btn-primary btn-block" type="submit" style="background:#2A3F54;border:none;" >
					<span class="btn-text">
						Masuk
					</span>
				</button>
				</div>
				<div class="col-sm-12 mb-20">
				<button class="btn btn-danger btn-block">
				<a href="<?php echo Yii::app()->createUrl("stores/create") ?>"  style="color:white"> 
					<span class="btn-text">
						Daftar
					</span>
				</a>
				</button>
				</div>

								<!-- <div class="col-sm-6 mb-20"><button class="btn btn-sky btn-block btn-wth-icon"> <span class="icon-label"><i class="fa fa-twitter"></i> </span><span class="btn-text">Login with Twitter</span></button></div> -->
							<!-- </div> -->
							<!-- <p class="text-center">Do have an account yet? <a href="#">Sign Up</a></p> -->
					
							
<?php $this->endWidget(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Main Content -->

</div>
<!-- /HK Wrapper -->

<!-- JavaScript -->

<!-- jQuery -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/burnette/vendors/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/burnette/vendors/popper.js/dist/umd/popper.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/burnette/vendors/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Slimscroll JavaScript -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/burnette/dist/js/jquery.slimscroll.js"></script>

<!-- Fancy Dropdown JS -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/burnette/dist/js/dropdown-bootstrap-extended.js"></script>

<!-- FeatherIcons JavaScript -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/burnette/dist/js/feather.min.js"></script>

<!-- Init JavaScript -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/burnette/dist/js/init.js"></script>
</body>
</html>