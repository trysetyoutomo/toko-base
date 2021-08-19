
	<script type="text/javascript">
		$(document).ready(function() {
    		$("#Setor_total_awal").focus();

    		$("#Setor_total_awal").keyup(function(e){
    			var ttl = $("#Setor_total_awal").val();
    			$("#total_awal_formatted").html(format(ttl));
    		});
		});
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
	</script>
</head>
<body>
<h1>
	<i class="fa fa-edit"></i>
Input Saldo Awal</h1>
	<hr>
<div class="form" style="margin-left: 20px;">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'form-input-saldo',
	'enableAjaxValidation'=>false,
	"action"=>$this->createUrl("site/index"),
	'htmlOptions'=>array(
		"method"=>"POST",
		'name'=>'form-input-saldo',
		// "name"=>"form-input-saldo",
	)
)); ?>

	<!-- <p class="note">untuk penyesuaian saldo, anda dapat menggunakan minus (-).</p> -->

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'total_awal'); ?>
		<?php echo $form->textField($model,'total_awal',['class'=>'form-control','style'=>'max-width:300px']); ?>
		<label id="total_awal_formatted" style="margin-top:1rem;margin-bottom:1rem"></label>
		<?php echo $form->error($model,'total_awal'); ?>
	</div>
	<?php  
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Mulai Transaksi' : 'Mulai Transaksi',array("class"=>"btn btn-primary")); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

