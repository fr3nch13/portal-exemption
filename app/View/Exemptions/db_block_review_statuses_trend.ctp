<?php

$line_options = array('colors' => array());
foreach($snapshotStats['legend'] as $key => $name)
{
	$line_options['colors'][] = '#'. substr(md5($name), 0, 6);
}

$content = $this->element('Utilities.object_dashboard_chart_line', array(
	'title' => '',
	'data' => $snapshotStats,
	'options' => $line_options,
));

echo $this->element('Utilities.object_dashboard_block', array(
	'title' => __('%s %s By %s Trending', __('All'), __('Exemptions'), __('Review Status')),
	'content' => $content,
));