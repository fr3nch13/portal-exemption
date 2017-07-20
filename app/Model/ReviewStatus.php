<?php
App::uses('AppModel', 'Model');

class ReviewStatus extends AppModel 
{	
	public $hasMany = array(
		'Exemption' => array(
			'className' => 'Exemption',
			'foreignKey' => 'exemption_type_id',
			'dependent' => false,
		)
	);
}
