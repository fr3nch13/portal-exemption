<?php
App::uses('AppModel', 'Model');
/**
 * ExemptionFile Model
 *
 * @property Exemption $Exemption
 * @property User $User
 */
class ExemptionFile extends AppModel 
{
	public $displayField = 'filename';
	
	public $validate = array(
		'exemption_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);
	
	public $belongsTo = array(
		'Exemption' => array(
			'className' => 'Exemption',
			'foreignKey' => 'exemption_id',
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
		),
	);
	
	// define the fields that can be searched
	public $searchFields = array(
		'Exemption.name',
		'ExemptionFile.nicename',
		'ExemptionFile.filename',
	);
	
	public $manageUploads = true;
	
	
	public function beforeSave($options = array()) 
	{
		if(isset($this->data[$this->alias]['nicename']) and !trim($this->data[$this->alias]['nicename']))
		{
			if(isset($this->data[$this->alias]['filename']))
			{
				$nicename = $this->data[$this->alias]['filename'];
				if(stripos($nicename, '.') !== false)
				{
					$fileparts = explode('.', $nicename);
					array_pop($fileparts);
					$nicename = implode('.', $fileparts);
				}
				$nicename = Inflector::underscore($nicename);
				$nicename = Inflector::humanize($nicename);
				
				$this->data[$this->alias]['nicename'] = $nicename;
			}
		}
	}
}
