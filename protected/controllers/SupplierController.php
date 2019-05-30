<?php

class SupplierController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='main2';

	// /**
	//  * @return array action filters
	//  */
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
	// 			'actions'=>array('admin','delete','hapus'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('deny',  // deny all users
	// 			'users'=>array('*'),
	// 		),
	// 	);
	// }

	public function actionGetSupplier()
	{
		?>
		    <?php foreach (Supplier::model()->findAll() as $c) {?>
                <option value='<?php echo $c->id ?>'><?php echo $c->nama ?></option>                            
            <?php } ?>
          <?php
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
		$model = new Supplier;
		if(isset($_POST['Supplier']))
		{
			$model->attributes=$_POST['Supplier'];
			if($model->save()){
				if (! isset($_POST['isajax']) )
					$this->redirect(array('admin','id'=>$model->id));
				else
					echo "sukses";
					// $this->redirect(array('admin','id'=>$model->id));
			}else{
				foreach ($model->getErrors() as $key => $value) {
					// echo $value."<br>";
					foreach ($value as $z => $b) {
						echo " $b \n";
					}
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

		if(isset($_POST['Supplier']))
		{
			$model->attributes=$_POST['Supplier'];
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
	public function actionHapus($id)
	{
		// if(Yii::app()->request->isPostRequest)
		// {
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();
			$this->redirect(array('admin','id'=>$model->id));

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			// if(!isset($_GET['ajax']))
			// 	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		// }
		// else
		// 	throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Supplier');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		// $model=new Supplier('search');
		// $model->unsetAttributes();  // clear any default values
		// if(isset($_GET['Supplier']))
		// 	$model->attributes=$_GET['Supplier'];

		// $this->render('admin',array(
		// 	'model'=>$model,
		// ));
		if (isset($_REQUEST['cari'])){
			$value = $_REQUEST['cari'];
			$filter = " and nama like '%$value%' ";
		}

		$filtersForm=new FiltersForm;
		if (isset($_GET['FiltersForm']))
		$filtersForm->filters=$_GET['FiltersForm'];
		// $idh = $_REQUEST['id'];
		$rawData = Yii::app()->db->createCommand()
		->select('*')
		->from('supplier s')
		->where("1=1 $filter")
		// ->group("u.id")
		->queryAll();

		
		$filteredData=$filtersForm->filter($rawData);
		$dataProvider=new CArrayDataProvider($filteredData);
		$this->render('admin', array(
			'filtersForm' => $filtersForm,
			'model' => $dataProvider,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Supplier::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='supplier-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
