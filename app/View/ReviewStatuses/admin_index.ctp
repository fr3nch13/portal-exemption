<?php 
// File: app/View/ReviewStatuses/index.ctp


$page_options = array(
	$this->Html->link(__('Add %s', __('Review Status')), array('action' => 'add')),
);

// content
$th = array(
	'ReviewStatus.name' => array('content' => __('Name'), 'options' => array('sort' => 'ReviewStatus.name')),
	'ReviewStatus.sendemail' => array('content' => __('Send Email?'), 'options' => array('sort' => 'ReviewStatus.sendemail')),
	'ReviewStatus.mon' => array('content' => __('Mon'), 'options' => array('sort' => 'ReviewStatus.mon')),
	'ReviewStatus.tue' => array('content' => __('Tues'), 'options' => array('sort' => 'ReviewStatus.tue')),
	'ReviewStatus.wed' => array('content' => __('Wed'), 'options' => array('sort' => 'ReviewStatus.wed')),
	'ReviewStatus.thu' => array('content' => __('Thurs'), 'options' => array('sort' => 'ReviewStatus.thu')),
	'ReviewStatus.fri' => array('content' => __('Fri'), 'options' => array('sort' => 'ReviewStatus.fri')),
	'ReviewStatus.sat' => array('content' => __('Sat'), 'options' => array('sort' => 'ReviewStatus.sat')),
	'ReviewStatus.sun' => array('content' => __('Sun'), 'options' => array('sort' => 'ReviewStatus.sun')),
	'ReviewStatus.notify_time' => array('content' => __('Send Email At'), 'options' => array('sort' => 'ReviewStatus.notify_time')),
	'ReviewStatus.notify_email' => array('content' => __('Notification Email'), 'options' => array('sort' => 'ReviewStatus.notify_email')),
	'actions' => array('content' => __('Actions'), 'options' => array('class' => 'actions')),
);

$td = array();
foreach ($reviewStatuses as $i => $reviewStatus)
{
	$td[$i] = array(
		$reviewStatus['ReviewStatus']['name'],
		$this->Wrap->yesNo($reviewStatus['ReviewStatus']['sendemail']),
		$this->Wrap->check($reviewStatus['ReviewStatus']['mon']),
		$this->Wrap->check($reviewStatus['ReviewStatus']['tue']),
		$this->Wrap->check($reviewStatus['ReviewStatus']['wed']),
		$this->Wrap->check($reviewStatus['ReviewStatus']['thu']),
		$this->Wrap->check($reviewStatus['ReviewStatus']['fri']),
		$this->Wrap->check($reviewStatus['ReviewStatus']['sat']),
		$this->Wrap->check($reviewStatus['ReviewStatus']['sun']),
		$this->Local->emailTimes($reviewStatus['ReviewStatus']['notify_time']),
		$reviewStatus['ReviewStatus']['notify_email'],
		array(
			$this->Html->link(__('Edit'), array('action' => 'edit', $reviewStatus['ReviewStatus']['id'])).
			$this->Html->link(__('Delete'), array('action' => 'delete', $reviewStatus['ReviewStatus']['id']),array('confirm' => 'Are you sure?')), 
			array('class' => 'actions'),
		),
	);
}

echo $this->element('Utilities.page_index', array(
	'page_title' => __('Review Statuses'),
	'page_options' => $page_options,
	'th' => $th,
	'td' => $td,
));