<?php

class SiteController extends Controller
{
    
    public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('KirimEMail','hapusAll','pajak','waiterhapus','ubahpassword','uservoid','cekpassword','gabungmeja','reloadoptionmeja','reloadMeja','updatetable','getmenutable','waiterkirim','login','logout','setting','hutang','pengaturan'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','table','waiter','admin'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionKirimEMail(){
				// the message
		$msg = "First line of text\nSecond line of text";

		// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,70);

		// send email
		$bOK = mail("try35u@gmail.com","My subject",$msg);
		var_dump($bOK);
	}

	
	public function actionPengaturan(){
		$this->layout = "backend";
		// echo "<pre>";
		// print_r($_REQUEST['Config']);
		// echo "</pre>";

		// exit;
		if (isset($_REQUEST['Config'])){

			// echo count($_REQUEST['Config']);
			$i = 0;
			foreach ($_REQUEST['Config'] as $key => $value) {
				if ($key=="hari"){ // jika kelola hari maka
					foreach ($_REQUEST['Config']['hari'] as $key_hari => $value_hari) {
						$data = CentralConfig::model()->find(" variable = '$key_hari' ");
						$data->value = $value_hari['awal']."-".$value_hari['akhir'];
						$data->update();
					}
				}
				else  if ($key=="location"){ // jika kelola hari maka
						$x = CentralConfig::model()->find(" variable = '$key' ");

						$lt_lng = $value['location']['latitude'].",".$value['location']['longitude'];			
						$x->value =  $lt_lng;
						$x->update();
				}else{
					$data = CentralConfig::model()->find(" variable = '$key' ");
					if (count($data)>0){

						if ($key=="main_service_list" || $key=="service_benefit"){
							$string_service = "";
							foreach ($value as $key2 => $v2) {
								$string_service .= $v2.",";
							}
							$string_service = rtrim($string_service,",");
							$data->value = $string_service;
						}else{	
							$data->value = trim($value);
						}
						if ($data->update()==false){
							// echo "gagal";
							// echo "gagal";
						}else{
							// echo "sukses";
						}
					}else{
						// echo "d bawah 8";
					}
				}

				$i++;
				// echo $i;
			}
			
		}
		$model = CentralConfig::model()->findAll("isaktif = 1",array("order"=>"id asc"));

		$this->render('pengaturan',array(
			"model"=>$model
		));
	}
	public static function getConfig($id){
		// $config = CentralConfig::model()->find("variable = '$id' ")->value;
		$sql = "select * from central_config where isaktif = '1' ";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		foreach ($data as $v){	
			$array[$v['variable']] = $v['value'];
		}
		return $array[$id];
		// return $data[];
	}

	/**
	 * Declares class-based actions.
	 
         * 
         */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	// public function actionAuth(){
	// 	echo "123";
	// }

	public function actionHapusAll(){
		// echo "123";
		$que = "truncate barangkeluar";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate  barangkeluar_detail";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate barangmasuk";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate barangmasuk_detail";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate pengeluaran";
		$e1 = Yii::app()->db->createCommand($que)->execute();
		

		$que = "truncate purchase_order";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate purchase_order_detail";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate sales";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate sales_items";
		$e1 = Yii::app()->db->createCommand($que)->execute();
		
		$que = "truncate sales_payment";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate pengeluaran";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate deposit";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		$que = "truncate setor";
		$e1 = Yii::app()->db->createCommand($que)->execute();

		// if ($e1){	
			// echo "ok";
			$this->redirect(array('site/pengaturan'));
		// }





	}
	// public function actionHapusAll(){

	// }
	public function actionAdmin(){
		$this->layout = "backend";
		$this->render("admin",array());
	}
	public function actionPajak(){
		$this->layout = "backend";
		// echo "<pre>";
		// print_r($_REQUEST[minimal]);
		// echo "</pre>";
		$model = array();
		$min_bill = $_REQUEST[minimal];
		if (isset($_REQUEST[bulan]) || isset($_REQUEST[tahun]) ){
			$bulan = $_REQUEST[bulan];
			$tahun = $_REQUEST[tahun];
		}else{
			$bulan = date("m");
			$tahun = date("Y");		
		}
  	  $adt_sql1 = " case  ";
  	  $adt_sql2 = " ";
  	  $adt_sql3 = " end order by s.date desc  ";

  	  if (isset($_REQUEST[minimal]) ):
	  foreach ($_REQUEST[minimal] as $key => $value) {
		  $adt_sql2 = $adt_sql2." when ( (FLOOR((DayOfMonth(s.date)-1)/7)+1 )  = $key ) then sale_sub_total <= $value  ";
	  }
	  $adt_sql4 =  $adt_sql1.$adt_sql2.$adt_sql3;

		// $min_bill = $_REQUEST[minimal];
		$subtotal = "si.item_price*si.quantity_purchased" ; 
		$sale_service = "si.item_service" ; 
		$sql  = "select s.bayar,s.table,inserter, s.comment comment, 
		s.id id,sum(si.quantity_purchased) as total_items, 
		date,
		s.waiter waiter,

		sum($subtotal) sale_sub_total,
		
		sum($sale_service) sale_service,
		sum(si.item_tax) sale_tax,
		sum( si.item_discount/100 * ($subtotal) )  sale_discount,
		
		sum((

			($subtotal) + $sale_service + (si.item_tax)-  ( si.item_discount/100 * ($subtotal))
			))  
		sale_total_cost,

		 u.username inserter 
		 from sales s,sales_items si , users u , items i
		 where 
		  
		  i.id = si.item_id  and
		  
		 s.id = si.sale_id and 
		 year(s.date)='$tahun' and 
		 month(s.date)='$bulan' 



		  and s.status=1 and inserter = u.id  group by s.id 
		  having
		  sale_tax != 0 
		  and
		  $adt_sql4
		  ";
		  // if (isset($_REQUEST[minimal])){
		 
		  // }else{
		  // 	echo "123";
		  // }
		 // echo $sql;
		 $model = Yii::app()->db->createCommand($sql)->queryAll();
		  endif;
		 $this->render("pajak",array(
	 	 	'model' => $model
	 	 ));


	}
	public function actionUbahpassword(){
	 	$this->layout = "backend";
		$user = Yii::app()->user->id;
		// echo $user;
		$model = Users::model()->find("username = '$user'");
		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if ($model->validate()){	
				$model->password = $_POST['Users']['new_password'];
				if ($model->update()){	
					$this->redirect(array('site/logout'));
				}
			}

	 	}

	 	// $this->menu = "account";
	 	$this->render("account",array(
	 		'model'=>$model
 		));

	}
	public function actionGabungmeja($mjl,$mjb){
		$id_sales_lama = Sales::model()->find("t.table = '$mjl' and status = 0")->id;
		$id_sales_baru = Sales::model()->find("t.table = '$mjb' and status = 0")->id;
		$sales_lamaitems = SalesItems::model()->findAll("sale_id = '$id_sales_lama' ");
		foreach ($sales_lamaitems as $sli) {
			$sli->sale_id = $id_sales_baru;
			$sli->update();
		}
		$model = Sales::model()->find("t.table = '$mjl' and status = 0");
		if ($model->delete()){
			echo "sukses dele";
		}

		
	}
	public function actionCekpassword($username,$password){
		$user = Users::model()->find("username = '$username' and password = '$password' ");
		if (count($user)==0){
			echo false;
		}else{
			// echo "true";
			Yii::app()->user->logout();
			$model=new LoginForm;
			$model->username = $username;
			$model->password = $password;
			if ($model->login())
				echo  true;
			else
				echo "login gagal";
		}		
	}
	public function actionReloadmeja(){
		$this->renderPartial('reloadmeja');
	}
	public function actionReloadOptionMeja(){
		$this->renderPartial('reloadoptionmeja');
	}
	public function actionUpdatetable($mjl,$mjb){
		$sales = Sales::model()->find("t.table = '$mjl' and status = 0");
		$sales->table = $mjb;
		if ($sales->update())
			echo "meja telah terpindahkan";

	}
	public function actionGetmenutable($table){
		$this->renderPartial('getmenutable',array('table'=>$table));
	}
	public function actionWaiter(){
		// $this->layout = "waiter";
		// echo "masuk";
		$this->renderPartial('waiter');
	}
	public function actionWaiterkirim(){
			$nilai = $_REQUEST['jsonObj'];
			$head = $_REQUEST['head'];
			$namapel = $_REQUEST['namapel'];

			$mj = $_REQUEST['head']['meja'];
	
			$jml = Sales::model()->count(" t.table = '$mj' and status = 0 ");
			if ($jml==0)
				$modelh = new Sales;
			else
				$modelh = Sales::model()->find(" t.table = '$mj' and status = 0 ");
			

			// $modelh->date = $_REQUEST['head']['tanggal'];
			$modelh->date = date('Y-m-d H:i:s');
			$modelh->inserter = Yii::app()->user->id;
			$modelh->customer_id = 0;
			$modelh->nama = $namapel;
			$modelh->sale_sub_total = 0;
			$modelh->sale_discount = 0;
			$modelh->sale_service = 0;
			$modelh->sale_tax = 0;
			$modelh->sale_total_cost = 0;
			$modelh->paidwith_id = 0;
			$modelh->total_items = 0;
			$modelh->sale_payment = 0;
			$modelh->branch = 1;
			$modelh->user_id = 0;
			$modelh->status = 0;
			if (Yii::app()->user->getLevel()==7){
				$modelh->waiter = Yii::app()->user->id;
			}
			
			$modelh->table = $_REQUEST['head']['meja'];


			$pajak = Parameter::model()->findByPk(1)->pajak/100;
			$service = Parameter::model()->findByPk(1)->service/100;
			// $modelh->pemasok_id = $_REQUEST['head']['pemasok'];
			if ($modelh->save()){

				// if (
				// echo $modelh->nama;
				SalesItems::model()->deleteAll("sale_id = '$modelh->id' ");

				foreach ($nilai as $n){
					$model = new SalesItems;
					$model->item_id = $n['idb'];
					
					$items = Items::model()->findByPk($n['idb']);

					$model->quantity_purchased = $n['jml'];
					$model->item_tax = ($items->unit_price*$n['jml']) * $pajak ;
					$model->item_service = ($items->unit_price*$n['jml']) * $service ;
					$model->item_price = $items->unit_price;
					$model->item_discount = 0;
					$model->cetak = 1;
					$model->item_total_cost =  ($items->unit_price * $n['jml']) + ( ($items->unit_price*$n['jml']) * $pajak ) + (($items->unit_price*$n['jml']) * $service)  ;
					$model->permintaan = $n['permintaan'];
					// $model->harga = Barang::model()->findByPk($n['idb'])->harga;
					$model->sale_id = $modelh->id;
					if ($model->save()){
						echo "sukses bro";
						// $barang = Barang::model()->findByPk($n['idb']);
						// $barang->stok = $barang->stok + $n['jml'];
						// if ($barang->save()){
						// 	echo "sukses update ";
						// }
					}
					else{
						echo "<pre>";
						print_r($model->getErrors());
						echo "</pre>";
						// echo "gagal bro";			
					}
				}

				// }else{
				// 	echo "gagal deleteAll";
				// }
			}else{
				echo "<pre>";
				print_r($modelh->getErrors());
				echo "</pre>";
			}

	}	
	public function actionWaiterhapus(){
			$nilai = $_REQUEST['jsonObj'];
			$head = $_REQUEST['head'];
			$namapel = $_REQUEST['namapel'];

			$mj = $_REQUEST['head']['meja'];
	
			$jml = Sales::model()->count(" t.table = '$mj' and status = 0 ");
			if ($jml==0)
				$modelh = new Sales;
			else
				$modelh = Sales::model()->find(" t.table = '$mj' and status = 0 ");
			

			// $modelh->date = $_REQUEST['head']['tanggal'];
			$modelh->date = date('Y-m-d H:i:s');
			$modelh->inserter = Yii::app()->user->id;
			$modelh->customer_id = 0;
			$modelh->nama = $namapel;
			$modelh->sale_sub_total = 0;
			$modelh->sale_discount = 0;
			$modelh->sale_service = 0;
			$modelh->sale_tax = 0;
			$modelh->sale_total_cost = 0;
			$modelh->paidwith_id = 0;
			$modelh->total_items = 0;
			$modelh->sale_payment = 0;
			$modelh->branch = 1;
			$modelh->user_id = 0;
			$modelh->status = 1;
			$modelh->inserter = 33;
			$modelh->comment  = "Di hapus pada POS";
			if (Yii::app()->user->getLevel()==7){
				$modelh->waiter = Yii::app()->user->id;
			}
			
			$modelh->table = $_REQUEST['head']['meja'];


			$pajak = Parameter::model()->findByPk(1)->pajak/100;
			$service = Parameter::model()->findByPk(1)->service/100;
			// $modelh->pemasok_id = $_REQUEST['head']['pemasok'];
			if ($modelh->save()){

				// if (
				// echo $modelh->nama;
				SalesItems::model()->deleteAll("sale_id = '$modelh->id' ");

				foreach ($nilai as $n){
					$model = new SalesItems;
					$model->item_id = $n['idb'];
					
					$items = Items::model()->findByPk($n['idb']);

					$model->quantity_purchased = 0;
					$model->item_tax = 0 ;
					$model->item_service = 0 ;
					$model->item_price = 0;
					$model->item_discount = 0;
					$model->cetak = 1;
					$model->item_total_cost =  0;
					$model->permintaan = $n['permintaan'];
					// $model->harga = Barang::model()->findByPk($n['idb'])->harga;
					$model->sale_id = $modelh->id;
					if ($model->save()){
						echo "sukses bro";
						// $barang = Barang::model()->findByPk($n['idb']);
						// $barang->stok = $barang->stok + $n['jml'];
						// if ($barang->save()){
						// 	echo "sukses update ";
						// }
					}
					else{
						echo "<pre>";
						print_r($model->getErrors());
						echo "</pre>";
						// echo "gagal bro";			
					}
				}

				// }else{
				// 	echo "gagal deleteAll";
				// }
			}else{
				echo "<pre>";
				print_r($modelh->getErrors());
				echo "</pre>";
			}

	}	
	public function actionUservoid(){
		if (isset($_REQUEST['username'])) {
			$un = $_REQUEST['username'];
			$pass = $_REQUEST['password'];
			//cari dari database yg usename & passwordnya dikirim
			$user = Users::model()->find("username=:un AND  password=:pass",array(":un"=>$un, ":pass"=>$pass));
			$userLevel = $user['level'];
			//echo "<br>".$username = $user->name;
			if(isset($userLevel)){
				if($userLevel<5){
					//jika userlevel < 5 maka eksekusi void_bayar
					echo "authorized";
				}else{
					echo "unauthorized";
				}
			}else{
				echo "unauthorized";
			}
		}
	}
	
	public function actionSetting(){
	echo "nama saya try setyo utomo";
	$this->render('contact');
	}
	
	public function actionUservoidform(){
		$model = new Users;
		
		$this->renderPartial('user_void',array(
			'model'=>$model,
		));
	} 
	
	public function actionTable(){
		$this->renderPartial('table');
	}
	
	public function actionMenu(){
		$this->render('menu');
	}
	
	public function actionCategoryform(){
		$model = new Items;
		$this->render('/items/create',array(
			'model'=>$model,
		));
	}
	
	

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	
	 
	public function actionIndex()
	{
		$username = Yii::app()->user->name;
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$now = date("Y-m-d");
		// print_r($_REQUEST);
		if (isset($_POST['Setor'])){

			$cekKasir = Setor::model()->find(" is_closed = 0 and user_id = '$user->id' and  date(tanggal) = '$now'   ");
			if ($cekKasir){
			 	?>
			 	<script type="text/javascript">
				alert('Input Saldo sudah dilakukan!');
				window.location.href = '<?php echo Yii::app()->createUrl('site/index') ?>'
			 	</script>
			 	<?php 

			}else{

				$setor = new Setor;
				$setor->tanggal = $now;
				$setor->user_id = $user->id;
				$setor->total_awal = $_POST['Setor']['total_awal'];
				$setor->total = 0;
				$setor->created_at = date("Y-m-d H:i:s");
				$setor->store_id =  Yii::app()->user->store_id();
				if ($setor->save()){
					$this->redirect(array('site/index'));
				}else{
					// print_r($setor->getErrors());
				}
			}
			exit;
		}



		$cekKasir = Setor::model()->find(" is_closed = 1 and user_id = '$user->id' and  date(tanggal) = '$now'   ");
		if ($cekKasir){
			?>
				<script type="text/javascript">
				alert("Tranksaksi kasir <?php echo $username ?> pada tanggal <?php echo date("d M Y", strtotime($cekKasir->tanggal)) ?> telah ditutup pada <?php echo date("d M Y H:i", strtotime($cekKasir->updated_at)) ?> , kasir hanya bisa melakukan register 1 kali dalam sehari",);
				window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
				</script>
			<?php 
		}

		$cekClosed = Setor::model()->find(" is_closed = 0 and user_id = '$user->id' and  date(tanggal) < '$now'   "); // checking setor table 

		if (!$cekClosed && $cekSales <= 0){

			$setor = Setor::model()->find(" user_id = '$user->id' and  date(tanggal) = '$now'  ");

			if ($setor){
				$this->render('index');
			}else{
				if ($user->level != "1"){
					?>
					<script type="text/javascript">
					alert("Hanya pengguna dengan level kasir yang dapat mengakses halaman ini");
					window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
					</script>
					<?php 
				}
				$setor = new Setor;
				$this->layout = "backend";
				$this->render('input_saldo',array("model"=>$setor));
			}
		}else{
				$criteria = new CDbCriteria;
				$criteria->select = 't.* ';
				$criteria->join = ' INNER JOIN `sales_items` AS `si` ON si.sale_id = t.id INNER JOIN `items` AS `i` ON i.id = si.item_id';
				// $criteria->join = ' ';
				$criteria->addCondition("t.inserter = '$user->id' and  date(t.date) = '".$cekClosed->tanggal."' and t.status = 1 ");
			$cekSales  = Sales::model()->findAll($criteria);
			if (count($cekSales) > 0){
			?>
					<script type="text/javascript">
					alert("Tranksaksi kasir <?php echo $username ?> pada tanggal <?php echo $cekClosed->tanggal ?> belum ditutup, silahkan hubungi admin ");
					window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
					</script>
					<?php 
			}else{  // jika tidak ada transaksi sales, dan belum d close maka close otomatis dengan reason tidak ada trasnsaksi
				$setor = Setor::model()->find(" user_id = '$user->id' and  date(tanggal) = '".$cekClosed->tanggal."' ");
				if ($setor){
					$setor->is_closed = 1;
					$setor->closed_reason = "Otomatis tutup, karena tidak ada transaksi";
					if ($setor->save())
						$this->redirect(array('site/index'));
				}
				// $setor = new Setor;
				// $this->layout = "backend";
				// $this->render('input_saldo',array("model"=>$setor));
			}
		}
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$this->layout = 'main2';
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			// print_r($_POST['LoginForm']);
			// exit;
// 			echo "21";
// 				exit;
// // 
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			// if($model->validate() && $model->login())
			// 	$this->redirect(Yii::app()->user->returnUrl);
			if($model->validate() && $model->login()){

				$level = Yii::app()->user->getLevel(); 
				// if ($level==2 || $level==3 || $level==4 || $level==1)
					$this->redirect(array('site/admin'));
				// if ($level==6)
					// $this->redirect(array('site/admin'));
				// if ($level==7)
					// $this->redirect(array('site/admin'));
				
			}
		}
		// display the login form
		$this->renderPartial('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}