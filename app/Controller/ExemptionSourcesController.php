<?php
App::uses('AppController', 'Controller');

class ExemptionSourcesController extends AppController
{
	public $allowAdminDelete = true;

	public function admin_index()
	{
		$this->Prg->commonProcess();
		
		$conditions = array();
		
		$this->ExemptionSource->recursive = 0;
		$this->paginate['order'] = array('ExemptionSource.shortname' => 'asc');
		$this->paginate['conditions'] = $this->ExemptionSource->conditions($conditions, $this->passedArgs); 
		
		$this->set('exemptionSources', $this->paginate());
	}
	
	public function admin_view($id = null) 
	{
		$options = array('conditions' => array('ExemptionSource.' . $this->Exemption->primaryKey => $id));
		if (!$exemption =  $this->ExemptionSource->find('first', $options)) 
		{
			throw new NotFoundException(__('Invalid %s Source', __('Exemption')));
		}
		$this->set('exemptionSource', $exemptionSource);
	}
	
	public function admin_add() 
	{
		if ($this->request->is('post')) 
		{
			$this->ExemptionSource->create();
			if ($this->ExemptionSource->save($this->request->data)) 
			{
				$this->Session->setFlash(__('The %s Source has been saved.', __('Exemption')));
				return $this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('The %s Source could not be saved. Please, try again.', __('Exemption')));
			}
		}
	}
	
	public function admin_edit($id = null) 
	{
		$options = array('conditions' => array('ExemptionSource.' . $this->ExemptionSource->primaryKey => $id));
		if (!$exemptionSource =  $this->ExemptionSource->find('first', $options)) 
		{
			throw new NotFoundException(__('Invalid %s Source', __('Exemption')));
		}
		
		if ($this->request->is(array('post', 'put'))) 
		{
			if ($this->ExemptionSource->save($this->request->data)) 
			{
				$this->Session->setFlash(__('The %s Source has been saved.', __('Exemption')));
				return $this->redirect(array('action' => 'index'));
			} 
			else 
			{
				$this->Session->setFlash(__('The %s Source could not be saved. Please, try again.', __('Exemption')));
			}
		} 
		else 
		{
			$this->request->data = $exemptionSource;
		}
	}
}
