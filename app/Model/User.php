<?php
// app/Model/User.php

App::uses('AppModel', 'Model');

class User extends AppModel
{
	public $name = 'User';
	
	public $displayField = 'name';
	
	public $validate = array(
		'email' => array(
			'required' => array(
				'rule' => array('email'),
				'message' => 'A valid email adress is required',
			)
		),
		'role' => array(
			'valid' => array(
				'rule' => array('notBlank'),
				'message' => 'Please enter a valid role',
				'allowEmpty' => false,
			),
		),
	);
	
	public $hasOne = array(
		'UsersSetting' => array(
			'className' => 'UsersSetting',
			'foreignKey' => 'user_id',
		)
	);
	
	public $hasMany = array(
		'LoginHistory' => array(
			'className' => 'LoginHistory',
			'foreignKey' => 'user_id',
			'dependent' => true,
		),
		'ExemptionReviewedUser' => array(
			'className' => 'Exemption',
			'foreignKey' => 'reviewed_user_id',
			'dependent' => false,
		),
		'ExemptionAddedUser' => array(
			'className' => 'Exemption',
			'foreignKey' => 'added_user_id',
			'dependent' => false,
		),
		'ExemptionModifiedUser' => array(
			'className' => 'Exemption',
			'foreignKey' => 'modified_user_id',
			'dependent' => false,
		),
	);
	
	public $actsAs = array(
		'Snapshot.Stat' => array(
			'entities' => array(
				'all' => array(),
				'created' => array(),
				'modified' => array(),
				'active' => array(
					'conditions' => array(
						'User.active' => true,
					),
				),
			),
		),
    );
	
	// define the fields that can be searched
	public $searchFields = array(
		'User.name',
		'User.email',
	);
	
	// fields that are boolean and can be toggled
	public $toggleFields = array('active');
	
	// the path to the config file.
	public $configPath = false;
	
	// Any error relating to the config
	public $configError = false;
	
	// used to store info, because the photo name is changed.
	public $afterdata = false;
	
	public function beforeSave($options = array())
	{
		// hash the password before saving it to the database
		if (isset($this->data[$this->alias]['password']))
		{
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return parent::beforeSave($options);
	}
	
	public function afterSave($created = false, $options = array())
	{
		// if we edited ourselves
		if($this->id == AuthComponent::user('id'))
		{
			$user = $this->findById(AuthComponent::user('id'));
			CakeSession::write('Auth',$user);
		}
	}
	
	public function lastLogin($user_id = null)
	{
		if($user_id)
		{
			$this->id = $user_id;
			// callback false to aviod reupdating the session that was just created
			return $this->saveField('lastlogin', date('Y-m-d H:i:s'), array('callbacks' => false));
		}
		return false;
	}
	
	public function loginAttempt($input = false, $success = false, $user_id = false)
	{
		$email = false;
		if(isset($input['User']['email'])) 
		{
			$email = $input['User']['email'];
			if(!$user_id)
			{
				$user_id = $this->field('id', array('email' => $email));
			}
		}
		
		$data = array(
			'email' => $email,
			'user_agent' => env('HTTP_USER_AGENT'),
			'ipaddress' => env('REMOTE_ADDR'),
			'success' => $success,
			'user_id' => $user_id,
			'timestamp' => date('Y-m-d H:i:s'),
		);
		
		$this->LoginHistory->create();
		return $this->LoginHistory->save($data);
	}
	
	public function adminEmails()
	{
		return $this->emails('admin', true);
	}
	
	public function emails($role = false, $active = true)
	{
		$conditions = array(
			'active' => $active,
		);
		
		if($role)
		{
			$conditions['role'] = $role;
		}
		
		return $this->find('list', array(
			'recursive' => -1,
			'conditions' => $conditions,
			'fields' => array(
				'email',
			),
		));
	}
	
	public function userList($user_ids = array(), $recursive = 0)
	{
		// fill the user cache
		$_users = $this->find('all', array(
			'recursive' => $recursive,
			'conditions' => array(
				'User.id' => $user_ids,
			),
		));
		
		$users = array();
		
		foreach($_users as $user)
		{
			$user_id = $user['User']['id'];
			$users[$user_id] = $user; 
		}
		
		unset($_users);
		return $users;
	}
	
	public function availableRoles($user_id = false)
	{
		$this->id = $user_id;
		$originalRole = $this->field('role');
		$roles = $this->userRoles(); // returns a list of roles from hightest to lowest
		
		// automatically take out some roles that aren't available to any users
		if(isset($roles['api']))
			unset($roles['api']);
		
		// filter out the roles;
		foreach($roles as $role => $roleNice)
		{
			// the admin
			if($originalRole != $role)
			{
				unset($roles[$role]);
				continue;
			}
			return $roles;
		}
		
		return $roles;
	}
}

