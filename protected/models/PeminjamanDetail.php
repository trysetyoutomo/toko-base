<?php

/**
 * This is the model class for table "peminjaman_detail".
 *
 * The followings are the available columns in table 'peminjaman_detail':
 * @property integer $id
 * @property integer $head_id
 * @property integer $item_id
 * @property integer $qty
 */
class PeminjamanDetail extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PeminjamanDetail the static model class
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
		return 'peminjaman_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('head_id, item_id, qty', 'required'),
			array('head_id, item_id, qty', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, head_id, item_id, qty', 'safe', 'on'=>'search'),
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
			'head_id' => 'Head',
			'item_id' => 'Item',
			'qty' => 'Qty',
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
		$criteria->compare('head_id',$this->head_id);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('qty',$this->qty);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}