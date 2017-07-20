<?php
App::uses('ContactsDivision', 'Contacts.Model');

class Division extends ContactsDivision 
{	
	public $actsAs = array(
		'Snapshot.Stat' => array(
			'stats' => true, // must also have the snapshotStats method below for this to work.
			'entities' => array(
				'all' => array(),
				'created' => array(),
				'modified' => array(),
			),
		),
	);
	
	public function snapshotStats()
	{
		$entities = $this->Snapshot_dynamicEntities();
		return $entities;
	}
}
