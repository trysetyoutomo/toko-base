<div id="hasil"></div>
<style type="text/css">
	.dalem{
		width:100%;
	}
</style>
<!--
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/app/SalesItems/main.js"></script>
-->
<?php 
$this->renderPartial('application.views.site.main');
?>
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click",".close-bayar",function(e){
			e.preventDefault();
			$("#bayar-hutang").fadeOut();
			
		});
		$(document).on("submit","#form-bayar",function(e){
			if ( $("#bayar").val() > $("#sisa").val() ){
				alert("Pembayaran yang di input lebih besar daripada sisa hutang");
				return false;		
				exit;	
			}
			// e.preventDefault();
			if (!confirm("Pembayaran akan mengurangi hutang, yakin ?")){
				return false;
			}else{
				return true;
			}
		});

			
		// });
		$(document).on("click",".btn-bayar",function(e){
			e.preventDefault();
			$("#bayar-hutang").fadeIn();
			var pid = $(this).attr("pid");
			var sisa = $(this).attr("sisa");
			$("#peminjaman_id").val(pid);
			$("#sisa").val(sisa);
			$("#bayar").focus();
			
		});
		$(document).on("click",".cetak-bukti",function(){
			try{
				$.ajax({
					url : "<?php echo Yii::app()->createUrl('items/cetakpinjam'); ?>",
					data: "id="+$(this).attr("value"),
					success:function(data){
					var i =1;					
					function myLoop(){
						setTimeout(function(){
							cetak(data);
							//print_bayar(sales);
							i++;
							if (i<2){
								myLoop();			
							}
						},1000)
					}
					myLoop();

					
					},
					error:function(err){
						alert(err);
					}
				});
			}catch(err){
				alert(err);
			}
		});

	});
	function useDefaultPrinter() {
	    var applet = document.jzebra;
	    if (applet != null) {
	        // Searches for default printer
	        applet.findPrinter();
	    }
	         
	    monitorFinding();
	}
	function monitorFinding() {
	    var applet = document.jzebra;
	    if (applet != null) {
	        if (!applet.isDoneFinding()) {
	            window.setTimeout('monitorFinding()', 100);
	        } else {
	            var printer = applet.getPrinter();
	            // alert(printer == null ? "Printer not found" );
	            // alert(printer == null ? "Printer not found" : "Printer \"" + printer + "\" found");
	            // alert(printer == null ? "Printer not found":none);
	        }
	    } else {
	        alert("Applet not loaded!");
    }
	}
	function chr(i){	
		return String.fromCharCode(i);
	}

	function cetak(data){
		useDefaultPrinter();
		// alert(JSON.stringify(data));
		var data = jQuery.parseJSON(data);		
		var applet = document.jzebra;
		if (applet != null) {
			applet.append(chr(27) + "\x61" + "\x31"); // center justify
			applet.append(chr(27) + chr(33) + chr(128));//underliner
			applet.append("<?php echo Branch::model()->findByPk(1)->branch_name ?> "+"\r\n");
			applet.append(chr(27) + chr(64));//cancel character sets			
	        applet.append(chr(27) + "\x61" + "\x31"); // center justify
			applet.append("<?php echo Branch::model()->findByPk(1)->address ?>"+"\r\n");
	        applet.append("<?php echo Branch::model()->findByPk(1)->telp ?>"+"\r\n");
	        applet.append(data.id+"\r\n");
	       	applet.append(chr(27) + chr(64));//cancel character sets
	        applet.append("\n");
	        applet.append("===============================================\n");
			applet.append(chr(27) + "\x61" + "\x31"); // center justify
			applet.append("PEMINJAMAN BARANG \n"); // center justify
			applet.append(chr(27) + chr(64));

	        applet.append("===============================================\n");
			

			// applet.append(data.id+"\n");
			applet.append(data.tanggal_pinjam+"\n");
			applet.append(data.tanggal_harus_kembali+"\n");
			applet.append(data.status+"\n");
			applet.append(data.peminjam+"\n");
			applet.append(data.deposit+"\n");
			applet.append(data.keterangan+" \n");
			applet.append("\n");
			applet.append("===============================================\n");
			// alert(JSON.stringify(data.detail));
			$.each(data.detail, function(i,cetak){
				// alert(cetak.nama);
				applet.append(cetak.nama+" \n");
				applet.append(cetak.harga+" \n");
			});
			applet.append("===============================================\n");
			applet.append(data.total_akhir);
			applet.append("\n");
			applet.append("===============================================\n");
			applet.append(data.keterangan_akhir+" \n");
			// applet.append(" ")
			
			applet.append("\n");
			applet.append("\n");
			applet.append("\n");
			applet.append("\n");
			applet.append("\n");
			applet.append("\n");
			applet.append("\n");
			applet.append("\n");
			applet.append("\x1Bm");
			applet.print();
		}
	}
</script>
<applet name="jzebra" code="jzebra.PrintApplet.class" archive="jZebra/jzebra/jzebra.jar" width="0" height="0">
    <param name="printer" value="zebra">
</applet>

<?php
/* @var $this SalesController */
/* @var $dataProvider CActiveDataProvider */

// $this->breadcrumbs=array(
	// 'Sales',
// );

// $this->menu=array(
	// array('label'=>'Create Sales', 'url'=>array('create')),
	// array('label'=>'Manage Sales', 'url'=>array('admin')),
// );
?>
<fieldset>
	<legend>
		
<h1>Laporan Peminjaman</h1>
<br>
	</legend>

<?php
 // $this->widget('zii.widgets.CListView', array(
	// 'dataProvider'=>$dataProvider,
	// 'itemView'=>'_view',
// )); 
?>
<?php 
// if (isset($_REQUEST['tgl'])){
// 	$tgl = $_REQUEST['tgl'];
// 	$tgl2 = $_REQUEST['tgl2'];
// }else{
// 	$tgl = date('Y-m-d');
// 	$tgl2 = date('Y-m-d');

// }
?>
<?php $form=$this->beginWidget('CActiveForm',array(
	'action'=>Yii::app()->createUrl('items/laporanpinjam'),
	'method'=>'get',
)); ?>
<!-- <div class="row"> -->
<?php
	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'Sales[date]',
		'attribute'=>'date',
//		'model'=>$model,
		// additional javascript options for the date picker plugin
		'options'=>array(
			'dateFormat'=>'yy-mm-dd',
			'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
			'showOn'=>'button', // 'focus', 'button', 'both'
			'buttonText'=>Yii::t('ui','Select form calendar'),
			'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
			'buttonImageOnly'=>true,
		),
		'value'=>$tgl,
		'htmlOptions'=>array(
			// 'style'=>'height:20px;'
			'style'=>'height:20px;;width:80px;display:inline-block'
		),
	));
	
// $this->renderPartial('summary',array('summary'=>$summary));

?>
<?php
	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
		'name'=>'Sales[date2]',
		'attribute'=>'date',
//		'model'=>$model,
		// additional javascript options for the date picker plugin
		'options'=>array(
			'dateFormat'=>'yy-mm-dd',
			'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
			'showOn'=>'button', // 'focus', 'button', 'both'
			'buttonText'=>Yii::t('ui','Select form calendar'),
			'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
			'buttonImageOnly'=>true,
		),
		'value'=>$tgl2,
		'htmlOptions'=>array(
			// 'style'=>'height:20px;'
			'style'=>'height:20px;;width:80px;display:inline-block'
		),
	));
	
// $this->renderPartial('summary',array('summary'=>$summary));

?>
	<!-- </div> -->
			<?php echo CHtml::submitButton('Cari'); ?>
			<?php //echo CHtml::button('Export to CSV',array('id'=>'export')); ?>
<?php $this->endWidget(); ?>


<?php


$sql  = "select * from peminjaman where date(tanggal_pinjam) between '$tgl' and '$tgl2' order by tanggal_pinjam desc";

$model = Yii::app()->db->createCommand($sql)->queryAll();

?>
<style type="text/css">
	.table tr td{
		border: 1px solid rgb(163, 0, 0,1);
	}
</style>
<br>
<table class="table">
	<thead>
		
		<tr style="background:rgba(163, 0, 0,1) ;color:white">
			<td>No</td>
			<td>ID</td>
			<td>Kasir</td>
			<td>Nama Peminjam</td>
			<td>Petugas</td>
			<td>Tanggal Pinjam</td>
			<td>Jatuh Tempo</td>
			<td>Tanggal Kembali</td>
			<!-- <td>Deposit</td> -->
			<td>Keterangan</td>
			<td>Status</td>
			<td>Aksi</td>
		</tr>

	</thead>
	<tbody>
		<?php 
		$no=1;
		foreach ($model as $m):
			$pid = $m[id];
		 ?>
		<tr style="background:#E8BCBC">
			<td style="width:5%"><?php echo $no;?></td>
			<td style="width:5%"><?php echo $m[id];?></td>
			<td style="width:5%"><?php echo $m[user];?></td>
			<td style="width:50%"><?php echo $m[nama]; ?></td>
			<td style="width:50%"><?php echo $m[user]; ?></td>
			<td style="width:50%"><?php echo $m[tanggal_pinjam]; ?></td>
			<td style="width:50%"><?php echo $m[tanggal_kembali]; ?></td>
			<td style="width:50%;"><?php 
			if ($m[iskembali]==1){		
				echo $m[tanggal_dikembalikan]; 
			}else{
				echo "Belum Kembali";
			}

			?></td>

			<td style="width:30%"><?php echo $m[keterangan]; ?></td>
			<td style="font-weight:bolder">
				<?php 
				if ($m[iskembali]==1){
					echo "Telah Kembali";
				}else{
					echo "Belum Kembali";

				}
				?>
			</td>
			<!-- <td style="width:50%"><?php echo number_format($m[deposit]); ?></td> -->
			<td>
			<?php if ($m[iskembali]==0) { ?>
			<a href="<?php echo Yii::app()	->createUrl('items/setkembali',array("id"=>$m[id])) ?>" style="cursor:pointer">Kembalikan</a>
			<?php }else{ ?>
			<a href="<?php echo Yii::app()	->createUrl('items/batalkembali',array("id"=>$m[id])) ?>" style="cursor:pointer">batal </a>
			<?php } ?>
			<br>
			<a value="<?php echo $m[id]  ?>" class="cetak-bukti">Cetak Bukti</a>
			</td>

			<tr>
				<td colspan="11">
				
					<table style="font-size:12px;width:500px;float:left" >
						<tr >
							<td colspan="5" ><p style="color:red">Daftar Items</p></td>
							
						</tr>
						<tr>
							<td>No</td>
							<td>Nama Item</td>
							<td>Qty</td>
							<td>Harga</td>
							<td>Total</td>
							
						</tr>
						<?php 
						$sql = "SELECT 
						s.id, i.item_name, si.qty qty, i.total_cost price
						FROM
						peminjaman s, peminjaman_detail si, items i
						WHERE 
						si.head_id = s.id 
						AND
						i.id = si.item_id

						AND s.id = '$m[id]'
						group by i.id
						";
						$no2=1;
						$total = 0;
						foreach (Yii::app()->db->createCommand($sql)->queryAll() as $q ) {
						$total = $total + ($q[price]*$q[qty]);
						?>
						<tr>
							<td style="width:5%"><?php echo $no2; ?></td>
							<td style="width:10%"><?php echo $q[item_name]  ?></td>
							<td style="width:10%"><?php echo $q[qty]  ?></td>
							<td style="width:10%;text-align:right"><?php echo $q[price]  ?></td>
							<td style="width:10%;text-align:right"><?php echo number_format($q[price]*$q[qty])  ?></td>
							
						</tr>
					
						<?php 
						$no2++;
						} ?>
						<tr>
							<TD COLSPAN="5" style="text-align:right"><?PHP echo number_format($total); ?></TD>
						</tr>
					</table>

					<!-- pembayaran -->
						<table style="font-size:12px;width:300px;float:left;margin-left:20px;">
						<tr >
							<td colspan="4" ><p style="color:red">Pembayaran Items (Deposit)</p></td>
							
						</tr>
						<tr>
							<td>No</td>
							<td>Tanggal</td>
							<td>Total</td>
							
						</tr>
						<?php 
						$sql = "SELECT 
						*
						FROM
						peminjaman s, peminjaman_bayar si
						WHERE 
						si.head_id = s.id 
						
						AND s.id = '$m[id]'";
						$no2=1;
						$total2=0;
						foreach (Yii::app()->db->createCommand($sql)->queryAll() as $q ) {
						$total2 = $total2 + $q[total];
						?>
						<tr>
							<td style="width:5%"><?php echo $no2; ?></td>
							<td style="width:10%"><?php echo $q[tanggal]   ?></td>
							<td style="width:10%;text-align:right"><?php echo number_format($q[total])  ?></td>
							
						</tr>
						<?php 
						$no2++;
						} ?>
						<tr>
							<td  colspan="4">
								<?php 
								$sisa = $total-$total2;
								?>
								<?php if ($sisa>0): ?>
								<button sisa="<?php echo $sisa; ?>" pid="<?php echo $pid ?>" class="btn-bayar">Tambah</button>
							<?php endif; ?>
								<h2 style="text-align:right"> Sisa : <?php echo number_format($sisa) ?> </h2>
							</td>
						</tr>
						<!-- <tr>
							<TD COLSPAN="5" style="text-align:right"><?PHP echo number_format($total); ?></TD>
						</tr> -->
					</table>
					
					
				</td>
			</tr>
		</tr>
	<?php 
	$no++;
	endforeach; ?>
	</tbody>
</table>

<div id="hasil"></div>
<style type="text/css">
	#bayar-hutang{
		width: 400px;
		height: 200px;
		position: fixed;
		background: rgba(163,0,0,1);
		top: 0px;
		left: 0px;
		right: 0px;
		bottom: 0px;
		margin: auto;
		border: 5px solid black;
		padding: 20px;
		display: none;

	}
	#fulll{
		width: 100vw;
		height: 100vh;
		background: white;
		position: fixed;
	}
	.close-bayar{
		width:25px;
		position:absolute;
		right: 10px;
		top: 10px;
	}
</style>
<div id="bayar-hutang">
	<h1>Pembayaran Utang</h1>
		<img style="" class="close-bayar" src="img/delete.ico">

		<form id="form-bayar" >

		<label  style="color:white;" >
			Tanggal Bayar

			<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'tanggal',
				'attribute'=>'date',
			//		'model'=>$model,
				// additional javascript options for the date picker plugin
				'options'=>array(
					'dateFormat'=>'yy-mm-dd',
					'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
					'showOn'=>'button', // 'focus', 'button', 'both'
					'buttonText'=>Yii::t('ui','Select form calendar'),
					'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
					'buttonImageOnly'=>true,
				),
				'value'=>date('Y-m-d'),
				'htmlOptions'=>array(
					// 'style'=>'height:20px;'
					'style'=>'height:20px;;width:80px;display:inline-block'
				),
			));
			?>

		</label>
		<br>
		<br>
			<input type="hidden" value="items/bayarhutang" name="r">
			<input style="display:none" type="text" id="peminjaman_id" name="peminjaman_id">
			<input style="display:none" type="text" id="sisa" name="sisa" value="<?php echo "$sisa"; ?>">
			<label style="color:white;">
				Total Bayar
				<input required type="text" value="0" name="bayar" id="bayar">
			<label>
			<br>
			<br>
			<br>
			<input type="submit" style="padding:10px;background-color:black!important" value="bayar">
		</form>


</div>
<div id="fulll"></div>
</fieldset>
