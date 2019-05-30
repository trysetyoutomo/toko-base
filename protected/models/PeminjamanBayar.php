<?php

/**
 * This is the model class for table "peminjaman_bayar".
 *
 * The followings are the available columns in table 'peminjaman_bayar':
 * @property integer $id
 * @property integer $head_id
 * @property integer $tanggal
 * @property integer $total
 */
class PeminjamanBayar extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PeminjamanBayar the static model class
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
		return 'peminjaman_bayar';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('head_id, tanggal, total', 'required'),
			array('head_id, total', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, head_id, tanggal, total', 'safe', 'on'=>'search'),
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
			'tanggal' => 'Tanggal',
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
		$criteria->compare('head_id',$this->head_id);
		$criteria->compare('tanggal',$this->tanggal);
		$criteria->compare('total',$this->total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}