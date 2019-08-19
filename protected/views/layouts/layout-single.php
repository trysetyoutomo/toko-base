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
<meta name="description" content="Aplikasi kasir terbaik untuk anda" />

<!-- Favicon -->
<link rel="shortcut icon" href="favicon.ico">
<link rel="icon" href="favicon.ico" type="image/x-icon">

<!-- Toggles CSS -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/burnette/vendors/jquery-toggles/css/toggles.css" rel="stylesheet" type="text/css">
<link href="<?php echo Yii::app()->request->baseUrl; ?>/burnette/vendors/jquery-toggles/css/themes/toggles-light.css" rel="stylesheet" type="text/css">

<!-- font google -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

<!-- icon -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css" rel="stylesheet"/>
<!-- Custom CSS -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/burnette/dist/css/style.css" rel="stylesheet" type="text/css">
<style type="text/css">
     p,h1,h2,h3,h4,h5,a,td,th,label,body{
       font-family: 'Open Sans', sans-serif!important;
     }

	.custom-checkbox .custom-control-input:checked ~ .custom-control-label::before{
		background: #2A3F54;
	}
	#datatable_wrapper{
         overflow: auto;
         }
         .errorMessage{
         color: red;
         display: none;
         }
         input.error,select.error{
         border:1px solid red;
         }
         .modal-dialog {
         width:700px!important;
         }
         .fa,.glyphicon{
         cursor: pointer;
         }
         div.wide.form label
         {
         float: left;
         margin-right: 10px;
         position: relative;
         text-align: right;
         width: 100px;
         }
         div.wide.form .row
         {
         clear: left;
         padding: 4px;
         }
         div.wide.form .buttons, div.wide.form .hint, div.wide.form .errorMessage
         {
         clear: left;
         padding-left: 110px;
         }
</style>

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
			<a href="tel:08986044235" class="btn btn-outline-secondary">Hubungi kami</a>
		</div>
	</header>
	<div class="container">
	<?php echo $content;
	 ?>
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