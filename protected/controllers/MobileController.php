<?php 
// echo "123";
class MobileController extends Controller
{
	

	public function index(){
		echo "123";
	}
	public function actionList_items(){
		$sql = "SELECT
	is_stockable,
	is_bahan,
	i.id id,
	panjang,
	ketebalan,
	ukuran,
	price_reseller,
	price_distributor,
	iss.barcode AS barcode,
	motif,
	iss.stok_minimum,
	total_cost,
	discount,
	stok,
	modal,
	lokasi,
	i.id,
	item_name,
	item_number,
	description,
	category_id,
	has_bahan,
	iss.harga
FROM
	items i
	INNER JOIN items_satuan AS iss ON iss.item_id = i.id 
WHERE
	i.hapus = 0 
	AND iss.is_default = 1 

	 and i.is_bahan = 0
GROUP BY
	i.id 
ORDER BY
	i.total_cost desc
	LIMIT 10 "; 

		$rawData = Yii::app()->db->createCommand($sql)->queryAll();
		echo json_encode($rawData);	

	}
}