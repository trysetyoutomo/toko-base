<?php

/**
 * This is the model class for table "config_role".
 *
 * The followings are the available columns in table 'config_role':
 * @property integer $id
 * @property string $role_name
 * @property string $created_datetime
 * @property string $updated_datetime
 */
class ConfigRole extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ConfigRole the static model class
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
		return 'config_role';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('role_name', 'length', 'max'=>50),
			array('created_datetime, updated_datetime', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, role_name, created_datetime, updated_datetime', 'safe', 'on'=>'search'),
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
			'role_name' => 'Role Name',
			'created_datetime' => 'Created Datetime',
			'updated_datetime' => 'Updated Datetime',
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
		$criteria->compare('role_name',$this->role_name,true);
		$criteria->compare('created_datetime',$this->created_datetime,true);
		$criteria->compare('updated_datetime',$this->updated_datetime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}