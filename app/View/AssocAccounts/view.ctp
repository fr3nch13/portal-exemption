<?php 
// File: app/View/AssocAccounts/view.ctp
$page_options = array(
	$this->Html->link(__('Edit'), array('action' => 'edit', $assocAccount['AssocAccount']['id'])),
);

$stats = array();
$tabs = array();

$stats['ExemptionsAll'] = array(
	'id' => 'ExemptionsAll',
	'name' => __('%s %s', __('All'), __('Exemptions')), 
	'tip' => __('%s %s assigned to them.', __('All'), __('Exemptions')),
	'ajax_count_url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'all'),
	'tab' => array('tabs', (count($tabs) + 1)), // the tab to display
);
$tabs['ExemptionsAll'] = array(
	'key' => 'ExemptionsAll',
	'title' => __('%s %s', __('All'), __('Exemptions')),
	'url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'all'),
);

$stats['ExemptionsActive'] = array(
	'id' => 'ExemptionsActive',
	'name' => __('%s %s', __('Active'), __('Exemptions')), 
	'tip' => __('%s %s assigned to them.', __('Active'), __('Exemptions')),
	'ajax_count_url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'active'),
	'tab' => array('tabs', (count($tabs) + 1)), // the tab to display
);
$tabs['ExemptionsActive'] = array(
	'key' => 'ExemptionsActive',
	'title' => __('%s %s', __('Active'), __('Exemptions')),
	'url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'active'),
);

$stats['ExemptionsExpired'] = array(
	'id' => 'ExemptionsExpired',
	'name' => __('%s %s', __('Expired'), __('Exemptions')), 
	'tip' => __('%s %s assigned to them.', __('Expired'), __('Exemptions')),
	'ajax_count_url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'expired'),
	'tab' => array('tabs', (count($tabs) + 1)), // the tab to display
);
$tabs['ExemptionsExpired'] = array(
	'key' => 'ExemptionsExpired',
	'title' => __('%s %s', __('Expired'), __('Exemptions')),
	'url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'expired'),
);

$stats['ExemptionsReviewed'] = array(
	'id' => 'ExemptionsReviewed',
	'name' => __('%s %s', __('Reviewed'), __('Exemptions')), 
	'tip' => __('%s %s assigned to them.', __('Reviewed'), __('Exemptions')),
	'ajax_count_url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'reviewed'),
	'tab' => array('tabs', (count($tabs) + 1)), // the tab to display
);
$tabs['ExemptionsReviewed'] = array(
	'key' => 'ExemptionsReviewed',
	'title' => __('%s %s', __('Reviewed'), __('Exemptions')),
	'url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'reviewed'),
);

$stats['ExemptionsNotReviewed'] = array(
	'id' => 'ExemptionsNotReviewed',
	'name' => __('%s %s', __('Not Reviewed'), __('Exemptions')), 
	'tip' => __('%s %s assigned to them.', __('Not Reviewed'), __('Exemptions')),
	'ajax_count_url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'notreviewed'),
	'tab' => array('tabs', (count($tabs) + 1)), // the tab to display
);
$tabs['ExemptionsNotReviewed'] = array(
	'key' => 'ExemptionsNotReviewed',
	'title' => __('%s %s', __('Not Reviewed'), __('Exemptions')),
	'url' => array('controller' => 'exemptions', 'action' => 'assoc_account', $assocAccount['AssocAccount']['id'], 'list' => 'notreviewed'),
);

$this->set(compact(array('stats', 'tabs')));
$this->extend('Contacts.ContactsAssocAccounts/view');