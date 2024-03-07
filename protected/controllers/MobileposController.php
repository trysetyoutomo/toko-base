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
				'actions'=>array('index','table','waiter','admin','table','gettablebynumber'),
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
		$username = Yii::app()->user->name;
		$user = Users::model()->find('username=:un',array(':un'=>$username));
		$now = date("Y-m-d");

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
		// print_r($_REQUEST);
		if (isset($_POST['Setor'])){

			$cekKasir = Setor::model()->find(" is_closed = 0 and user_id = '$user->id' and  date(tanggal) = '$now'   ");
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
				$setor->user_id = $user->id;
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
				$this->render("index", [
					'items' => json_encode($items),
					'table' => json_encode($resultsAsArrays),
					'categories' => json_encode($categoriesArray)
				]);
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
				$this->render('application.views.site.input_saldo',array("model"=>$setor,"callback"=>"mobilepos/index"));
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
						$this->redirect(array('mobilepos'));
				}
				// $setor = new Setor;
				// $this->layout = "backend";
				// $this->render('input_saldo',array("model"=>$setor));
			}
		}







	}
}