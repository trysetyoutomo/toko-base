<?php


class NeracaController extends Controller
{
    public $layout='main2';

      public function actionIndex(){
		$akunID = $_REQUEST['akun_id'];
		if (isset($_GET['Sales']['date']) and isset($_GET['Sales']['tgl']) ) {
			$tgl = $_GET['Sales']['date'];
			$tgl2 = $_GET['Sales']['tgl'];
		}
		else{
			$tgl = date('Y-m-d',strtotime('-7 days')); 
			//$tgl =  strtotime("+7 day", $tgl);
			$tgl2 = date('Y-m-d'); 
		}
		$this->render('laporan_neraca',
			array(
				'tgl'=>$tgl,
				'tgl2'=>$tgl2,
				'akunID'=>$akunID,
			)
		);
	}
}