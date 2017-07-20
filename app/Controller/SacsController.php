<?php

App::uses('ContactsSacsController', 'Contacts.Controller');

class SacsController extends ContactsSacsController
{
	public function index()
	{
		$exemptionTypes = $this->Sac->AdAccount->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes'));
		
		parent::index();
	}
}
