<?php

class BranchController extends Controller
{
	public $layout = "backend";
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */


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

	public function actionHapus($id)
	{
		$model = $this->loadModel($id);
		$model->hapus = 1;
		$model->update();
		$this->redirect(array('Branch/admin'));	
		
	}
	
	
	
	public function actionAjaxsave()
	{
		//echo "try";
		$cafe = $_POST['data1'];
		$alamat = $_POST['data2'];
		$telp = $_POST['data3'];
		$slogan = $_POST['slogan'];
		$model=$this->loadModel(1);
		$model->branch_name = $cafe;
		$model->address = $alamat;
		$model->telp = $telp;
		$model->slogan = $slogan;
		if ($model->save())
		echo "sukses";
		else
		echo  $model->getErrors;
		
		
		
		
		
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Branch;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Branch']))
		{
			$formData = file_get_contents('php://input');
			parse_str($formData, $parsedData);
			$model->attributes=$_POST['Branch'];
			$model->store_id = Yii::app()->user->store_id();
			if($model->save()){

				// BranchCategoryItems
				if (count($parsedData['Branch']['categories']) > 0){
					foreach($parsedData['Branch']['categories'] as $category) {
						$branchCategoryItems = new BranchCategoryItems;
						$branchCategoryItems->branch_id =  $model->id;
						$branchCategoryItems->category_id = $category;
						$branchCategoryItems->created_dt = date("Y-m-d H:i:s") ;
						$branchCategoryItems->created_by =  Yii::app()->user->user_id() ;
						$branchCategoryItems->save();
					}
				}
				Yii::app()->user->setFlash('success', "Cabang {$model->branch_name} berhasil dibuat!");

				$this->redirect(array('admin'));
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


		$arrayCategories = [];
		$model=$this->loadModel($id);
		$branchCategoryItems =  BranchCategoryItems::model()->findAllByAttributes(array('branch_id' => $id));
		if(count($branchCategoryItems) > 0) {
			$arrayCategories = CHtml::listData($branchCategoryItems, 'category_id', 'category_id');
		}
		$model->categories = $arrayCategories;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Branch']))
		{
			// Get the raw form data
			$formData = file_get_contents('php://input');
			parse_str($formData, $parsedData);
			$model->attributes=$_POST['Branch'];
			$model->slogan=$_POST['Branch']['slogan'];

			BranchCategoryItems::model()->deleteAllByAttributes(array('branch_id' => $id));
			// BranchCategoryItems
			foreach($parsedData['Branch']['categories'] as $category) {
				$branchCategoryItems = new BranchCategoryItems;
				$branchCategoryItems->branch_id =  $id;
				$branchCategoryItems->category_id = $category;
				$branchCategoryItems->created_dt = date("Y-m-d H:i:s") ;
				$branchCategoryItems->created_by =  Yii::app()->user->user_id() ;
				$branchCategoryItems->save();
			}

			if($model->save()){
				Yii::app()->user->setFlash('success', 'Cabang berhasil diubah!');
				$this->redirect(array('admin'));
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
		$dataProvider=new CActiveDataProvider('Branch');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		// $store_id = Yii::app()->user->store_id();
		if (isset($_REQUEST['cari'])){
			$value = $_REQUEST['cari'];
			$filter = " and branch_name like '%$value%' ";
		}

		$filtersForm=new FiltersForm;
		if (isset($_GET['FiltersForm']))
		$filtersForm->filters=$_GET['FiltersForm'];
		// $idh = $_REQUEST['id'];

		// $sql


		$rawData = Yii::app()->db->createCommand()
		->select('b.*')
		->from(' branch as b, stores as s  ')
		// ->
		->where(" 1=1 and hapus = 0 and b.store_id = ".Yii::app()->user->store_id()."   $filter   ")
		->group("b.id")
		// ->order("item_name asc")
		->queryAll();
		
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
		$model=Branch::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='branch-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
