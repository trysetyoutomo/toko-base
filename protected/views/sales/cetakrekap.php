<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl ?>/js/jQuery.print.min.js"></script>

<style type="text/css">
	*{
		font-weight:bold;
	}
	#faktur{
		width:100%;
	}
	#faktur .x{
		float: left;
	}
	tfoot{
		font-style: initial;
	}
	@media print
	{
		button{
			display:none;
		}
		@page
		{
			size: auto;   /* auto is the initial value */
			margin: 0mm;  /* this affects the margin in the printer settings */
		}

		body
		{
			font-size:15px!important
		}
	}
</style>
<script>
	function onload(){
		$('#faktur').print();
		setTimeout(() => {
			window.close();
		}, 1000); 
		// window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }
	}
</script>
<body onload="onload()">
<?php 
echo $html_noprint;
?>
</body>
