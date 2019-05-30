<?php

/**
 * This is the model class for table "saldo".
 *
 * The followings are the available columns in table 'saldo':
 * @property integer $id
 * @property string $tanggal
 * @property string $harga
 * @property integer $stok
 * @property integer $item_id
 */
class Saldo extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Saldo the static model class
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
		return 'saldo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tanggal, harga, stok, item_id', 'required'),
			array('stok, item_id', 'numerical', 'integerOnly'=>true),
			array('harga', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tanggal, harga, stok, item_id', 'safe', 'on'=>'search'),
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
			'harga' => 'Harga',
			'stok' => 'Stok',
			'item_id' => 'Item',
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
		$criteria->compare('harga',$this->harga,true);
		$criteria->compare('stok',$this->stok);
		$criteria->compare('item_id',$this->item_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}