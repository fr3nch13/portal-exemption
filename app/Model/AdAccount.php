<?php
App::uses('AppModel', 'Model');
App::uses('ContactsAdAccount', 'Contacts.Model');

class AdAccount extends ContactsAdAccount 
{
	public $hasMany = array(
		'Exemption' => array(
			'className' => 'Exemption',
			'foreignKey' => 'ad_account_id',
			'dependent' => true,
		),
	);
	
	public $actsAs = array(
		'Snapshot.Stat' => array(
			'stats' => true, // must also have the snapshotStats method below for this to work.
			'entities' => array(
				'all' => array(),
				'created' => array(),
				'modified' => array(),
			),
		),
	);
	
	public $rescanExemptions = false;
	
	public function beforeSave($options = array()) 
	{
		$this->rescanExemptions = false;
		// this already exists, and therefore would probably have exemptions associated with it.
		if(isset($this->id) and isset($this->data[$this->alias]['sac_id']))
		{
			$this->rescanExemptions = true;
		}
		return parent::beforeSave($options);
	}
	
	public function afterSave($created = false, $options = array())
	{
		if($this->rescanExemptions and isset($this->data[$this->alias]['sac_id']))
		{
			$ex_conditions = array(
				'Exemption.ad_account_id' => $this->id,
				'Exemption.primary_account_type' => 0,
			);
			
			$exemptions = $this->Exemption->find('list', array(
				'conditions' => $ex_conditions,
				'fields' => array('Exemption.id', 'Exemption.id'),
			));
			
			if($exemptions)
			{
				$saveMany_data = array();
				foreach($exemptions as $exemption_id)
				{
					$saveMany_data[$exemption_id] = array('id' => $exemption_id, 'sac_id' => $this->data[$this->alias]['sac_id']);
				}
		
				$this->Exemption->saveMany($saveMany_data);
			}
		}
		return parent::afterSave($created, $options);
	}
}
