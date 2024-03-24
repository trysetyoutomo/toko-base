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
				'actions'=>array('index','table','waiter','admin','table','gettablebynumber','inputsaldo'),
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

	public function actionInputSaldo(){
		$setor = new Setor;
		$this->layout = "backend";
		$this->render('application.views.site.input_saldo',array("model"=>$setor,"callback"=>"mobilepos/index"));
	}
	public function actionIndex(){
		$username = Yii::app()->user->name;
		$user_id = Yii::app()->user->user_id();
		// var_dump($user_id);
		// exit;
		$level = Yii::app()->user->level();
		$now = date("Y-m-d");


		if (isset($_POST['Setor'])){

			$cekKasir = Setor::model()->find(" is_closed = 0 and user_id = '$user_id' and  date(tanggal) = '$now' and store_id = '".Yii::app()->user->store_id()."'   ");
			if ($cekKasir){
			 	?>
			 	<script type="text/javascript">
				alert('Input Saldo sudah dilakukan!');
				window.location.href = '<?php echo Yii::app()->createUrl('mobilepos') ?>'
			 	</script>
			 	<?php 

			}else{

				$setor = new Setor;
				$setor->tanggal = $now;
				$setor->user_id = $user_id;
				$setor->total_awal = $_POST['Setor']['total_awal'];
				$setor->total = 0;
				$setor->created_at = date("Y-m-d H:i:s");
				$setor->store_id =  Yii::app()->user->store_id();
				if ($setor->save()){
					$this->redirect(array('mobilepos/index'));
				}else{
					// print_r($setor->getErrors());
				}
			}
			exit;
		}



		$items = Items::model()->data_items("MENU",true);
		$criteria = new CDbCriteria;
		$criteria->limit = 20; 
		$table = Meja::model()->findAll($criteria);
		$resultsAsArrays = array();
		foreach ($table as $model) {
			$resultsAsArrays[] = $model->attributes;
		}

		$categories = Categories::model()->findAll("store_id = '".Yii::app()->user->store_id()."' ");
		$categoriesArray = array();
		foreach ($categories as $model) {
			$categoriesArray[] = $model->attributes;
		}

		$cekKasir = Setor::model()->find(" is_closed = 1 and user_id = '$user_id' and  date(tanggal) = '$now' and store_id = '".Yii::app()->user->store_id()."'    ");
		$cekClosed = Setor::model()->find(" is_closed = 0 and user_id = '$user_id' and  date(tanggal) < '$now' and store_id = '".Yii::app()->user->store_id()."'   "); // checking setor table 
		$setor = Setor::model()->find(" user_id = '$user_id' and  date(tanggal) = '$now' and store_id = '".Yii::app()->user->store_id()."'  ");

		$this->render("index", [
			'items' => json_encode($items),
			'table' => json_encode($resultsAsArrays),
			'categories' => json_encode($categoriesArray),
			'cekKasir' => $cekKasir,
			'cekClosed' => $cekClosed,
			'setor' => $setor,
			'user_id' => $user_id,
			'now' => $now,
			'level' => $level

		]);



	}
}