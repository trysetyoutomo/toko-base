<?php

/**
 * This is the model class for table "purchase_order_detail".
 *
 * The followings are the available columns in table 'purchase_order_detail':
 * @property integer $id
 * @property integer $kode
 * @property double $jumlah
 * @property integer $head_id
 * @property integer $supplier_id
 * @property integer $harga
 * @property integer $satuan
 * @property integer $jumlah_satuan
 * @property integer $letak_id
 * @property integer $status_pengiriman
 */
class PurchaseOrderDetail extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PurchaseOrderDetail the static model class
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
		return 'purchase_order_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kode, jumlah, head_id, harga', 'required'),
			array('kode, head_id, supplier_id, harga, satuan, jumlah_satuan, letak_id, status_pengiriman', 'numerical', 'integerOnly'=>true),
			array('jumlah', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, kode, jumlah, head_id, supplier_id, harga, satuan, jumlah_satuan, letak_id, status_pengiriman', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kode' => 'Kode',
			'jumlah' => 'Jumlah',
			'head_id' => 'Head',
			'supplier_id' => 'Supplier',
			'harga' => 'Harga',
			'satuan' => 'Satuan',
			'jumlah_satuan' => 'Jumlah Satuan',
			'letak_id' => 'Letak',
			'status_pengiriman' => 'Status Pengiriman',
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
		$criteria->compare('kode',$this->kode);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('head_id',$this->head_id);
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('harga',$this->harga);
		$criteria->compare('satuan',$this->satuan);
		$criteria->compare('jumlah_satuan',$this->jumlah_satuan);
		$criteria->compare('letak_id',$this->letak_id);
		$criteria->compare('status_pengiriman',$this->status_pengiriman);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}