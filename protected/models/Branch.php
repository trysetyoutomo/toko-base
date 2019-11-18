<?php

/**
 * This is the model class for table "branch".
 *
 * The followings are the available columns in table 'branch':
 * @property integer $id
 * @property string $branch_name
 * @property string $address
 * @property string $telp
 */
class Branch extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Branch the static model class
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
		return 'branch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			// array('id', 'numerical', 'integerOnly'=>true),
			array('branch_name, address,telp,slogan', 'required'),
			array('branch_name', 'length', 'max'=>150),
			array('address', 'length', 'max'=>225),
			array('telp', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, branch_name, address, telp', 'safe', 'on'=>'search'),
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
	public function findAllNew()
	{
		$branch_id = "";
		$filter2 = "";
		if (Yii::app()->user->level()=="1" || Yii::app()->user->level()=="3"){
			$branch_id = Yii::app()->user->branch();
			$filter2 = " id = '$branch_id' and hapus=0";
		}else{	
			$filter2 = " hapus= 0";
		}
		// $nilai = Branch::model()->findAll($filter2);
		$nilai = Branch::model()->findAll($filter2);
		// var_dump($filter2);
		return $nilai;
	}
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'branch_name' => 'Nama Tempat',
			'address' => 'Alamat',
			'telp' => 'Telepon/HP',
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
		$criteria->compare('branch_name',$this->branch_name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('telp',$this->telp,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}