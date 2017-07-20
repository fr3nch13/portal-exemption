<?php
App::uses('AppController', 'Controller');
/**
 * ExemptionFiles Controller
 *
 * @property ExemptionFile $ExemptionFile
 * @property PaginatorComponent $Paginator
 */
class ExemptionFilesController extends AppController 
{
	public function index() 
	{
		$this->Prg->commonProcess();
		
		$conditions = array(
		); 
		
		$this->ExemptionFile->recursive = 0;
		$this->paginate['conditions'] = $this->ExemptionFile->conditions($conditions, $this->passedArgs); 
		
		$this->paginate['order'] = array('ExemptionFile.created' => 'desc');
		$this->set('exemption_files', $this->paginate());
	}
	
	public function exemption($exemption_id = null) 
	{
		if (!$exemption =  $this->ExemptionFile->Exemption->read(null, $exemption_id)) 
		{
			throw new NotFoundException(__('Invalid %s', __('Exemption')));
		}
		$this->set('exemption', $exemption);
		
		$this->Prg->commonProcess();
		
		$conditions = array(
			'ExemptionFile.exemption_id' => $exemption_id,
		); 
		
		$this->ExemptionFile->Exemption->recursive = -1;
		$this->set('Exemption', $this->ExemptionFile->Exemption->read(null, $exemption_id));
		
		$this->ExemptionFile->recursive = 0;
		$this->paginate['conditions'] = $this->ExemptionFile->conditions($conditions, $this->passedArgs); 
		if(isset($this->request->params['ext']) and $this->request->params['ext'] === 'csv')
		{
			$this->paginate['limit'] = $this->ExemptionFile->find('count', array('conditions' => $this->paginate['conditions']));
			$this->layout = 'Utilities.../ajax_nodebug';
		}
		
		$this->paginate['order'] = array('ExemptionFile.created' => 'desc');
		$this->set('exemption_files', $this->paginate());
	}
	
	public function add($exemption_id = null) 
	{
		if (!$exemption =  $this->ExemptionFile->Exemption->read(null, $exemption_id)) 
		{
			throw new NotFoundException(__('Invalid %s', __('Exemption')));
		}
		$this->set('exemption', $exemption);
		
		$this->request->data[$this->ExemptionFile->alias]['exemption_id'] = $exemption_id;
		$this->request->data[$this->ExemptionFile->alias]['user_id'] = AuthComponent::user('id');
		
		if ($this->request->is('post')) 
		{
			$this->ExemptionFile->create();
			if ($this->ExemptionFile->save($this->request->data))
			{
				$this->Session->setFlash(__('The %s has been saved.', __('Exemption %s', __('File'))));
				return $this->redirect(array('controller' => 'exemptions', 'action' => 'view', $exemption_id, 'hash' => 'ui-tabs-1'));
			}
			else
			{
				$this->Session->setFlash(__('The %s could not be saved. Please, try again. (%s)', __('Exemption %s', __('File')), $this->ExemptionFile->modelError) );
			}
		}
	}
	
	public function edit($id = null) 
	{
		$this->ExemptionFile->recursive = -1;
		if (!$exemption_file = $this->ExemptionFile->read(null, $id))
		{
			throw new NotFoundException(__('Invalid %s', __('Exemption %s', __('File'))));
		}
		
		if ($this->request->is(array('post', 'put'))) 
		{
			if ($this->ExemptionFile->save($this->request->data))
			{
				$this->Session->setFlash(__('The 5s has been saved.', __('Exemption %s', __('File'))));
				return $this->redirect(array('controller' => 'exemptions', 'action' => 'view', $exemption_file['ExemptionFile']['exemption_id'], 'hash' => 'ui-tabs-1'));
			}
			else
			{
				$this->Session->setFlash(__('The %s could not be saved. Please, try again. (%s)', __('Exemption %s', __('File')), $this->ExemptionFile->modelError) );
			}
		}
		else
		{
			$this->request->data = $exemption_file;
		}
	}
	
	public function delete($id = null)
	{
		$this->ExemptionFile->recursive = -1;
		if (!$exemption_file = $this->ExemptionFile->read(null, $id))
		{
			throw new NotFoundException(__('Invalid %s', __('Exemption %s', __('File'))));
		}
		
		if ($this->ExemptionFile->delete())
		{
			$this->Session->setFlash(__('The %s has been deleted.', __('Exemption %s', __('File'))));
		}
		else
		{
			$this->Session->setFlash(__('The %s could not be deleted. Please, try again.', __('Exemption %s', __('File'))));
		}
		
		return $this->redirect(array('controller' => 'exemptions', 'action' => 'view', $exemption_file['ExemptionFile']['exemption_id'], 'hash' => 'ui-tabs-1'));
	}
}
