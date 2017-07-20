<?php
App::uses('AppController', 'Controller');

class ExemptionTypesController extends AppController
{
	public $allowAdminDelete = true;

	public function admin_index()
	{
		$this->Prg->commonProcess();
		
		$conditions = array();
		
		$this->ExemptionType->recursive = 0;
		$this->paginate['order'] = array('ExemptionType.shortname' => 'asc');
		$this->paginate['conditions'] = $this->ExemptionType->conditions($conditions, $this->passedArgs); 
		
		$this->set('exemptionTypes', $this->paginate());
	}
	
	public function admin_view($id = null) 
	{
		$options = array('conditions' => array('ExemptionType.' . $this->Exemption->primaryKey => $id));
		if (!$exemption =  $this->ExemptionType->find('first', $options)) 
		{
			throw new NotFoundException(__('Invalid %s Type', __('Exemption')));
		}
		$this->set('exemptionType', $exemptionType);
	}
	
	public function admin_add() 
	{
		if ($this->request->is('post')) 
		{
			$this->ExemptionType->create();
			if ($this->ExemptionType->save($this->request->data)) 
			{
				$this->Session->setFlash(__('The %s type has been saved.', __('Exemption')));
				return $this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('The %s type could not be saved. Please, try again.', __('Exemption')));
			}
		}
	}
	
	public function admin_edit($id = null) 
	{
		$options = array('conditions' => array('ExemptionType.' . $this->ExemptionType->primaryKey => $id));
		if (!$exemptionType =  $this->ExemptionType->find('first', $options)) 
		{
			throw new NotFoundException(__('Invalid %s Type', __('Exemption')));
		}
		
		if ($this->request->is(array('post', 'put'))) 
		{
			if ($this->ExemptionType->save($this->request->data)) 
			{
				$this->Session->setFlash(__('The %s type has been saved.', __('Exemption')));
				return $this->redirect(array('action' => 'index'));
			} 
			else 
			{
				$this->Session->setFlash(__('The %s type could not be saved. Please, try again.', __('Exemption')));
			}
		} 
		else 
		{
			$this->request->data = $exemptionType;
		}
	}
}
