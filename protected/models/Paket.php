<?php

/**
 * This is the model class for table "paket".
 *
 * The followings are the available columns in table 'paket':
 * @property integer $id_paket
 * @property string $nama_paket
 * @property integer $harga
 * @property string $keterangan
 */
class Paket extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Paket the static model class
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
		return 'paket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('harga', 'numerical', 'integerOnly'=>true),
			array('nama_paket', 'length', 'max'=>100),
			array('keterangan', 'safe'),
			array('nama_paket', 'unique','message'=>"Nama Paket Tersebut telah ada"),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_paket, nama_paket, harga, keterangan', 'safe', 'on'=>'search'),
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
			'id_paket' => 'Id Paket',
			'nama_paket' => 'Nama Paket',
			'harga' => 'Harga',
			'keterangan' => 'Keterangan',
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

		$criteria->compare('id_paket',$this->id_paket);
		$criteria->compare('nama_paket',$this->nama_paket,true);
		$criteria->compare('harga',$this->harga);
		$criteria->compare('keterangan',$this->keterangan,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}