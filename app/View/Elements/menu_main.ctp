<?php  
?>
<?php if (AuthComponent::user('id')): ?>
<ul class="sf-menu">
	<li><?php echo $this->Html->link(__('Add New %s(s)', __('Exemption')), array('controller' => 'exemptions', 'action' => 'add', 'admin' => false, 'plugin' => false), array('class' => 'top')); ?></li>
	<!-- not ready yet
	<li>
		<?php echo $this->Html->link(__('Add New %s', __('Exemption')), '#', array('class' => 'top')); ?>
		<ul>
			<li><?php echo $this->Html->link(__('Add with Form'), array('controller' => 'exemptions', 'action' => 'add', 'admin' => false, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('Add with Waiver PDF'), array('controller' => 'exemptions', 'action' => 'add_waiver_form', 'admin' => false, 'plugin' => false)); ?></li>
			
		</ul>
	</li>
	-->
	<li>
		<?php echo $this->Html->link(__('Dashboards'), '#', array('class' => 'top')); ?>
		<ul>
			<li><?php echo $this->Html->link(__('My Overview'), array('controller' => 'main', 'action' => 'my_dashboard', 'admin' => false, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('All %s', __('Exemptions')), array('controller' => 'exemptions', 'action' => 'dashboard', 'list' => 'all', 'admin' => false, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('Active %s', __('Exemptions')), array('controller' => 'exemptions', 'action' => 'dashboard', 'list' => 'active', 'admin' => false, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('Expired %s', __('Exemptions')), array('controller' => 'exemptions', 'action' => 'dashboard', 'list' => 'expired', 'admin' => false, 'plugin' => false)); ?></li>
		</ul>
	</li>
	<li>
		<?php echo $this->Html->link(__('List %s', __('Exemptions')), '#', array('class' => 'top')); ?>
		<ul>
			<li><?php echo $this->Html->link(__('%s %s', __('All'), __('Exemptions')), array('controller' => 'exemptions', 'action' => 'index', 'list' => 'all', 'admin' => false, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('%s %s', __('Active'), __('Exemptions')), array('controller' => 'exemptions', 'action' => 'index', 'list' => 'active', 'admin' => false, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('%s %s', __('Expired'), __('Exemptions')), array('controller' => 'exemptions', 'action' => 'index', 'list' => 'expired', 'admin' => false, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('%s %s', __('Reviewed'), __('Exemptions')), array('controller' => 'exemptions', 'action' => 'index', 'list' => 'reviewed', 'admin' => false, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('%s %s', __('Not Reviewed'), __('Exemptions')), array('controller' => 'exemptions', 'action' => 'index', 'list' => 'notreviewed', 'admin' => false, 'plugin' => false)); ?></li>
			<li>
				<?php echo $this->Html->link(__('%s by %s', __('Exemptions'), __('Type')), '#'); ?>
				<?php echo $this->element('Utilities.menu_items', array(
					'request_url' => array('controller' => 'exemptions', 'action' => 'menu_main_type', $this->params['prefix'] => false, 'plugin' => false),
				)); ?>
			</li>
			<li>
				<?php echo $this->Html->link(__('%s by %s', __('Exemptions'), __('Source')), '#'); ?>
				<?php echo $this->element('Utilities.menu_items', array(
					'request_url' => array('controller' => 'exemptions', 'action' => 'menu_main_source', $this->params['prefix'] => false, 'plugin' => false),
				)); ?>
			</li>
			<li>
				<?php echo $this->Html->link(__('%s by %s', __('Exemptions'), __('Review Statuses')), '#'); ?>
				<?php echo $this->element('Utilities.menu_items', array(
					'request_url' => array('controller' => 'exemptions', 'action' => 'menu_main_review_status', $this->params['prefix'] => false, 'plugin' => false),
				)); ?>
			</li>
		</ul>
	</li>
	<li>
		<?php echo $this->Html->link(__('Contacts'), '#', array('class' => 'top')); ?>
		<ul>
			<li><?php echo $this->Html->link(__('Associated Accounts'), array('controller' => 'assoc_accounts', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?>
				<ul>
					<li><?php echo $this->Html->link(__('All'), array('controller' => 'assoc_accounts', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Duplicates'), array('controller' => 'assoc_accounts', 'action' => 'duplicates', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Orphans'), array('controller' => 'assoc_accounts', 'action' => 'orphans', 'admin' => false, 'plugin' => false)); ?></li>
				</ul>
			</li>
			<li>
				<?php echo $this->Html->link(__('AD Accounts'), array('controller' => 'ad_accounts', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?>
				<ul>
					<li><?php echo $this->Html->link(__('All'), array('controller' => 'ad_accounts', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Duplicates'), array('controller' => 'ad_accounts', 'action' => 'duplicates', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Empty'), array('controller' => 'ad_accounts', 'action' => 'empties', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Orphans'), array('controller' => 'ad_accounts', 'action' => 'orphans', 'admin' => false, 'plugin' => false)); ?></li>
				</ul>
			</li>
			<li>
				<?php echo $this->Html->link(__('SACs'), array('controller' => 'sacs', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?>
				<ul>
					<li><?php echo $this->Html->link(__('All'), array('controller' => 'sacs', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Duplicates'), array('controller' => 'sacs', 'action' => 'duplicates', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Empty'), array('controller' => 'sacs', 'action' => 'empties', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Orphans'), array('controller' => 'sacs', 'action' => 'orphans', 'admin' => false, 'plugin' => false)); ?></li>
				</ul>
			</li>
			<li>
				<?php echo $this->Html->link(__('Branches'), array('controller' => 'branches', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?>
				<ul>
					<li><?php echo $this->Html->link(__('All'), array('controller' => 'branches', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Duplicates'), array('controller' => 'branches', 'action' => 'duplicates', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Empty'), array('controller' => 'branches', 'action' => 'empties', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Orphans'), array('controller' => 'branches', 'action' => 'orphans', 'admin' => false, 'plugin' => false)); ?></li>
				</ul>
			</li>
			<li>
				<?php echo $this->Html->link(__('Divisions'), array('controller' => 'divisions', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?>
				<ul>
					<li><?php echo $this->Html->link(__('All'), array('controller' => 'divisions', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Duplicates'), array('controller' => 'divisions', 'action' => 'duplicates', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Empty'), array('controller' => 'divisions', 'action' => 'empties', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Orphans'), array('controller' => 'divisions', 'action' => 'orphans', 'admin' => false, 'plugin' => false)); ?></li>
				</ul>
			</li>
			<li>
				<?php echo $this->Html->link(__('ORG/ICs'), array('controller' => 'orgs', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?>
				<ul>
					<li><?php echo $this->Html->link(__('All'), array('controller' => 'orgs', 'action' => 'index', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Duplicates'), array('controller' => 'orgs', 'action' => 'duplicates', 'admin' => false, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Empty'), array('controller' => 'orgs', 'action' => 'empties', 'admin' => false, 'plugin' => false)); ?></li>
				</ul>
			</li>
		</ul>
	</li>
	<!-- Not needed right now <li><?php echo $this->Html->link(__('View Users'), array('controller' => 'users', 'action' => 'index', 'admin' => false, 'plugin' => false), array('class' => 'top')); ?></li> -->
	<?php echo $this->Common->loadPluginMenuItems(); ?>
	

	<?php if (AuthComponent::user('id') and AuthComponent::user('role') == 'admin'): ?>
	<li>
		<?php echo $this->Html->link(__('Admin'), '#', array('class' => 'top')); ?>
		<ul>
			<li><?php echo $this->Html->link(__('%s Types', __('Exemption')), array('controller' => 'exemption_types', 'action' => 'index', 'admin' => true, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('%s Sources', __('Exemption')), array('controller' => 'exemption_sources', 'action' => 'index', 'admin' => true, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('Review Statuses'), array('controller' => 'review_statuses', 'action' => 'index', 'admin' => true, 'plugin' => false)); ?></li>
			<li><?php echo $this->Html->link(__('Divisions'), array('controller' => 'divisions', 'action' => 'index', 'admin' => true, 'plugin' => false)); ?></li>
			<?php echo $this->Common->loadPluginMenuItems('admin'); ?>
			<li>
				<?php echo $this->Html->link(__('Users'), '#', array('class' => 'sub')); ?>
				<ul>
					<li><?php echo $this->Html->link(__('All %s', __('Users')), array('controller' => 'users', 'action' => 'index', 'admin' => true, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Login History'), array('controller' => 'login_histories', 'action' => 'index', 'admin' => true, 'plugin' => false)); ?></li>
				</ul>
			</li>
			<li>
				<?php echo $this->Html->link(__('App Admin'), '#', array('class' => 'sub')); ?>
				<ul>
					<li><?php echo $this->Html->link(__('Config'), array('controller' => 'users', 'action' => 'config', 'admin' => true, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Statistics'), array('controller' => 'users', 'action' => 'stats', 'admin' => true, 'plugin' => false)); ?></li>
					<li><?php echo $this->Html->link(__('Process Times'), array('controller' => 'proctimes', 'action' => 'index', 'admin' => true, 'plugin' => 'utilities')); ?></li> 
				</ul>
			</li>
		</ul>
	</li>
	<?php endif; ?>
</ul>
<?php endif; ?>