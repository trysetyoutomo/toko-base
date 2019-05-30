<?php

/**
 * This is the model class for table "penukaran_items".
 *
 * The followings are the available columns in table 'penukaran_items':
 * @property integer $id
 * @property integer $si_id
 * @property integer $item_id_asal
 * @property integer $item_qty_asal
 * @property integer $item_id_baru
 * @property integer $item_qty_baru
 * @property string $tanggal
 * @property string $keterangan
 */
class PenukaranItems extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PenukaranItems the static model class
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
		return 'penukaran_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('si_id, item_id_asal, item_qty_asal, item_id_baru, item_qty_baru, tanggal, keterangan', 'required'),
			array('si_id, item_id_asal, item_id_baru', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, si_id, item_id_asal, item_qty_asal, item_id_baru, item_qty_baru, tanggal, keterangan', 'safe', 'on'=>'search'),
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
			'si_id' => 'Si',
			'item_id_asal' => 'Item Id Asal',
			'item_qty_asal' => 'Item Qty Asal',
			'item_id_baru' => 'Item Id Baru',
			'item_qty_baru' => 'Item Qty Baru',
			'tanggal' => 'Tanggal',
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
		$criteria->compare('si_id',$this->si_id);
		$criteria->compare('item_id_asal',$this->item_id_asal);
		$criteria->compare('item_qty_asal',$this->item_qty_asal);
		$criteria->compare('item_id_baru',$this->item_id_baru);
		$criteria->compare('item_qty_baru',$this->item_qty_baru);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('keterangan',$this->keterangan,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}