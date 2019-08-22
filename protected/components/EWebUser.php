<?php
class EWebUser extends CWebUser{
	protected $_model;
	protected $username;
	
	protected function loadUser(){
		// echo "id = ".$this->id;
		if ( $this->_model === null ) {
		$this->_model = Users::model()->find('username='."'".$this->id."'");
		}
		return $this->_model;
	}
	
	function getLevel(){
		$user=$this->loadUser();
		if($user){
		return $user->level;
		}else{
		return 100;
		}
	}
	function level(){
		$user=$this->loadUser();
		if($user){
		return $user->level;
		}else{
		return 100;
		}
	}
	function branch(){
		$user=$this->loadUser();
		if($user){
			return $user->branch_id;
		}else{
			return false;
		}
	}

	function store_id(){
		$user=$this->loadUser();
		if($user){
			$br = Branch::model()->findByPk($user->branch_id);
			return $br->store_id;
			// return $user->branch_id;
		}else{
			return false;
		}
	}
}
?>