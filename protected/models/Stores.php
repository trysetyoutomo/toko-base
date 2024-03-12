<?php

/**
 * This is the model class for table "stores".
 *
 * The followings are the available columns in table 'stores':
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $logo
 * @property string $email
 * @property string $phone
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $postal_code
 * @property string $country
 * @property string $currency_code
 * @property string $receipt_header
 * @property string $receipt_footer
 * @property string $TaxType
 * @property string $nop
 */
class Stores extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Stores the static model class
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
		return 'stores';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,store_type, phone, email,address1, postal_code', 'required'),
			array('name, address1, address2, nop', 'length', 'max'=>50),
			array('code, city, state', 'length', 'max'=>20),
			array('email', 'unique', 'message'=>"Email telah digunakan"),
			// array('logo', 'length', 'max'=>40),
			array('logo', 'file', 'types'=>'jpg,gif,png', 'maxSize'=>'204800', 'allowEmpty'=>true, 'maxFiles'=>4),
			array('email', 'length', 'max'=>100),
			array('phone', 'length', 'max'=>15),
			array('postal_code', 'length', 'max'=>8),
			array('country', 'length', 'max'=>25),
			array('currency_code', 'length', 'max'=>3),
			array('TaxType', 'length', 'max'=>5),
			array('receipt_header, receipt_footer, instagram, percent_tax, percent_service', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, code, logo, email, phone, address1, address2, city, state, postal_code, country, currency_code, receipt_header, receipt_footer, TaxType, nop', 'safe', 'on'=>'search'),
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
			'id' => 'ID Stores',
			'percent_tax' => 'Pajak',
			'percent_service' => 'Service',
			'name' => 'Nama Toko',
			'store_type' => 'Bidang Usaha',
			'code' => 'Kode Toko',
			'logo' => 'Logo',
			'email' => 'Email ',
			'phone' => 'Telepon/HP',
			'address1' => 'Alamat Lengkap',
			'address2' => 'Alamat 2',
			'city' => 'Kota',
			'state' => 'Provinsi',
			'postal_code' => 'Kode POS',
			'country' => 'Negara',
			'currency_code' => 'Currency Code',
			'receipt_header' => 'Receipt Header',
			'receipt_footer' => 'Receipt Footer',
			'TaxType' => 'Tax Type',
			'nop' => 'Nop',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('address1',$this->address1,true);
		$criteria->compare('address2',$this->address2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('currency_code',$this->currency_code,true);
		$criteria->compare('receipt_header',$this->receipt_header,true);
		$criteria->compare('receipt_footer',$this->receipt_footer,true);
		$criteria->compare('TaxType',$this->TaxType,true);
		$criteria->compare('nop',$this->nop,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}