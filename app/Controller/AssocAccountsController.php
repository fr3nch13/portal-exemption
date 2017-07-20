<?php
App::uses('ContactsAssocAccountsController', 'Contacts.Controller');

class AssocAccountsController extends ContactsAssocAccountsController
{
	public function index()
	{
		$exemptionTypes = $this->AssocAccount->Exemption->ExemptionType->find('list');
		$this->set(compact('exemptionTypes'));
		
		parent::index();
	}
}
