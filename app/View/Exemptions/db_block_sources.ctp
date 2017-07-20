<?php
$stats = array(
	'total' => array('name' => __('Total'), 'value' => count($exemptions), 'color' => 'FFFFFF'),
	'ExemptionSource.0' => array('name' => __('No Source'), 'value' => 0, 'color' => '000000'),
);

foreach($exemptionSources as $exemptionSource_id => $exemptionSource_name)
{
	$stats['Source.'.$exemptionSource_id] = array(
		'name' => $exemptionSource_name,
		'value' => 0,
		'color' => substr(md5($exemptionSource_name), 0, 6),
	);
}

foreach($exemptions as $exemption)
{
	if($exemption['ExemptionSource']['id'])
	{
		$exemptionSource_id = $exemption['ExemptionSource']['id'];
		if(!isset($stats['ExemptionSource.'.$exemptionSource_id]))
		{
			$stats['ExemptionSource.'.$exemptionSource_id] = array(
				'name' => $exemption['ExemptionSource']['name'],
				'value' => 0,
				'color' => substr(md5($exemption['ExemptionSource']['name']), 0, 6),
				
			);
		}
		$stats['ExemptionSource.'.$exemptionSource_id]['value']++;
	}
	else
	{
		$stats['ExemptionSource.0']['value']++;
	}	
}
$stats = Hash::sort($stats, '{s}.value', 'desc');

$pie_data = array(array(__('Source'), __('num %s', __('Exemptions')) ));
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
	'title' => __('%s %s by %s', $title_prefix, __('Exemptions'), __('Source')),
	'content' => $content,
));