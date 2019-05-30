<?php

/**
 * This is the model class for table "items_detail".
 *
 * The followings are the available columns in table 'items_detail':
 * @property integer $id
 * @property integer $kode
 * @property integer $jumlah
 * @property integer $supplier_id
 * @property integer $harga
 * @property string $tanggal
 */
class ItemsDetail extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ItemsDetail the static model class
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
		return 'items_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kode, jumlah, supplier_id, harga, tanggal', 'required'),
			array('kode, jumlah, supplier_id, harga', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, kode, jumlah, supplier_id, harga, tanggal', 'safe', 'on'=>'search'),
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
			'supplier_id' => 'Supplier',
			'harga' => 'Harga',
			'tanggal' => 'Tanggal',
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
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('harga',$this->harga);
		$criteria->compare('tanggal',$this->tanggal,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}