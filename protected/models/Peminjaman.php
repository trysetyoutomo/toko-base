<?php

/**
 * This is the model class for table "peminjaman".
 *
 * The followings are the available columns in table 'peminjaman':
 * @property integer $id
 * @property string $tanggal_pinjam
 * @property string $tanggal_kembali
 * @property string $nama
 * @property integer $deposit
 * @property string $keterangan
 */
class Peminjaman extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Peminjaman the static model class
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
		return 'peminjaman';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tanggal_pinjam, tanggal_kembali, nama, deposit, keterangan', 'required'),
			array('deposit', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, tanggal_pinjam, tanggal_kembali, nama, deposit, keterangan', 'safe', 'on'=>'search'),
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
			'tanggal_pinjam' => 'Tanggal Pinjam',
			'tanggal_kembali' => 'Tanggal Kembali',
			'nama' => 'Nama',
			'deposit' => 'Deposit',
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
		$criteria->compare('tanggal_pinjam',$this->tanggal_pinjam,true);
		$criteria->compare('tanggal_kembali',$this->tanggal_kembali,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('deposit',$this->deposit);
		$criteria->compare('keterangan',$this->keterangan,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}