<?php

class ItemsController extends Controller
{
	/**
	 * @var string the default yout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='main2';

	/**s
	 * @return array action filters
	 */
	// public function filters()
	// {
	// 	return array(
	// 		'accessControl', // perform access control for CRUD operations
	// 		'postOnly + delete', // we only allow deletion via POST request
	// 	);
	// }

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	// public function accessRules()
	// {
	// 	return array(
	// 		array('allow',  // allow all users to perform 'index' and 'view' actions
	// 			'actions'=>array('kartupersediaan','averagene','sqlAverage','getAverage','getlistprice','laporanrusak','laporanmasuk','barangrusak','barangmasuk','cari','barcode','index','view','check','delete'),
	// 			'users'=>array('*'),
	// 		),
	// 		array('allow', // allow authenticated user to perform 'create' and 'update' actions
	// 			'actions'=>array('masukubahdetail','Pengeluaranhapus','laporan_pengeluaran','bayarhutang','masukubahdetail','masukhapusdetail','masukhapus','keluarhapus','cetakpinjam','batalkembali','setkembali','laporanpinjam','pinjam','notifikasi','prosesrusakbarang','getmotif','getname','prosesmasukbarang','detailpaket','adminpaket','admin','create','createpaket','update','unitprice','itemnumber','category'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('allow', // allow admin user to perform 'admin' and 'delete' actions
	// 			'actions'=>array('Kosongkanstok','hapus','pengeluaranbaru','admin','checkbarcode'),
	// 			'users'=>array('@'),
	// 		),
	// 		array('deny',  // deny all users
	// 			'users'=>array('*'),
	// 		),
	// 	);
	// }
	// public function actionSetstok($before,$skrg,$id){
	// public function getLastPrice($id){

	// }

	public function actionDeletekartu($id,$type,$idb){
		// echo "masuk";
		if ($type){
			if ($type == "masuk"){
				$model = BarangMasuk::model()->findByPk($id);
			}
			else if ($type == "keluar"){
				$model = BarangKeluar::model()->findByPk($id);
			}
			if ($model->delete()){
				$this->redirect(array('items/view','id'=>$idb));
			}

		}
	}


	public function deletePO($id){
		echo "masuk";
	}

	public  function actionGetAllItems(){
		$data = Items::model()->data_items("MENU");
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";

		foreach ($data as $key => $value) {
			echo "<option value='$key'>$value</option>";
		}
	}
	public static function queryStok($filter){
		$store_id = Yii::app()->user->store_id();
		$sql = "select 
		iss.barcode as barcode,
		i.stok_minimum as stok_minimum, 
		m.nama nama_subkategori,
		concat(i.item_name,' - ',iss.nama_satuan,'') as item_name,
		c.category as nama_kategori,
		i.hapus hapus, iss.id as satuan_id, i.id id

		from items i inner join items_satuan iss
		on iss.item_id = i.id and i.is_stockable = 1 and i.hapus = 0
		
		left join categories as c on c.id = i.category_id
		left join motif m on m.category_id = c.id and m.id = i.motif
		inner join stores st on st.id = i.store_id 

		
		where  (iss.is_default = 1 ) and st.id = '{$store_id}'  $filter
		group by iss.id
		order by c.category, m.id  asc";
		// echo $sql ;
		// exit;
		return $sql;
	}

	public function actionDetailmasuk(){
		$this->render('detailmasuk' ) ;
	}

	public function actionLaporanstokJSON(){
		if (isset($_REQUEST['adjust'])){
			$adjust = 1;
		}else{
			$adjust = 0;
		}

	    $draw                   = $_REQUEST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex     = $_REQUEST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	   	if ($_REQUEST['columns'][$orderByColumnIndex]['data']=="0") {
			$orderBy = "id";
	   	}else{
	    	$orderBy                = $_REQUEST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	   	}
	    $orderType              = $_REQUEST['order'][0]['dir']; // ASC or DESC
	    if (isset($_REQUEST['start'])){	
		    $start                  = $_REQUEST["start"];//Paging first record indicator.
	    }else{
		    $start                  = 0;
	    }

	    if (isset($_REQUEST['length'])){	
	    $length                 = $_REQUEST['length'];//Number of records that the table can display in the current draw
	    }else{
	    	$length = 0;
	    }
	    /* END of POST variables */
		$query  = "select 
		m.nama nama_sub_kategori,
		concat(i.item_name,'  ','') as item_name,
		c.category as nama_kategori,
		m.nama as motif,
		i.hapus hapus, iss.id as satuan_id, i.id id, 
		iss.barcode barcode

		from items i inner join items_satuan iss 
		on iss.item_id = i.id and i.is_stockable = 1 
		left join categories as c on c.id = i.category_id
		left join motif m on m.category_id = c.id and m.id = i.motif
		inner join stores s on s.id =  i.store_id 

		$filter 
		and iss.is_default = 1 and i.hapus=0 and s.id = ".Yii::app()->user->store_id()."
		group by i.id
		order by c.category, m.id, i.item_name  asc

		";


	    $recordsTotal = count($this->getAdminJSON($query,$adjust));
	      if(!empty($_REQUEST['search']['value'])){

	        for($i=0 ; $i<count($_REQUEST['columns']);$i++){
	        	// echo  $_REQUEST['columns'][$i]['searchable'];
	        	// echo "<br>";
	        	if ($_REQUEST['columns'][$i]['searchable']=="true"){

		            $column     =   $_REQUEST['columns'][$i]['name'];//we get the name of each column using its index from POST request
		            $where[]    =   "$column like '%".$_REQUEST['search']['value']."%'";
	        	}else{
	        		// echo "masuk";
	        	}
	        }
	        $where = "WHERE ".implode(" OR " , $where);// id like '%searchValue%' or name like '%searchValue%' ....
	        /* End WHERE */

	        // $sql = sprintf("SELECT * FROM %s %s %s", $query , $where, " AND CREATED_BY='".$uid."'");//Search query without limit clause (No pagination)
            $sql = "SELECT * FROM ($query) as d $where  limit  $start,$length  ";



	        $recordsFiltered = count($this->getLaporanStokJSON($sql,$adjust));//Count of search result
	        $data = $this->getLaporanStokJSON($sql,$adjust);

	      

    	}
    	else {
         $sql = "SELECT * FROM ($query) as d  ORDER BY $orderBy $orderType limit  $start,$length ";
         $data = $this->getLaporanStokJSON($sql,$adjust);
         $recordsFiltered = $recordsTotal;
         // var_dump($recordsFiltered);
    	}
    	// var_dump($data);

    	 $response = array(
	         "draw"             => intval($draw),
	         "recordsTotal"     => $recordsTotal,
	         "recordsFiltered"  => $recordsFiltered,
	         "data"             => $data
        );

    	
        echo json_encode($response);
        exit;
	}

	public function actionLaporancabangpusat(){
    	 $this->render('laporanstok',['cabangpusat'=>1]) ;
    }

	public function actionLaporanstok(){
    	 $this->render('laporanstok' ) ;

    }
	public function getLastPrice($id){
		// $sql = "select "
	}
	public function actionGetHargaJualBySatuan($id,$satuan_name,$price_type){
		$sql = "
		 select iss.* , 
		 IFNULL(isp.price, iss.harga) harga_jual 
			from 
		 items_satuan iss LEFT join items_satuan_price isp  on iss.id = isp.item_satuan_id   
		 inner join  items_satuan_price_master ispm on ispm.name = isp.price_type
		 and ispm.label_name = '$price_type'

		 where item_id = '$id' and nama_satuan='$satuan_name'  ";
		// echo $sql;
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		echo json_encode($data);
	}
	public function actionGetHargaBeliBySatuanID($id){
		$sql = "select * from items_satuan where id = '$id'";
		$data = Yii::app()->db->createCommand($sql)->queryRow();
		echo json_encode($data);
	}
	public function actionSetstok($before,$skrg,$id,$satuan_id,$harga){
		$bid =Yii::app()->user->branch();
		if (floatval($skrg)>floatval($before) ){
			// echo "1234";
			$selisih = floatval($skrg) - floatval($before);
			// var_dump($selisih);
			// var_dump($skrg);
			// var_dump($before);
			// exit;
			$modelh = new BarangMasuk;
			$modelh->tanggal = date(" Y-m-d H:i:s");
			$modelh->user = Yii::app()->user->name;
			$modelh->sumber = "Penyesuaian Stok ";
			$modelh->jenis = "masuk";
			$modelh->faktur = "999999";
			$modelh->keterangan = "Penyesuaian Stok ";
			$modelh->branch_id = Yii::app()->user->branch();
			

			if ($modelh->save()){
				$model = new BarangMasukDetail;
				$model->kode = $id;
				$model->jumlah = $selisih ;
				$model->harga = $harga;
				$model->supplier_id = 0000;
				$model->head_id = $modelh->id;
				$model->satuan = $satuan_id;
				// $model->branch_id = Yii::app()->user->branch();
				// $model->branch_id = Yii::app()->user->branch();
				if ($model->save()){
						$data = array("success"=>true);
					// $d = new ItemsDetail;
					// $d->kode = $id;
					// $d->jumlah = $selisih;
					// $d->harga = 900000;
					// $d->supplier_id = 0000;
					// $d->tanggal = date("Y-m-d H:i:s");
					// if ($d->save()){
					// }else{
					// 	$data = array("success"=>false);
					// }
				}else{
					$data = array("success"=>false);
				}
			}else{
				$data = array("success"=>false);
			}
		}else if   (floatval($skrg)<floatval($before) ) { // jika barang nya jadi kurang
			// exit;
			$selisih = floatval($before) - floatval($skrg);
			if ($selisih==0) {
				$selisih = $skrg;
			}

			$modelh = new BarangKeluar;
			$modelh->tanggal = date(" Y-m-d H:i:s");
			$modelh->user = Yii::app()->user->name;
			$modelh->sumber = "Penyesuaian Stok ";
			$modelh->jenis = "keluar";
			$modelh->jenis_keluar = "Penyesuaian Stok";
			// $modelh->faktur = "999999";
			$modelh->keterangan = "Penyesuaian Stok ";
			$modelh->branch_id = $bid;
			
			

			if ($modelh->save()){
				$model = new BarangKeluarDetail;
				$model->kode = $id;

				$model->jumlah = $selisih ;
				$model->satuan = $satuan_id;

				// $model->harga = $this->getAverage($id,$satuan_id,$bid);
				$model->harga = $harga;
				// $model->supplier_id = 0000;
				$model->head_id = $modelh->id;
				if ($model->save()){
						$data = array("success"=>true);
					// $d = new ItemsDetail;
					// $d->kode = $id;
					// $d->jumlah = $selisih;
					// $d->harga =  $this->getAverage($id,$satuan_id,$bid);
					// $d->supplier_id = 0000;
					// $d->tanggal = date("Y-m-d H:i:s");
					// if ($d->save()){
					// }else{
					// 	$data = array("success"=>false);
					// }
				}else{
					$data = array("success"=>false);
				}
			}else{
				$data = array("success"=>false);
			}
		}
		
		echo json_encode($data);
	}
	public function actionPrintDataStok(){
		if (isset($_REQUEST['tgl1']) && isset($_REQUEST['tgl2']) ){	
			$tgl1 = $_GET['tgl1'];
			$tgl2 = $_GET['tgl2'];
		}else{
			$tgl1 = date("Y-m-d");
			$tgl2 = date("Y-m-d");
		}
		
		if (isset($_REQUEST['id'])){	
			$id = $_GET['id'];
		}else{
			$id = "1";
		}

		if (isset($_REQUEST['jenis'])){	
			$jenis = $_GET['jenis'];
		}else{
			$jenis = "2";
		}
		// $jenis = $_GET['jenis'];
		
		$dataProvider = Yii::app()->db->createCommand()
		->select('sales.id as sid,sales.date as date,item_name ,item_price, item_discount,nama_outlet,	sum(if(item_price<0,-quantity_purchased,quantity_purchased)) jumlah, items.item_tax, sum((quantity_purchased*item_price)-((item_discount*item_price/100)*quantity_purchased)) total	')
		->from('sales_items, sales ,items,outlet')
		->where("
				sales.id = sales_items.sale_id  and
				sales_items.item_id = items.id and
				items.kode_outlet = outlet.kode_outlet  and 
				outlet.kode_outlet = $id and
				date(sales.date) >= '$tgl1' and date(sales.date) <= '$tgl2'
				")
		->group('sales.id,sales_items.item_id')
		->order('sales.id')
		
		->queryAll();

		$ambilData = Yii::app()->db->createCommand()
		->select('nama_outlet nm, persentase_hasil ps, sum((quantity_purchased*item_price)-(quantity_purchased*(item_discount*item_price/100)))  total , sum((quantity_purchased*item_price)-(quantity_purchased*(item_discount*item_price/100)))  * persentase_hasil /100 as bersih 	')
		->from('sales_items, sales ,items,outlet')
		->where("
				sales.id = sales_items.sale_id  and
				sales_items.item_id = items.id and
				items.kode_outlet = outlet.kode_outlet  and
				outlet.kode_outlet = $id and
				date(sales.date) >= '$tgl1' and date(sales.date) <= '$tgl2'
				")
		->queryRow();

		$x = new CArrayDataProvider($dataProvider,array(
			'pagination'=>array(
							'pageSize'=>1000,
						),
		));//dikonfersi ke CArrayDataProvider
		
		$tglheader  = date('d-M-Y',strtotime($tgl1));
		$tgl2header = date('d-M-Y',strtotime($tgl2));
	//$this->renderPartial('detailoutlet',array('summary'=>$outletsum,'bersih_d'=>$outletbersih_d));
		if ($jenis==1){
		$this->renderPartial('detailoutlet_print', array(
				'dataProvider' => $dataProvider,
				'data'=>$ambilData,
				'tglheader'=>$tglheader,
				'tgl2header'=>$tgl2header,
			));
		}else{
		$this->renderPartial('detailoutlet_print_v2', array(
				'dataProvider' => $dataProvider,
				'data'=>$ambilData,
				'tglheader'=>$tglheader,
				'tgl2header'=>$tgl2header,
				'tgl1'=>$tgl1,
				'tgl2'=>$tgl2,
				'id'=>$id,
			));
		
		
		}
		

			
	}

	public function actionUbah_pengeluaran($id){
		$model = Pengeluaran::model()->findByPk($id);
		if(isset($_POST['Pengeluaran']))
		{
			$model->attributes=$_POST['Pengeluaran'];
			if($model->save()){
				$this->redirect(array('items/laporan_pengeluaran','id'=>$model->id));
			}
		}

		$this->render('update_pengeluaran',array(
			'model'=>$model,
		));
	}

	
	public function actionKartupersediaan(){
		// echo "123";

		$this->render("filter_kartupersediaan");
		// $this->render("kartupersediaan");
	}
	public function actionKosongkanstok(){
		$sql = "update items set stok = 0 ";
		$d = Yii::app()->db->createCommand($sql)->execute();
		$this->redirect(array('items/admin'));	
		
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionMasukHapus($id){
		// echo $id;
		$model = BarangMasuk::model()->findByPk($id);
		$pm = BarangMasukDetail::model()->findAll("head_id = '$id'");
		foreach ($pm as $p) {
			$brg = Items::model()->findByPk($p->kode);
			$brg->stok = $brg->stok - $p->jumlah;
			$brg->update();
		}
		if ($model->delete()){
			$this->redirect(array('items/laporanmasuk'));	
			// echo "sukses";
		}	
	}
	public function actionKeluarHapus($id){
		// echo $id;
		$model = BarangKeluar::model()->findByPk($id);
		$pm = BarangKeluarDetail::model()->findAll("head_id = '$id'");
		// foreach ($pm as $p) {
		// 	$brg = Items::model()->findByPk($p->kode);
		// 	$brg->stok = $brg->stok + $p->jumlah;
		// 	$brg->update();
		// }
		//ambil data dari user yg login
		$username = Yii::app()->user->name;
		$user = Users::model()->find('username=:un',array(':un'=>$username));

		//UPDATE DATA
		$model->status_keluar = 2;
		$model->deleted_by =  $user->id;
		$model->deleted_at = date("Y-m-d H:i:s");

		if ($model->update()){
			$this->redirect(array('items/laporanrusak'));	
			// echo "sukses";
		}	
	}
	public function actionPengeluaranhapus($id){
		// echo $id;
		$model = Pengeluaran::model()->findByPk($id);
		if ($model->delete()){
			$this->redirect(array('items/laporan_pengeluaran'));	
		}	
	}
	public function actionMasukUbahDetail($id){
		$model = BarangMasukDetail::model()->findByPk($id);
		if (isset($_REQUEST['BarangmasukDetail'])){
			$jml = $_REQUEST['BarangmasukDetail']['ubahke'] - $_REQUEST['BarangmasukDetail']['jumlah'];
			$model->jumlah =   $_REQUEST['BarangmasukDetail']['jumlah'];
			$model->supplier_id =   $_REQUEST['BarangmasukDetail']['supplier_id'];
			$model->harga =   $_REQUEST['BarangmasukDetail']['harga'];
			if ($model->update()){
				$brg = Items::model()->findByPk($model->kode);
				$brg->stok = $brg->stok + $jml;
				if ($brg->update())
					$this->redirect(array('items/laporanmasuk'));	
				else {
					print_r($model->getErrors());
					exit;
				}
			}else{
				print_r($model->getErrors());
				exit;
			}
		}

		$this->render("masukubahdetail",array('model'=>$model));
	}	
	public function actionHapusdetail($id){
		$sql = "update  barangmasuk set status_masuk = 2 where id = '$id'";
		// $sql2 = "delete from barangmasuk_detail where head_id = '$id'";
		$q1 = Yii::app()->db->createCommand($sql)->execute();
		// $q2 = Yii::app()->db->createCommand($sql2)->execute();
		if ($q1)
			$this->redirect(array('items/laporanmasuk'));	



		// BarangMasukDetail::model()->findByPk($id);
	}
	public function actionMasukHapusDetail($id){
		// echo $id;
		// $model = BarangMasuk::model()->findByPk($id);
		$pm = BarangMasukDetail::model()->findByPk($id);
		// echo $pm-kode;
		$brg = Items::model()->findByPk($pm->kode);
		$brg->stok = $brg->stok - $pm->jumlah;
		$brg->update();
		// }
		if ($pm->delete()){
			$this->redirect(array('items/laporanmasuk'));	
			// echo "sukses";
		}	
	}


	private function setKanan($string){
		return str_pad($string,22," ",STR_PAD_RIGHT);
	}

	private function setKiri($string){
		return str_pad($string,17," ",STR_PAD_RIGHT).":";
	}
	private function setKiri2($string){
		return str_pad($string,17," ",STR_PAD_RIGHT);
	}
	private function setKananAngka($string){
		return str_pad($string,25," ",STR_PAD_LEFT);
	}
	private function setKiriAngka($string){
		return str_pad($string,22," ",STR_PAD_LEFT);
	}

	public function actionCetaklabel(){
		// echo "cooming soon";
		$this->renderPartial('cetaklabel');
	}
	public function actionCetakpinjam($id){
		$array = array();
		$p = Peminjaman::model()->findByPk($id);
		$pd = PeminjamanDetail::model()->findAll("head_id = '$id'");
		$array["id"] = $id;
		$array["tanggal_pinjam"] = $this->setKiri("Tgl Pinjam ").$this->setKanan($p->tanggal_pinjam) ;
		$array["tanggal_harus_kembali"] = $this->setKiri("Tgl Wjb Kmbli ").$this->setKanan($p->tanggal_kembali) ;
		if ($p->iskembali==1)
			$array["status"] = $this->setKiri("Status ").$this->setKanan("Sudah Dikembalikan") ;
		else
			$array["status"] = $this->setKiri("Status ").$this->setKanan("Belum Dikembalikan") ;

		$array["peminjam"] = $this->setKiri("Nama  ").$this->setKanan($p->nama) ;
		$array["deposit"] = $this->setKiri("Deposit  ").$this->setKanan( number_format($p->deposit) ) ;
		$array["keterangan"] = $this->setKiri("keterangan  ").$this->setKanan($p->keterangan) ;
		$array["keterangan_akhir"] = $this->setKanan("Barang yang sudah dipinjam harus  di kembalikan sesuai dengan tanggal yang  telah ditentukan ") ;
		$arr_det = array();
		$no = 0;
		$total_akhir = 0;
		foreach ($pd as $pd) {
			$arr_det[$no]["nama"] = $this->setKiri2( substr(Items::model()->findByPk($pd->item_id)->item_name,0,50 ) );
			$total = Items::model()->findByPk($pd->item_id)->total_cost * $pd->qty;
			$arr_det[$no]["harga"] = $this->setKiri2( number_format(Items::model()->findByPk($pd->item_id)->total_cost) ." x ". $pd->qty  ).$this->setKananAngka( number_format($total) ) ;
			$total_akhir = $total_akhir + $total;
			$no++;
			// $arr_det[]["nama"] = $this->setKiri( substr(Items::model()->findByPk($pd->item_id)->item_name,0,15 ) ).$this->setKananAngka($pd->qty) ;
			// $arr_det["qty"] = ;

		}
		$array["total_akhir"] = $this->setKananAngka("Total Bayar : ". number_format($total_akhir));
		$array["detail"] = $arr_det;
		// array_push($array, $arr_det);


		echo json_encode($array);
	}
	public function actionSetkembali($id){
		$model = Peminjaman::model()->findByPk($id);
		$model->iskembali = 1;
		$model->tanggal_dikembalikan = date('Y-m-d H:i:s');
		if ($model->update()){
			$pm = PeminjamanDetail::model()->findAll("head_id = '$id'");
			foreach ($pm as $p) {
				$brg = Items::model()->findByPk($p->item_id);
				$brg->stok = $brg->stok + $p->qty;
				$brg->update();
			}
			$this->redirect(array('items/laporanpinjam'));	
		}
	}
	public function actionBatalkembali($id){
		$model = Peminjaman::model()->findByPk($id);
		$model->iskembali = 0;
		$model->tanggal_dikembalikan = " ";
		if ($model->update()){
			$pm = PeminjamanDetail::model()->findAll("head_id = '$id'");
			foreach ($pm as $p) {
				$brg = Items::model()->findByPk($p->item_id);
				$brg->stok = $brg->stok - $p->qty;
				$brg->update();
			}
			$this->redirect(array('items/laporanpinjam'));	
		}
	}
	public function actionGetMotif($id){
	  	$store_id = Yii::app()->user->store_id();     
		if (empty($id))
			$model = Motif::model()->findAll(" store_id = '".$store_id."' ");
		else
			$model = Motif::model()->findAll(" category_id = '$id' and  store_id = '".$store_id."'  ");

		echo "<option>Pilih</option>";
		foreach ($model as $k ) {
			echo "<option value='$k->id' > $k->nama </option>";
		}
	}
	public function actionNotifikasi(){
		// echo "123";
		// $sql = "select * from items where stok <= stok_minimum and status = 0 limit 10";
		// $model = Yii::app()->db->createCommand($sql)->queryAll();
		// $array = array();
		// $array['data'] = $model;
		// $array['count'] = count($model);
		$this->render('notifikasi', array(
			// 'filtersForm' => $filtersForm,
			// 'model' => $dataProvider,
			// 'model' => $model
		));
		// echo json_encode($array);
	}
	public function actionNotifikasiJSON(){
		$branch_id = Yii::app()->user->branch();
		// echo "123";
		// $sql = "select * from items where stok <= stok_minimum and status = 0 limit 10";
		$sql = ItemsController::queryStok($filter);
		// echo $sql;
		// exit;
		$model = Yii::app()->db->createCommand($sql)->queryAll();
		$count = 0;
		$array = array("count"=>count($model),"data"=>array());
		foreach ($model as $key => $value) {
			// $array['data']['nama'][] = $value['item_name'] ;
			// $array['data']['stok'][] = $this->getStok($value['id'],$value['satuan_id'],$branch_id) ;
			$stok = $this->getStok($value['id'],"",$branch_id);
			if ($stok<$value['stok_minimum']){	
				$count++;
				array_push($array['data'], array("barcode"=>$value['barcode'],"nama"=>$value['item_name'],"stok"=>$stok,"stok_minimum"=>$value['stok_minimum']));
			}
		}	
		$array['count'] = $count;
		// for
		// echo "<pre>";
		// print_r($array);
		// echo "</pre>";
		// $array = array();
		// $array['count'] = count($model);
		
		echo json_encode($array);
	}
	public function actionCari($query){

		$this->renderPartial('carimenu',
			array(
				'query'=>$query
			));
	}
	public function actionLaporanmasuk(){
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			//$tgl =  strtotime("+7 day", $tgl);
			$tgl2 = date('Y-m-d'); 
		}
		$this->render('laporanbarmas',
			array(
				'tgl'=>$tgl,
				'tgl2'=>$tgl2,
			)
		);
	}
	public function actionLaporan_pengeluaran(){
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			//$tgl =  strtotime("+7 day", $tgl);
			$tgl2 = date('Y-m-d'); 
		}
		$this->render('laporan_pengeluaran',
			array(
				'tgl'=>$tgl,
				'tgl2'=>$tgl2,
			)
		);
	}
	public function actionLaporanrusak(){
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			//$tgl =  strtotime("+7 day", $tgl);
			$tgl2 = date('Y-m-d'); 
		}
		$this->render('laporanrusak',
			array(
				'tgl'=>$tgl,
				'tgl2'=>$tgl2,
			)
		);
	}

	public function actionLaporanpinjam(){
		if (isset($_GET['Sales']['date']) || isset($_GET['Sales']['date2']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['date2'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			//$tgl =  strtotime("+7 day", $tgl);
			$tgl2 = date('Y-m-d'); 
		}
		$this->render('laporanpinjam',
			array(
				'tgl'=>$tgl,
				'tgl2'=>$tgl2,
			)
		);
	}



	public static function sqlAverage($id,$satuan_id,$branch_id){
		if ($satuan_id==""){
			$querySatuan = " ";
		}else{
			$querySatuan = " and iss.id = '$satuan_id' ";
		}
		// $branch_id = Yii::app()->user->branch();
		// var_dump($branch_id);
		// $satuan_id 
		if ($branch_id!=""){
			$whereBarangMasukBranch = " and bm.branch_id = '$branch_id' ";
			$whereBarangKeluarBranch = " and bk.branch_id = '$branch_id'  ";
			$wherePenjualanBranch = " and s.branch = '$branch_id'  ";
		}else{
			$whereBarangMasukBranch = "";
			$whereBarangKeluarBranch = "";
			$wherePenjualanBranch = "";
		}
		$sql = "
		SELECT 'masuk' as tipetransaksi ,bm.id as id_transaksi, bm.tanggal tanggal, bm.tanggal nama, bmd.harga harga, bmd.jumlah,bmd.jumlah_satuan, bm.jenis jenis, concat(bm.keterangan,' - ID ',bm.id) keterangan
		FROM barangmasuk bm
		INNER JOIN barangmasuk_detail bmd ON bm.id = bmd.head_id
		INNER JOIN items_satuan iss on iss.id = bmd.satuan
		left JOIN supplier s ON s.id = bmd.supplier_id
		WHERE bmd.kode = '$id' and bm.status_masuk <> '2'
		{$whereBarangMasukBranch}
		{$querySatuan}
		GROUP BY bmd.id

		UNION

		

		SELECT 'keluar' as tipetransaksi ,bk.id as id_transaksi, bk.tanggal tanggal, bk.id nama, bmd.harga harga, bmd.jumlah,bmd.jumlah_satuan,bk.jenis jenis,bk.keterangan keterangan
		FROM barangkeluar bk
		INNER JOIN barangkeluar_detail bmd ON bk.id = bmd.head_id
		INNER JOIN items_satuan iss on iss.id = bmd.satuan
	
		WHERE bmd.kode = '$id' {$whereBarangKeluarBranch}
				
		{$querySatuan}

				 and bk.status_keluar = 1


		GROUP BY bmd.id
		
		UNION 

		SELECT
		'jual' as tipetransaksi,
		s.id as id_transaksi,
		s.date tanggal,
		s.id nama,
		si.item_modal,
		si.quantity_purchased,
		si.quantity_purchased,
		s.jenis jenis,
		concat(s. COMMENT, ' - ID ', s.id) keterangan
		FROM
			sales s
		INNER JOIN sales_items_paket si ON si.sale_id = s.id
		INNER JOIN paket p on p.id_paket = si.item_id
		inner join paket_detail pd on pd.paket_id = p.id_paket
		inner join items i on i.id = pd.item_id
		where i.id = '$id' {$wherePenjualanBranch} and s.status = 1
		
		group by s.id


		ORDER BY tanggal ASC
		";
		// echo $sql;
		// exit;
		return $sql;
	}
	#SELECT s.date tanggal,s.id nama, si.item_modal, si.quantity_purchased,s.jenis jenis, concat(s.comment,' - ID ',s.id) keterangan 
		#FROM sales s
		#INNER JOIN sales_items si ON si.sale_id = s.id 
		#INNER JOIN items_satuan iss on iss.id = si.item_satuan_id
		#AND si.item_id = '$id'
		#where  s.branch = '$branch_id'
		#and iss.id = '$satuan_id' and s.status <> 2 
		
		
		#UNION	
	public static function GetStok($id,$satuan_id,$branch_id){
		$sql = ItemsController::sqlAverage($id,$satuan_id,$branch_id);
		// echo $sql;
		// exit;
		$model = Yii::app()->db->createCommand($sql)->queryAll();
		$qty_awal = 0;
		$saldo_awal = 0;
		foreach ($model as $key => $value) { 

			 if ( $value[jenis]=="masuk" ): 
				$qty_awal += $value[jumlah];
				$saldo_awal += $value[jumlah] * $value[harga];
			endif;

			if ($value[jenis]=="keluar"): 
				$qty_awal = $qty_awal -  $value[jumlah];
				$saldo_awal = $saldo_awal -  ($value[harga]*$value[jumlah]);
			endif;
		
		}
		return $qty_awal;		
	}

	public static function ActionAverage($id,$val){ // mendapatkan via ajax
		$av = ItemsController::getAverage($id);
		$one = $av *0.01; // untung 1 %
		$av = $av + $one;
		$two = $av * ($val/100);
		$av = $av + $two;

		$angka = round($av);
		$angka = $angka - ($angka % 100);		
		echo round($angka);

		// echo $data;
	}
	public function actioncheckAverage($id,$satuan_id){
		echo $this->getAverage($id,$satuan_id);
	}
	public static function GetModal2($id,$satuan_id){
		$sql = "select harga_beli from items_satuan where item_id =  '$id' and id = '$satuan_id'";
		$model = Yii::app()->db->createCommand($sql)->queryRow();
		return $model['harga_beli'];
	}
	public static function GetAverage($id,$satuan_id,$branch_id){
		$sql = ItemsController::sqlAverage($id,$satuan_id,$branch_id);
		$model = Yii::app()->db->createCommand($sql)->queryAll();
		$qty_awal = 0;
		$saldo_awal = 0;
		foreach ($model as $key => $value) { 

			 if ( $value[jenis]=="masuk" ): 
				$qty_awal += $value[jumlah];
				$saldo_awal += $value[jumlah] * $value[harga];
			endif;

			if ($value[jenis]=="keluar"): 
				$qty_awal = $qty_awal -  $value[jumlah];
				$saldo_awal = $saldo_awal -  ($value[harga]*$value[jumlah]);
			endif;
		
		}

		try{
			if ($qty_awal == 0){
				$qty_awal = 0;
				$saldo_awal = 0;
			}

			if ($saldo_awal>0 || $qty_awal>0)
				return round($saldo_awal/$qty_awal);
			else
				return round($qty_awal);

		}catch(Exception $err){
			return 0;
		}
		// }else{
		// 	return $qty_awal;
		// }
		// echo round($saldo_awal/$qty_awal);


		
	}

	public static function getSatuanItems($item_id,$stok){
		// echo $stok;
		// exit;
			$sqlx = " SELECT  * FROM items_satuan WHERE item_id = '$item_id' order by satuan desc";
			 // $datastauan = ItemsSatuan::model()->findAll(" item_id = '$m[id]' ");
			$datastauan = Yii::app()->db->createCommand($sqlx)->queryAll();
			// echo "<pre>";
			// print_r($datastauan);
			// exit;
			// echo $stok;
			// echo "<br>";
			$stok2 = $stok;
			// echo $stok;
			$return = "";
			foreach ($datastauan as $key => $value) {
			
				$return .= $value['barcode']." - ".$value['nama_satuan']." :"." ";			
				$satuan = $value['nama_satuan'];
				$satuan_jml = $value['satuan'];
				// echo $stok2 . " - ". $stok;
				// exit;
				if ($stok2>=$stok){
					$s = $stok % $satuan_jml;
					if ($s==0){ // jika ga ada sisa maka
						$total =  $stok/$satuan_jml;
						$temp = $total*$satuan_jml;
						$stok2 -= $temp;
						$return .= $total."";
					}else{ // jika ada sisa maka
						// echo $s;
						$total = $stok - $s;
						$stok2-=$total;

						$total =  $total/$satuan_jml;
						$return .= $total;
						
						// echo $total; 
					}
				}else{ // jika di bawah sotk yang ada maka
					$s = $stok2%$satuan_jml;
					// echo $s;
					if ($s==0){
						// echo $stok2*$satuan_jml;
						$total =  $stok2/$satuan_jml;
						$temp = $total*$satuan_jml;
						$stok2 -=$temp ;
						$return .=$total;
						// $stok2 -= $temp;
					}else{
						$s = $stok2%$satuan_jml;
						$sisa = $stok2 - $s;
						$s = $sisa/$satuan_jml;
						$return .= $s;
						// echo $sisa;
						$stok2-=$sisa;
					}

					//echo "-"."<br>";
				}
				// echo $stok2;
				// exit;

				if ($stok2<>0){	
					$return .= "<br>";
				}else{
					break;
					// echo $return;
					// exit;
					// return $return;
					// return false;
				}
			}
			// echo $return;
			return $return;

	}
	public function actionProsesMasukbarang(){
	$transaction = Yii::app()->db->beginTransaction();
	try {
		// echo "<pre>";
		// print_r($_REQUEST);
		// echo "</pre>";
		// exit;
			$nilai = $_REQUEST['jsonObj'];

			if (count($nilai)<1){
				echo json_encode(array("status"=>0));
				exit;
			}
			$head = $_REQUEST['head'];	


			$modelh = new BarangMasuk;
			$modelh->tanggal = $_REQUEST['head']['tanggal']. " ".date("H:i:s");
			$modelh->user = Yii::app()->user->name;
			$modelh->sumber = $_REQUEST['head']['sumber'];
			$modelh->jenis = "masuk";
			$modelh->faktur = $_REQUEST['head']['faktur'];
			$modelh->keterangan = $_REQUEST['head']['keterangan'];
			$modelh->kode_trx = $_REQUEST['head']['kode_trx'];
			$modelh->branch_id = $_REQUEST['head']['cabang'];
			$modelh->status_aktif = 1;
			$modelh->subtotal = $_REQUEST['head']['subtotal'];
			$modelh->diskon = $_REQUEST['head']['diskon'];
			$modelh->grand = $_REQUEST['head']['grand'];
			$modelh->bayar = $_REQUEST['head']['bayar'];
			$modelh->kembali = $_REQUEST['head']['kembali'];
			$modelh->metode_pembayaran = $_REQUEST['head']['metode_pembayaran'];


			if ($_REQUEST['head']['isbayar']=="1"){
				$modelh->isbayar = 1;
			}else{
				$modelh->isbayar = 0;
			}
			$modelh->tanggal_jt = $_REQUEST['head']['tanggal_jt'];
			// $modelh->branch_id = $_REQUEST['head']['cabang'];

			if ($modelh->save()){
				foreach ($nilai as $n){
					$i = explode("##", $n['idb']);
					// $i = explode("-", $n['idb']);
					$barcode = $i[0];
					// $items = Items::model()->find("barcode = '$barcode' ");
					$items = ItemsSatuan::model()->find("barcode = '$barcode' ");
					// var_dump($items->item_id);
					// exit;


				 // get satuan now
                    $satuanUtama1 = ItemsSatuan::model()->find("   id = '$items->id' ");
					$satuanUtamaID = $satuanUtama1->id;
					$satuanUtama_jumlah_1 = $satuanUtama1->satuan;


					//get satuan utama
                    // $satuanUtama2 = ItemsSatuan::model()->find(" is_default = '1' and item_id ='$' ");

                    // cek satuan utama
                    $satuanUtama2 = ItemsSatuan::model()->find(" is_default = '1' and item_id ='$items->item_id' ");
					$satuanUtamaID_default = $satuanUtama2->id;
					$satuanUtamaKode_default = $satuanUtama2->item_id;
					$satuanUtama_jumlah_2 = $satuanUtama2->satuan;


					$satuan_total_masuk = $satuanUtama_jumlah_1 * $satuanUtama_jumlah_2;
					if ($satuanUtama_jumlah_2==1){
						$satuan_total_masuk = $satuanUtama_jumlah_1*$n['jml'];
					}
					// var_dump($items->id);
					// var_dump($satuanUtamaID);
					// var_dump($satuanUtamaID_default);
					// exit;


					
					$model = new BarangMasukDetail;
					$model->kode = $satuanUtamaKode_default;
					// $model->kode = $items->item_id;
					// $model->jumlah =  $n['jml']*$n['satuan'];
					$spp = $n['supplier'];
					// $model->jumlah =  $n['jml'];
					$model->jumlah =  $satuan_total_masuk;
					$model->satuan = $n['satuan'];
					// $model->satuan = $satuanUtamaID_default;
					$model->jumlah_satuan = $n['jml'];
					$model->harga = $n['harga'];
					$model->supplier_id = $spp;
					$model->head_id = $modelh->id;
					$model->letak_id = $_REQUEST['head']['letak'];

					$d = new ItemsDetail;
					// // $sat = ItemsSatuan::model()->find("item_id = '$n[idb]' and id='$n[satuan]' ")->satuan;
					$d->kode = $items->id;
				

					if ($model->save() && $d->save(false) ){



						$brg = Items::model()->findByPk($model->kode);
						$untung = $brg->persentasi / 100;
						//get harga jual
						$av = ItemsController::getAverage($model->kode,$n['satuan'], $_REQUEST['head']['cabang']);
						$one = $av *0.01; // untung 1 %
						$av = $av + $one;
						$two = $av * $untung;
						$av = $av + $two;

						$poid = $_REQUEST['head']['poid'];
						if (!empty($poid)){
							$po = PurchaseOrder::model()->find("kode_trx = '$poid' ");
							$po->status_aktif = 0;
							$po->update();
						}

						
						// update items satuan harga [start]
						$metode_stok = SiteController::getConfig("metode_stok");
						if ($metode_stok == "lifo"){
							$sat = ItemsSatuan::model()->find("barcode = '$barcode' and id='$n[satuan]' ");
							$sat->harga_beli = $n['harga']; 
							$sat->save(); // update master by last price
						}
						// else if ($metode_stok == "average"){
						// 	$sat = ItemsSatuan::model()->find("barcode = '$barcode' and id='$n[satuan]' ");
						// 	$sat->harga_beli = $av; // set average price
						// 	$sat->save(); // update master by average price
						// }
						// update items satuan harga [end]



						//set stok
						// $brg->persentasi = $untung * 100;
						$angka = round($av);
						$angka = $angka - ($angka % 100);
						$brg->total_cost = $angka;
						// $brg->price_reseller = round($av);
						// $brg->price_distributor = round($av);
						// $brg->stok = $brg->stok + $model->jumlah;
						// if (!$brg->update()){
						// 	echo "gagal on saldo";
						// 	exit;
						// }
						// end set stok 

	                    
	                    // end of set saldo


						// echo "sukses bro";
					}else{
						echo json_encode(
							array(
								"status"=>0,
								"error"=>$model->getErrors(),
								"error 2"=>$d->getErrors(),
							)
						);
						exit;
					}


				}
				// $model
				// $modelh->update();
				JurnalController::createBuyTransaction($modelh);
				$transaction->commit();
				echo json_encode(array("status"=>1));
			}else{
				// echo "123";
				// print_r();
				echo json_encode(
					array(
						"status"=>0,
						"error"=>$modelh->getErrors(),
					)
				);
				$transaction->rollback();
				exit;
			}	

			 //end try
        }catch(Exception $err){
        	echo $err;
			echo json_encode(
				array(
					"status"=>0,
					"error"=>$err,
				)
			);

			$transaction->rollback();
		}
	}

public function actionProsesMasukbarangPO(){
	$transaction = Yii::app()->db->beginTransaction();
	try {
		// echo "<pre>";
		// print_r($_REQUEST);
		// echo "</pre>";
		// exit;
			$nilai = $_REQUEST['jsonObj'];

			if (count($nilai)<1){
				echo json_encode(array("status"=>0));
				exit;
			}
			$head = $_REQUEST['head'];	


			$modelh = new PurchaseOrder;
			$modelh->tanggal = $_REQUEST['head']['tanggal']. " ".date("H:i:s"); // tanggal pembuatan PO
			$modelh->user = Yii::app()->user->name; // user yang menginput
			$modelh->sumber = $_REQUEST['head']['sumber'];
			$modelh->jenis = "masuk";
			$modelh->faktur = $_REQUEST['head']['faktur'];
			$modelh->keterangan = $_REQUEST['head']['keterangan'];
			$modelh->kode_trx = $_REQUEST['head']['kode_trx'];
			$modelh->branch_id = $_REQUEST['head']['cabang'];
			$modelh->status_aktif = 1;
			if ($_REQUEST['head']['isbayar']=="1"){
				$modelh->isbayar = 1;
			}else{
				$modelh->isbayar = 0;
			}
			$modelh->tanggal_jt = $_REQUEST['head']['tanggal_jt'];
			// $modelh->branch_id = $_REQUEST['head']['cabang'];

			if ($modelh->save()){
				foreach ($nilai as $n){
					
					$i = explode("##", $n['idb']);
					$barcode = $i[0];
					// echo $barcode;
					// echo "<br>";
					// echo $i;
					// echo "<br>";
					// echo $barcode;
					// echo "<br>";
					$items = ItemsSatuan::model()->find("barcode = '$barcode' ");

					// echo $items->item_id;
					// exit;

					$model = new PurchaseOrderDetail;
					$model->kode = $items->item_id;
					


					// $model->jumlah =  $n['jml']*$n['satuan'];
					$spp = $n['supplier'];
					$model->jumlah =  $n['jml'];
					$model->satuan = $n['satuan'];
					$model->jumlah_satuan = $n['jml'];
					$model->harga = $n['harga'];
					$model->supplier_id = $spp;
					$model->head_id = $modelh->id;
					$model->letak_id = $_REQUEST['head']['letak'];
					


					// $d = new ItemsDetail;

					// // $sat = ItemsSatuan::model()->find("item_id = '$n[idb]' and id='$n[satuan]' ")->satuan;
					
					// $d->kode = $items->id;
					
					// $d->jumlah = $n['jml']*$n['satuan'];
					// $d->harga = $n['harga'];
					// $d->satuan = $n['satuan'];
					// $d->jumlah_satuan = $n['jml'];
					// $d->supplier_id = $n['supplier'];
					// $d->tanggal = $_REQUEST['head']['tanggal']. " ".date("H:i:s");


					if ($model->save()  ){
						$brg = Items::model()->findByPk($model->kode);
						$untung = $brg->persentasi / 100;
						//get harga jual
						$av = ItemsController::getAverage($model->kode,$n['satuan'], $_REQUEST['head']['cabang']);
						$one = $av *0.01; // untung 1 %
						$av = $av + $one;
						$two = $av * $untung;
						$av = $av + $two;



						//set stok
						// $brg->persentasi = $untung * 100;
						$angka = round($av);
						$angka = $angka - ($angka % 100);
						$brg->total_cost = $angka;
				

						// echo "sukses bro";
					}else{
						echo json_encode(
							array(
								"status"=>0,
								"error"=>$model->getErrors()
								// "error 2"=>$d->getErrors(),
							)
						);
						exit;
					}


				}
				// $model
				// $modelh->update();
				$transaction->commit();
				echo json_encode(array("status"=>1));
			}else{
				// echo "123";
				// print_r();
				echo json_encode(
					array(
						"status"=>0,
						"error"=>$modelh->getErrors(),
					)
				);
				exit;
			}	

			 //end try
        }catch(Exception $err){
        	echo $err;
			echo json_encode(
				array(
					"status"=>0,
					"error"=>$err,
				)
			);

			$transaction->rollback();
		}
	}

public function getModal($id){
$sql = "SELECT SUM(total) total_modal
		FROM 

		(
		SELECT s.nama nama, bmd.harga harga, SUM( jumlah ) , harga * SUM( jumlah ) AS total
		FROM barangmasuk bm
		INNER JOIN barangmasuk_detail bmd ON bm.id = bmd.head_id
		INNER JOIN supplier s ON s.id = bmd.supplier_id
		WHERE bmd.kode = $id 
		GROUP BY bmd.harga

		) AS  DATA";
		$model = Yii::app()->db->createCommand($sql)->queryRow();
		
		if ($model['total_modal']!='')
			return $model['total_modal'];
		else
			return "0";

}
// public function getStok($id){
// $sql = "SELECT SUM(jumlah) total
// 		FROM 

// 		(
// 		SELECT s.nama nama, bmd.harga harga, SUM( jumlah ) jumlah , harga * SUM( jumlah ) AS total
// 		FROM barangmasuk bm
// 		INNER JOIN barangmasuk_detail bmd ON bm.id = bmd.head_id
// 		INNER JOIN supplier s ON s.id = bmd.supplier_id
// 		WHERE bmd.kode = $id 
// 		GROUP BY bmd.harga

// 		) AS  DATA";
// 		$model = Yii::app()->db->createCommand($sql)->queryRow();
		
// 		if ($model['total']!='')
// 			return $model['total'];
// 		else
// 			return "0";

// }


public function getHargamodal($id){
	

}
		public function actionpengeluaranbaru(){
			$transaction = Yii::app()->db->beginTransaction();
			$username = Yii::app()->user->name;
			$user = Users::model()->find('username=:un',array(':un'=>$username));
			$now = date("Y-m-d");
			if ($user->level == "1"):
				$cekKasir = Setor::model()->find(" is_closed = 1 and user_id = '$user->id' and  date(tanggal) = '$now'   ");
				if ($cekKasir){
				?>
					<script type="text/javascript">
					alert("Tranksaksi kasir <?php echo $username ?> pada tanggal <?php echo date("d M Y", strtotime($cekKasir->tanggal)) ?> telah ditutup pada <?php echo date("d M Y H:i", strtotime($cekKasir->updated_at)) ?> , kasir hanya bisa melakukan register 1 kali dalam sehari",);
					window.location.href = '<?php echo Yii::app()->createUrl('site/admin') ?>'
					</script>
				<?php 
				}
			endif;





			if (isset($_REQUEST['head'])):
			$nilai = $_REQUEST['jsonObj'];
			$head = $_REQUEST['head'];	

			$modelh = new Pengeluaran;
			$modelh->tanggal = $_REQUEST['head']['tanggal']. " ".date("H:i:s");
			$modelh->user = Yii::app()->user->name;
			$modelh->branch_id = Yii::app()->user->branch();
			$modelh->jenis_pengeluaran = $_REQUEST['head']['jeniskeluar'];
			$modelh->keterangan = $_REQUEST['head']['keterangan'];
			$modelh->keterangan = $_REQUEST['head']['keterangan'];
			$modelh->total = $_REQUEST['head']['total'];
			$modelh->akun_id = $_REQUEST['head']['jeniskeluar'];
			$modelh->pembayaran_via = $_REQUEST['head']['pembayaran_via'];
			JurnalController::createExpenseTransaction($modelh); // journal posting
			if ($modelh->save()){
				$transaction->commit();	
				echo "sukses";
				// foreach ($nilai as $n){
				// 	$model = new BarangKeluarDetail;
				// 	$model->kode = $n['idb'];
				// 	$model->jumlah = $n['jml'];
				// 	$model->head_id = $modelh->id;
				// 	if ($model->save()){
				// 		$brg = Items::model()->findByPk($model->kode);
				// 		$brg->stok = $brg->stok - $model->jumlah;
				// 		$brg->update();
				// 		// echo "sukses bro";
				// 	}else{
				// 		print_r($model->getErrors());
				// 	}
				// }
			}else{
				print_r($modelh->getErrors());
			}	
			endif;
			$this->render('pengeluaranbaru');
		}
		public function actionProsesrusakbarang(){
			$transaction = Yii::app()->db->beginTransaction();

			$nilai = $_REQUEST['jsonObj'];
			$head = $_REQUEST['head'];	


			$modelh = new BarangKeluar;
			$modelh->tanggal = $_REQUEST['head']['tanggal']. " ".date("H:i:s");
			$modelh->user = Yii::app()->user->name;
			$modelh->sumber = "sumber ";
			$modelh->jenis = "keluar";
			$modelh->jenis_keluar = $_REQUEST['head']['jeniskeluar'];
			$modelh->keterangan = $_REQUEST['head']['keterangan'];
			$modelh->kode_trx = BarangKeluarController::generateKodeBKS();
			$modelh->branch_id = Yii::app()->user->branch();
			$modelh->keluar_ke = $_REQUEST['head']['cabang'];

			if (!empty($_REQUEST['head']['cabang'])){
				$status_aktif = 0;
			}else{
				$status_aktif = 1;
			}
			// keluar dan masukan ke tujuan 
			$bms = new BarangMasuk;
			$bms->tanggal = $_REQUEST['head']['tanggal']. " ".date("H:i:s");
			$bms->user = Yii::app()->user->name;
			$bms->sumber =  Yii::app()->user->branch();
			$bms->jenis = "masuk";
			$bms->faktur = $_REQUEST['head']['faktur'];
			$bms->keterangan = $_REQUEST['head']['keterangan'];
			$bms->branch_id = $_REQUEST['head']['cabang'];
			$bms->kode_trx = BarangMasukController::generateKodeBMS();
			$bms->status_aktif = $status_aktif;


			

			if ($modelh->save()){
				if ($bms->save()){
					// if ($nilai>)
					foreach ($nilai as $n){
						// $nilai = explode("-", $n['idb']);
						$nilai = explode("##", $n['idb']);
						// var_dump($nilai);
						$barcode = $nilai[0];
						$satuan_id = $nilai[1];

						$itemsSatuan  = ItemsSatuan::model()->find("barcode='$barcode' ");
						// $item = Items::model()->find("barcode = '$itemsSatuan->barcode' ");
						
						// $id = $item->id;
						$id = $itemsSatuan->item_id;
						// var_dump($id);
						// exit;




						// simpan detai barang keluar
						$model = new BarangKeluarDetail;
						$model->kode = $id;
						$model->jumlah = $n['jml'];
						
						$harga_mdl = ItemsController::getAverage($id,$satuan_id,Yii::app()->user->branch());
						if ($harga_mdl<=0){
							$satuanharga = ItemsSatuan::model()->find("item_id = '$id' and id='$satuan_id' ")->harga_beli;

							if ($satuanharga>0){
								$harga_mdl = $satuanharga;
							}else{
								$harga_mdl = 0;
							}
						}	

						$model->harga = $harga_mdl;

						$model->head_id = $modelh->id;
						$model->satuan = $satuan_id;
						if (!$model->save()){
							// print_r($model->getErrors());
							echo json_encode(array("success"=>false,"error"=>$model->getErrors()));
							exit;
						}

						//simpan detail barang masuk

						$dm = new BarangMasukDetail;
						$dm->kode = $id;
						$dm->jumlah = $n['jml'];
						$dm->satuan = $satuan_id;
						$dm->harga = $harga_mdl;
						$dm->head_id = $bms->id;
						$dm->status_pengiriman = 0;

						if (!$dm->save()){
							// print_r($dm->getErrors());
							echo json_encode(array("success"=>false,"error"=>$dm->getErrors()));
							exit;
						}



					}
				}
				$transaction->commit();	
				echo json_encode(array("success"=>true));
			}else{
				// print_r();
				echo json_encode(array("success"=>false,"error"=>$modelh->getErrors()));
			}	
	}
	public function actionPurchase_order(){

		$this->render('purchase_order');
	}

	public function actionBarangMasuk(){

		$this->render('barangmasuk');
	}
	public function actionBarangrusak(){

		$this->render('barangrusak');
	}
	public function actionBayarHutang(){
		// echo "123";
		$total = $_REQUEST['bayar'];
		$peminjaman_id = $_REQUEST['peminjaman_id'];
		$tanggal = $_REQUEST['tanggal'];
		if ($total != '' &&  $tanggal!="" && $peminjaman_id!=""){
			$pm  = new PeminjamanBayar;
			$pm->head_id = $peminjaman_id;
			$pm->tanggal = $tanggal;
			$pm->total = $total;
			if ($pm->save())
				$this->redirect(array('items/laporanpinjam'));
			else
				echo "gagal";

		}
		// echo $total ;
		// echo $tanggal ;
	}
	public function actionPinjam(){
		if (isset($_REQUEST['head'])){
			$nilai = $_REQUEST['jsonObj'];
			$head = $_REQUEST['head'];	


			$modelh = new Peminjaman;
			$modelh->tanggal_pinjam = $_REQUEST['head']['tanggal_pinjam']. " ".date("H:i:s");
			$modelh->tanggal_kembali = $_REQUEST['head']['tanggal_kembali']. " ".date("H:i:s");
			$modelh->deposit = $_REQUEST['head']['deposit'];
			$modelh->nama = $_REQUEST['head']['nama'];
			$modelh->user = Yii::app()->user->name;
			$modelh->keterangan = $_REQUEST['head']['keterangan'];
			
			
			$status = false;
			if ($modelh->save()){
				$pm  = new PeminjamanBayar;
				$pm->head_id = $modelh->id;
				$pm->tanggal = date("Y-m-d H:i:s");
				$pm->total = $_REQUEST['head']['deposit'];

				if ($pm->save()){
					foreach ($nilai as $n){
						$model = new PeminjamanDetail;
						$model->item_id = $n['idb'];
						$model->qty = $n['jml'];
						$model->head_id = $modelh->id;
						if ($model->save()){
							$brg = Items::model()->findByPk($model->item_id);
							$brg->stok = $brg->stok - $model->qty;
							if ($brg->update())
								$status= true;
							else
								$status= false;

							// echo "sukses bro";
						}else{
							print_r($model->getErrors());
						}
					}
				}else{
					print_r($pm->getErrors());
				}
				echo $status;
			}else{
				print_r($modelh->getErrors());
			}	
		}else{
			$this->render('pinjam');
		}
	}
	// public function actionBarangMasuk(){

	// 	$this->render('barangmasuk');
	// }

	public function actionBarcode(){
		// Yii::import('application.extensions.barcode.*');
		// include('Barcode.php');
		$this->renderPartial('barcode');

	}
	public function actionDetailpaket(){
		$id = $_REQUEST['id'];
		$filtersForm=new FiltersForm;
		if (isset($_GET['FiltersForm']))
		$filtersForm->filters=$_GET['FiltersForm'];
		$rawData = Yii::app()->db->createCommand()
		->select('p.id_paket, i.item_name, i.unit_price')
		->from('paket p, items i')
		->where("i.id = p.id_item and id_paket = $id")
		// ->group("p.id_paket")
		->queryAll();
		
		$filteredData=$filtersForm->filter($rawData);
		$dataProvider=new CArrayDataProvider($filteredData);
		$this->render('detailpaket', array(
			'filtersForm' => $filtersForm,
			'model' => $dataProvider,
		));
	}
	 
	
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
        
        public function actionGetname($id){
	        $model = Items::model()->findByPk($id);
	        if  (count($model)>0){
	        	$query_get_satuan = "select * from items_satuan where item_id = '$id' ";
				$model2 = Yii::app()->db->createCommand($query_get_satuan)->queryAll();
				if (count($model2)<1){
					$satuan = new ItemsSatuan;
					$satuan->item_id = $id;
					$satuan->nama_satuan = "pcs";
					$satuan->satuan = 1;
					$satuan->is_default = 1;
					$satuan->save();

					$query_get_satuan = "select * from items_satuan where item_id = '$id' ";
					$model2 = Yii::app()->db->createCommand($query_get_satuan)->queryAll();

				}

		        $data = array(
			        		"nama"=>$model->item_name,
			        		"harga_modal"=>$model->modal,
			        		"harga_jual"=>$model->total_cost,
			        		"ukuran"=>$model->ukuran,
			        		"panjang"=>$model->panjang,
			        		"ketebalan"=>$model->ketebalan,
			        		"satuan"=>$model2,
	        			);
		        echo json_encode($data);
	        }else{
	        	echo json_encode(array("nama"=>"null"));
	        }
        }
        public function actionGetlistprice($id,$satuan_id){
        	// $masterPrice = ItemsSatuanMaster::model()->find("")
            // $m = ItemsSatuan::model()->find(" id = '$satuan_id' ");
            // echo "<option value='$m->total_cost'>Normal : $m->total_cost</option>";
            // echo "<option value='$m->price_distributor'>Distributor : $m->price_distributor</option>";
            // echo "<option value='$m->price_reseller'>Reseller : $m->price_reseller</option>";


        }
        public function actionCheck($id,$name="")
        {
        	if (isset($name) && !empty($name)){
        		// $name  = $name;
				$customer = Customer::model()->find("nama = '$name' ");
				if ($customer){
					$nilai = CustomerType::model()->find(" id = '$customer->customer_type' ")->diskon;
				}else{
					$nilai = 0;
				}

        	}else{
        		$nilai = 0;
        	}
        	// var_dump($nilai);
        	// exit;

        	$id = explode("##", $id);
        	$satuan_id = $id[1];
        	$id  = $id[0];

        	// var_dump($id);
        	// exit;

            $ms = ItemsSatuan::model()->find("barcode = '$id'" );
            $id =  $ms->item_id;

            // echo count($ms);
            // exit;	

            // $id = $ms->item_id;
            // var_dump($id);
            // exit;
            // $m = Items::model()->findByPk($m->id);
            // var_dump($m->id);
            if (!empty($ms->id)){
            	$where2 = " and iis.id = '$ms->id' ";
            }
            $query = "SELECT
						iis.nama_satuan nama_satuan,i.*,
						iis.harga_beli harga_beli,
						iis.id nama_satuan_id,
						iis.id item_satuan_id,
						c.category nama_kategori,
						m.nama nama_sub_kategori,
						'0' as service_angka,
						'0' as pajak_angka,
						is_pulsa as is_pulsa,
						-- iisp.price as harga_jual,
						#iis.harga as harga_jual,
						iis.harga as harga_jual,
						ispm.label_name as price_type,
						iis.barcode as barcode_new

					FROM
						items i
					LEFT JOIN items_satuan iis ON iis.item_id = i.id
					LEFT JOIN items_satuan_price iisp ON iisp.item_satuan_id = iis.id 
					LEFT JOIN items_satuan_price_master ispm on ispm.name = iisp.price_type

					LEFT JOIN categories as c on i.category_id = c.id 
					LEFT JOIN motif as m on m.id  = i.motif
					WHERE
						i.id = '$id' $where2
						
					group by iis.id";
					// echo $query;
					// exit;
			$m = Yii::app()->db->createCommand($query)->queryRow();


			$queryDetail = "SELECT
						iis.*
					FROM
						items i
					LEFT JOIN items_satuan iis ON iis.item_id = i.id
					WHERE
						i.id = '$m[id]'
					group by iis.id";
					// echo $queryDetail;
			$mDetail = Yii::app()->db->createCommand($queryDetail)->queryAll();

			$priceDetail = "SELECT
						isp.id, isp.item_satuan_id, isp.price, isp.default, ispm.label_name as price_type
					FROM
						items_satuan_price isp inner join  items_satuan_price_master ispm on ispm.name = isp.price_type
					WHERE
						isp.item_satuan_id = '$m[item_satuan_id]'
					group by isp.id";
					// echo $queryDetail;
			$priceDetail = Yii::app()->db->createCommand($priceDetail)->queryAll();


			$branch_id = Yii::app()->user->branch();

			// echo 
			$stoknow = ItemsController::getStok($m['id'],$m['nama_satuan_id'],$branch_id);

			$itemsSatuan = ItemsSatuan::model()->findByPk($m['nama_satuan_id']);
			$stoknow = $stoknow / $itemsSatuan->satuan;			


			// var_dump($stoknow);
			// exit;
            $model2 = array();
             if (count($m)>0){
	        	$model2['stok']  = $stoknow ;
	        	$model2['is_pulsa']  = $m['is_pulsa'] ;
	        	$model2['service_angka']  = $m['service_angka'] ;
	        	$model2['pajak_angka']  = $m['pajak_angka'] ;
	        	$model2['nama_kategori']  = $m['nama_kategori'] ;
	        	$model2['nama_sub_kategori']  = $m['nama_sub_kategori'] ;
	        	$model2['price_type']  =  (empty($m['price_type']) ? "-" : $m['price_type']  ) ;
	        	$model2['kode']  = $m['barcode_new'] ;
	        	$model2['id']  = $m['id'] ;
	        	$model2['lokasi']  = $m['lokasi'] ;
	        	$model2['item_name']  = $m['item_name'] ;
	        	$model2['nama_satuan_id']  = $m['nama_satuan_id'] ;
	        	$model2['item_satuan_id']  = $m['item_satuan_id'] ;
	        	$model2['nama_satuan']  = $m['nama_satuan'] ;
	        	$model2['item_number']  = $m['item_number'] ;
	        	$model2['unit_price']  = $m['unit_price'] ;
	        	$model2['tax_percent']  = $m['tax_percent'] ;
	        	$model2['total_cost']  = $m['harga_jual'] ;
	        	$model2['harga_beli']  = $m['harga_beli'] ;
	        	$model2['discount']  = $m['discount'];
	        	$model2['discount_customer']  = $nilai;
	        	$model2['price_distributor']  = $m['price_distributor']  ;
	        	$model2['price_reseller']  = $m['price_reseller'] ; 
	        	$model2['ukuran']  = $m['ukuran']  ;
	        	$model2['panjang']  = $m['panjang']  ;
	        	$model2['ketebalan']  = $m['ketebalan']  ;
	        	$model2['is_paket']  = 0;
	        	$model2['satuan_detail'] = $mDetail;
	        	$model2['price_detail'] = $priceDetail;
            }else{
				$p = Paket::model()->findByPk($id);
				$model2['service_angka']  = $m['service_angka'] ;
	        	$model2['pajak_angka']  = $m['pajak_angka'] ;
				$model2['nama_kategori']  = $m['nama_kategori'] ;
	        	$model2['nama_sub_kategori']  = $m['nama_sub_kategori'] ;
				$model2['price_type']  = $p['price_type'] ;
				$model2['kode']  = $p['id_paket'] ;
				$model2['id']  = $p['id_paket'];
				$model2['lokasi']  = "-";
				$model2['item_name']  = $p['nama_paket']; 
				$model2['nama_satuan']  = $p['nama_satuan']; 
				$model2['nama_satuan_id']  = 0; 
				$model2['item_number']  = "-";
				$model2['unit_price']  = $p['harga'] ;
				$model2['tax_percent']  = "-";
				$model2['total_cost']  = $p['harga'];
				$model2['discount']  = 0;
				$model2['price_distributor']  = 0;
				$model2['price_reseller']  = 0;  
				$model2['ukuran']  = 0;
				$model2['panjang']  = 0;  
				$model2['ketebalan']  = 0;  
	        	$model2['is_paket']  = 1; 
	        	$model2['satuan_detail'] =0; 
            }

            // foreach ($model as $m) {
    //         if (count($m)>0){
	   //      	$model2['kode']  = $m->barcode; 
	   //      	$model2['id']  = $m->id; 
	   //      	$model2['lokasi']  = $m->lokasi; 
	   //      	$model2['item_name']  = $m->item_name; 
	   //      	$model2['item_number']  = $m->item_number; 
	   //      	$model2['unit_price']  = $m->unit_price; 
	   //      	$model2['tax_percent']  = $m->tax_percent; 
	   //      	$model2['total_cost']  = $m->total_cost; 
	   //      	$model2['discount']  = $m->discount;
	   //      	$model2['price_distributor']  = $m->price_distributor;  
	   //      	$model2['price_reseller']  = $m->price_reseller;  
	   //      	$model2['ukuran']  = $m->ukuran;  
	   //      	$model2['panjang']  = $m->panjang;  
	   //      	$model2['ketebalan']  = $m->ketebalan;  
	   //      	$model2['is_paket']  = 0;  
    //         }else{
				// $p = Paket::model()->findByPk($id);
				// $model2['kode']  = $p->id_paket; 
				// $model2['id']  = $p->id_paket; 
				// $model2['lokasi']  = "-"; 
				// $model2['item_name']  = $p->nama_paket; 
				// $model2['item_number']  = "-"; 
				// $model2['unit_price']  = $p->harga; 
				// $model2['tax_percent']  = "-"; 
				// $model2['total_cost']  = $p->harga ;
				// $model2['discount']  = 0;
				// $model2['price_distributor']  = 0;  
				// $model2['price_reseller']  = 0;  
				// $model2['ukuran']  = 0;  
				// $model2['panjang']  = 0;  
				// $model2['ketebalan']  = 0;  
	   //      	$model2['is_paket']  = 1;  
    //         }
            echo json_encode($model2);
        }	
            // }
        public function actionData_all_items(){
			$data = Items::model()->queryDataItems("BAHAN");
			$command=Yii::app()->db->createCommand($data)->queryAll();
			// echo json_encode($command);
			$branch_id = Yii::app()->user->branch();
			// echo "<pre>";
			// print_r($command);
			// echo "</pre>";

			// var_dump(expression)
			$arrayAll = array();
			foreach ($command as $key => $m) {
				$value = array();		
				$stoknow = ItemsController::getStok($m['id'],$m['nama_satuan_id'],$branch_id);
	        	$value['stok']  = $stoknow ;
				$value['service_angka']  = $m['service_angka'] ;
				$value['pajak_angka']  = $m['pajak_angka'] ;
				$value['nama_kategori']  = $m['nama_kategori'] ;
				$value['nama_sub_kategori']  = $m['nama_sub_kategori'] ;
				$value['price_type']  = $m['price_type'] ;
				$value['kode']  = $m['barcode'] ;
				$value['id']  = $m['id'] ;
				$value['lokasi']  = $m['lokasi'] ;
				$value['item_name']  = $m['nama'] ;
				$value['nama_satuan_id']  = $m['nama_satuan_id'] ;
				$value['nama_satuan']  = $m['nama_satuan'] ;
				$value['item_number']  = $m['item_number'] ;
				$value['unit_price']  = $m['unit_price'] ;
				$value['tax_percent']  = $m['tax_percent'] ;
				$value['total_cost']  = $m['harga_jual'] ;
				$value['harga_beli']  = $m['harga_beli'] ;
				$value['discount']  = $m['discount'];
				$value['price_distributor']  = $m['price_distributor']  ;
				$value['price_reseller']  = $m['price_reseller'] ; 
				$value['ukuran']  = $m['ukuran']  ;
				$value['panjang']  = $m['panjang']  ;
				$value['ketebalan']  = $m['ketebalan']  ;
				$value['is_paket']  = 0;
				$value['satuan_detail'] = 0;
				array_push($arrayAll, $value);
			}
			echo json_encode($arrayAll);
			// echo "<pre>";
			// print_r($arrayAll);
			// echo "</pre>";
	}

	 public function actionGetdataPO($poid){
			$data = Items::model()->queryDataItemsPO($poid);
			$command=Yii::app()->db->createCommand($data)->queryAll();
			// echo json_encode($command);
			$branch_id = Yii::app()->user->branch();
			// echo "<pre>";
			// print_r($command);
			// echo "</pre>";

			// var_dump(expression)
			$arrayAll = array();
			foreach ($command as $key => $m) {
				$value = array();		
				$stoknow = ItemsController::getStok($m['id'],$m['nama_satuan_id'],$branch_id);
	        	$value['stok']  = $stoknow ;
	        	$value['sumber']  = $m['sumber'] ;
	        	$value['jumlah_po']  = $m['jumlah_po'] ;
				$value['service_angka']  = $m['service_angka'] ;
				$value['pajak_angka']  = $m['pajak_angka'] ;
				$value['nama_kategori']  = $m['nama_kategori'] ;
				$value['nama_sub_kategori']  = $m['nama_sub_kategori'] ;
				$value['price_type']  = $m['price_type'] ;
				$value['kode']  = $m['barcode'] ;
				$value['id']  = $m['id'] ;
				$value['lokasi']  = $m['lokasi'] ;
				$value['item_name']  = $m['nama'] ;
				$value['nama_satuan_id']  = $m['nama_satuan_id'] ;
				$value['nama_satuan']  = $m['nama_satuan'] ;
				$value['item_number']  = $m['item_number'] ;
				$value['unit_price']  = $m['unit_price'] ;
				$value['tax_percent']  = $m['tax_percent'] ;
				$value['total_cost']  = $m['harga_jual'] ;
				$value['harga_beli']  = $m['harga_beli'] ;
				$value['discount']  = $m['discount'];
				$value['price_distributor']  = $m['price_distributor']  ;
				$value['price_reseller']  = $m['price_reseller'] ; 
				$value['ukuran']  = $m['ukuran']  ;
				$value['panjang']  = $m['panjang']  ;
				$value['ketebalan']  = $m['ketebalan']  ;
				$value['is_paket']  = 0;
				$value['satuan_detail'] = 0;
				array_push($arrayAll, $value);
			}
			echo json_encode($arrayAll);
			// print_r($arrayAll);
	}

            // echo json_encode($model->getAttributes(array('lokasi','id','item_name','item_number','unit_price','tax_percent','total_cost','discount')));
            
        public function actionCheckBarcode($id)
        {
            $m = ItemsSatuan::model()->find("barcode = '$id' ");
        if (count($m)!=0){
	            // echo json_encode($model->getAttributes(array('lokasi','id','item_name','item_number','unit_price','tax_percent','total_cost','discount')));
	         // $m = Items::model()->findByPk($id);
            $model2 = array();

            // foreach ($model as $m) {
        	$model2['id']  = $m->id; 
        	$model2['lokasi']  = $m->lokasi; 
        	$model2['item_name']  = $m->item_name; 
        	$model2['item_number']  = $m->item_number; 
        	// $model2['unit_price']  = $m->unit_price; 
        	$model2['item_tax']  = $m->item_tax; 
        	$model2['tax_percent']  = $m->tax_percent; 
        	$model2['total_cost']  = $m->total_cost; 
        	$model2['discount']  = $m->discount; 
        	$model2['price_distributor']  = $m->price_distributor; 
        	$model2['price_reseller']  = $m->price_reseller; 
            	
            // }
            echo json_encode($model2);

	        }else{
	        	echo "error";
	        }
        }


	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	  // public static function generateBarcode() {
   //      $query = "SELECT
			// 		CONCAT(
			// 			'35U',LPAD( MAX( SUBSTR(barcode, 4, 10))+1,10,'0')
			// 		) AS urutan
			// 	FROM
			// 		items
   //               ";
   //      $model = Yii::app()->db->createCommand($query)->queryRow();
   //      return $model['urutan'];
   //                  // echo "$query";
   //                  // exit;

   //   }
       public static function generateBarcode() {
       	$store_id_real = Yii::app()->user->store_id();
       	$store_id = str_pad($store_id_real,3,"0",STR_PAD_LEFT);
        $query = "SELECT
				IFNULL(
					CONCAT(
						'$store_id',
						LPAD(
							MAX(SUBSTR(barcode, 4, 10)) + 1,
							10,
							'0'
						)
					),
					'{$store_id}0000000001'
				) AS urutan
			FROM
				items_barcode where store_id = '{$store_id_real}'
                 ";
        $model = Yii::app()->db->createCommand($query)->queryRow();
        return $model['urutan'];
   }
   public function actionGenerateBarcodeAction(){
   		echo self::generateBarcode();
   }

    public function actionImportFromItems(){
    	// echo Yii::app()->user->store_id();
		// exit;
    	$model = Items::model()->findAll();
    	foreach ($model as $key => $value) {
    		if ($value->barcode == "" || $value->barcode === null){
				$bcd = self::generateBarcode();
				$item = Items::model()->findByPk($value->id);
				$item->barcode = $bcd;
				$item->update();

				$status = "otomatis";
			}else{
				$bcd = $value->barcode;
				$status = "manual";
			}

    		$ItemsSatuan = ItemsSatuan::model()->find("item_id = '$value->id' ");
			if ($ItemsSatuan){
				// lanjut
			}else{
				$ItemsSatuan = new ItemsSatuan;
				$ItemsSatuan->item_id = $value->id;
				$ItemsSatuan->nama_satuan = "PCS";
				$ItemsSatuan->satuan = "1";
				$ItemsSatuan->jumlah = "1";
				$ItemsSatuan->is_default = "1";
				$ItemsSatuan->harga = $value->unit_price;
				$ItemsSatuan->harga_beli = $value->total_cost;
				$ItemsSatuan->barcode = $bcd;
				$ItemsSatuan->urutan = "1";
				$ItemsSatuan->stok_minimum = "0";
				$ItemsSatuan->save();
				
				$ItemsSatuan = ItemsSatuan::model()->find("item_id = '$value->id' ");

			}

			if ($status == "otomatis"){
			// 	$ItemsSatuan->barcode = $bcd;
			// 	$ItemsSatuan->update();
				
				$ib = new ItemsBarcode;
				$ib->barcode = $bcd;
				$ib->store_id = Yii::app()->user->store_id();
				$ib->save();
			} 



    		
    	}
   		// echo self::generateBarcode();
   }

   public function actionInsertStok(){
    	$model = Items::model()->findAll();
		$BarangMasuk = new BarangMasuk;
		$BarangMasuk->tanggal = date("Y-m-d");
		$BarangMasuk->sumber = "stok awal";
		$BarangMasuk->keterangan = "stok awal";
		$BarangMasuk->user = "1";
		$BarangMasuk->branch_id = "30";
		if ($BarangMasuk->save(false)){
			foreach ($model as $key => $value) {
				$BarangMasukDetail = new BarangMasukDetail;
				$BarangMasukDetail->kode = $value->id;
				$BarangMasukDetail->jumlah = $value->stok;
				$BarangMasukDetail->satuan = ItemsSatuan::model()->find("item_id = '$value->id' and is_default='1' ")->id;
				$BarangMasukDetail->head_id = $BarangMasuk->id;
				$BarangMasukDetail->harga = $value->unit_price;
				$BarangMasukDetail->status_pengiriman = 1; 
				$BarangMasukDetail->save(false);
			}
		}
		echo "Sukses bos";
   }

	public function actionCreate()
	{
		$transaction = Yii::app()->db->beginTransaction();
		$model=new Items;
		$satuan = new ItemsSatuan;

		if(isset($_POST['Items']))
		{
			// echo "<pre>";
			// print_r();
			// echo "</pre>";
			// exit;


			$model->attributes = $_REQUEST['Items'];
			$model->item_name = strtoupper($_REQUEST['Items']['item_name']);
			$model->modal = $_REQUEST['Items']['total_cost'];
			$model->motif = $_REQUEST['Items']['motif'];
			$model->unit_price = $_REQUEST['Items']['unit_price'];
			$model->total_cost = $_REQUEST['Items']['total_cost'];
			$model->kode_outlet = 1;
			$model->item_number = 1;
			$model->store_id = Yii::app()->user->store_id();
			$model->lokasi = $_POST['Items']['lokasi'];
			$model->letak_id = $_POST['Items']['letak_id'];
			$model->provider_id = $_POST['Items']['provider_id'];
			if($model->save()){


				// jika generate
				if (isset($_REQUEST['is_generate'])){
					if ($_REQUEST['is_generate']=="on"){
						$ib = new ItemsBarcode;
						$ib->barcode = $_REQUEST['Items']['barcode'];
						$ib->store_id = Yii::app()->user->store_id();
						$ib->save();
					}
				}


					// buat satuan baru
					$satuan->item_id = $model->id;
					// $satuan->nama_satuan = ItemsSatuanMaster::model()->findByPk($_REQUEST['Items']['satuan_id'])->nama_satuan;
					$satuan->nama_satuan = ItemsSatuanMaster::model()->findByPk($_REQUEST['Items']['satuan_id'])->nama_satuan;
					$satuan->harga =  $_REQUEST['Items']['unit_price'];
					$satuan->harga_beli =  $_REQUEST['Items']['total_cost'];
					$satuan->satuan = 1;
					$satuan->letak_id = $_REQUEST['Items']['letak_id'] ;
					$satuan->is_default = 1;
					$satuan->letak_id = $_REQUEST['Items']['letak_id'];
					$satuan->barcode = $_REQUEST['Items']['barcode'];
					$satuan->stok_minimum = $_REQUEST['Items']['stok_minimum'];


					 $criteria = new CDbCriteria;

					$criteria->select = 't.*,i.hapus ';

					$criteria->join = ' INNER JOIN `items` AS `i` ON i.id = t.item_id';

					$criteria->addCondition("t.barcode = '".$satuan->barcode."' and hapus = 0");

					$cekUnique    =    ItemsSatuan::model()->findAll($criteria);



					// $cekUnique = ItemsSatuan::model()->findAll("barcode = '".$satuan->barcode."' and hapus = 0 ");
					// $cekUnique2 = Items::findByPk();
					if (count($cekUnique)){
							echo "barcode sudah digunakan <br>";
							echo "<a href='' onclick='window.history.back()'>Klik disini untuk Kembali</a>";
						exit;
					}


					if ($satuan->save()){


						// bkin stok baru awal
						if (isset($_REQUEST['Items']['stok']) && $_REQUEST['Items']['stok'] > 0 ){
							$modelh = new BarangMasuk;
							$modelh->tanggal = date("Y-m-d H:i:s");
							$modelh->user = Yii::app()->user->name;
							$modelh->sumber = "stok awal";
							$modelh->jenis = "masuk";
							$modelh->faktur = BarangMasukController::generateKodeBMS();
							$modelh->keterangan = "stok awal";
							$modelh->kode_trx = "0000000000";
							$modelh->branch_id = Yii::app()->user->branch();
							$modelh->status_aktif = 1;
							$modelh->subtotal = 0;
							$modelh->diskon = 0;
							$modelh->grand = 0;
							$modelh->bayar = 0;
							$modelh->kembali = 0;
							if ($modelh->save()){
								$BMD = new BarangMasukDetail;
								$BMD->kode = $model->id;
								$BMD->jumlah = $_REQUEST['Items']['stok'];
								$BMD->satuan = $satuan->id;
								$BMD->jumlah_satuan = $_REQUEST['Items']['stok'];
								$BMD->harga =$_REQUEST['Items']['total_cost'];
								$BMD->supplier_id = 0;
								$BMD->head_id = $modelh->id;
								$BMD->letak_id = 0;
								$BMD->save();
							}else{
								print_r($modelh->getErrors);
								exit; 
							}
						}
						// end bikin stok baru 


						// echo "masuk 2";
						// exit;
						//save to itemprice
						$price = new ItemsSatuanPrice;
						$price->item_satuan_id = $satuan->id;
						$price->price_type = "HARGA 1";
						$price->price = $_REQUEST['Items']['total_cost'];
						$price->default = 1;

						$save = $price->save();
						if (!$save){
							print_r($price->getErrors);
							exit;
						}


						$price = new ItemsSatuanPrice;
						$price->item_satuan_id = $satuan->id;
						$price->price_type = "HARGA 2";
						$price->price = 0;
						$price->default = 0;

						$save = $price->save();

						$price = new ItemsSatuanPrice;
						$price->item_satuan_id = $satuan->id;
						$price->price_type = "HARGA 3";
						$price->price = 0;
						$price->default = 0;

						$save = $price->save();
						
								// $this->redirect(array('admin','id'=>$model->id));
						// if ($save){// jika berhasil simpan maka
							$transaction->commit();

							if (! isset($_POST['isajax'])){ $this->redirect(array('view',"id"=>$model->id));}
							else{ echo "sukses"; exit;}
						// }else{// jika gagal save maka
						// 	if (isset($_POST['isajax'])){
						// 		foreach ($model->getErrors() as $key => $value) {
						// 			foreach ($value as $z => $b) {echo " $b \n";}
						// 		}
						// 		exit;
						// 	}else{
						// 		echo "wkwkw";
						// 	}
						// }
					}else{  // jika gagal save satuan maka


						if (! isset($_POST['isajax'])){ 
								echo "barcode sudah digunakan<br>";
								echo "<a onclick='window.history.back()'>Klik disini untuk Kembali</a>";
								// echo "<pre>";
								// print_r($satuan->getErrors());
								// print_r($model->getErrors());
								// echo "</pre>";
						exit;

						}else{ 
							$item_id =  ItemsSatuan::model()->find("barcode = '".$_REQUEST['Items']['barcode']."'")->item_id;
							$ItemName = Items::model()->findByPk($item_id)->item_name;
							echo "Barcode ".$_REQUEST['Items']['barcode']."  sudah digunakan untuk ".$ItemName." !"; exit;
						
						}
					
						// exit;
					}

				}else{ // if set items set false
						if (isset($_POST['isajax'])){
							foreach ($model->getErrors() as $key => $value) {
							// echo $value."<br>";
								foreach ($value as $z => $b) {
									echo " $b \n";
								}
							}
							exit;
						}else{
							// echo "<pre>";
							// print_r($model->getErrors());
							// echo "</pre>";
						}
				}
			}
			else{

				// echo "<pre>";
				// print_r($model->getErrors());
				// echo "</pre>";
				// exit;
			}
			
			// else
				// print_r($model->getErrors());
		
			$transaction->commit();
		$this->render('create',array(
			'model'=>$model,
			'datasatuan'=>$satuan
		));
	}
	public static function GetKodePaket(){
		$sql = "select max(id_paket) as kode from paket  ";
		$model = Yii::app()->db->createCommand($sql)->queryRow();
		// print_r($model);
		if (count($model)){
			$kode = $model['kode'];
			$kode = floatval( substr($kode, 4,11) ) + 1 ;
			$kode = str_pad($kode, 11,"0",STR_PAD_LEFT);
			$kode = "P35U".$kode; 
		}else{
			$kode = "P35U00000000001";
		}
		// echo $kode;
		return $kode;

	}
	public function actionCreatepaket()
	{
	

		if ($_REQUEST["status"]=="ubah"){
			$paket = Paket::model()->findAll("id_paket=:i",array(":i"=>$_REQUEST["id"]));
			$data_model = Paket::model()->findByPk($_REQUEST["id"]);
			$namapaket = $data_model->nama_paket;
			$hargapaket = $data_model->harga;
		}

		if(isset($_REQUEST['kode']))
		{
			$data = $_REQUEST['kode'];
			$menu =  $_REQUEST['nama'];
			$total =  $_REQUEST['total'];
			$status =  $_REQUEST['status'];
			$kode_paket =  $_REQUEST['kode_paket'];
			

			$mn_new = preg_split('/,/', $menu, -1, PREG_SPLIT_NO_EMPTY);
			// print_r($mn_new);
			 if ($status=="ubah"){
				// $nama =  $_REQUEST['nama'];
                $item = Paket::model()->find("id_paket = '$kode_paket' ");

			 }else{
				// $nama =  $_REQUEST['nama'];
				$item = new Paket;
				$kode_paket = $this->GetKodePaket();
				$item->id_paket = $kode_paket;
			 }
			 // echo count($item);
            // echo $kode_paket;
			$item->nama_paket = $menu;
			// echo $menu;
			// exit;
			$item->harga = $total;
			$item->keterangan = "-";
                // echo($kode_paket);

			if ($item->save()){
				PaketDetail::model()->deleteAllByAttributes(array('paket_id' => $kode_paket));
				$status_sukses = array();
				foreach($_REQUEST['kode']as $d){
					$pd = new PaketDetail;
					$pd->paket_id = $kode_paket;
					$pd->item_id = $d['kode'];
					$pd->item_qty = $d['jumlah'];
					$pd->keterangan = "-";
					$pd->item_price = $d['harga'];
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
			}else{
				echo json_encode(array("sukses"=>false,"err"=>$item->getErrors()) );
			}
		}else{
			$this->render('createpaket',array(
				'namapaket'=>$namapaket,
				'hargapaket'=>$hargapaket,
				'array'=>$array,
				'from'=>"admin"	
			));
		}
	}
        
        public function actionItemnumber(){
		$id = $_GET["id"];
		$id2 = $_GET["id2"];
		//select dari table items where category_id = id
		$lastID = Yii::app()->db->createCommand()
		->select('MAX(item_number)')
		->from('Items')
		->where('category_id = '.$id)
		->queryScalar();

		$number = intval(substr($lastID,-5));
		$newNumber = $number+1;
		
		switch(strlen($newNumber)){
			case 1:$newNumber = $id2.$id."0000".$newNumber;break;
			case 2:$newNumber = $id2.$id."000".$newNumber;break;
			case 3:$newNumber = $id2.$id."00".$newNumber;break;
			case 4:$newNumber = $id2.$id."0".$newNumber;break;
			case 5:$newNumber = $id2.$id.$newNumber;break;
		}
		
		echo $newNumber;
		
		
		// echo CHtml::dropDownList('categories', $category, $data, array('empty' => '(Select a category', 'onChange'=>''));
	}
	
	public function actionUnitprice(){
		$id = $_GET["id"];
		//select dari table items where category_id = id
		$model = Items::model()->findByPk($id);
		// echo CHtml::textField('unitPrice',$model->unit_price);
		echo $model->unit_price;
	}
	
	public function actionCategory(){
		$model = Items::model()->with('categories')->findAll();
		
		$dataProvider=new CActiveDataProvider('Items', array(
			'criteria'=>array(
				// 'condition'=>'status=1',
				// 'order'=>'create_time DESC',
				'with'=>array('categories'),
			),
			'pagination'=>array(
				'pageSize'=>20,
			),
		));
		$this->render('category',array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		// echo round(ItemsController::GetAverage($id));
		$branch_id = Yii::app()->user->branch();
  	
		$model=$this->loadModel($id);
		$satuan=ItemsSatuan::model()->find("item_id = '$id' ");

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Items']))
		{
			foreach ($_POST['Items'] as $key => $value) {
				$_POST['Items'][$key] = strtoupper($value);
			}
			$model->attributes=$_POST['Items'];
			$model->persentasi = $_POST['Items']['persentasi'];
			$model->letak_id = $_POST['Items']['letak_id'];
			$model->motif = $_POST['Items']['motif'];
			$model->barcode = $_POST['Items']['barcode'];
			// set stok
		

			//get satuan utana
			$stok = ItemsController::getStok($id,$ItemsSatuan_id,$branch_id); // current stok
			$ItemsSatuan_id = ItemsSatuan::model()->find("item_id = '$id' and is_default = 1"); 
			$ItemsSatuan_id->harga = $_POST['Items']['unit_price'];
			$ItemsSatuan_id->harga_beli = $_POST['Items']['total_cost'];
			$ItemsSatuan_id->save();

			if (isset($_REQUEST['Items']['stok'])){
				$average  = $this->getAverage($id,$ItemsSatuan_id->id,$branch_id);
				$this->actionSetstok($stok,$model->stok,$id,$ItemsSatuan_id,$_POST['Items']['total_cost']);
				$model->stok = $_POST['Items']['stok'];
			}
			
			// $model->stok = $_POST['Items']['stok'];


			if($model->save())
				$this->redirect(array('Items/view&id='.$id));
		}

		$this->render('update',array(
			'model'=>$model,
			'satuan'=>$satuan,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionHapus($id)
	{
		$model = $this->loadModel($id)->delete();
		// $model->hapus = 1;
		// $model->update();
		$this->redirect(array('items/admin'));	
		

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		// if(!isset($_GET['ajax']))
		// 	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	public function actionDeletepaket($id)
	{
		// Paket::model()->($id)->delete();
		$connection = Yii::app()->db;
		$que = "delete from paket id_paket = $id";
		Yii::app()->db->createCommand($que)->execute();
	
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}


	/**
	 * Lists all models.
	 */
	// public static function generateBarcode(){

	// }
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Items');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */

	 public function actionAdminJSON() {

	    $draw                   = $_REQUEST["draw"];//counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables
	    $orderByColumnIndex     = $_REQUEST['order'][0]['column'];// index of the sorting column (0 index based - i.e. 0 is the first record)
	   	if ($_REQUEST['columns'][$orderByColumnIndex]['data']=="0") {
			$orderBy = "id";
	   	}else{
	    	$orderBy                = $_REQUEST['columns'][$orderByColumnIndex]['data'];//Get name of the sorting column from its index
	   	}
	    $orderType              = $_REQUEST['order'][0]['dir']; // ASC or DESC
	    if (isset($_REQUEST['start'])){	
		    $start                  = $_REQUEST["start"];//Paging first record indicator.
	    }else{
		    $start                  = 0;
	    }

	    if (isset($_REQUEST['length'])){	
	    $length                 = $_REQUEST['length'];//Number of records that the table can display in the current draw
	    }else{
	    	$length = 0;
	    }
	    /* END of POST variables */
	   	$query = "SELECT 
		    is_stockable,
	   	is_bahan,i.id id,
		    ukuran,
			iss.barcode as barcode ,
			m.nama as motif, 
			iss.stok_minimum,
			total_cost ,discount,
			stok,modal,lokasi, item_name, has_bahan, c.category as nama_kategori, l.nama as nama_letak, iss.harga as harga

			FROM items i inner join items_satuan as iss on iss.item_id = i.id
			left join categories as c on c.id = i.category_id
			left join letak as l on l.id = i.letak_id
			left join motif as m on m.id = i.motif
			inner join stores s on s.id = i.store_id 

			where 
			i.hapus = 0
			and
			s.id = ".Yii::app()->user->store_id()."
			 $filter 

			and iss.is_default = 1
			group by i.id 
			order by item_name asc

		";
		// echo $query ;
		// exit;
	    $recordsTotal = count($this->getAdminJSON($query));
	      if(!empty($_REQUEST['search']['value'])){

	        for($i=0 ; $i<count($_REQUEST['columns']);$i++){
	        	// echo  $_REQUEST['columns'][$i]['searchable'];
	        	// echo "<br>";
	        	if ($_REQUEST['columns'][$i]['searchable']=="true"){

		            $column     =   $_REQUEST['columns'][$i]['name'];//we get the name of each column using its index from POST request
		            $where[]    =   "$column like '%".$_REQUEST['search']['value']."%'";
	        	}else{
	        		// echo "masuk";
	        	}
	        }
	        $where = "WHERE ".implode(" OR " , $where);// id like '%searchValue%' or name like '%searchValue%' ....
	        /* End WHERE */

	        // $sql = sprintf("SELECT * FROM %s %s %s", $query , $where, " AND CREATED_BY='".$uid."'");//Search query without limit clause (No pagination)
            $sql = "SELECT * FROM ($query) as d $where  limit  $start,$length  ";



	        $recordsFiltered = count($this->getAdminJSON($sql));//Count of search result
	        $data = $this->getAdminJSON($sql);

	      

    	}
    	else {
			// echo "masuk";
			// exit;
         $sql = "SELECT * FROM ($query) as d  ORDER BY $orderBy $orderType limit  $start,$length ";
		//  echo $sql;
		//  exit;
         $data = $this->getAdminJSON($sql);
         $recordsFiltered = $recordsTotal;
         // var_dump($recordsFiltered);
    	}
    	// var_dump($data);

    	 $response = array(
	         "draw"             => intval($draw),
	         "recordsTotal"     => $recordsTotal,
	         "recordsFiltered"  => $recordsFiltered,
	         "data"             => $data
        );

    	
        echo json_encode($response);
        exit;
      }

  public function getLaporanStokJSON($query,$adjust) {
  	// var_dump($adjust);
	$stokpercabang = [];
  	$branch_id = Yii::app()->user->branch();
	$store_id = Yii::app()->user->store_id();

  	// echo $query;
  	// exit;
  	$rawData = Yii::app()->db->createCommand($query)->queryAll();
  	$array = array();

  	$no = 1;
	// tampilkan stok tiap cabang
	foreach ($rawData as $key => $value) {

		$stok = ItemsController::getStok($value['id'],"",$branch_id);
		$harga = round(ItemsController::getAverage($value['id'],$value['satuan_id'],$branch_id));
		$satuanlist = ItemsController::getSatuanItems($value['id'],$stok);

		if (isset($_REQUEST['cabangpusat'])){
			if ($_REQUEST['cabangpusat'] == "1"){
				$satuanlist = "";
				$cabang = Branch::model()->findAll("store_id = '$store_id' ");
				$satuanlist .= "<table class='table'>";  
				$satuanlist .= "<thead><tr><td>Tempat</td><td>Stok</td></tr></thead>";  
					foreach ($cabang as $key => $value2) {
						$stokx = ItemsController::getStok($value['id'],"",$value2->id);
						$satuanlist .= "<tr>";  					
							$satuanlist .= "<td>".$value2->branch_name."</td>";  					
							$satuanlist .= "<td>".$stokx."</td>";  					
						$satuanlist .= "</tr>";  
					}
					$satuanlist .= "</table>";  
			}
		}
		
		
	  	$input = "<input type='text' name='stok_real' value='$stok' maxlengt='5'
				class='stok_real' style='width: 70px'>
				<button harga='$harga'  stok-before='$stok'  class='set-stok btn btn-primary' 
				item-id=' $value[id]'
				satuan-id='$value[satuan_id]' >Atur Stok</button>";


  		$json = array(
  			$value['barcode'],
  			// $no++,
  			$value['nama_kategori'],
  			$value['motif'],
  			// $aksi,
			$value['item_name'],
			// '<a href="'.Yii::app()->createUrl("ItemsSatuan/kartu",array("id"=>$value['id'],'satuan_id'=>$value['satuan_id'])).'">'.$value['item_name'].'</a>',
			// $satuanlist,
			number_format((float)$stok, 2, '.', ''),
  		);
  		if ($adjust=="1"){
  			array_push($json, $input);
  		}
		  
		array_push($array, $json);
	}
//   echo "<pre>";
//   print_r($stokpercabang);
  	return $array;
  }
  public function getAdminJSON($query) {
  	$rawData = Yii::app()->db->createCommand($query)->queryAll();
  	$array = array();
  	foreach ($rawData as $key => $value) {

  		if ($value['is_bahan']=="1"){
  			$bahan = "Bahan Baku";
  		}else{
  			$bahan = "Menu";
  		}
  		$aksi = '<div class="btn-group">
		  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    Aksi <span class="caret"></span>
		  </button>
		  <ul class="dropdown-menu">
		  <li>
		      <a href="'.Yii::app()->createUrl("itemsSatuan/admin", array("id"=>$value[id],"status"=>"ubah")).'" >
		              <i class="fa fa-pencil"></i>
		              Kelola Satuan & Harga
		            </a>
		        
		  </li>
		    <li>';
		       
	      if ($value['has_bahan']=="1"){
		     
		       $aksi .= '<a href="'.Yii::app()->createUrl("itemsSource/create", array("id"=>$value[id])).'" >
		        <i class="fa fa-pencil"></i>
		        Kelola Sub Item
		      </a>';
		      } 

		    $aksi .= '</li>
		    <li>
		      
		          <a href='.Yii::app()->createUrl("items/update", array("id"=>$value[id],"status"=>"ubah")).'>
		            <i class="fa fa-pencil"></i> Ubah
		          </a>
		    </li>
		     <li>
		      
		          <a href='.Yii::app()->createUrl("items/view", array("id"=>$value[id],"status"=>"ubah")).'>
		            <i class="fa fa-eye"></i> Lihat
		          </a>
		    </li>
		    <li>
		        <a class="hapus" href='.Yii::app()->createUrl("Items/hapus", array("id"=>$value[id])).'>
		                <i class="fa fa-times"></i> Hapus
		              </a>
		    </li>
		  </ul>
		</div>';
  		$json = array(
  			$aksi,
		    	$value['barcode'],       
  			   '<a href='.Yii::app()->createUrl("Items/view", array("id"=>$value[id],"status"=>"ubah")).'>
		    	'.$value['item_name'].'       
		          </a>',
  			$value['nama_kategori'],
  			$value['motif'],
			'<a href='.Yii::app()->createUrl("ItemsSatuan/admin", array("id"=>$value[id],"status"=>"ubah")).'>
			'.number_format($value['harga']).'       
			</a>',
  			$bahan

  			);
  		array_push($array, $json);
  	}
  	return $array;
		
  }
	public function actionAdmin()
	{
		if (isset($_REQUEST['cari'])){
			$value = $_REQUEST['cari'];
			$filter = " and item_name like '%$value%' ";
		}

		$filtersForm=new FiltersForm;
		if (isset($_GET['FiltersForm']))
		$filtersForm->filters=$_GET['FiltersForm'];
		// $idh = $_REQUEST['id'];

		// $sql
		
		$sql = "SELECT  is_stockable,is_bahan,i.id id,panjang,ketebalan, ukuran,price_reseller,price_distributor,iss.barcode as barcode ,m.nama as motif, iss.stok_minimum,total_cost ,discount,stok,modal,lokasi,i.id, item_name, item_number, description, i.category_id,has_bahan

			FROM 
			items i inner join 
			items_satuan as iss on iss.item_id = i.id 
			inner join motif as m on m.id = i.motif
			where i.hapus = 0 $filter 
			and iss.is_default = 1
			group by i.id 
			order by item_name asc

		";
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
	 * @param integer $id the ID of the model to be loaded
	 * @return Items the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Items::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Items $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='items-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}


}
