<?php 
// protected/models/UsahaModel.php

class UsahaModel extends CFormModel
{
    public $jenisUsaha;

    public function rules()
    {
        return array(
            array('jenisUsaha', 'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'jenisUsaha' => 'Jenis Usaha',
        );
    }
}
?>
