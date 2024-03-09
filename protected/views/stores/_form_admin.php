<style>
	#Users_password::after{
		color:red;
	}
</style>
<script>
    function togglePasswordVisibility() {
      var passwordInput = document.getElementById("Users_password");
      var icon = document.getElementById("togglePasswordIcon");

      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        passwordInput.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }
  </script>
<div class="form wide" style="margin-left:1rem" >
	<div class="row">
		<div class="col-sm-12">
		<div class="container mt-20" >
			
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'stores-form',
				'enableAjaxValidation'=>false,
				'htmlOptions'=>array(
					'enctype'=>"multipart/form-data"
					)
				)); ?>
		<?php if (!empty($message)): ?>
			<div class="alert alert-danger" role="alert">
				<?=$message?>
			</div>
			<?php endif; ?>
		<?php echo $form->errorSummary($model); ?>

		<p class="note mt-10 mb-10">Isian dengan <span class="required">*</span> wajib disi.</p>

		<div class="row-group d-block">
		<div class="row">
		<?php echo $form->labelEx($model,'name',array('class'=>"col-sm-2")); ?> 	
		<?php echo $form->textField($model,'name',array('class'=>'col-sm-10 form-control','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
		</div>

		<?php 
		function getJenisUsahaOptions()
		{
			$jenisUsahaUMKM = array(
				'KULINER' => array(
					'WARUNGMAKAN' => 'WARUNG MAKAN',
					'KATERING' => 'KATERING',
					'KUEROTI' => 'KUE DAN ROTI',
					'CEMILAN' => 'CEMILAN'
					// Tambahkan jenis usaha kuliner lainnya sesuai kebutuhan
				),
				'PERTANIAN' => array(
					'PERTANIANSAYURAN' => 'PERTANIAN SAYURAN',
					'PETERNAKAN' => 'PETERNAKAN',
					'PERKEBUNAN' => 'PERKEBUNAN',
					// Tambahkan jenis usaha pertanian lainnya sesuai kebutuhan
				),
				'JASA' => array(
					'JASAKEBERSIHAN' => 'JASA KEBERSIHAN',
					'JASAPENGIRIMAN' => 'JASA PENGIRIMAN',
					'JASAPERAWATANTANAMAN' => 'JASA PERAWATAN TANAMAN',
					// Tambahkan jenis usaha jasa lainnya sesuai kebutuhan
				),
				'PAKAIANDANAKSESORIS' => array(
					'PRODUKSIPAKAIAN' => 'PRODUKSI DAN PENJUALAN PAKAIAN',
					'TOKOAKSESORIS' => 'TOKO AKSESORIS',
					'JASAJAHIT' => 'JASA JAHIT',
					// Tambahkan jenis usaha pakaian dan aksesoris lainnya sesuai kebutuhan
				),
				'KECANTIKANDANKESEHATAN' => array(
					'SALONKECANTIKAN' => 'SALON KECANTIKAN',
					'TOKOKOSMETIK' => 'TOKO KOSMETIK',
					'JASAPIJAT' => 'JASA PIJAT ATAU SPA',
					// Tambahkan jenis usaha kecantikan dan kesehatan lainnya sesuai kebutuhan
				),
				'TEKNOLOGIINFORMASIDANKOMUNIKASI(TIK)' => array(
					'JASAPEMBUATANWEBSITE' => 'JASA PEMBUATAN WEBSITE',
					'PENJUALANELEKTRONIK' => 'PENJUALAN DAN PERBAIKAN PERANGKAT ELEKTRONIK',
					'PENGEMBANGANAPLIKASI' => 'PENGEMBANGAN APLIKASI',
					// Tambahkan jenis usaha TIK lainnya sesuai kebutuhan
				),
				'KREATIFDANDESAIN' => array(
					'DESAINGRAFIS' => 'DESAIN GRAFIS',
					'PRODUKSIKERAJINAN' => 'PRODUKSI KERAJINAN TANGAN',
					'FOTOGRAFI' => 'FOTOGRAFI',
					// Tambahkan jenis usaha kreatif dan desain lainnya sesuai kebutuhan
				),
				'PENDIDIKANDANPELATIHAN' => array(
					'LESPRIVAT' => 'LES PRIVAT',
					'PELATIHANKETERAMPILAN' => 'PUSAT PELATIHAN KETERAMPILAN',
					'JASAPENERJEMAH' => 'JASA PENERJEMAH',
					// Tambahkan jenis usaha pendidikan dan pelatihan lainnya sesuai kebutuhan
				),
				'PARIWISATA' => array(
					'PENGELOLAANHOMESTAY' => 'PENGELOLAAN HOMESTAY',
					'JASAPEMANDUWISATA' => 'JASA PEMANDU WISATA',
					'JUALANSOUVENIR' => 'JUAL BELI SOUVENIR LOKAL',
					// Tambahkan jenis usaha pariwisata lainnya sesuai kebutuhan
				),
				'PERDAGANGAN' => array(
					'TOKOKELONTONG' => 'TOKO KELONTONG',
					'TOKOBAHANBANGUNAN' => 'TOKO BAHAN BANGUNAN',
					'TOKOPAKAIANSECONDHAND' => 'TOKO PAKAIAN SECOND-HAND',
					// Tambahkan jenis usaha perdagangan lainnya sesuai kebutuhan
				),
				'PERIKANAN' => array(
					'BUDIDAYAIKAN' => 'USAHA BUDIDAYA IKAN',
					'PENGOLAHANHASILPERIKANAN' => 'USAHA PENGOLAHAN HASIL PERIKANAN',
					'JUALANHASILPERIKANAN' => 'PENJUALAN HASIL PERIKANAN',
					// Tambahkan jenis usaha perikanan lainnya sesuai kebutuhan
				),
				'MANUFAKTUR' => array(
					'PRODUKSIKERAJINANTANGAN' => 'PRODUKSI KERAJINAN TANGAN',
					'PRODUKSIMAKANANOLAHAN' => 'PRODUKSI MAKANAN OLAHAN',
					'INDUSTRIKECILDANMENENGAH' => 'INDUSTRI KECIL DAN MENENGAH',
					// Tambahkan jenis usaha manufaktur lainnya sesuai kebutuhan
				),
				'PERTUKANGAN' => array(
					'BENGKELMOTOR' => 'BENGKEL MOTOR',
					'BENGKELTUKANGKAYU' => 'BENGKEL TUKANG KAYU',
					'JASAREPARASIALATALATRUMAHTANGGA' => 'JASA REPARASI ALAT-ALAT RUMAH TANGGA',
					// Tambahkan jenis usaha pertukangan lainnya sesuai kebutuhan
				),
				'E-COMMERCE' => array(
					'PENJUALANONLINE' => 'PENJUALAN ONLINE',
					'TOKOONLINE' => 'TOKO ONLINE DI PLATFORM TERTENTU',
					'DROPSHIPPING' => 'DROPSHIPPING',
					// Tambahkan jenis usaha e-commerce lainnya sesuai kebutuhan
				),
			);
			
			
			
			return $jenisUsahaUMKM;
		}
		?>
		<div class="row">
		<?php echo $form->labelEx($model,'store_type',array('class'=>"col-sm-2")); ?>
		<?php echo $form->dropdownList($model,'store_type',getJenisUsahaOptions(),array('class'=>'col-sm-10 form-control','maxlength'=>50,'prompt'=>"Pilih jenis usaha")); ?>
		<?php echo $form->error($model,'store_type'); ?>
		</div>

	

		<div class="row">
		<?php echo $form->labelEx($model,'email',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textField($model,'email',array('class'=>'col-sm-10 form-control','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'email'); ?>
		</div>

		<div class="row">
		<?php echo $form->labelEx($model,'phone',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textField($model,'phone',array('class'=>'col-sm-10 form-control','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'phone'); ?>
		</div>

		<div class="row">
		<?php echo $form->labelEx($model,'address1',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textArea($model,'address1',array('size'=>200,'maxlength'=>200,'class'=>'col-sm-10 form-control')); ?>
		<?php echo $form->error($model,'address1'); ?>
		</div>


		<div class="row">
		<?php echo $form->labelEx($model,'postal_code',array('class'=>"col-sm-2")); ?>
		<?php echo $form->textField($model,'postal_code',array('class'=>'col-sm-10 form-control','size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'postal_code'); ?>
		</div>
		</div>
		<?php if ($model->isNewRecord): ?>
		<div class="row" style="position:relative" >
			<?php echo $form->labelEx($u,'password',array('class'=>"col-sm-2")); ?>
			<?php echo $form->passwordField($u,'password',array('class'=>'col-sm-10 form-control','size'=>50,'maxlength'=>50)); ?>
			<i style="position:absolute;right:1.5rem;top:13px;" id="togglePasswordIcon" class="fas fa-eye icon" onclick="togglePasswordVisibility()"></i> 
			<?php echo $form->error($u,'password'); ?>
		</div>
		<?php endif ?>



		<div class="row" style="margin-top:2rem" >
		<label> </label>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Daftar ',array("class"=>"btn btn-primary","style"=>"min-width:auto")); ?>
		</div>

		<?php $this->endWidget(); ?>

		</div>
		<!-- form -->
		<div>
	</div>
</div>