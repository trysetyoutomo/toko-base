<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property string $id
 * @property integer $nama
 * @property integer $alamat
 * @property integer $no_telepon
 */
class Customer extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array(' nama, alamat, no_telepon, customer_type', 'required'),
			// array('nama, alamat, no_telepon', '', 'integerOnly'=>true),
			array('id', 'length', 'max'=>40),
			// array('id', 'required'),
			array('kode', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nama, alamat, no_telepon', 'safe', 'on'=>'search'),
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
			'kode_agen' => 'kode customer',
			'id' => 'ID',
			'nama' => 'Nama',
			'alamat' => 'Alamat',
			'no_telepon' => 'No Telepon',
			'customer_type' => 'Tipe Konsumen',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('nama',$this->nama);
		$criteria->compare('alamat',$this->alamat);
		$criteria->compare('no_telepon',$this->no_telepon);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}