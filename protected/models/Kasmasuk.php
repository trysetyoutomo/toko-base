<?php

/**
 * This is the model class for table "pengeluaran".
 *
 * The followings are the available columns in table 'pengeluaran':
 * @property integer $id
 * @property string $tanggal
 * @property string $jenis_masuk
 * @property string $user
 * @property string $keterangan
 * @property integer $total
 */
class Kasmasuk extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Pengeluaran the static model class
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
		return 'kasmasuk';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tanggal, jenis_masuk, user, keterangan, total', 'required'),
			array('total', 'numerical', 'integerOnly'=>true),
			array('jenis_masuk, user', 'length', 'max'=>60),
			array('keterangan', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tanggal, jenis_masuk, user, keterangan, total', 'safe', 'on'=>'search'),
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
			'tanggal' => 'Tanggal',
			'jenis_masuk' => 'Jenis Masuk',
			'user' => 'User',
			'keterangan' => 'Keterangan',
			'total' => 'Total',
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
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('jenis_masuk',$this->jenis_masuk,true);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('keterangan',$this->keterangan,true);
		$criteria->compare('total',$this->total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}