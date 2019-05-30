<?php

/**
 * This is the model class for table "purchase_order".
 *
 * The followings are the available columns in table 'purchase_order':
 * @property integer $id
 * @property string $tanggal
 * @property string $sumber
 * @property string $user
 * @property string $keterangan
 * @property string $jenis
 * @property string $faktur
 * @property string $kode_trx
 * @property string $branch_id
 * @property integer $status_aktif
 * @property integer $isbayar
 * @property string $tanggal_jt
 */
class PurchaseOrder extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PurchaseOrder the static model class
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
		return 'purchase_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tanggal, sumber, user, keterangan, jenis, faktur, kode_trx, branch_id', 'required'),
			array('status_aktif, isbayar', 'numerical', 'integerOnly'=>true),
			array('user', 'length', 'max'=>60),
			array('keterangan', 'length', 'max'=>100),
			array('jenis', 'length', 'max'=>20),
			array('faktur, kode_trx', 'length', 'max'=>50),
			array('branch_id', 'length', 'max'=>11),
			array('tanggal_jt', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tanggal, sumber, user, keterangan, jenis, faktur, kode_trx, branch_id, status_aktif, isbayar, tanggal_jt', 'safe', 'on'=>'search'),
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
			'jenis' => 'Jenis',
			'faktur' => 'Faktur',
			'kode_trx' => 'Kode Trx',
			'branch_id' => 'Branch',
			'status_aktif' => 'Status Aktif',
			'isbayar' => 'Isbayar',
			'tanggal_jt' => 'Tanggal Jt',
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
		$criteria->compare('jenis',$this->jenis,true);
		$criteria->compare('faktur',$this->faktur,true);
		$criteria->compare('kode_trx',$this->kode_trx,true);
		$criteria->compare('branch_id',$this->branch_id,true);
		$criteria->compare('status_aktif',$this->status_aktif);
		$criteria->compare('isbayar',$this->isbayar);
		$criteria->compare('tanggal_jt',$this->tanggal_jt,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}