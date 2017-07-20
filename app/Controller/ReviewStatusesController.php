<?php
App::uses('AppController', 'Controller');

class ReviewStatusesController extends AppController
{
	public $allowAdminDelete = true;

	public function admin_index()
	{
		$this->Prg->commonProcess();
		
		$conditions = array();
		
		$this->ReviewStatus->recursive = 0;
		$this->paginate['order'] = array('ReviewStatus.shortname' => 'asc');
		$this->paginate['conditions'] = $this->ReviewStatus->conditions($conditions, $this->passedArgs); 
		
		$this->set('reviewStatuses', $this->paginate());
	}
	
	public function admin_view($id = null) 
	{
		$options = array('conditions' => array('ReviewStatus.' . $this->Exemption->primaryKey => $id));
		if (!$exemption =  $this->ReviewStatus->find('first', $options)) 
		{
			throw new NotFoundException(__('Invalid %s', __('Review Status')));
		}
		$this->set('reviewStatus', $reviewStatus);
	}
	
	public function admin_add() 
	{
		if ($this->request->is('post')) 
		{
			$this->ReviewStatus->create();
			if ($this->ReviewStatus->save($this->request->data)) 
			{
				$this->Session->setFlash(__('The %s has been saved.', __('Review Status')));
				return $this->redirect(array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('The %s could not be saved. Please, try again.', __('Review Status')));
			}
		}
	}
	
	public function admin_edit($id = null) 
	{
		$options = array('conditions' => array('ReviewStatus.' . $this->ReviewStatus->primaryKey => $id));
		if (!$reviewStatus =  $this->ReviewStatus->find('first', $options)) 
		{
			throw new NotFoundException(__('Invalid %s', __('Review Status')));
		}
		
		if ($this->request->is(array('post', 'put'))) 
		{
			if ($this->ReviewStatus->save($this->request->data)) 
			{
				$this->Session->setFlash(__('The %s has been saved.', __('Review Status')));
				return $this->redirect(array('action' => 'index'));
			} 
			else 
			{
				$this->Session->setFlash(__('The %s could not be saved. Please, try again.', __('Review Status')));
			}
		} 
		else 
		{
			$this->request->data = $reviewStatus;
		}
	}
}
