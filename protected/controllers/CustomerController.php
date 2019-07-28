<?php

class CustomerController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='main2';
	// public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	// public function filters()
	// {
	// 	return array(
	// 		'accessControl', // perform access control for CRUD operations
	// 	);
	// }

	// /**
	//  * Specifies the access control rules.
	//  * This method is used by the 'accessControl' filter.
	//  * @return array access control rules
	//  */
	// public function accessRules()
	// {
	// 	return array(
	// 		array('allow',  // allow all users to perform 'index' and 'view' actions
	// 			'actions'=>array('index','view'),
	// 			'users'=>array('*'),
	// 		),
	// 		array('allow', // allow authenticated user to perform 'create' and 'update' actions
	// 			'actions'=>array('create','update'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('allow', // allow admin user to perform 'admin' and 'delete' actions
	// 			'actions'=>array('admin','delete','laporan'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('deny',  // deny all users
	// 			'users'=>array('*'),
	// 		),
	// 	);
	// }
	public static function generateCustomerCode() {
        $query = "SELECT
				IFNULL(
					CONCAT(
						'CSM',
						LPAD(
							MAX(SUBSTR(kode, 4, 10)) + 1,
							10,
							'0'
						)
					),
					'CSM0000000001'
				) AS urutan
			FROM
				customer
                 ";
        $model = Yii::app()->db->createCommand($query)->queryRow();
        return $model['urutan'];
   }

	public function actionLaporan(){
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			//$tgl =  strtotime("+7 day", $tgl);
			$tgl2 = date('Y-m-d'); 
		}
		$this->render('laporan',
			array(
				'tgl'=>$tgl,
				'tgl2'=>$tgl2,
			)
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public static function Lunasin($name,$bayar,$last_id,$voucher){
		$tes = new SalesController(this);
		$table = $tes->sqlSales();

	
		$sql = " SELECT * FROM ($table) AS  D 
		where D.nama = '$name' 
		group by D.id 
		having sb = 'Kredit' 
		order by D.date asc
		";		
		$data =  Yii::app()->db->createCommand($sql)->queryAll();
		// echo count($data);
		// exit;
		if (count($data)>0){
			$s = Sales::model()->findByPk($last_id);
			$s->bayar = 0;
			// $s->bayar = 0;
			$s->update();
		}





		// $sql = " SELECT * FROM ($table) AS  D 
		// where D.nama = '$name' 
		// group by D.id 
		// having sb = 'Kredit' 
		// order by D.date asc
		// ";
		// $data =  Yii::app()->db->createCommand($sql)->queryAll();
		// if (count($data)>0){
		// 	$s = Sales::model()->findByPk($last_id);
		// 	$s->bayar = 0;
		// 	$s->update();
		// }
		// var_dump($nama);
		// var_dump($bayar);
		// var_dump($last_id);


		$data =  Yii::app()->db->createCommand($sql)->queryAll();
		// if (count($data)>0){

		// }

		$sisabayar = $bayar;
		$hutang = 0;
		//hutang 1 juta , now 3 ratus, bayar 1.200
		// 200
		// echo count($data);
		// exit;
		if (count($data)>0){

			foreach ($data as $key => $value) {
				if ($value['bayar']<$value['sale_total_cost']){	

					$hutang = intval($value['sale_total_cost']) - intval($value['bayar']);

					if ($sisabayar>0){
							$id = $value['id'];
						if ($sisabayar>$hutang){
							$sales = Sales::model()->findByPk($id);
							$lunasin = $value['sale_total_cost'] - $value['bayar'];
							$sales->bayar+=$lunasin;
							$sales->update();	
							$sisabayar-=$lunasin;
						}else{
							$sales = Sales::model()->findByPk($id);
							$sales->bayar += intval($sisabayar);
							$sales->update();	
							$sisabayar-=$hutang;
						}
					}
				
				}
			}

			if (!empty($voucher)){

					$data =  Yii::app()->db->createCommand($sql)->queryAll();

					if (count($data)>0){
						// $s = Sales::model()->findByPk($last_id);
						// $s->bayar = 0;
						// $s->update();

						$sp = SalesPayment::model()->findByPk($last_id);
						$sp->voucher = 0;
						$sp->update();

					}	
					
					$data =  Yii::app()->db->createCommand($sql)->queryAll();

					$sisabayar = $voucher;

					// set untuk voucher
						foreach ($data as $key => $value) {
							if ($value['bayar']<$value['sale_total_cost']){	

								$hutang = intval($value['sale_total_cost']) - intval($value['bayar']);

								if ($sisabayar>0){
										$id = $value['id'];
									if ($sisabayar>$hutang){
										$sales = Sales::model()->findByPk($id);
										$lunasin = $value['sale_total_cost'] - $value['bayar'];
										
										$sp = SalesPayment::model()->findByPk($id);
										$sp->voucher+=$lunasin;
										$sp->update();	
										$sisabayar-=$lunasin;
									}else{
										$sales = SalesPayment::model()->findByPk($id);
										$sales->voucher += intval($sisabayar);
										$sales->update();	
										$sisabayar-=$hutang;
									}
								}
							
							}
						}
			} 

			// jika tidak empty
		}

		// echo $sisabayar;
		// exit;


	}
	public function actionGetDiskonMember2(){
		$nilai = 0;
		$name = $_REQUEST['name'];
		$customer = Customer::model()->find("nama = '$name' ");
		
		if ($customer){
			$nilai = CustomerType::model()->find(" id = '$customer->customer_type' ")->diskon;
		}else{
			$nilai = 0;
		}
		$hutang = SalesController::getHutangByCustomer2($name);
						
					//	echo str_replace(',', '.', number_format($hutang));
		echo json_encode(array("nilai_diskon"=>$nilai,"sisa_hutang"=>$hutang));
		// echo $nilai;
	}

	public function actionGetDiskonMember(){
		$nilai = 0;
		$name = $_REQUEST['name'];
		$customer = Customer::model()->find("nama = '$name' ");
		
		if ($customer){
			$nilai = CustomerType::model()->find(" id = '$customer->customer_type' ")->diskon;
		}else{
			$nilai = 0;
		}
		$hutang = SalesController::getHutangByCustomer2($model['nama']);
						
					//	echo str_replace(',', '.', number_format($hutang));
		echo json_encode(array("nilai_diskon"=>$nilai,"sisa_hutang"=>$hutang));
		// echo $nilai;
	}
	public function actionGetCustomer()
	{
		?>
		 <option value="">Umum</option>
            <?php foreach (Customer::model()->findAll() as $c) {?>
                <option value="<?php echo $c->nama ?>"><?php echo $c->id." - ".$c->nama ?></option>                            
            <?php } ?>
          <?php
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new Customer;
		if(isset($_POST['Customer']))
		{
			$model->attributes=$_POST['Customer'];
			$model->kode = CustomerController::generateCustomerCode();
			if($model->save()){
				if (! isset($_POST['isajax']) )
					$this->redirect(array('admin','id'=>$model->id));
				else
					echo "sukses";
					// $this->redirect(array('admin','id'=>$model->id));
			}else{
				if (isset($_POST['isajax']) ){
					foreach ($model->getErrors() as $key => $value) {
						// echo $value."<br>";
						foreach ($value as $z => $b) {
							echo " $b \n";
						}
					}
					exit;
				}
			}
		}

		if (! isset($_POST['isajax']) ){
			$this->render('create',array(
				'model'=>$model,
			));
		}
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Customer']))
		{
			$model->attributes=$_POST['Customer'];
			if($model->save())
				$this->redirect(array('admin','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
			$this->loadModel($id)->delete();
			$this->redirect(array('admin'));
	}
		// if(Yii::app()->request->isPostRequest)
		// {
			// we only allow deletion via POST request



			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		// 	if(!isset($_GET['ajax']))
		// 		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		// }
		// else
		// 	throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Customer');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$rawData = Yii::app()->db->createCommand()
		->select('*')
		->from('customer')
		->where("1=1 $filter")
		->queryAll();
		$this->render('admin', array(
			'rawData' => $rawData,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Customer::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='customer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
