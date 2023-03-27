<?php

/**
 * This is the model class for table "items".
 *
 * The followings are the available columns in table 'items':
 * @property integer $id
 * @property string $item_name
 * @property string $item_number
 * @property string $description
 * @property integer $category_id
 * @property integer $unit_price
 * @property integer $tax_percent
 * @property integer $total_cost
 * @property integer $discount
 * @property string $image
 * @property integer $status
 */
class AkuntansiAkun extends CActiveRecord
{
	public $min = 0;
	public $gambar ;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Items the static model class
	 */

	public function rules()
	{
		return [
            ["nama_akun, subgroup_id,store_id","required"]
        ];
    }

	 public function attributeLabels() {
        return array(
            'subgroup_id' => 'Kategori Akun',
            'subgroup_detail_id' => 'SubGroup Detail (Neraca)',

        );
    }

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'akuntansi_akun';
	}

}