<?php
App::uses('AppModel', 'Model');

class Exemption extends AppModel 
{
	public $useTable = 'exemption';
	public $order = array('Exemption.created' => 'DESC');
	
	public $validate = array(
		'exemption_type_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'ad_account_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'assoc_account_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'added_user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
		'modified_user_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
			),
		),
	);
	
	public $belongsTo = array(
		'ExemptionType' => array(
			'className' => 'ExemptionType',
			'foreignKey' => 'exemption_type_id',
			'plugin_snapshot' => true,
		),
		'ExemptionSource' => array(
			'className' => 'ExemptionSource',
			'foreignKey' => 'exemption_source_id',
			'plugin_snapshot' => true,
		),
		'AdAccount' => array(
			'className' => 'AdAccount',
			'foreignKey' => 'ad_account_id',
		),
		'AssocAccount' => array(
			'className' => 'AssocAccount',
			'foreignKey' => 'assoc_account_id',
		),
		'ReviewStatus' => array(
			'className' => 'ReviewStatus',
			'foreignKey' => 'review_status_id',
			'plugin_snapshot' => true,
		),
		'ExemptionReviewedUser' => array(
			'className' => 'User',
			'foreignKey' => 'reviewed_user_id',
		),
		'ExemptionAddedUser' => array(
			'className' => 'User',
			'foreignKey' => 'added_user_id',
		),
		'ExemptionModifiedUser' => array(
			'className' => 'User',
			'foreignKey' => 'modified_user_id',
		)
	);
	
	public $hasMany = array(
		'ExemptionFile' => array(
			'className' => 'ExemptionFile',
			'foreignKey' => 'exemption_id',
			'dependent' => true,
		)
	);
	
	public $actsAs = array(
		'Tags.Taggable', 
		'Utilities.Email',
		'Snapshot.Stat' => array(
			'entities' => array(
				'all' => array(),
				'created' => array(),
				'modified' => array(),
				'expired' => array(
					'conditions' => array(
						"Exemption.expiration_date < datetime('now')",
					),
				),
			),
		),
	);
	
	// define the fields that can be searched
	public $searchFields = array(
		'Exemption.id',
		'Exemption.asset_tag',
		'ExemptionType.shortname',
		'ExemptionSource.shortname',
		'AdAccount.username',
		'AdAccount.name',
		'AssocAccount.username',
		'AssocAccount.name',
	);
	
	public $manageUploads = true;
	
	public function beforeSave($options = array()) 
	{
		// check the ad_account
		$assocAccountExtra = array();
		
		if(isset($this->data[$this->alias]['ad_account']) and trim($this->data[$this->alias]['ad_account']))
		{
			$this->data[$this->alias]['ad_account_id'] = $this->AdAccount->checkAdd($this->data[$this->alias]['ad_account']);
			$assocAccountExtra['ad_account_id'] = $this->data[$this->alias]['ad_account_id'];
		}
		if(isset($this->data[$this->alias]['ad_account_id']) and trim($this->data[$this->alias]['ad_account_id']) == '')
			$this->data[$this->alias]['ad_account_id'] = 0;
		
		if(isset($this->data[$this->alias]['assoc_account']))
		{
			if(trim($this->data[$this->alias]['assoc_account']))
				$this->data[$this->alias]['assoc_account_id'] = $this->AssocAccount->checkAdd($this->data[$this->alias]['assoc_account'], $this->data[$this->alias]['ad_account_id'], $assocAccountExtra);
			else
				$this->data[$this->alias]['assoc_account_id'] = 0;
		}
		if(isset($this->data[$this->alias]['assoc_account_id']) and trim($this->data[$this->alias]['assoc_account_id']) == '')
			$this->data[$this->alias]['assoc_account_id'] = 0;
			
		if(isset($this->data[$this->alias]['exemption_type_id']) and trim($this->data[$this->alias]['exemption_type_id']) == '')
			$this->data[$this->alias]['exemption_type_id'] = 0;
			
		if(isset($this->data[$this->alias]['review_status_id']) and trim($this->data[$this->alias]['review_status_id']) == '')
			$this->data[$this->alias]['review_status_id'] = 0;
		
		if(isset($this->data[$this->alias]['primary_account_type']))
		{
			if($this->data[$this->alias]['primary_account_type'] == 0)
			{
				if(!isset($this->data[$this->alias]['ad_account_id']) and isset($this->data[$this->alias]['id']))
				{
					$this->data[$this->alias]['ad_account_id'] = $this->field('ad_account_id');
				}
			}
			elseif($this->data[$this->alias]['primary_account_type'] == 1)
			{
				if(!isset($this->data[$this->alias]['assoc_account_id']) and isset($this->data[$this->alias]['id']))
				{
					$this->data[$this->alias]['assoc_account_id'] = $this->field('assoc_account_id');
				}
			}
		}
		
		// if the expiration date changes, change the notified to false for the cron emails
		if($this->id and 
			isset($this->data[$this->alias]['expiration_date']) and 
			trim($this->data[$this->alias]['expiration_date']) and 
			strtotime($this->data[$this->alias]['expiration_date']) > time())
		{
			$current_expiration_date = $this->field('expiration_date');
			if($current_expiration_date != $this->data[$this->alias]['expiration_date'])
			{
				$this->data[$this->alias]['notified'] = false;
			}
		}
		
		return parent::beforeSave($options);
	}
	
	public function afterSave($created = false, $options = array())
	{
		if($this->id and isset($this->data['ExemptionFile']['file']['error']) and $this->data['ExemptionFile']['file']['error'] == 0)
		{
			$this->data['ExemptionFile']['exemption_id'] = $this->id;
			$this->data['ExemptionFile']['user_id'] = (isset($this->data[$this->alias]['added_user_id'])?$this->data[$this->alias]['added_user_id']:0);
			$this->data['ExemptionFile']['nicename'] = '';
			$this->ExemptionFile->create();
			$this->ExemptionFile->data = array(
				'ExemptionFile' => $this->data['ExemptionFile'],
			);
			$this->ExemptionFile->save($this->ExemptionFile->data);
		}
		return parent::afterSave($created, $options);
	}
	
	public function afterFind($results = array(), $primary = false)
	{
		if($primary)
		{
			foreach($results as $i => $result)
			{
				$ad_account_username = false;
				if(isset($result[$this->alias]['ad_account_id']))
				{
					if(isset($result['AdAccount']['username']))
						$ad_account_username = $result['AdAccount']['username'];
					else
						$ad_account_username = $this->AdAccount->getUsername($result[$this->alias]['ad_account_id']);
				}
				
				$assoc_account_username = false;
				if(isset($result[$this->alias]['assoc_account_id']))
				{
					if(isset($result['AssocAccount']['username']))
						$assoc_account_username = $result['AssocAccount']['username'];
					else
						$assoc_account_username = $this->AssocAccount->getUsername($result[$this->alias]['assoc_account_id']);
				}
				
				$exemption_type_shortname = false;
				if(isset($result[$this->alias]['exemption_type_id']))
				{
					if(isset($result['ExemptionType']['shortname']))
						$exemption_type_shortname = $result['ExemptionType']['shortname'];
					else
						$exemption_type_shortname = $this->ExemptionType->getShortname($result[$this->alias]['exemption_type_id']);
	
				}
				
				$result[$this->alias]['ad_account'] = $ad_account_username;
				$result[$this->alias]['assoc_account'] = $assoc_account_username;
				$result[$this->alias]['exemption_type'] = $exemption_type_shortname;
				
				// find and attach the latest artifact
				if(isset($result[$this->alias]['id']))
				{
					$exemptionFileLatest = $this->ExemptionFile->find('first', array(
						'conditions' => array('ExemptionFile.exemption_id' => $result[$this->alias]['id']),
						'order' => array('ExemptionFile.created' => 'desc'),
					));
					
					
					$result[$this->alias]['ExemptionFileLatest'] = array();
					if($exemptionFileLatest)
						$result['ExemptionFileLatest'] = $exemptionFileLatest['ExemptionFile'];
				}
				
				$results[$i] = $result;
					
			}
		}
		return parent::afterFind($results, $primary);
	}
	
	public function save($data = null, $validate = true, $fieldList = array()) 
	{
		if($data)
			$this->data = $data;
		$data = $this->set($this->data);
		
		if($validate)
		{
			if (!$this->validates())
			{
				return false;
			}
		}
		
		$many = false;
		if(isset($data[$this->alias]['asset_tags']) and trim($data[$this->alias]['asset_tags']))
			$many = true;
			
		if(isset($data[$this->alias]['assoc_accounts']) and trim($data[$this->alias]['assoc_accounts']))
			$many = true;
		
		if(!$many)
			return parent::save($data, $validate, $fieldList);
		
		$originalData = $data;
		if(isset($originalData[$this->alias]['asset_tags']))
			unset($originalData[$this->alias]['asset_tags']);
			
		if(isset($originalData[$this->alias]['assoc_accounts']))
			unset($originalData[$this->alias]['assoc_accounts']);
		
		$assetTags = array();
		if(isset($data[$this->alias]['asset_tags']))
			$assetTags = trim($data[$this->alias]['asset_tags']);
		if($assetTags)
			$assetTags = preg_split('/\n+|,\s*/', $assetTags);
		else
			$assetTags = array();
		
		$assocAccounts = array();
		if(isset($data[$this->alias]['assoc_accounts']))
			$assocAccounts = trim($data[$this->alias]['assoc_accounts']);
		if($assocAccounts)
			$assocAccounts = preg_split('/\n+|,\s*/', $assocAccounts);
		else
			$assocAccounts = array();
		
		$data = array();
		$data_keys = array();
		if(count($assetTags))
		{
			$data_key = '';
			$data_value = '';
			foreach($assetTags as $i => $assetTag)
			{
				$assetTag = trim($assetTag);
				if(!$assetTag) continue;
				
				$data_key = Inflector::slug($assetTag).'^^^';
				$data_value = $assetTag.'^^^';
				if(count($assocAccounts))
				{
					foreach($assocAccounts as $i => $assocAccount)
					{
						$assocAccount = trim($assocAccount);
						if(!$assocAccount) continue;
					
						$data_key = Inflector::slug($assetTag).'^^^'. Inflector::slug($assocAccount);
						$data_value = $assetTag.'^^^'.$assocAccount;
						$data_keys[$data_key] = $data_value;
					}
				}
				else
				{
					$data_keys[$data_key] = $data_value;
				}
			}
		}
		
		if(count($assocAccounts))
		{
			$data_key = '';
			$data_value = '';
			foreach($assocAccounts as $i => $assocAccount)
			{
				$assocAccount = trim($assocAccount);
				if(!$assocAccount) continue;
				
				$data_key = '^^^'. Inflector::slug($assocAccount);
				$data_value = '^^^'. $assocAccount;
				if(count($assetTags))
				{
					foreach($assetTags as $i => $assetTag)
					{
						$assetTag = trim($assetTag);
						if(!$assetTag) continue;
						
						$data_key = Inflector::slug($assetTag).'^^^'. Inflector::slug($assocAccount);
						$data_value = $assetTag.'^^^'.$assocAccount;
						$data_keys[$data_key] = $data_value;
					}
				}
				else
				{
					$data_keys[$data_key] = $data_value;
				}
			}
		}
		
		if(count($data_keys))
		{
			$tmp_name = false;
			foreach($data_keys as $i => $data_key)
			{
				$thisData = $originalData;
				list($assetTag, $assocAccount) = explode('^^^', $data_key);
				$thisData[$this->alias]['asset_tag'] = $assetTag;
				$thisData[$this->alias]['assoc_account'] = $assocAccount;
				
				// if a file was uploaded copy it so this
				if(isset($thisData['ExemptionFile']['file']['tmp_name'])
				and $thisData['ExemptionFile']['file']['tmp_name']
				and $thisData['ExemptionFile']['file']['error'] == 0)
				{
					$tmp_name = $thisData['ExemptionFile']['file']['tmp_name'];
					copy($tmp_name, $tmp_name.'-'.$i);
					$thisData['ExemptionFile']['file']['tmp_name'] = $tmp_name.'-'.$i;
				}
				
				$data[] = $thisData;
			}
			
			return $this->saveMany($data);
		}
		
		return parent::save($data, $validate, $fieldList);
	}
	
	public function multiselect_items($data = array(), $values = array())
	{
		$this->multiselectReferer = array();
		if(isset($data[$this->alias]['multiselect_referer']))
		{
			$this->multiselectReferer = unserialize($data[$this->alias]['multiselect_referer']);
		}
		
		$ids = array();
		if(isset($data['multiple']))
		{
			$ids = $data['multiple'];
		}
		
		$values = Hash::flatten($values);
		return $this->updateAll(
			$values,
			array($this->alias.'.id' => $ids)
		);
	}
	
	public function _buildIndexConditions($adAccountIds = array(), $assocAccountIds = array())
	{
		$conditions = array();
		$adConditions = array(
			'Exemption.primary_account_type' => 0,
			'Exemption.ad_account_id' => $adAccountIds,	
		);
		$assocConditions = array(
			'Exemption.primary_account_type' => 1,
			'Exemption.assoc_account_id' => $assocAccountIds,
		);
		
		if($adAccountIds and $assocAccountIds)
			$conditions = array(
				'OR' => array($adConditions, $assocConditions),
			);
		elseif($adAccountIds)
			$conditions = $adConditions;
		elseif($assocAccountIds)
			$conditions = $assocConditions;
		
		return $conditions;
	}
	
	public function snapshotStats()
	{
		$entities = $this->Snapshot_dynamicEntities();
		return $entities;
	}
	
	public function snapshotDashboardGetStats($snapshotKeyRegex = false, $start = false, $end = false)
	{
		return $this->Snapshot_dashboardStats($snapshotKeyRegex, $start, $end);
	}
}
