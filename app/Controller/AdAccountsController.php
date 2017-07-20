<?php

App::uses('ContactsAdAccountsController', 'Contacts.Controller');

class AdAccountsController extends ContactsAdAccountsController
{
	public function index($id = false)
	{
		$exemptionTypes = $this->AdAccount->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes'));
		
		parent::index();
	}
	
	public function division($division_id = null)  
	{ 
		parent::division($division_id);
		$adAccounts = $this->viewVars['adAccounts'];
		
		$exemptionTypes = $this->AdAccount->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes'));
		
		$count = 0;
		foreach($adAccounts as $i => $adAccount)
		{
			$count = 0;
			foreach($exemptionTypes as $exemptionType_id => $exemptionType_shortname)
			{
				$adAccounts[$i]['ExemptionType.id.'. $exemptionType_id] = $this->AdAccount->Exemption->find('count', array(
					'conditions' => array(
						'Exemption.ad_account_id' => $adAccount['AdAccount']['id'],
						'Exemption.exemption_type_id' => $exemptionType_id,
					),
				));
			}
		}
		
		$this->set('adAccounts', $adAccounts);
	}
	
	public function tag($tag_id = null)  
	{ 
		parent::division($division_id);
		$adAccounts = $this->viewVars['adAccounts'];
		
		$exemptionTypes = $this->AdAccount->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes'));
		
		$count = 0;
		foreach($adAccounts as $i => $adAccount)
		{
			$count = 0;
			foreach($exemptionTypes as $exemptionType_id => $exemptionType_shortname)
			{
				$adAccounts[$i]['ExemptionType.id.'. $exemptionType_id] = $this->AdAccount->Exemption->find('count', array(
					'conditions' => array(
						'Exemption.ad_account_id' => $adAccount['AdAccount']['id'],
						'Exemption.exemption_type_id' => $exemptionType_id,
					),
				));
			}
		}
		
		$this->set('adAccounts', $adAccounts);
	}
}
