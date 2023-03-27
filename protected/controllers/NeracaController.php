<?php


class NeracaController extends Controller
{

	public static function queryNeraca($branch,$filter, $orderby = ""){
		$sql = "
		SELECT
		  ak.subgroup_detail_id nama_subgroup_detail_id,
		  asb.nama_subgroup,
		  asb.id subgroup_id,
		  ag.nama_subgroup AS nama_group,
		  ag.id AS ag_id,
		  akun_id,
		  kode_akun,
		  ak.nama_akun,
	  (
		  CASE 
					  WHEN sum( debit) > sum( kredit) > 0 AND ( ag.id = 2 OR ag.id = 3 OR ag.id = 4 ) THEN sum( debit ) - sum( kredit)
					  WHEN sum( debit ) > sum( kredit ) > 0 AND ( ag.id = 1 OR ag.id = 5 OR ag.id = 6  ) THEN sum( debit ) - sum( kredit )
					  ELSE 0
		  END) AS debit,
			  (
			  CASE 
					  WHEN sum( kredit ) > sum( debit ) > 0 AND ( ag.id = 2 OR ag.id = 3 OR ag.id = 4 ) THEN sum( kredit ) - sum( debit )
					  WHEN sum( kredit ) > sum( debit ) > 0 AND ( ag.id = 1 OR ag.id = 5 OR ag.id = 6) THEN sum( kredit ) - sum( debit )
					  else 0
	  
		  END) AS kredit
			  
	  FROM
		  akuntansi_jurnal aj
		  INNER JOIN akuntansi_jurnal_detail ajd ON aj.id = ajd.jurnal_id
		  INNER JOIN akuntansi_akun ak ON ak.id = ajd.akun_id
		  INNER JOIN akuntansi_subgroup asb ON asb.id = ak.subgroup_id
		  INNER JOIN akuntansi_group ag ON ag.id = asb.group_id 
	  WHERE
		  aj.branch_id='$branch'
		  $filter
	  GROUP BY
			ak.id
	   
		{$orderby}
		";
		return $sql;
	}

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
		$this->render('index',
			array(
				'tgl'=>$tgl,
				'tgl2'=>$tgl2,
				'akunID'=>$akunID,
			)
		);
	}
}