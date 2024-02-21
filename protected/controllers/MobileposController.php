<?php

class MobileposController extends Controller
{
	
	public $layout='mobile';

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
				'actions'=>array('@'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','table','waiter','admin','table','Gettablebynumber'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionTable(){
		// $store_id = Yii::app()->user->store_id();
		$branch_id = Yii::app()->user->branch();
		$criteria = new CDbCriteria;
		$criteria->compare('branch',  $branch_id);
		$criteria->compare('status',  0);

		$sales = Sales::model()->findAll($criteria);
		$resultsAsArrays = array();
		foreach ($sales as $model) {
			$resultsAsArrays[] = $model->attributes;
		}
		echo json_encode($resultsAsArrays);
	}

	public function actionGettablebynumber($id){
		// $store_id = Yii::app()->user->store_id();
		$branch_id = Yii::app()->user->branch();
		$criteria = new CDbCriteria;
		$criteria->compare('branch',  $branch_id);
		$criteria->compare('status',  0);
		$criteria->compare('t.table',  $id);
		$sales = Sales::model()->find($criteria);
		$data = [];
		foreach ($sales->sales_items as $items){
			$data[] = $items->attributes;
		}
		echo json_encode(["main"=>$sales->attributes,"detail"=>$data]);
	}

	public function actionIndex(){
        // $items = Items::model()->findAll()->limit(5);
		$items = Items::model()->data_items("MENU",true);

		$criteria = new CDbCriteria;
		$criteria->limit = 20; 
		$table = Meja::model()->findAll($criteria);
		$resultsAsArrays = array();
		foreach ($table as $model) {
			$resultsAsArrays[] = $model->attributes;
		}

		$categories = Categories::model()->findAll();
		$categoriesArray = array();
		foreach ($categories as $model) {
			$categoriesArray[] = $model->attributes;
		}



        $this->render("index", [
            'items' => json_encode($items),
            'table' => json_encode($resultsAsArrays),
            'categories' => json_encode($categoriesArray)
        ]);
	}
}