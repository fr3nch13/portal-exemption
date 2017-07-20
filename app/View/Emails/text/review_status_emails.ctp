<?php 
// File: app/View/Emails/text/review_status_emails.ctp

$this->Html->setFull(true);
$this->Html->asText(true);

$page_options = array();

// content
$th = array(
	'Exemption.id' => array('content' => __('ID'), 'options' => array('sort' => 'Exemption.id')),
	'AdAccount.username' => array('content' => __('AD Username'), 'options' => array('sort' => 'AdAccount.username')),
	'ExemptionType.shortname' => array('content' => __('%s Type', __('Exemption')), 'options' => array('sort' => 'ExemptionType.shortname')),
	'ExemptionSource.shortname' => array('content' => __('%s Source', __('Exemption')), 'options' => array('sort' => 'ExemptionSource.shortname')),
	'AssocAccount.username' => array('content' => __('Associated Account'), 'options' => array('sort' => 'AssocAccount.username')),
	'Exemption.asset_tag' => array('content' => __('Asset Tag'), 'options' => array('sort' => 'Exemption.asset_tag')),
	'ExemptionFileLatest.filename' => array('content' => __('Latest Artifact')),
	'Exemption.expiration_date' => array('content' => __('Expiration Date'), 'options' => array('sort' => 'Exemption.expiration_date')),
//	'ReviewStatus.name' => array('content' => __('Review Status'), 'options' => array('sort' => 'ReviewStatus.name')),
	'actions' => array('content' => __('Actions'), 'options' => array('class' => 'actions')),
);


$td = array();
foreach ($exemptions as $i => $exemption)
{
	$actions = array(
		$this->Html->link(__('view'), array('action' => 'view', $exemption['Exemption']['id'])),
	);
	
	$exemptionFileLatest = false;
	if(isset($exemption['ExemptionFileLatest']['id']))
		$exemptionFileLatest = $this->Html->link($exemption['ExemptionFileLatest']['filename'], array('controller' => 'exemption_files', 'action' => 'download', $exemption['ExemptionFileLatest']['id']));
	
	$td[$i] = array(
		$this->Html->link($exemption['Exemption']['id'], array('action' => 'view', $exemption['Exemption']['id'])),
		$this->Html->link($exemption['AdAccount']['username'], array('controller' => 'ad_accounts', 'action' => 'view', $exemption['AdAccount']['id'])),
		$this->Html->link($exemption['ExemptionType']['shortname'], $this->Html->urlModify(array(0 => $exemption['ExemptionType']['id']))),
		$this->Html->link($exemption['ExemptionSource']['shortname'], array('action' => 'source', $exemption['ExemptionSource']['id'])),
		$exemption['AssocAccount']['username'],
		$exemption['Exemption']['asset_tag'],
		$exemptionFileLatest,
		$this->Wrap->niceTime($exemption['Exemption']['expiration_date']),
//		$exemption['ReviewStatus']['name'],
		//$active,
		array(
			implode("\n", $actions),
			array('class' => 'actions'),
		),
	);
}

echo $this->element('Utilities.email_text_index', array(
	'instructions' => $review_status['ReviewStatus']['instructions'],
	'page_title' => __('%s Assigned to %s: %s', __('Exemptions'), __('Review Status'), $review_status['ReviewStatus']['name']),
//	'page_description' => $review_status['ReviewStatus']['instructions'],
	'page_options' => $page_options,
	'th' => $th,
	'td' => $td,
));