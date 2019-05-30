<?php

class ItemsSourceController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
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
	public function actionCreate()
	{
		$item_id =  $_REQUEST['item_id'];
		ItemsSource::model()->deleteAllByAttributes(array('item_menu' => $item_id));
			
			// echo "123";
		// if ($_REQUEST["status"]=="ubah"){
		// 	$paket = Paket::model()->findAll("id_paket=:i",array(":i"=>$_REQUEST["id"]));
		// 	$data_model = Paket::model()->findByPk($_REQUEST["id"]);
		// 	$namapaket = $data_model->nama_paket;
		// 	$hargapaket = $data_model->harga;
		// }
		// echo "<pre>";
		// print_r($_REQUEST);
		// echo "</pre>";
		if(isset($_REQUEST['kode']))
		{	
			// echo "<pre>";
			// print_r($_REQUEST);
			// echo "</pre>";
			// // exit;
			$data = $_REQUEST['kode'];
			$menu =  $_REQUEST['nama'];
			$total =  $_REQUEST['total'];
			$status =  $_REQUEST['status'];
			$kode_paket =  $_REQUEST['kode_paket'];
			// $satuan =  $_REQUEST['satuan'];
			

			$mn_new = preg_split('/,/', $menu, -1, PREG_SPLIT_NO_EMPTY);
			
			// if ($item->save()){
			// ItemsSource::model()->deleteAllByAttributes(array('item_id' => $id));

				$status_sukses = array();
				foreach($_REQUEST['kode'] as $d){
					$pd = new ItemsSource;

					// var_dump($i);
					$i = explode("-", $d['kode']);
					$barcode = $i[0];
					// echo $barcode;
					$items = ItemsSatuan::model()->find("barcode = '$barcode' ");
					// print_r($items);
					// exit;
					// echo $items->item_id;
					// exit;
					$pd->item_id = $items->item_id;
					$pd->item_menu = $item_id;
					$pd->jumlah = $d['jumlah'];
					$pd->satuan = $d['satuan'];
					$pd->harga = $d['harga'];
					if ($pd->save())
						array_push($status_sukses,true);
					else
						array_push($status_sukses,false,"");
				}
				if (in_array(false,$status_sukses)){
					echo json_encode(array("sukses"=>false,"err"=>$pd->getErrors()) );
				}else{
					echo json_encode(array("sukses"=>true));
				}
			// }else{
			// 	echo json_encode(array("sukses"=>false,"err"=>$item->getErrors()) );
			// }
		}else{
			$this->render('create',array(
				'namapaket'=>$namapaket,
				'hargapaket'=>$hargapaket,
				'array'=>$array,
				'from'=>"admin"	
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

		if(isset($_POST['ItemsSource']))
		{
			$model->attributes=$_POST['ItemsSource'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('ItemsSource');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new ItemsSource('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['ItemsSource']))
			$model->attributes=$_GET['ItemsSource'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=ItemsSource::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='items-source-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
