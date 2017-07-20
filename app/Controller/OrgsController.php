<?php

App::uses('ContactsOrgsController', 'Contacts.Controller');

class OrgsController extends ContactsOrgsController
{
	public function index()
	{
		$exemptionTypes = $this->Org->Division->Branch->Sac->AdAccount->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes'));
		
		parent::index();
	}
}
