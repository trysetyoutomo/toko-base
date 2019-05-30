<?php

/**
 * This is the model class for table "central_config".
 *
 * The followings are the available columns in table 'central_config':
 * @property string $variable
 * @property string $variable_display
 * @property string $value
 * @property string $type
 * @property integer $id
 * @property string $group
 * @property integer $isaktif
 * @property string $option
 */
class CentralConfig extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CentralConfig the static model class
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
		return 'central_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('variable', 'required'),
			array('isaktif', 'numerical', 'integerOnly'=>true),
			array('variable, variable_display', 'length', 'max'=>100),
			array('type', 'length', 'max'=>50),
			array('group', 'length', 'max'=>255),
			array('value, option', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('variable, variable_display, value, type, id, group, isaktif, option', 'safe', 'on'=>'search'),
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
			'variable' => 'Variable',
			'variable_display' => 'Variable Display',
			'value' => 'Value',
			'type' => 'Type',
			'id' => 'ID',
			'group' => 'Group',
			'isaktif' => 'Isaktif',
			'option' => 'Option',
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

		$criteria->compare('variable',$this->variable,true);
		$criteria->compare('variable_display',$this->variable_display,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('id',$this->id);
		$criteria->compare('group',$this->group,true);
		$criteria->compare('isaktif',$this->isaktif);
		$criteria->compare('option',$this->option,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}