<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */


App::uses('CommonAppController', 'Utilities.Controller');
class AppController extends CommonAppController
{
	public $components = array(
		'Auth' => array(
			'loginRedirect' => array('controller' => 'exemptions', 'action' => 'index', 'prefix' => false, 'plugin' => false),
		),
	);
	
	public $helpers = array(
		'Local',
		'Contacts.Contacts',
	);
	
	public $conditions = array();
	
	public function beforeFilter() 
	{
		// used to help with filtering exemptions in multiple places
		$title_prefix = false;
		if(!isset($this->passedArgs['list']))
		{
			$this->passedArgs['list'] = 'active';
		}
		if($this->passedArgs['list'] == 'active')
		{
			$title_prefix = __('Active');
		}
		elseif($this->passedArgs['list'] == 'expired')
		{
			$title_prefix = __('Expired');
		}
		elseif($this->passedArgs['list'] == 'reviewed')
		{
			$title_prefix = __('Reviewed');
		}
		elseif($this->passedArgs['list'] == 'notreviewed')
		{
			$title_prefix = __('Not Reviewed');
		}
		elseif($this->passedArgs['list'] == 'all')
		{
			$title_prefix = __('All');
		}
		$this->set('title_prefix', $title_prefix);
		parent::beforeFilter();
	}
}