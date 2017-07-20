<?php

$dashboard_blocks = array();
$dashboard_blocks['exemptions_overview'] = array('action' => 'db_block_overview', 'list' => $this->passedArgs['list'], 'plugin' => false);
$dashboard_blocks['db_block_review_statuses'] = array('action' => 'db_block_review_statuses', 'list' => $this->passedArgs['list'], 'plugin' => false);

if($this->passedArgs['list'] == 'all')
	$dashboard_blocks['db_block_review_statuses_trend'] = array('action' => 'db_block_review_statuses_trend', 'list' => $this->passedArgs['list'], 'plugin' => false);
$dashboard_blocks['db_block_sources'] = array('action' => 'db_block_sources', 'list' => $this->passedArgs['list'], 'plugin' => false);
$dashboard_blocks['db_block_types'] = array('action' => 'db_block_types', 'list' => $this->passedArgs['list'], 'plugin' => false);

echo $this->element('Utilities.page_dashboard', array(
	'page_title' => __('Dashboard: %s %s', $title_prefix, __('Exemptions')),
	'page_options_html' => $this->element('dashboard_options'),
	'dashboard_blocks' => $dashboard_blocks,
));