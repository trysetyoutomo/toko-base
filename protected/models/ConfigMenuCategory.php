<?php

/**
 * This is the model class for table "config_menu".
 *
 * The followings are the available columns in table 'config_menu':
 * @property integer $id
 * @property string $controllerID
 * @property string $actionID
 * @property string $value
 * @property integer $category_menu_id
 * @property integer $hapus
 */
class ConfigMenuCategory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ConfigMenu the static model class
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
		return 'config_menu_category';
	}
}