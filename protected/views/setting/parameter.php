<script src="<?php echo Yii::app()->request->baseUrl; ?>/vendors/select2/dist/js/select2.full.min.js"></script>
<link  href="<?php echo Yii::app()->request->baseUrl; ?>/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
<style type="text/css">

	.table-rules thead td{
		background: rgba(255,0,0,1);
		color: white;
		font-size:14px; 

		/*border:1px solid black;*/
	}
	
	.table-rules tr td{
		border:1px solid black;
		padding: 5px;
	}
	.table-rules tr td input[type="checkbox"]{
		cursor: pointer;

	}
	#wrapper-rules{
		position: relative;
		width: auto;
        overflow-x:scroll;  
        margin-left:10px;
        overflow-y:visible;
        padding-bottom:1px;
	}
	#wrapper-rules .loader-ajax{
		display: none;
		width:15px;
		position:absolute;
		text-align:center;
		margin: auto;
		left: 0px;
		right: 0px;
		top: 0px;
		bottom: 0px;
	}

	/*.headcol {*/
		 /*border:1px solid grey;*/
    /*    position:absolute;
        width:195px;
        overflow: hidden;
        left:0;
        top:auto;
        border-right: 0px none black;
        border-top-width:3px; 
        margin-top:0px; 
    }
	}
    */
   /*  td:first-child{
     	background-color: gray!important;
     	color: white;
     	display: none;
     	
     }*/
      td:not(.headcol){
      	width: auto;
        margin:0;
        /*float: left;*/
        /*border:1px solid grey;*/
        border-top-width:0px;
        white-space:nowrap;
        text-align: right;
        text-align: center;
        overflow: visible;
    }
  td:first-child{
  	background-color: white;
  	width: 100px;
  }

</style>
<script type="text/javascript">
	$(document).ready(function(){

		$("select").select2();
		$(document).on("click",".loader-ajax",function(e){
			var i = $('.loader-ajax').index(this);
			// alert(i);
		});
		$(document).on("click","#cek-all",function(e){
			$('.cek-rules').each(function (index, value){
				if (!$(this).is(":checked"))	
					$(this).trigger("click");
				// else
					// $(this).trigger("click");
			});
		});
		$(document).on("click",".cek-rules",function(e){
			// alert("masuk");
			// alert($(this).val());
			var i = $('.cek-rules').index(this);
			// alert(i);
			// var user= $(this).attr("data-user");
			var user= "<?php echo $_REQUEST['role_id'] ?>" ;
			var per_id = $(this).attr("data-per");
			var bool;
			if ($(this).is(":checked"))
				bool = true;
			else
				bool = false;

			// alert(bool);

			$.ajax({
				url : '<?php echo Yii::app()->createUrl('Setting/setpermission') ?>',
				data : {
					user : user,
					per_id : per_id,
					bool : bool
				},
				cache : false,
				beforeSend : function(data){
					$(".loader-ajax").eq(i).show();
					$('.cek-rules').eq(i).hide();
				},
				success : function(data){
					var js = JSON.parse(data);
					if (js.status=="200"){
					// alert(data);
						$.toast({
						    heading: 'Sukses',
						    text: 'Berhasil Merubah Data ',
						    position: 'top-right',
						    stack: false,
						    icon: 'info'
						});
					}

					$('.cek-rules').eq(i).show();
					$(".loader-ajax").eq(i).hide();
					// $(".loader-ajax").eq(i).hide();
					// $(this).show();

				},
				error :function(d){
					alert(JSON.stringify(d));
					// window.location.reload();
				}
			});
		});
	});
</script>
<?php 
$permission = ConfigMenu::model()->findAll(array("order"=>"value asc"));
$user = Users::model()->findAll("level = 1 or level=2 ");
$no = 1;
?>
<h1>Kelola Akses 
<a title="add action rule" href="<?php echo Yii::app()->createUrl("ConfigMenu/create"); ?>"><i class="fa fa-plus"></i></a>
</h1>
<br>

<?php $form=$this->beginWidget('CActiveForm', array(
  // 'id'=>'barang-form',
  'enableAjaxValidation'=>false,
         'method' => 'post',
  'htmlOptions' => array(
         'enctype' => 'multipart/form-data',
     ),
  )); ?>
<div class="row" style="margin-left:10px;" >
<label>
	Username
</label>
<select name="role_id"  style="width:50%" >
<?php foreach (ConfigRole::model()->findAll() as $key => $value) { ?>
	<option <?php if ($_REQUEST['role_id']==$value->id) echo "selected" ?> value="<?php echo $value->id ?>"><?php echo $value->role_name; ?></option>
<?php } ?>
</select>
<button type="submit" class="btn btn-primary" > <i class="fa fa-search"></i> Cari</button> 
 <?php $this->endWidget(); ?>

<?php if (isset($_REQUEST['role_id'])): ?>
<div id="wrapper-rules">
<br>
<br>
	<table class=" table" id="datatable">
		<thead>
			<td >No</td>
			<td style="text-align:left">Akses</td>
			<td style="text-align:left">Controller</td>
			<td style="text-align:left">Action</td>
			<td>
				<input type="checkbox" id="cek-all">
			</td>
		</thead>
		<tbody>
			<?php foreach ($permission as $p) {  ?>
			<tr>
			<td>
				<?php echo $no ?>
			</td>
			<td align="left" style="text-align:left"  > 
				<a href="<?php echo Yii::app()->createUrl("ConfigMenu/update",array("id"=>$p->id)) ?>">
				<?php 
					echo $p->value;
				 ?>
				</a>
			</td>
			<td  style="text-align:left"><?php echo $p->controllerID ?></td>
			<td  style="text-align:left"><?php echo $p->actionID ?></td>

			<td align="center" style="position:relative">
				<?php //echo $p->id ?>
				<?php 
				// $cek =  Permission::model()->count("permission_id = '$p->id' and role_id = '$u->role_id' ");
				$cek = Yii::app()->db->createCommand("select * from config_role_detail
				 where menu_id = '$p->id' and role_id = '$_REQUEST[role_id]'  ")->queryAll();
				// echo "";
				// echo "<pre>";
				// print_r($cek);
				// echo "</pre>";
				$cek = count($cek);
				// echo $cek;
				if ($cek==0)
					$checked = " ";
				else
					$checked = " checked ";
				?>
				<input <?php echo $checked ?>  data-per="<?php echo $p->id ?>" data-user="<?php echo $_REQUEST['role_id'] ?>" type="checkbox"  class="cek-rules">
				<center>
					<img src="<?php echo Yii::app()->request->baseUrl ?>/images/ajax-loader.gif" class="loader-ajax"  style="">
				</center>
				<!-- <i class="fa loader-ajax fa-spin" style="color:black"></i> -->
			</td>
			</tr>
			<?php 
	$no++;
			}  ?>
		
			<?php //foreach ($user as $u) {?>
		

			<?php 
		
			//}?>
		</tbody>
	</table>
</div>
<?php endif; ?>