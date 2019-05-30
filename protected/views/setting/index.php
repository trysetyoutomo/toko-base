<script>
function reloadGrid(data) {
    $.fn.yiiGridView.update('penghuni-grid');
}

function gagal(data){
alert('eror '+data);
}
</script>
<?php
$this->breadcrumbs=array(
	'Setting',
);?>

<?php
$model = Branch::model()->findByPk(1);
//echo $model->branch_name;
?>



<fieldset style="display:none;border:1px solid #888;padding:10px;"><legend>Cetak</legend>
<table width="100%" cellpadding="30" >
<tr>
<td width="100"> Toko :</td>
<td><?echo  CHtml::textField('try',$model->branch_name,array('style'=>'width:100%','maxlength'=>40,'id'=>'cafe'))?></td>
</tr>
<tr>
<td>Alamat :</td>
<td><?echo  CHtml::textField('try',$model->address,array('style'=>'width:100%','maxlength'=>40,'id'=>'alamat'))?></td>
</tr>
<tr>
	<td>Telepon :</td>
	<td><?echo  CHtml::textField('try',$model->telp,array('style'=>'width:100%','maxlength'=>40,'id'=>'telp'))?></td>
</tr>
<tr>
	<td>Slogan :</td>
	<td><?echo  CHtml::textField('try',$model->slogan,array('style'=>'width:100%','maxlength'=>40,'id'=>'slogan'))?></td>
</tr>
<tr>
<td></td>
<td><?php
echo CHtml::ajaxSubmitButton('simpan',Yii::app()->createUrl('Branch/ajaxsave'),
                    array(
                        'type'=>'POST',
                        'data'=> 'js:{"data1":$("#cafe").val(), "data2": $("#alamat").val(),"data3":$("#telp").val(),"slogan":$("#slogan").val()  }',                        
                        'success'=>'js:function(string){ alert(string); }',
						'error'=>'js:function(string){ alert("eror"+string); }'
                    ),array('class'=>'btn btn-primary',));
?></td>
</tr>

</table>
</fieldset>




<script type="text/javascript">
	$(document).ready(function(e){
		$(document).on("submit","#form-printer",function(e){
			e.preventDefault();
			var data = $(this).serialize();
			 $.ajax({
		        url : 'index.php?r=setting/saveprinter',
		        data :  data,
		        success : function(data){
		        	if (data=='sukses'){
		        		alert('Berhasil memperbaharui! ');
		        		window.location.reload();
		        	}
		        	// alert(data);
		        }        
	    	});
		});
	});

</script>
<fieldset style="width:550px;border:1px solid #888;padding:10px;display: none;" ><legend></legend>
<table >
	<form id="form-printer">

		<?php 
		// $getprt=printer_list(PRINTER_ENUM_LOCAL| PRINTER_ENUM_SHARED );
		// $printers = serialize($getprt);
		// $printers=unserialize($printers);
		// $setting = Parameter::model()->findByPk(1);
		?>
	<tr >
		<td>Printer Utama :</td>
		<td>
			<select name="main_printer">
			<option>Pilih</option>
			<?php //foreach ($printers as $x) : ?>
				<?php //foreach ($x as $z) : ?>
				<option <?php// if ($setting->printer_utama==$x['NAME']) echo "selected" ?> value='<?php echo  $x[NAME] ?>'><?php// echo $x[NAME] ?></option>
				<?php //endforeach; ?>				
			<?php// endforeach; ?>				
			</select>

		</td>
	</tr>
	<tr>
		<td>Jumlah Cetak Bayar :</td>
		<td>
			<input type="number" name="qty_cetak" value="<?php echo $setting->qty_cb ?>"  style="width:40px;" >
		</td>
	</tr>
	<tr style="display:none">
		<td>Cetak Data Per Kategori :</td>
		<td>
			<input 
			<?php if ($setting->cetak_per_ketegori==1){echo "checked";}?>
			}
			type="checkbox" value="1"  name="cetak_kategori">
		</td>
	</tr>
		<tr>
		<td>Buka Cash Drawer (Laci Uang) :</td>
		<td>
			<input 
			<?php if ($setting->drawer==1){echo "checked";}?>
			}
			type="checkbox" value="1"  name="drawer">
		</td>
	</tr>
	<tr>
		<td>
			<input type="submit" value="Simpan">
		</td>
	</tr>
	</form>


</table>
</fieldset>

<fieldset style="width:550px;border:1px solid #888;padding:10px;"><legend>Logo</legend>
<legend>Logo Perusahaan</legend>
	<form enctype="multipart/form-data" action="index.php?r=setting/upload" method="post" mul >
	<table>
	<tr>
		<td>Upload :</td>
		<td>
			<input type="file" required   name="gambar">
		</td>
	</tr>
	<tr>
		<td>
			<input type="submit" class="btn btn-primary" name="submit" value="Upload">
		</td>
	</tr>
	<tr>
		<td>
			Logo Saat ini <br>
		</td>
		<td>
			<img style="width:200px;height:auto" src="<?php echo Yii::app()->request->baseUrl."/logo/".Parameter::model()->findByPk(1)->gambar ?>">
			
		</td>

	</tr>
	</form>

	<tr>
		<td colspan="2">
		<br>
		<br>
		
			<!-- <button ></button> -->
		</td>
	</tr>

	</table>
</fieldset>
<!--
<fieldset style="width:250px;border:1px solid #888;padding:10px;"><legend>Service</legend>

<?php
/*
$model = Service::model()->findAll();
 $form=$this->beginWidget('CActiveForm', array(
'id'=>'service-form',
'enableAjaxValidation'=>false,
)); 

$models = Service::model()->findAll(array('order' => 'status desc'));

$list = CHtml::listData($models, 'id', 'service');
  
echo "service : ".$form->dropDownList($model,'service', $list).'%';
echo CHtml::button('Aktifkan', array('submit' => array('service/service')));

$this->endWidget(); */?>


</fieldset>
-->