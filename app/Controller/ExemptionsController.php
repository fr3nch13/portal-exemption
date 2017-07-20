<?php
App::uses('AppController', 'Controller');

class ExemptionsController extends AppController
{
	public function menu_main_type() 
	{
		if ($this->request->is('requested')) 
		{
			$exemptionTypes = $this->Exemption->ExemptionType->find('list');
			
			// format for the menu_items
			$items = array();
			
			foreach($exemptionTypes as $id => $name)
			{
				$items[] = array(
					'title' => $name,
					'url' => array('controller' => 'exemptions', 'action' => 'type', $id, 'admin' => false, 'plugin' => false)
				);
			}
			return $items;
		}
		
		$this->redirect(array('action' => 'index'));
	}
	
	public function menu_main_source() 
	{
		if ($this->request->is('requested')) 
		{
			$exemptionSources = $this->Exemption->ExemptionSource->find('list');
			
			// format for the menu_items
			$items = array();
			
			foreach($exemptionSources as $id => $name)
			{
				$items[] = array(
					'title' => $name,
					'url' => array('controller' => 'exemptions', 'action' => 'source', $id, 'admin' => false, 'plugin' => false)
				);
			}
			return $items;
		}
		
		$this->redirect(array('action' => 'source'));
	}
	
	public function menu_main_review_status() 
	{
		if ($this->request->is('requested')) 
		{
			$reviewStatus = $this->Exemption->ReviewStatus->find('list');
			
			// format for the menu_items
			$items = array();
			
			foreach($reviewStatus as $id => $name)
			{
				$items[] = array(
					'title' => $name,
					'url' => array('controller' => 'exemptions', 'action' => 'review_status', $id, 'admin' => false, 'plugin' => false)
				);
			}
			return $items;
		}
		
		$this->redirect(array('action' => 'review_status'));
	}
	
	public function db_block_overview()
	{
		$conditions = array();
		$conditions = array_merge($conditions, $this->_getListConditions());
		
		$exemptions = $this->Exemption->find('all', array('conditions' => $conditions));
		$this->set(compact('exemptions'));
	}
	
	public function db_block_sources()
	{
		$conditions = array();
		$conditions = array_merge($conditions, $this->_getListConditions());
		
		$exemptionSources = $this->Exemption->ExemptionSource->find('list');
		$exemptions = $this->Exemption->find('all', array(
			'recursive' => 0,
			'conditions' => $conditions,
		));
		$this->set(compact('exemptions', 'exemptionSources'));
	}
	
	public function db_block_review_statuses()
	{
		$conditions = array();
		$conditions = array_merge($conditions, $this->_getListConditions());
		
		$reviewStatuses =  $this->Exemption->ReviewStatus->find('list');
		$exemptions = $this->Exemption->find('all', array(
			'recursive' => 0,
			'conditions' => $conditions,
		));
		$this->set(compact('exemptions', 'reviewStatuses'));
	}
	
	public function db_block_review_statuses_trend()
	{
		$snapshotStats = $this->Exemption->snapshotDashboardGetStats('/^exemption\.review_status\-\d+$/');
		
		$this->set(compact('snapshotStats'));
	}
	
	public function db_block_types()
	{
		$conditions = array();
		$conditions = array_merge($conditions, $this->_getListConditions());
		
		$exemptionTypes = $this->Exemption->ExemptionType->find('list');
		$exemptions = $this->Exemption->find('all', array(
			'recursive' => 0,
			'conditions' => $conditions,
		));
		$this->set(compact('exemptions', 'exemptionTypes'));
	}
	
	public function dashboard()
	{
	}
	
	public function all() 
	{
		$exemptionTypes = $this->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes'));
	}
	
	public function index($stripped = false) 
	{
		$this->Prg->commonProcess();
		
		$conditions = array();
		$conditions = array_merge($conditions, $this->conditions);
		
		$conditions = array_merge($conditions, $this->_getListConditions());
		
		$this->Exemption->recursive = 0;

		$this->paginate['contain'] = array(
			'ExemptionType', 'ExemptionSource', 'ReviewStatus', 'ExemptionAddedUser',
			'AdAccount', 'AdAccount.Sac', 'AdAccount.Sac.Branch', 'AdAccount.Sac.Branch.Division', 'AdAccount.Sac.Branch.Division.Org',
			'AssocAccount', 'AssocAccount.AdAccount', 'AssocAccount.AdAccount.Sac', 'AssocAccount.AdAccount.Sac.Branch', 'AssocAccount.AdAccount.Sac.Branch.Division', 'AssocAccount.AdAccount.Sac.Branch.Division.Org',
		);
		
		$this->paginate['conditions'] = $this->Exemption->conditions($conditions, $this->passedArgs); 
		
		// show all
		if($stripped)
		{
			$this->paginate['limit'] = $this->paginate['maxLimit'] = $this->Exemption->find('count', array('conditions' => $this->paginate['conditions']));
		}
		
		$this->set('exemptions', $this->paginate());
		
		$exemptionTypes = $this->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes', 'exemptionType', 'stripped'));
	}
	
	public function org($org_id = null, $exemption_type_id = false, $stripped = false)  
	{
		if (!$org_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('ORG/IC')));
		}
		
		$org = $this->Exemption->AdAccount->Sac->Branch->Division->Org->find('first', array(
			'conditions' => array('Org.id' => $org_id),
		));
		if (!$org) 
		{
			throw new NotFoundException(__('Invalid %s', __('ORG/IC')));
		}
		$this->set('object', $org);
		
		$adAccountIds = $this->Exemption->AdAccount->idsForOrg($org_id);
		$assocAccountIds = $this->Exemption->AdAccount->AssocAccount->idsForOrg($org_id);
		
		$conditions = $this->Exemption->_buildIndexConditions($adAccountIds, $assocAccountIds);
		if($exemption_type_id) $conditions['Exemption.exemption_type_id'] = $exemption_type_id;
		$conditions = array_merge($conditions, $this->conditions);
		
		$this->conditions = $conditions;
		$this->index($stripped);
	}
	
	public function division($division_id = null, $exemption_type_id = false, $stripped = false)  
	{
		if (!$division_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('Division')));
		}
		
		$division = $this->Exemption->AdAccount->Sac->Branch->Division->find('first', array(
			'conditions' => array('Division.id' => $division_id),
			'contain' => array('Org'),
		));
		if (!$division) 
		{
			throw new NotFoundException(__('Invalid %s', __('Division')));
		}
		$this->set('object', $division);
		
		$ad_account_ids = $this->Exemption->AdAccount->idsForDivision($division_id);
		
		$conditions = $this->Exemption->_buildIndexConditions($ad_account_ids);
		if($exemption_type_id) $conditions['Exemption.exemption_type_id'] = $exemption_type_id;
		$conditions = array_merge($conditions, $this->conditions);
		
		$this->conditions = $conditions;
		$this->index($stripped);
	}
	
	public function branch($branch_id = null, $exemption_type_id = false, $stripped = false)  
	{
		if (!$branch_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('Branch')));
		}
		
		$branch = $this->Exemption->AdAccount->Sac->Branch->find('first', array(
			'conditions' => array('Branch.id' => $branch_id),
			'contain' => array('Division', 'Division.Org'),
		));
		if (!$branch) 
		{
			throw new NotFoundException(__('Invalid %s', __('Branch')));
		}
		$this->set('object', $branch);
		
		$ad_account_ids = $this->Exemption->AdAccount->idsForBranch($branch_id);
		
		$conditions = $this->Exemption->_buildIndexConditions($ad_account_ids);
		if($exemption_type_id) $conditions['Exemption.exemption_type_id'] = $exemption_type_id;
		$conditions = array_merge($conditions, $this->conditions);
		
		$this->conditions = $conditions;
		$this->index($stripped);
	}
	
	public function sac($sac_id = null, $exemption_type_id = false, $stripped = false)  
	{
		if (!$sac_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('SAC')));
		}
		
		$sac = $this->Exemption->AdAccount->Sac->find('first', array(
			'conditions' => array('Sac.id' => $sac_id),
			'contain' => array('Branch', 'Branch.Division', 'Branch.Division.Org'),
		));
		if (!$sac) 
		{
			throw new NotFoundException(__('Invalid %s', __('SAC')));
		}
		$this->set('object', $sac);
		
		$ad_account_ids = $this->Exemption->AdAccount->idsForSac($sac_id);
		
		$conditions = $this->Exemption->_buildIndexConditions($ad_account_ids);
		if($exemption_type_id) $conditions['Exemption.exemption_type_id'] = $exemption_type_id;
		$conditions = array_merge($conditions, $this->conditions);
		
		$this->conditions = $conditions;
		$this->index($stripped);
	}
	
	public function ad_account($ad_account_id = false, $exemption_type_id = false, $stripped = false) 
	{
		if (!$ad_account_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('AD Account')));
		}
		
		$adAccount = $this->Exemption->AdAccount->find('first', array(
			'conditions' => array('AdAccount.id' => $ad_account_id),
			'contain' => array('Sac', 'Sac.Branch', 'Sac.Branch.Division', 'Sac.Branch.Division.Org'),
		));
		if (!$adAccount) 
		{
			throw new NotFoundException(__('Invalid %s', __('AD Account')));
		}
		$this->set('object', $adAccount);
		
		$conditions = $this->Exemption->_buildIndexConditions($ad_account_id);
		if($exemption_type_id) $conditions['Exemption.exemption_type_id'] = $exemption_type_id;
		$conditions = array_merge($conditions, $this->conditions);
		
		$this->conditions = $conditions;
		$this->index($stripped);
	}
	
	public function assoc_account($assoc_account_id = false, $exemption_type_id = false, $stripped = false) 
	{
		if (!$assoc_account_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('Assoc Account')));
		}
		
		$assocAccount = $this->Exemption->AssocAccount->find('first', array(
			'conditions' => array('AssocAccount.id' => $assoc_account_id),
			'contain' => array('AdAccount', 'AdAccount.Sac', 'AdAccount.Sac.Branch', 'AdAccount.Sac.Branch.Division', 'AdAccount.Sac.Branch.Division.Org'),
		));
		if (!$assocAccount) 
		{
			throw new NotFoundException(__('Invalid %s', __('Assoc Account')));
		}
		$this->set('object', $assocAccount);
		
		$conditions = $this->Exemption->_buildIndexConditions(false, $assoc_account_id);
		if($exemption_type_id) $conditions['Exemption.exemption_type_id'] = $exemption_type_id;
		$conditions = array_merge($conditions, $this->conditions);
		
		$this->conditions = $conditions;
		$this->index($stripped);
	}
	
	public function source($exemption_source_id = false, $stripped = false) 
	{
		if (!$exemption_source_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('Source')));
		}
		
		$exemptionSource = $this->Exemption->ExemptionSource->find('first', array(
			'conditions' => array('ExemptionSource.id' => $exemption_source_id),
		));
		if (!$exemptionSource) 
		{
			throw new NotFoundException(__('Invalid %s', __('Source')));
		}
		$this->set('object', $exemptionSource);
		
		$conditions = array();
		
		$conditions['Exemption.exemption_source_id'] = $exemption_source_id;
		$page_title = __('%s %s - Source: %s', $this->viewVars['title_prefix'], __('Exemptions'), $exemptionSource['ExemptionSource']['shortname']);
		
		$this->set(compact('page_title', 'stripped'));
		$this->conditions = $conditions;
		$this->index($stripped);
	}
	
	public function review_status($review_status_id = false, $stripped = false) 
	{
		if (!$review_status_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('Review Status')));
		}
		
		$reviewStatus = $this->Exemption->ReviewStatus->find('first', array(
			'conditions' => array('ReviewStatus.id' => $review_status_id),
		));
		if (!$reviewStatus) 
		{
			throw new NotFoundException(__('Invalid %s', __('Review Status')));
		}
		$this->set('object', $reviewStatus);
		
		$conditions = array();
		 
		$page_title = __('%s %s - %s: %s', $this->viewVars['title_prefix'], __('Exemptions'), __('Review Status'), $reviewStatus['ReviewStatus']['name']);
		
		$this->set(compact('page_title', 'stripped'));
		$this->conditions = $conditions;
		$this->index($stripped);
	}
	
	public function type($exemption_type_id = false, $stripped = false) 
	{
		if (!$exemption_type_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('Type')));
		}
		
		$exemptionType = $this->Exemption->ExemptionType->find('first', array(
			'conditions' => array('ExemptionType.id' => $exemption_type_id),
		));
		if (!$exemptionType) 
		{
			throw new NotFoundException(__('Invalid %s', __('Type')));
		}
		$this->set('object', $exemptionType);
		
		$conditions = array();
		
		$conditions['Exemption.exemption_type_id'] = $exemption_type_id;
		$page_title = __('%s %s - Type: %s', $this->viewVars['title_prefix'], __('Exemptions'), $exemptionType['ExemptionType']['shortname']);
		
		$this->set(compact('page_title', 'stripped'));
		$this->conditions = $conditions;
		$this->index($stripped);
	}
	
	public function tag($tag_id = null)  
	{ 
		if (!$tag_id) 
		{
			throw new NotFoundException(__('Invalid %s', __('Tag')));
		}
		
		$tag = $this->Exemption->Tag->read(null, $tag_id);
		if (!$tag) 
		{
			throw new NotFoundException(__('Invalid %s', __('Tag')));
		}
		$this->set('object', $tag);
		
		$conditions = array();
		
		$conditions[] = $this->Exemption->Tag->Tagged->taggedSql($tag['Tag']['keyname'], 'Exemption');
		
		$this->conditions = $conditions;
		$this->index();
	}
	
	public function view($id = null) 
	{
		$this->Exemption->id = $id;
		$this->Exemption->recursive = 0;
		$this->Exemption->contain(array_merge(
			array_keys($this->Exemption->belongsTo), 
			array(
				'AdAccount', 'AdAccount.Sac', 'AdAccount.Sac.Branch', 'AdAccount.Sac.Branch.Division', 'AdAccount.Sac.Branch.Division.Org',
				'AssocAccount.AdAccount', 'AssocAccount.AdAccount.Sac', 'AssocAccount.AdAccount.Sac.Branch', 'AssocAccount.AdAccount.Sac.Branch.Division', 'AssocAccount.AdAccount.Sac.Branch.Division.Org',
			)
		));
		
		if (!$exemption = $this->Exemption->read(null, $id))
		{
			throw new NotFoundException(__('Invalid %s', __('Exemption')));
		}
		
		$this->set('exemption', $exemption);
	}
	
	public function add() 
	{
		
		// add validation rules when adding/editing an exemption
		$this->Exemption->validator()->add('exemption_type', 'required', array('rule' => 'notBlank'));
		$this->Exemption->validator()->add('ad_account', 'required', array('rule' => 'notBlank'));
		$this->Exemption->validator()->add('expiration_date', 'required', array('rule' => 'notBlank'));
		
		
		if ($this->request->is('post')) 
		{
			$this->Exemption->create();
			$this->request->data['Exemption']['added_user_id'] = AuthComponent::user('id');
			if(isset($this->data['Exemption']['review_status_id']) and $this->data['Exemption']['review_status_id'] != '')
			{
				$this->request->data['Exemption']['reviewed_user_id'] = AuthComponent::user('id');
				$this->request->data['Exemption']['reviewed_date'] = date('Y-m-d H:i:s');
			}
			
			if ($this->Exemption->save($this->request->data)) 
			{
				$this->Flash->success(__('The %s has been saved.', __('Exemption')));
				return $this->redirect(array('action' => 'view', $this->Exemption->id));
			}
			else 
			{
				$this->Flash->error(__('The %s could not be saved. Please, try again.', __('Exemption')));
			}
		}
		
		$exemptionTypes = $this->Exemption->ExemptionType->typeFormList();
		$exemptionSources = $this->Exemption->ExemptionSource->typeFormList();
		$reviewStatuses = $this->Exemption->ReviewStatus->typeFormList();
		
		$this->set(compact('exemptionTypes', 'exemptionSources', 'reviewStatuses'));
	}
	
	public function add_waiver_form() 
	{
		
		// add validation rules when adding/editing an exemption
		$this->Exemption->validator()
			->add('file', 'required', array(
				'rule' => 'notBlank',
			))
			->add('file', 'required', array(
				'rule' => array('extension', array('pdf')), 
				'message' => __('Only pdf files') 
			));
		
		if ($this->request->is('post')) 
		{
			$this->Exemption->create();
			$this->request->data['Exemption']['added_user_id'] = AuthComponent::user('id');
			
			if ($this->Exemption->saveFromWaiver($this->request->data)) 
			{
				$this->Session->setFlash(__('The %s has been saved.', __('Exemption')));
				return $this->redirect(array('action' => 'index'));
			}
			else 
			{
				$this->Session->setFlash(__('The %s could not be saved. Please, try again.', __('Exemption')));
			}
		}
	}
	
	public function edit($id = null)
	{
		$this->Exemption->id = $id;
		$this->Exemption->recursive = 0;
		$this->Exemption->contain(array_merge(
			array_keys($this->Exemption->belongsTo), 
			array(
				'AdAccount', 'AdAccount.Sac', 'AdAccount.Sac.Branch', 'AdAccount.Sac.Branch.Division', 'AdAccount.Sac.Branch.Division.Org',
				'AssocAccount.AdAccount', 'AssocAccount.AdAccount.Sac', 'AssocAccount.AdAccount.Sac.Branch', 'AssocAccount.AdAccount.Sac.Branch.Division', 'AssocAccount.AdAccount.Sac.Branch.Division.Org',
			)
		));
		
		if (!$exemption = $this->Exemption->read(null, $id))
		{
			throw new NotFoundException(__('Invalid %s', __('Exemption')));
		}
		
		// add validation rules when adding/editing an exemption
		$this->Exemption->validator()->add('exemption_type', 'required', array('rule' => 'notBlank'));
		$this->Exemption->validator()->add('ad_account', 'required', array('rule' => 'notBlank'));
		$this->Exemption->validator()->add('expiration_date', 'required', array('rule' => 'notBlank'));
		
		if ($this->request->is(array('post', 'put')))
		{
			if(isset($this->data['Exemption']['review_status_id']) and isset($exemption['Exemption']['review_status_id']))
			{
				if($this->data['Exemption']['review_status_id'] != $exemption['Exemption']['review_status_id'])
				{
					$this->request->data['Exemption']['reviewed_user_id'] = AuthComponent::user('id');
					$this->request->data['Exemption']['reviewed_date'] = date('Y-m-d H:i:s');
				}
			}
			
			$this->request->data['Exemption']['modified_user_id'] = AuthComponent::user('id');
			if ($this->Exemption->save($this->request->data))
			{
				$this->Flash->success(__('The %s has been saved.', __('Exemption')));
				return $this->redirect(array('action' => 'view', $this->Exemption->id));
			} 
			else
			{
				$this->Flash->error(__('The %s could not be saved. Please, try again.', __('Exemption')));
			}
		}
		else
		{
			$this->request->data = $exemption;
		}
		
		$exemptionTypes = $this->Exemption->ExemptionType->typeFormList();
		$exemptionSources = $this->Exemption->ExemptionSource->typeFormList();
		$reviewStatuses = $this->Exemption->ReviewStatus->typeFormList();
		
		$this->set(compact('exemptionTypes', 'exemptionSources', 'reviewStatuses'));
	}
	
	public function reviewed($id = null)
	{
		$options = array('conditions' => array('Exemption.' . $this->Exemption->primaryKey => $id));
		if (!$exemption =  $this->Exemption->find('first', $options)) 
		{
			throw new NotFoundException(__('Invalid %s', __('Exemption')));
		}
		
		if ($this->request->is(array('post', 'put')))
		{
			$this->request->data['Exemption']['reviewed_user_id'] = AuthComponent::user('id');
			$this->request->data['Exemption']['reviewed_date'] = date('Y-m-d H:i:s');
			if ($this->Exemption->save($this->request->data))
			{
				$this->Session->setFlash(__('The %s has been reviewed.', __('Exemption')));
				return $this->redirect(array('action' => 'index'));
			} 
			else
			{
				$this->Session->setFlash(__('The %s could not be reviewed. Please, try again.', __('Exemption')));
			}
		}
		else
		{
			$this->request->data = $exemption;
		}
		
		$reviewStatuses = $this->Exemption->ReviewStatus->find('list');
		$this->set(compact('reviewStatuses'));
	}
	
	public function multiselect()
	{
		if(!$this->request->is('post'))
		{
			throw new MethodNotAllowedException();
		}
		
		// forward to a page where the user can choose a value
		$redirect = false;
		if(isset($this->request->data['multiple']))
		{
			$ids = array();
			foreach($this->request->data['multiple'] as $id => $selected) { if($selected) $ids[$id] = $id; }
			$this->request->data['multiple'] = $this->Exemption->find('list', array(
				'fields' => array('Exemption.id', 'Exemption.id'),
				'conditions' => array('Exemption.id' => $ids),
				'recursive' => -1,
			));
		}
		
		if($this->request->data['Exemption']['multiselect_option'] == 'primary_account_type_ad')
		{
			// update a saveMany, instead of an updateAll
			$data = array();
			foreach($this->request->data['multiple'] as $id) 
			{
				$data[$id] = array('id' => $id, 'primary_account_type' => 0);
			}
			
			if($data and $this->Exemption->saveMany($data))
			{
				$this->Flash->success(__('The %s were updated.', __('Exemptions')));
				return $this->redirect($this->referer());
			}
			
			$this->Flash->error(__('The %s were NOT updated.', __('Exemptions')));
			return $this->redirect($this->referer());
		}
		elseif($this->request->data['Exemption']['multiselect_option'] == 'primary_account_type_assoc')
		{
			// update a saveMany, instead of an updateAll
			$data = array();
			foreach($this->request->data['multiple'] as $id) 
			{
				$data[$id] = array('id' => $id, 'primary_account_type' => 1);
			}
			
			if($data and $this->Exemption->saveMany($data))
			{
				$this->Flash->success(__('The %s were updated.', __('Exemptions')));
				return $this->redirect($this->referer());
			}
			
			$this->Flash->error(__('The %s were NOT updated.', __('Exemptions')));
			return $this->redirect($this->referer());
		}
		elseif($this->request->data['Exemption']['multiselect_option'] == 'review_status')
		{
			$redirect = array('action' => 'multiselect_review_status');
		}
		
		if($redirect)
		{
			Cache::write('Multiselect_'.$this->Exemption->alias.'_'. AuthComponent::user('id'), $this->request->data, 'sessions');
			$this->bypassReferer = true;
			return $this->redirect($redirect);
		}
		
		if($this->Exemption->multiselect($this->request->data))
		{
			$this->Flash->success(__('The %s were updated.', __('Exemptions')));
			return $this->redirect($this->referer());
		}
		
		$this->Flash->error(__('The %s were NOT updated.', __('Exemptions')));
		$this->redirect($this->referer());
	}
	
	public function multiselect_review_status()
	{
		$sessionData = Cache::read('Multiselect_'.$this->Exemption->alias.'_'. AuthComponent::user('id'), 'sessions');
		if($this->request->is('post') || $this->request->is('put')) 
		{
			$multiselect_value = (isset($this->request->data['Exemption']['review_status_id'])?$this->request->data['Exemption']['review_status_id']:0);
			if($multiselect_value)
			{
				if($this->Exemption->multiselect_items($sessionData, $this->request->data)) 
				{
					Cache::delete('Multiselect_'.$this->Exemption->alias.'_'. AuthComponent::user('id'), 'sessions');
					$this->Flash->success(__('The %s were updated.', __('Exemptions')));
					return $this->redirect($this->Exemption->multiselectReferer());
				}
				else
				{
					$this->Flash->error(__('The %s were NOT updated.', __('Exemptions')));
				}
			}
			else
			{
				$this->Flash->default(__('Please select an %s', __('Review Status')));
			}
		}
		
		$selected_items = array();
		if(isset($sessionData['multiple']))
		{
			$selected_items = $this->Exemption->find('list', array(
				'conditions' => array(
					'Exemption.id' => $sessionData['multiple'],
				),
				'fields' => array('Exemption.id', 'Exemption.id'),
			));
		}
		
		$this->set('selected_items', $selected_items);
		
		$this->set('reviewStatuses', $this->Exemption->ReviewStatus->typeFormList());
	}
	
	public function admin_delete($id = null) 
	{
		$this->Exemption->id = $id;
		if (!$this->Exemption->exists()) {
			throw new NotFoundException(__('Invalid %s', __('Exemption')));
		}
		
		if ($this->Exemption->delete())
		{
			$this->Flash->success(__('The %s has been deleted.', __('Exemption')));
		}
		else
		{
			$this->Flash->error(__('The %s could not be deleted. Please, try again.', __('Exemption')));
		}
		return $this->redirect($this->referer());
	}
	
	public function _getListConditions()
	{
		$conditions = array();
		
		if(!isset($this->passedArgs['list']))
		{
			$this->passedArgs['list'] = 'active';
		}
		if($this->passedArgs['list'] == 'active')
		{
			$conditions['Exemption.expiration_date >'] = date('Y-m-d H:i:s');
		}
		elseif($this->passedArgs['list'] == 'expired')
		{
			$conditions['Exemption.expiration_date <'] = date('Y-m-d H:i:s');
		}
		elseif($this->passedArgs['list'] == 'reviewed')
		{
			$conditions['Exemption.review_status_id >'] = 0;
		}
		elseif($this->passedArgs['list'] == 'notreviewed')
		{
			$conditions['Exemption.review_status_id'] = 0;
		}
		
		return $conditions;
	}
}
