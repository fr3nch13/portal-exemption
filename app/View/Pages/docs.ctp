<?php 

$details_left = array();

$details_right = array();

echo $this->element('Utilities.page_compare', array(
	'page_title' => __('Documents and Forms'),
	'details_left_title' => __('Documents'),
	'details_left' => $details_left,
	'details_right_title' => __('Forms'),
	'details_right' => $details_right,
));
