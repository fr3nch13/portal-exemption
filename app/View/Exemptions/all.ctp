<?php 
// File: app/View/Exemptions/all.ctp
$page_options = array(
);

$stats = array();
$tabs = array();

$page_content = array();

foreach($exemptionTypes as $exemptionType_id => $exemptionType_name)
{
	$table = $this->requestAction(array('action' => 'index', $exemptionType_id, true, 'list' => 'all'), array('return'));
	$page_content[] = $this->Html->tag('div', $table, array('class' => 'multi-page-wrapper'));
}

echo $this->element('Utilities.page_generic', array(
	'page_title' => __('%s by %s', __('Exemptions'), __('Type')),
	'page_options' => $page_options,
	'page_content' => implode("\n", $page_content),
));