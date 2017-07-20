<?php

App::uses('ContactsBranchesController', 'Contacts.Controller');

class BranchesController extends ContactsBranchesController
{
	public function db_block_overview()
	{
		$branches = $this->Branch->find('all');
		$this->set(compact('branches'));
	}
	
	public function index()
	{
		$exemptionTypes = $this->Branch->Sac->AdAccount->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes'));
		
		parent::index();
	}
}
