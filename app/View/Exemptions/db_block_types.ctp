<?php
$stats = array(
	'total' => array('name' => __('Total'), 'value' => count($exemptions), 'color' => 'FFFFFF'),
	'ExemptionType.0' => array('name' => __('No Type'), 'value' => 0, 'color' => '000000'),
);

foreach($exemptionTypes as $exemptionType_id => $exemptionType_name)
{
	$stats['Type.'.$exemptionType_id] = array(
		'name' => $exemptionType_name,
		'value' => 0,
		'color' => substr(md5($exemptionType_name), 0, 6),
	);
}

foreach($exemptions as $exemption)
{
	if($exemption['ExemptionType']['id'])
	{
		$exemptionType_id = $exemption['ExemptionType']['id'];
		if(!isset($stats['ExemptionType.'.$exemptionType_id]))
		{
			$stats['ExemptionType.'.$exemptionType_id] = array(
				'name' => $exemption['ExemptionType']['name'],
				'value' => 0,
				'color' => substr(md5($exemption['ExemptionType']['name']), 0, 6),
				
			);
		}
		$stats['ExemptionType.'.$exemptionType_id]['value']++;
	}
	else
	{
		$stats['ExemptionType.0']['value']++;
	}	
}

$stats = Hash::sort($stats, '{s}.value', 'desc');

$pie_data = array(array(__('Type'), __('num %s', __('Exemptions')) ));
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
	'title' => __('%s %s by %s', $title_prefix, __('Exemptions'), __('Type')),
	'content' => $content,
));