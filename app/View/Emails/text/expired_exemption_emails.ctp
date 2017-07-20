<?php

$this->Html->setFull(true);
$this->Html->asText(true);

$page_options = array(
	$this->Html->link(__("View this %s", __('Exemption')), array('controller' => 'exemptions', 'action' => 'view', $exemption['Exemption']['id'])),
);

$details_blocks = array();

$details_blocks[1][1] = array(
	'title' => '',
	'details' => array(
		array('name' => __('Type'), 'value' => $this->Html->link($exemption['ExemptionType']['shortname'], array('action' => 'index', $exemption['ExemptionType']['id']))),
		array('name' => __('Source'), 'value' =>$exemption['ExemptionSource']['shortname']),
		array('name' => __('AD Account'), 'value' => ($exemption['AdAccount']['username']?$this->Html->link($exemption['AdAccount']['username'], array('controller' => 'ad_accounts', 'action' => 'view', $exemption['AdAccount']['id'])):false) ),
		array('name' => __('Associated Account'), 'value' => ($exemption['AssocAccount']['username']?$this->Html->link($exemption['AssocAccount']['username'], array('controller' => 'assoc_accounts', 'action' => 'view', $exemption['AssocAccount']['id'])):false) ),
		array('name' => __('Sample Ticket'), 'value' => $exemption['Exemption']['example_ticket']),
		array('name' => __('Other Tickets'), 'value' => $exemption['Exemption']['tickets']),
		array('name' => __('Expiration Date'), 'value' => $this->Wrap->niceTime($exemption['Exemption']['expiration_date'])),
		array('name' => __('Created'), 'value' => $this->Wrap->niceTime($exemption['Exemption']['created'])),
	),
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

$details_blocks[1][2] = array(
	'title' => '',
	'details' => array(
		array('name' => __('Division'), 'value' => $division),
		array('name' => __('Serial Number'), 'value' => $exemption['Exemption']['serial_number']),
		array('name' => __('Asset Tag'), 'value' => $exemption['Exemption']['asset_tag']),
		array('name' => __('MAC Address'), 'value' => $exemption['Exemption']['mac_address']),
		array('name' => __('Location'), 'value' => $exemption['Exemption']['location']),
//		array('name' => __('Artifact'), 'value' => $this->Html->link($exemption['Exemption']['filename'], array('action' => 'download', $exemption['Exemption']['id']))),
		array('name' => __('Review Status'), 'value' => ($exemption['ReviewStatus']['name']?$exemption['ReviewStatus']['name']:__('Not Reviewed'))),
		array('name' => __('Review Changed By'), 'value' => $exemption['ExemptionReviewedUser']['name']),
		array('name' => __('Review Changed'), 'value' => $this->Wrap->niceTime($exemption['Exemption']['reviewed_date'])),
	),
);

$out = $this->element('Utilities.email_text_view_columns', array(
	'page_title' => __('Subject - %s', $subject),
	'page_description' => $exemption['ExemptionType']['notification_details'],
	'details_blocks' => $details_blocks,
));

echo str_replace('&nbsp;', '  ', $out);
