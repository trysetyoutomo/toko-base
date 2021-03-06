<?php

/**
 * This is the model class for table "barangmasuk_detail".
 *
 * The followings are the available columns in table 'barangmasuk_detail':
 * @property integer $id
 * @property integer $kode
 * @property integer $jumlah
 */
class BarangmasukDetail extends CActiveRecord
{
	public $ubahke;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return BarangmasukDetail the static model class
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
		return 'barangmasuk_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kode, jumlah', 'required'),
			array('kode', 'numerical', 'integerOnly'=>true),
			array('ubahke','required','on'=>'update'),	
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, kode, jumlah', 'safe', 'on'=>'search'),
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
			'ubahke' => 'Perubahan Jumlah',
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}