<?php 
// File: app/View/Exemptions/view.ctp

$page_options = array(
//	$this->Html->link(__('Mark Reviewed'), array('action' => 'reviewed', $exemption['Exemption']['id'])),
	$this->Html->link(__('Edit'), array('action' => 'edit', $exemption['Exemption']['id'])),
);

$details_left = array(
	array('name' => __('Type'), 'value' => $this->Html->link($exemption['ExemptionType']['shortname'], array('action' => 'index', $exemption['ExemptionType']['id']))),
	array('name' => __('Source'), 'value' => $this->Html->link($exemption['ExemptionSource']['shortname'], array('action' => 'source', $exemption['ExemptionSource']['id']))),
	array('name' => __('AD Account'), 'value' => $this->Html->link($exemption['AdAccount']['username'], array('controller' => 'ad_accounts', 'action' => 'view', $exemption['AdAccount']['id']))),
	array('name' => __('Associated Account'), 'value' => (isset($exemption['AssocAccount']['username'])?$this->Html->link($exemption['AssocAccount']['username'], array('controller' => 'assoc_accounts', 'action' => 'view', $exemption['AssocAccount']['id'])):false)),
	array('name' => __('Account Path'), 'value' => $this->Contacts->makePath($exemption)),
	array('name' => __('Sample Ticket'), 'value' => $exemption['Exemption']['example_ticket']),
	array('name' => __('Other Tickets'), 'value' => $exemption['Exemption']['tickets']),
	array('name' => __('Expiration Date'), 'value' => $this->Wrap->niceTime($exemption['Exemption']['expiration_date'])),
	array('name' => __('Expiration Email Sent?'), 'value' => $this->Wrap->yesNo($exemption['Exemption']['notified'])),
	array('name' => __('Created'), 'value' => $this->Wrap->niceTime($exemption['Exemption']['created'])),
);

$division = false;
// ad account
if($exemption['Exemption']['primary_account_type'] == 0)
{
	if(isset($exemption['AdAccount']['Division']['id']))
		$division = $this->Html->link($exemption['AdAccount']['Division']['sacshortnamename'], array('controller' => 'divisions', 'action' => 'view', $exemption['AdAccount']['Division']['id']));
}
// assoc account
elseif($exemption['Exemption']['primary_account_type'] == 1)
{
	if(isset($exemption['AssocAccount']['Division']['id']))
		$division = $this->Html->link($exemption['AssocAccount']['Division']['sacshortnamename'], array('controller' => 'divisions', 'action' => 'view', $exemption['AssocAccount']['Division']['id']));
}

$details_right = array(
	array('name' => __('Division'), 'value' => $division),
	array('name' => __('Serial Number'), 'value' => $exemption['Exemption']['serial_number']),
	array('name' => __('Asset Tag'), 'value' => $exemption['Exemption']['asset_tag']),
	array('name' => __('MAC Address'), 'value' => $exemption['Exemption']['mac_address']),
	array('name' => __('Location'), 'value' => $exemption['Exemption']['location']),
//	array('name' => __('Artifact'), 'value' => $this->Html->link($exemption['Exemption']['filename'], array('action' => 'download', $exemption['Exemption']['id']))),
	array('name' => __('Review Status'), 'value' => ($exemption['ReviewStatus']['name']?$exemption['ReviewStatus']['name']:__('Not Reviewed'))),
	array('name' => __('Review Changed By'), 'value' => $exemption['ExemptionReviewedUser']['name']),
	array('name' => __('Review Changed'), 'value' => $this->Wrap->niceTime($exemption['Exemption']['reviewed_date'])),
);

$stats = array(
);

$tabs = array(
	array(
		'key' => 'request_details',
		'title' => __('Request Details'),
		'content' => $this->Wrap->descView($exemption['Exemption']['request_details']),
	),
);

$stats[] = array(
	'id' => 'exemptionFiles',
	'name' => __('Exemption %s', __('Artifacts')), 
	'ajax_count_url' => array('controller' => 'exemption_files', 'action' => 'exemption', $exemption['Exemption']['id']),
	'tab' => array('tabs', count($tabs)), // the tab to display
);	
$tabs[] = array(
	'key' => 'exemptionFiles',
	'title' => __('Exemption %s', __('Artifacts')),
	'url' => array('controller' => 'exemption_files', 'action' => 'exemption', $exemption['Exemption']['id']),
);

$stats[] = array(
	'id' => 'tagsReport',
	'name' => __('Tags'), 
	'ajax_count_url' => array('plugin' => 'tags', 'controller' => 'tags', 'action' => 'tagged', 'exemption', $exemption['Exemption']['id']),
	'tab' => array('tabs', count($tabs)), // the tab to display
);	
$tabs[] = array(
	'key' => 'tags',
	'title' => __('Tags'),
	'url' => array('plugin' => 'tags', 'controller' => 'tags', 'action' => 'tagged', 'exemption', $exemption['Exemption']['id']),
);



echo $this->element('Utilities.page_compare', array(
	'page_title' => __('%s Details', __('Exemption')),
	'page_options' => $page_options,
	'details_left' => $details_left,
	'details_right' => $details_right,
	'stats' => $stats,
	'tabs_id' => 'tabs',
	'tabs' => $tabs,
));