<?php

$dashboard_blocks = array(
	'exemptions_overview' => array('controller' => 'exemptions', 'action' => 'db_block_overview', 'plugin' => false),
	'db_block_review_statuses' => array('controller' => 'exemptions', 'action' => 'db_block_review_statuses', 'plugin' => false),
	'db_block_review_statuses_trend' => array('controller' => 'exemptions', 'action' => 'db_block_review_statuses_trend', 'plugin' => false),
	'db_block_sources' => array('controller' => 'exemptions', 'action' => 'db_block_sources', 'plugin' => false),
	'db_block_types' => array('controller' => 'exemptions', 'action' => 'db_block_types', 'plugin' => false),
	'db_block_byshortname' => array('controller' => 'divisions', 'action' => 'byshortname', 1, 'plugin' => false),
	'db_block_byorg' => array('controller' => 'divisions', 'action' => 'byorg', 1, 'plugin' => false),
	
);

echo $this->element('Utilities.page_dashboard', array(
	'page_title' => __('Dashboard: %s', __('Overview')),
	'page_options_html' => $this->element('dashboard_options'),
	'dashboard_blocks' => $dashboard_blocks,
));