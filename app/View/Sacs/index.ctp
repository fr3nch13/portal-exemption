<?php 

$page_options2 = array();
$page_options2['list_all'] = $this->Html->link(__('View counts on %s %s', __('All'), __('Exemptions')), $this->Html->urlModify(array('list' => 'all')), array('class' => 'tab-hijack'));
$page_options2['list_active'] = $this->Html->link(__('View counts on %s %s', __('Active'), __('Exemptions')), $this->Html->urlModify(array('list' => 'active')), array('class' => 'tab-hijack'));
$page_options2['list_expired'] = $this->Html->link(__('View counts on %s %s', __('Expired'), __('Exemptions')), $this->Html->urlModify(array('list' => 'expired')), array('class' => 'tab-hijack'));
$page_options2['list_reviewed'] = $this->Html->link(__('View counts on %s %s', __('Reviewed'), __('Exemptions')), $this->Html->urlModify(array('list' => 'reviewed')), array('class' => 'tab-hijack'));
$page_options2['list_notreviewed'] = $this->Html->link(__('View counts on %s %s', __('Not Reviewed'), __('Exemptions')), $this->Html->urlModify(array('list' => 'notreviewed')), array('class' => 'tab-hijack'));

$th = array();
$th['Exemption.count'] = array('content' => __('# %s', __('Exemptions')));
foreach($exemptionTypes as $exemptionType_id => $exemptionType_shortname)
{
	$th['ExemptionType.id.'. $exemptionType_id.'.count'] = array('content' => $exemptionType_shortname);
}

$td = array();
foreach ($sacs as $i => $sac)
{
	$lineId = $sac['Sac']['id'];
	$td[$i] = array();
	
	$td[$i]['Exemption.count'] = array('.', array(
		'ajax_count_url' => array('controller' => 'exemptions', 'action' => 'sac', $lineId, 'list' => $this->passedArgs['list']),
		'url' => array('action' => 'view', $lineId),
	));
	
	foreach($exemptionTypes as $exemptionType_id => $exemptionType_shortname)
	{
		$td[$i]['ExemptionType.id.'. $exemptionType_id.'.count'] = array('.', array(
			'ajax_count_url' => array('controller' => 'exemptions', 'action' => 'sac', $lineId, $exemptionType_id, 'list' => $this->passedArgs['list']), 
			'url' => array('action' => 'view', $lineId),
		));
	}
	
	$td[$i]['edit_id'] = array(
		'Sac' => $lineId,
	);
}

$use_multiselect = false;
if($this->Wrap->roleCheck(array('admin', 'saa')))
{
	$use_multiselect = true;
}

if($title_prefix)
{
	$page_subtitle = __('With counts on %s %s', $title_prefix, __('Exemptions'));
}

$this->set(compact('th', 'td', 'use_multiselect', 'page_subtitle', 'page_options2'));
$this->extend('Contacts.ContactsSacs/index');