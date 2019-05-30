<?php

/**
 * This is the model class for table "sales_items_paket".
 *
 * The followings are the available columns in table 'sales_items_paket':
 * @property integer $id
 * @property integer $sale_id
 * @property integer $item_id
 * @property integer $quantity_purchased
 * @property double $item_tax
 * @property double $item_price
 * @property double $item_discount
 * @property double $item_total_cost
 * @property double $item_service
 * @property string $permintaan
 * @property integer $cetak
 * @property integer $item_modal
 */
class SalesItemsPaket extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return SalesItemsPaket the static model class
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
		return 'sales_items_paket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sale_id, item_id, quantity_purchased, item_tax, item_price, item_discount, item_total_cost, item_service, permintaan, item_modal', 'required'),
			array('sale_id, quantity_purchased, cetak, item_modal', 'numerical', 'integerOnly'=>true),
			array('item_tax, item_price, item_discount, item_total_cost, item_service', 'numerical'),
			array('permintaan', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, sale_id, item_id, quantity_purchased, item_tax, item_price, item_discount, item_total_cost, item_service, permintaan, cetak, item_modal', 'safe', 'on'=>'search'),
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
			'sale_id' => 'Sale',
			'item_id' => 'Item',
			'quantity_purchased' => 'Quantity Purchased',
			'item_tax' => 'Item Tax',
			'item_price' => 'Item Price',
			'item_discount' => 'Item Discount',
			'item_total_cost' => 'Item Total Cost',
			'item_service' => 'Item Service',
			'permintaan' => 'Permintaan',
			'cetak' => 'Cetak',
			'item_modal' => 'Item Modal',
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
		$criteria->compare('sale_id',$this->sale_id);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('quantity_purchased',$this->quantity_purchased);
		$criteria->compare('item_tax',$this->item_tax);
		$criteria->compare('item_price',$this->item_price);
		$criteria->compare('item_discount',$this->item_discount);
		$criteria->compare('item_total_cost',$this->item_total_cost);
		$criteria->compare('item_service',$this->item_service);
		$criteria->compare('permintaan',$this->permintaan,true);
		$criteria->compare('cetak',$this->cetak);
		$criteria->compare('item_modal',$this->item_modal);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}