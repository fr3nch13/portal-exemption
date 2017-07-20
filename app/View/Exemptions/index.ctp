<?php 
// File: app/View/Exemptions/index.ctp

$hide_options = (isset($hide_options)?$hide_options:false);

$title_suffix = '';
$page_options = array();
$page_options2 = array();

if(isset($exemptionType['ExemptionType']['shortname']))
	$title_suffix = __('Type: %s', $exemptionType['ExemptionType']['shortname']);

if(!$stripped and !$hide_options)
{
	$page_options['add'] = $this->Html->link(__('Add Exemption'), array('action' => 'add', 'admin' => false));

	$page_options2['list_all'] = $this->Html->link(__('View %s', __('All')), $this->Html->urlModify(array('list' => 'all')), array('class' => 'tab-hijack'));
	$page_options2['list_active'] = $this->Html->link(__('View %s', __('Active')), $this->Html->urlModify(array('list' => 'active')), array('class' => 'tab-hijack'));
	$page_options2['list_expired'] = $this->Html->link(__('View %s', __('Expired')), $this->Html->urlModify(array('list' => 'expired')), array('class' => 'tab-hijack'));
	$page_options2['list_reviewed'] = $this->Html->link(__('View %s', __('Reviewed')), $this->Html->urlModify(array('list' => 'reviewed')), array('class' => 'tab-hijack'));
	$page_options2['list_notreviewed'] = $this->Html->link(__('View %s', __('Not Reviewed')), $this->Html->urlModify(array('list' => 'notreviewed')), array('class' => 'tab-hijack'));
}

// content
$th = array(
	'Exemption.id' => array('content' => __('ID'), 'options' => array('sort' => 'Exemption.id')),
	'ExemptionType.shortname' => array('content' => __('%s Type', __('Exemption')), 'options' => array('sort' => 'ExemptionType.shortname')),
	'ExemptionSource.shortname' => array('content' => __('%s Source', __('Exemption')), 'options' => array('sort' => 'ExemptionSource.shortname')),
	'AdAccount.username' => array('content' => __('AD Username'), 'options' => array('sort' => 'Exemption.ad_account_id')),
	'AssocAccount.username' => array('content' => __('Associated Account'), 'options' => array('sort' => 'Exemption.assoc_account_id')),
	'AdAccount.Sac.Branch.Division.shortname' => array('content' => __('Account Path')),
	'Exemption.asset_tag' => array('content' => __('Asset Tag'), 'options' => array('sort' => 'Exemption.asset_tag')),
	'ExemptionFileLatest.filename' => array('content' => __('Latest Artifact')),
	'Exemption.expiration_date' => array('content' => __('Expiration Date'), 'options' => array('sort' => 'Exemption.expiration_date')),
	'Exemption.notified' => array('content' => __('Expiration Email Sent?'), 'options' => array('sort' => 'Exemption.notified')),
	'ReviewStatus.name' => array('content' => __('Review Status'), 'options' => array('sort' => 'ReviewStatus.name')),
	'ExemptionAddedUser.name' => array('content' => __('Added By'), 'options' => array('sort' => 'ExemptionAddedUser.name')),
	'Exemption.created' => array('content' => __('Added'), 'options' => array('sort' => 'Exemption.created')),
	'Exemption.modified' => array('content' => __('Edited'), 'options' => array('sort' => 'Exemption.modified')),
	'actions' => array('content' => __('Actions'), 'options' => array('class' => 'actions')),
	'multiselect' => true,
);

if($stripped)
{
	foreach($th as $i => $_th)
	{
		if(isset($_th['options']['sort']))
			unset($th[$i]['options']['sort']);
	}
}

$td = array();
foreach ($exemptions as $i => $exemption)
{
	$actions = array(
//		$this->Html->link(__('Change Review Status'), array('action' => 'reviewed', $exemption['Exemption']['id'])),
		$this->Html->link(__('Edit'), array('action' => 'edit', $exemption['Exemption']['id'], 'admin' => false)),
	);
	
	if(AuthComponent::user('role') == 'admin')
	{
		$actions[] = $this->Html->link(__('Delete'), array('action' => 'delete', $exemption['Exemption']['id'], 'admin' => true), array('confirm' => 'Are you sure?'));
	}
	
	$exemptionFileLatest = false;
	if(isset($exemption['ExemptionFileLatest']['id']))
		$exemptionFileLatest = $this->Html->link($exemption['ExemptionFileLatest']['filename'], array('controller' => 'exemption_files', 'action' => 'download', $exemption['ExemptionFileLatest']['id'], 'admin' => false));
	
	// highlight which account the exemption is directly associated to
	$assoc_class = false;
	$ad_class = false;
	$highlight = false;
	if(isset($exemption['Exemption']['primary_account_type']))
	{
		if($exemption['Exemption']['primary_account_type'] == 0)
		{
			$ad_class = 'bold';
			if(isset($exemption['AdAccount']['Division']['id']) and $exemption['AdAccount']['Division']['id'] != $exemption['Division']['id'])
				$highlight = true;
			
		}
		elseif($exemption['Exemption']['primary_account_type'] == 1)
		{
			$assoc_class = 'bold';
			if(isset($exemption['AssocAccount']['Division']['id']) and $exemption['AssocAccount']['Division']['id'] != $exemption['Division']['id'])
				$highlight = true;
		}
	}
	
	$td[$i] = array(
		$this->Html->link($exemption['Exemption']['id'], array('action' => 'view', $exemption['Exemption']['id'], 'admin' => false)),
		$this->Html->link($exemption['ExemptionType']['shortname'], $this->Html->urlModify(array('action' => 'index', 0 => $exemption['ExemptionType']['id'], 'admin' => false))),
		$this->Html->link($exemption['ExemptionSource']['shortname'], array('action' => 'source', $exemption['ExemptionSource']['id'], 'admin' => false)),
		array(
			(isset($exemption['AdAccount']['username'])?$this->Html->link($exemption['AdAccount']['username'], array('controller' => 'ad_accounts', 'action' => 'view', $exemption['AdAccount']['id'], 'admin' => false)):false),
			array('class' => $ad_class)
		),
		array(
			(isset($exemption['AssocAccount']['username'])?$this->Html->link($exemption['AssocAccount']['username'], array('controller' => 'assoc_accounts', 'action' => 'view', $exemption['AssocAccount']['id'], 'admin' => false)):false),
			array('class' => $assoc_class)
		),
		$this->Contacts->makePath($exemption),
		$exemption['Exemption']['asset_tag'],
		$exemptionFileLatest,
		$this->Wrap->niceTime($exemption['Exemption']['expiration_date']),
		$this->Wrap->yesNo($exemption['Exemption']['notified']),
		$exemption['ReviewStatus']['name'],
		$exemption['ExemptionAddedUser']['name'],
		array(
			$this->Wrap->niceTime($exemption['Exemption']['created']),
			array('class' => 'nowrap'),
		),
		array(
			$this->Wrap->niceTime($exemption['Exemption']['modified']),
			array('class' => 'nowrap'),
		),
		array(
			implode("", $actions),
			array('class' => 'actions'),
		),
		'highlight' => $highlight,
		'multiselect' => $exemption['Exemption']['id'],
	);
}

$use_search = true;
$show_refresh_table = true;
$use_multiselect = true;
if($stripped)
{
	$use_search = false;
	$show_refresh_table = false;
	$use_multiselect = false;
}

if($title_suffix)
	$title_suffix = '- '. $title_suffix;

$page_title = (isset($page_title)?$page_title:__('%s %s %s', $title_prefix, __('Exemptions'), $title_suffix));

echo $this->element('Utilities.page_index', array(
	'page_title' => $page_title,
	'page_subtitle' => $page_title,
	'page_options' => $page_options,
	'page_options2' => $page_options2,
	'use_search' => $use_search,
	'show_refresh_table' => $show_refresh_table,
	'table_caption' => 
		$this->Html->tag('p', __('Bold %s and %s are ones directly associated with the %s.', __('AD/Associated Accounts'), __('Divisions'), __('Exemption'))),
	'th' => $th,
	'td' => $td,
	'use_multiselect' => $use_multiselect,
	'multiselect_options' => array(
		'primary_account_type_ad' => __('Marks selected %s to have the %s be the direct Account.', __('Exemptions'), __('AD Account')),
		'primary_account_type_assoc' => __('Marks selected %s to have the %s be the direct Account.', __('Exemptions'), __('Associated Account')),
		'review_status' => __('Change the %s of the selected %s', __('Review Status'), __('Exemptions')),
	),
	'multiselect_referer' => array(
		'admin' => $this->params['admin'],
		'controller' => $this->params['controller'],
		'action' => $this->params['action'],
	),
));