<?php

/**
 * This is the model class for table "items_satuan".
 *
 * The followings are the available columns in table 'items_satuan':
 * @property integer $id
 * @property integer $item_id
 * @property string $nama_satuan
 * @property integer $satuan
 */
class ItemsSatuan extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ItemsSatuan the static model class
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
		return 'items_satuan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('letak_id,item_id', 'numerical', 'integerOnly'=>true),
			array('nama_satuan', 'length', 'max'=>20),
			array('barcode', 'unique','on'=>'insert'),
			array('barcode,harga,is_default,satuan,nama_satuan,item_id,harga_beli', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, item_id, nama_satuan, satuan', 'safe', 'on'=>'search'),
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
			'item_id' => 'Item',
			'is_default' => 'Default ?',
			'nama_satuan' => 'Nama Satuan',
			'satuan' => 'Nilai pada Satuan Utama ',
			'harga' => 'Harga Jual Satuan',
			'harga_beli' => 'Harga Modal Satuan',
			'letak_id' => 'Letak '
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
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('nama_satuan',$this->nama_satuan,true);
		$criteria->compare('satuan',$this->satuan);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}