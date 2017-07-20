<?php
$this->extend('Utilities.object_dashboard_options');

$dashboard_options_title = __('Switch Dashboards');

$dashboard_options_items = array();
$dashboard_options_items[] = $this->Html->link(__('My Overview'), array('controller' => 'main', 'action' => 'my_dashboard'));
$dashboard_options_items[] = $this->Html->link(__('All %s', __('Exemptions')), array('controller' => 'exemptions', 'action' => 'dashboard', 'list' => 'all', 'admin' => false, 'plugin' => false));
$dashboard_options_items[] = $this->Html->link(__('Active %s', __('Exemptions')), array('controller' => 'exemptions', 'action' => 'dashboard', 'list' => 'active', 'admin' => false, 'plugin' => false));
$dashboard_options_items[] = $this->Html->link(__('Expired %s', __('Exemptions')), array('controller' => 'exemptions', 'action' => 'dashboard', 'list' => 'expired', 'admin' => false, 'plugin' => false));

$this->set('dashboard_options_title', $dashboard_options_title);
$this->set('dashboard_options_items', $dashboard_options_items);
