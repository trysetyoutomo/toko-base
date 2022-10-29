<?php

class ItemsSatuanController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='main2';

	/**
	 * @return array action filters
	 */
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
				'actions'=>array('index','view','kartu'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','GetSatuanByItemID','GetTipePriceSatuan','GetSatuanByID'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionKartu($id,$satuan_id){
		$this->render("application.views.items.kartupersediaan");
	}
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionGetTipePriceSatuan(){
		$id = $_REQUEST['id'];
		$id_item = $_REQUEST['id_item'];
		// and item_id ='$id_item'
		// $sql = "select * from items_satuan where item_id  = '$id' ";
		$id = ItemsSatuan::model()->find(" nama_satuan = '$id' and item_id='$id_item'   ");
		// exit;
		//  and item_id='$id_item'   
		$id = $id->id;
		// var_dump($id);


		$sql = "select isp.id, isp.item_satuan_id, isp.price, isp.default, ispm.label_name as price_type
		FROM items_satuan_price isp inner join  items_satuan_price_master ispm on ispm.name = isp.price_type
		where item_satuan_id  = '$id' order by price_type desc ";
		// echo $sql;
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		echo json_encode($data);
	}
	public function actionGetSatuanByItemID(){
		$id = $_REQUEST['id'];
		// $sql = "select * from items_satuan where item_id  = '$id' ";
		$sql = "select * from items_satuan where item_id  = '$id' ";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		echo json_encode($data);

	}
	public function actionGetSatuanByID($id){
		// $id = $_REQUEST['id'];
		// $sql = "select * from items_satuan where item_id  = '$id' ";
		$sql = "select * from items_satuan where id  = '$id' ";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		echo json_encode($data);

	}

	public function actionCreate()
	{
		$model=new ItemsSatuan;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['ItemsSatuan']))
		{
			$model->attributes=$_POST['ItemsSatuan'];
			
			if($model->save()){
				$ib = new ItemsBarcode;
				$ib->barcode = $_REQUEST['ItemsSatuan']['barcode'];
				$ib->store_id = Yii::app()->user->store_id();
				$ib->save();

				if ($model->is_default=="1"){
					$sql = "UPDATE items_satuan SET IS_DEFAULT = '0' WHERE item_id =  '$model->item_id' and id <> '$model->id' ";
					$exe = Yii::app()->db->createCommand($sql)->execute();
					$model->satuan = 1;
					$model->update();
				}	

				$this->redirect(array('admin','id'=>$model->item_id));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
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

		if(isset($_POST['ItemsSatuan']))
		{
			$model->attributes=$_POST['ItemsSatuan'];
			if($model->save()){
				if ($model->is_default=="1"){
					$sql = "UPDATE items_satuan SET IS_DEFAULT = '0' WHERE item_id =  '$model->item_id' and id <> '$model->id' ";
					$exe = Yii::app()->db->createCommand($sql)->execute();

				}	

				$itemSatuanPrice  = ItemsSatuanPrice::model()->find(" price_type = 'HARGA 1' and item_satuan_id='$model->id' " );
				if (count($itemSatuanPrice)>0){
					$itemSatuanPrice->price = $_POST['ItemsSatuan']['harga'];
					$save = $itemSatuanPrice->update();
					// var_dump($save);
					// exit;
					// var_dump($_POST['ItemsSatuan']['harga']);
					// exit;
					// echo "ok";
					// exit;
				}else{
					$model2 = new ItemsSatuanPrice;
					$model2->price = $_POST['ItemsSatuan']['harga'];
					$model2->item_satuan_id = $model->id;
					$model2->default = 1;
					$model2->price_type = "HARGA 1";
					$save = $model2->save();  	
					// var_dump($save);
					// exit;

				}

				$this->redirect(array('admin','id'=>$model->item_id));
			}
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
		// if(Yii::app()->request->isPostRequest)
		// {
			// we only allow deletion via POST request
			$m = $this->loadModel($id);
			$id2 =$m->item_id; 
			$m->delete();
			$this->redirect(array('admin','id'=>$id2));


			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		// 	if(!isset($_GET['ajax']))
		// 		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		// }
		// else
		// 	throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ItemsSatuan');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$id = $_REQUEST['id'];
		if (isset($_REQUEST['cari'])){
			$value = $_REQUEST['cari'];
			$filter = " and item_name like '%$value%' ";
		}

		$filtersForm=new FiltersForm;
		if (isset($_GET['FiltersForm']))
		$filtersForm->filters=$_GET['FiltersForm'];

		// $sql
		$sql = "
		 select iss.*, l.nama as nama_letak from
		 items_satuan iss 	
		 left join letak as l on l.id = iss.letak_id

		 where iss.item_id = '$id'
		 order by iss.urutan asc
		";
		// echo $sql;
		$rawData = Yii::app()->db->createCommand($sql)->queryAll();
		
		$sort = new CSort();
        $sort->attributes = array(
            'item_name'
        );

		$filteredData=$filtersForm->filter($rawData);
		$dataProvider=new CArrayDataProvider($filteredData,
			array(
				 'pagination'=>array('pageSize'=>1000000),
				 'sort' => array(
	                'attributes' => array(
	                    'item_name'
	                ),
        		),
			)
		);
		$this->render('admin', array(
			'filtersForm' => $filtersForm,
			'model' => $dataProvider,
			'rawData' => $rawData
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ItemsSatuan::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='items-satuan-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
