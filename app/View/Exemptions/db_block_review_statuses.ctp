<?php
$stats = array(
	'total' => array('name' => __('Total'), 'value' => count($exemptions), 'color' => 'FFFFFF'),
	'ReviewStatus.0' => array('name' => __('No Status'), 'value' => 0, 'color' => '000000'),
);

foreach($reviewStatuses as $reviewStatus_id => $reviewStatus_name)
{
	$stats['ReviewStatus.'.$reviewStatus_id] = array(
		'name' => $reviewStatus_name,
		'value' => 0,
		'color' => substr(md5($reviewStatus_name), 0, 6),
	);
}

foreach($exemptions as $exemption)
{
	if($exemption['ReviewStatus']['id'])
	{
		$reviewStatus_id = $exemption['ReviewStatus']['id'];
		if(!isset($stats['ReviewStatus.'.$reviewStatus_id]))
		{
			$stats['ReviewStatus.'.$reviewStatus_id] = array(
				'name' => $exemption['ReviewStatus']['name'],
				'value' => 0,
				'color' => substr(md5($exemption['ReviewStatus']['name']), 0, 6),
				
			);
		}
		$stats['ReviewStatus.'.$reviewStatus_id]['value']++;
	}
	else
	{
		$stats['ReviewStatus.0']['value']++;
	}	
}
$stats = Hash::sort($stats, '{s}.value', 'desc');

$pie_data = array(array(__('Review Status'), __('num %s', __('Exemptions')) ));
$pie_options = array('slices' => array());
foreach($stats as $i => $stat)
{
	if($i == 'total')
	{
		$stats[$i]['pie_exclude'] = true;
		$stats[$i]['color'] = 'FFFFFF';
		continue;
	}
	if(!$stat['value'])
	{
		unset($stats[$i]);
		continue;
	}
	$pie_data[] = array($stat['name'], $stat['value'], $i);
	$pie_options['slices'][] = array('color' => '#'. $stat['color']);
}

$content = $this->element('Utilities.object_dashboard_chart_pie', array(
	'title' => '',
	'data' => $pie_data,
	'options' => $pie_options,
));

$content .= $this->element('Utilities.object_dashboard_stats', array(
	'title' => '',
	'details' => $stats,
));

echo $this->element('Utilities.object_dashboard_block', array(
	'title' => __('%s %s by %s', $title_prefix, __('Exemptions'), __('Review Status')),
	'content' => $content,
));