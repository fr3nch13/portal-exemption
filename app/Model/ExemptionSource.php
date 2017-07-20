<?php
App::uses('AppModel', 'Model');

class ExemptionSource extends AppModel 
{
	public $displayField = 'shortname';
	
	public $hasMany = array(
		'Exemption' => array(
			'className' => 'Exemption',
			'foreignKey' => 'exemption_source_id',
			'dependent' => false,
		)
	);
	
	// fields that are boolean and can be toggled
	public $toggleFields = array('active');
	
	public function checkAdd($shortname = false, $extra = array())
	{
		if(!$shortname) return false;
		
		$shortname = trim($shortname);
		if(!$shortname) return false;
		
		$shortname = strtolower($shortname);
		
		if($id = $this->field($this->primaryKey, array($this->alias.'.shortname' => $shortname)))
		{
			return $id;
		}
		
		if(!isset($extra['created']))
			$extra['created'] = date('Y-m-d H:i:s');
		
		// not an existing one, create it
		$this->create();
		$this->data = array_merge(array('shortname' => $shortname), $extra);
		if($this->save($this->data))
		{
			return $this->id;
		}
		return false;
	}
	
	public function getShortname($id = false)
	{
		if(!$id) return false;
		
		if($shortname = $this->field('shortname', array($this->alias.'.'.$this->primaryKey => $id)))
		{
			return $shortname;
		}
		return false;
	}
}
