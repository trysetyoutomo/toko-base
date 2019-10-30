<?php

/**
 * This is the model class for table "barangmasuk".
 *
 * The followings are the available columns in table 'barangmasuk':
 * @property integer $id
 * @property string $tanggal
 * @property string $sumber
 * @property string $user
 * @property string $keterangan
 */
class BarangMasuk extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Barangmasuk the static model class
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
		return 'barangmasuk';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tanggal, sumber, user, keterangan', 'required'),
			array('user', 'length', 'max'=>60),
			array('keterangan', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tanggal, sumber, user, keterangan', 'safe', 'on'=>'search'),
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
			'sumber' => 'Sumber',
			'user' => 'User',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('sumber',$this->sumber,true);
		$criteria->compare('user',$this->user,true);
		$criteria->compare('keterangan',$this->keterangan,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}