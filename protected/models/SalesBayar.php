<?php

/**
 * This is the model class for table "sales_bayar".
 *
 * The followings are the available columns in table 'sales_bayar':
 * @property integer $id
 * @property integer $sales_id
 * @property string $waktu
 * @property string $bayar
 */
class SalesBayar extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SalesBayar the static model class
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
		return 'sales_bayar';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sales_id', 'numerical', 'integerOnly'=>true),
			array('bayar', 'length', 'max'=>20),
			array('waktu', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sales_id, waktu, bayar', 'safe', 'on'=>'search'),
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
			'sales_id' => 'Sales',
			'waktu' => 'Waktu',
			'bayar' => 'Bayar',
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
		$criteria->compare('sales_id',$this->sales_id);
		$criteria->compare('waktu',$this->waktu,true);
		$criteria->compare('bayar',$this->bayar,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}