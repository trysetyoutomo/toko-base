<?php

/**
 * This is the model class for table "items".
 *
 * The followings are the available columns in table 'items':
 * @property integer $id
 * @property string $item_name
 * @property string $item_number
 * @property string $description
 * @property integer $category_id
 * @property integer $unit_price
 * @property integer $tax_percent
 * @property integer $total_cost
 * @property integer $discount
 * @property string $image
 * @property integer $status
 */
class Items extends CActiveRecord
{
	public $min = 0;
	public $gambar ;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Items the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'items';
	}

	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// is_stockable
// is_bahan
			// has_bahan
			array('stok,is_pulsa,satuan_id,panjang,ukuran,ketebalan,persentasi,barcode,stok_minimum,modal,lokasi,item_name, item_number, description, category_id, unit_price,  status,category_id', 'required','on'=>'insert','message'=>'{attribute} tidak boleh kosong'),



// is_stockable,is_bahan,has_bahan,
			array('panjang,ukuran,ketebalan,price_reseller,price_distributor,stok_minimum,modal,lokasi,item_name, total_cost, description, category_id,  status,category_id', 'required','on'=>'update','message'=>'{attribute} tidak boleh kosong'),
			array('persentasi,modal,category_id, tax_percent, total_cost, discount, status', 'numerical', 'integerOnly'=>true),

			// array('barcode', 'unique','message'=>'Kode telah ada, tidak boleh sama '),
			// array('gambar', 'file'),
			array('item_name', 'length', 'max'=>30),
			array('item_number', 'length', 'max'=>20),
			array('image', 'length', 'max'=>80),

			// array('stok_minimum', 'compare','operator'=>'>=','compareAttribute'=>'min','message'=>'{attribute} minimal 1'),
			array('modal,unit_price', 'compare','operator'=>'>=','compareAttribute'=>'min','message'=>'{attribute} minimal 500'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, item_name, item_number, description, category_id, unit_price, tax_percent, total_cost, discount, image, status', 'safe', 'on'=>'search'),
		);
	}
	public function getQty($id){
		$sql = "SELECT 
		b.id id ,
		b.item_name,
		IFNULL(SUM(db.jumlah),'0') stok
		FROM 
		items b
		LEFT JOIN  items_detail db ON b.id = db.barang_id
		INNER JOIN store s on s.id = b.store_id
		WHERE 
		b.id = '$id'
		and s.id = ".Yii::app()->user->store_id()."
		GROUP BY b.id";
		$model = Yii::app()->db->createCommand($sql)->queryRow();

		return $model['stok'];
	}
	public  function queryDataItems($with_bahan){
		// var_dump($with_bahan);
		// exit;
		// $with_bahan = "ALL";
		$where = "";
		if ($with_bahan=="BAHAN"){ // dengan sub detail
			// $where = " and i.is_stockable = 1 ";
			$where = " and i.is_bahan = 1 ";
			// $where = " and c.category = 'BAHAN BAKU' or c.category = 'LAIN - LAIN'   ";
		}else if ($with_bahan=="MENU"){ // dengan sub detail
			// $where = " and (c.category like '%MENU PORSI' or c.category like '%MENU PAKET')  ";
			// $where = " and (c.category like '%MENU PORSI' or c.category like '%MENU PAKET')  ";
			$where = " and i.is_bahan = 0 ";
		}else if ($with_bahan=="ALL"){ 
			$where = "  ";
		}else if ($with_bahan=="TANPA_PULSA"){
			$where = " and i.is_pulsa <> 1 ";
		}

		$que = "

		SELECT
		*
		FROM
		(
			SELECT
			iss.harga_beli harga_beli,
			iss.harga harga_jual,
			iss.id nama_satuan_id,
			iss.nama_satuan nama_satuan,
			c.category nama_kategori,
			m.nama nama_sub_kategori,
			m.nama category,
			iss.id satuan_id,
			i.id id,
			concat(i.item_name,' (',iss.nama_satuan,')') as nama,

			iss.barcode,
			panjang,
			ketebalan,
			ukuran,
			total_cost AS harga,
			'0' AS is_paket
			FROM
			items i
			LEFT JOIN categories AS c ON c.id = i.category_id
			LEFT  join motif m on m.category_id = c.id and m.id = i.motif

			INNER JOIN items_satuan iss on iss.item_id = i.id
			INNER JOIN stores as s on s.id = i.store_id 

			AND i.hapus = 0 and s.id = ".Yii::app()->user->store_id()."
			where  1=1 $where 
			group by iss.id
#			group by i.id
			order by nama_kategori,nama_sub_kategori desc
			

			
			) AS i
		order by nama_kategori,nama_sub_kategori desc


		
		";
		// echo $que;
		// exit;
		return $que;
	}


	public  function queryDataItemsPO($poid){
		// var_dump($with_bahan);queryDataItemsPO
		// exit;
		// $where = "";
		// if ($with_bahan=="BAHAN"){ // dengan sub detail
		// 	$where = " and i.is_stockable = 1 ";
		// 	// $where = " and c.category = 'BAHAN BAKU' or c.category = 'LAIN - LAIN'   ";
		// }else if ($with_bahan=="MENU"){ // dengan sub detail
		// 	$where = " and c.category like '%MENU PORSI' or c.category like '%MENU PAKET'  ";
		// }else if ($with_bahan=="ALL"){ 
		// 	$where = "  ";
		// }

		$que = "

		SELECT
		*
		FROM
		(
			SELECT
			po.sumber as sumber,
			pod.jumlah as jumlah_po,
			pod.harga harga_beli,
			iss.id nama_satuan_id,
			iss.nama_satuan nama_satuan,
			c.category nama_kategori,
			m.nama nama_sub_kategori,
			iss.id satuan_id,
			i.id id,
			concat(i.item_name,' (',iss.nama_satuan,')') as nama,

			iss.barcode,
			panjang,
			ketebalan,
			ukuran,
			total_cost AS harga,
			'0' AS is_paket
			FROM
			items i
			LEFT JOIN categories AS c ON c.id = i.category_id
			LEFT  join motif m on m.category_id = c.id and m.id = i.motif

			INNER JOIN purchase_order_detail pod on pod.kode = i.id
			INNER JOIN items_satuan iss on iss.id = pod.satuan
			INNER JOIN purchase_order  po on po.id = pod.head_id 

			AND i.hapus = 0
			where  1=1 
			and po.kode_trx  = '$poid'

			group by pod.id
#			group by i.id
			order by nama_kategori,nama_sub_kategori desc
			

			
			) AS i
		order by nama_kategori,nama_sub_kategori desc


		
		";
		return $que;
		// echo $que;
		// exit;
	}
	
	
	public function data_items($with_bahan)
	{
		$que = $this->queryDataItems($with_bahan);
		
		// echo $que;
		// UNION ALL
		// 	(
		// 	SELECT
		// 	'-' as satuan_id,
		// 	p.id_paket as id,
		// 	p.nama_paket nama,
		// 	p.id_paket AS barcode,
		// 	'-' AS panjang,
		// 	'-' AS ketebalan,
		// 	'-' AS ukuran,
		// 	p.harga AS harga,
		// 	'1' AS is_paket
		// 	FROM
		// 	paket p


		// ) 
		$command=Yii::app()->db->createCommand($que);
		$reader=$command->query();
			
	
		
                // $data = array();
                // foreach ($model as $item)
                // {
                    // $temp = array();
                    // $data[$item->id] = $item->outlet->status." ".$item->item_name;
                // }
				
				$data = array();
                foreach ($reader as $item)
                {
                    $temp = array();
                    $stok = 0;
                    // $stok = ItemsController::getStok($item['id']);
                    // $data[$item->id] = $item->outlet->status." ".$item->item_name;
                    if ($item['is_paket']=="0"){
                    	$x = "";
	                    // $x = trim($item['ukuran']).".".trim($item['ketebalan']).".".trim($item['panjang']); 
$data[$item['barcode']."##".$item['satuan_id']] = trim($item['barcode'])." - ".trim($item['category'])." ".trim($item['nama'])." ".$x;
                    }else{
                    	 $data[$item['barcode']."##".$item['satuan_id']] = $item['barcode']." - ".$item['nama'];
                    }

                }
                return $data;
				// print_r($data);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'outlet'=>array(self::BELONGS_TO,'outlet','kode_outlet'),
			'categories'=>array(self::BELONGS_TO,'categories','id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'stok' => 'Stok Saat ini ',
			'is_stockable' => 'Kelola Stok ',
			'satuan_id' => 'Satuan ',
			'has_bahan' => 'Memiliki Bahan Baku ?',
			'is_bahan' => 'Item Ini adalah Bahan Baku ?',
			'id' => 'ID',
			'modal' => 'Harga Beli',
			'item_name' => 'Nama Item',
			'item_number' => 'Nomor',
			'description' => 'Keterangan',
			'category_id' => 'Kategori',
			'unit_price' => 'Harga Jual',
			'tax_percent' => 'Pajak',
			'total_cost' => 'Harga Modal',
			'discount' => 'Diskon (%)',
			'price_distributor' => 'Total Harga Jual Distributor',
			'price_reseller' => 'Total Harga Jual Reseller',
			'image' => 'Gambar',
			'status' => 'Status',
			'kode_outlet' => 'kode',
			'lokasi' => 'Lokasi',
			'gambar' => 'Gambar ',
			'persentasi' => 'Persentasi Keuntungan ',
			'motif' => 'Sub Kategori ',
			'iskembali' => 'iskembali ',
			'is_pulsa' => 'Produk Pulsa ? ',
			'provider_id' => 'Nama Provifer',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('item_name',$this->item_name,true);
		$criteria->compare('item_number',$this->item_number,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('unit_price',$this->unit_price);
		$criteria->compare('tax_percent',$this->tax_percent);
		$criteria->compare('total_cost',$this->total_cost);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('kode_outlet',$this->kode_outlet);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}