<?php

App::uses('ContactsDivisionsController', 'Contacts.Controller');

class DivisionsController extends ContactsDivisionsController
{
	public function index()
	{
		$exemptionTypes = $this->Division->Branch->Sac->AdAccount->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes'));
		
		parent::index();
	}
}
