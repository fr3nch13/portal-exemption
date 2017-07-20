<?php

class CronShell extends AppShell
{
	// the models to use
	public $uses = array('User', 'LoginHistory', 'ReviewStatus', 'Exemption');
	
	public function startup() 
	{
		$this->clear();
		$this->out('Cron Shell');
		$this->hr();
		return parent::startup();
	}
	
	public function getOptionParser()
	{
	/*
	 * Parses out the options/arguments.
	 * http://book.cakephp.org/2.0/en/console-and-shells.html#configuring-options-and-generating-help
	 */
	
		$parser = parent::getOptionParser();
		
		$parser->description(__d('cake_console', __('The Cron Shell runs all needed cron jobs') ));
		
		$parser->addSubcommand('failed_logins', array(
			'help' => __d('cake_console', 'Emails a list of failed logins to the admins and users every 10 minutes'),
			'parser' => array(
				'options' => array(
					'minutes' => array(
						'help' => __d('cake_console', 'Change the time frame from 10 minutes ago.'),
						'short' => 'm',
						'default' => 10,
					),
				),
			),
		));
		
		$parser->addSubcommand('review_status_emails', array(
			'help' => __d('cake_console', 'Sends out the list of Exemptions that are assigned to a Review Status.'),
		));
		
		$parser->addSubcommand('expired_exemption_emails', array(
			'help' => __d('cake_console', 'Sends out one email for each expired exemption on a per exemption type basis.'),
		));
		
		return $parser;
	}
	
	public function failed_logins()
	{
	/*
	 * Emails a list of failed logins to the admins every 5 minutes
	 * Only sends an email if there was a failed login
	 * Everything is taken care of in the Task
	 */
		$FailedLogins = $this->Tasks->load('Utilities.FailedLogins')->execute($this);
	}
	
	public function review_status_emails()
	{
	/*
	 * Sends a digest email of Exemptions that are assigned to a Review Status.
	 * Review states are chosen based on their email settings in the admin.
	 */
		$hour = date('H');
		$day = strtolower(date('D'));
		
		$this->out(__('Finding %s that needs to have notifications sent. Day: %s - Hour: %s', __('Review Statuss'), $day , $hour), 1, Shell::QUIET);
		
		/////////// get the list of changes
		$review_statuses = $this->ReviewStatus->find('all', array(
			'conditions' => array(
				'ReviewStatus.sendemail' => true,
				'ReviewStatus.'.$day => true,
				'ReviewStatus.notify_time' => $hour,
			),
		));
		if(!$review_statuses)
		{
			$this->out(__('No %s marked for notification at %s.', __('Review Statuss'), date('g a')), 1, Shell::QUIET);
			return false;
		}
		
		$this->out(__('Found %s %s%s to send at %s.', count($review_statuses), __('Review Status'), (count($review_statuses)>1?'s':''), date('g a')), 1, Shell::QUIET);
		
		// load the email task
		$Email = $this->Tasks->load('Utilities.Email');
		$Email->set('template', 'review_status_emails');
		
		// Each one gets an email to be sent out
		foreach($review_statuses as $review_status)
		{
			// if no email is sent, make a note, than move on
			if(!$review_status['ReviewStatus']['notify_email'])
			{
				$this->out(__('The %s "%s" doesn\'t have an email address associated.', __('Review Status'), $review_status['ReviewStatus']['name']), 1, Shell::QUIET);
				continue;
			}
			
			$exemptions = $this->Exemption->find('all', array(
				'recursive' => 0,
				'conditions' => array(
					'Exemption.review_status_id' => $review_status['ReviewStatus']['id'],
				),
				'order' => array('Exemption.created' => 'asc'),
			));
			
			if(!$exemptions)
			{
				$this->out(__('No %s was found with the %s of "%s".', __('Exemptions'), __('Review Status'), $review_status['ReviewStatus']['name']), 1, Shell::QUIET);
				continue;
			}
			
			$this->out(__('Found %s %s with the %s "%s".', count($exemptions), __('Exemptions'), __('Review Status'), $review_status['ReviewStatus']['name']), 1, Shell::QUIET);
			
			// set the variables so we can use view templates
			$viewVars = array(
				'instructions' => trim($review_status['ReviewStatus']['instructions']),
				'review_status' => $review_status,
				'exemptions' => $exemptions,
			);
			
			//set the email parts
			$Email->set('to', $review_status['ReviewStatus']['notify_email']);
			$Email->set('subject', __('%s: %s - Count: %s', __('Review Status'), $review_status['ReviewStatus']['name'], count($exemptions)));
			$Email->set('viewVars', $viewVars);
			
			
			// send the email
			if(!$results = $Email->executeFull())
			{
				$this->out(__('Error sending notification email for %s "%s".', __('Review Status'), $review_status['ReviewStatus']['name']), 1, Shell::QUIET);
			}
			
			$this->out(__('Sent notification email for %s "%s".', __('Review Status'), $review_status['ReviewStatus']['name']), 1, Shell::QUIET);
		}
	}
	
	public function expired_exemption_emails()
	{
		$this->out(__('Finding Expired %s that need a notification email sent.', __('Exemptions')), 1, Shell::QUIET);
		
		$now = date('Y-m-d H:i:s');
		
		$exemptionTypes = $this->Exemption->ExemptionType->find('all', array(
			'conditions' => array(
				'ExemptionType.notify' => true,
				'ExemptionType.notification_email !=' => '',
			),
		));
		
		$exemptionType_ids = Hash::extract($exemptionTypes, '{n}.ExemptionType.id');
		
		$exemptions = $this->Exemption->find('all', array(
			'conditions' => array(
				'Exemption.notified' => 0,
				'Exemption.expiration_date <' => $now, 
				'Exemption.exemption_type_id' => $exemptionType_ids,
			),
			'recursive' => 0,
		));
		
		if(!$exemptions)
		{
			$this->out(__('No Expired %s found.', __('Exemptions')), 1, Shell::QUIET);
			return true;
		}
		
		
		$this->out(__('Found %s %s that need to have a notification email sent.', count($exemptions), __('Exemptions')), 1, Shell::QUIET);
		
		$Email = $this->Tasks->load('Utilities.Email');
		$Email->set('template', 'expired_exemption_emails');
		$Email->set('emailFormat', 'text');
		
		foreach($exemptions as $exemption)
		{
			$acctType = __('AD Account');
			$acctUsername = $exemption['AdAccount']['username'];
			if($exemption['Exemption']['primary_account_type'])
			{
				$acctType = __('Assoc Account');
				$acctUsername = $exemption['AssocAccount']['username'];
			}
			
			$subject = __('%s Expired. %s: %s, Ticket: %s, ID:%s, Type: %s, Expired: %s',
				__('Exemption'),
				$acctType,
				$acctUsername,
				($exemption['Exemption']['example_ticket']?$exemption['Exemption']['example_ticket']:'n/a'),
				$exemption['Exemption']['id'],
				$exemption['ExemptionType']['shortname'],
				$exemption['Exemption']['expiration_date']
			);
			
			$viewVars = array(
				'subject' => $subject,
				'exemption' => $exemption,
			);
			
			$email_addresses = $exemption['ExemptionType']['notification_email'];
			$email_addresses = explode(',', $email_addresses);
			foreach($email_addresses as $i => $email_address)
				$email_addresses[$i] = trim($email_address);
			
			$Email->set('to', $email_addresses);
			$Email->set('subject',  $subject);
			$Email->set('viewVars', $viewVars);
			
			// send the email
			if(!$results = $Email->executeFull())
			{
				$this->out(__('Error sending notification email for expired %s ID: %s.', __('Exemption'), $exemption['Exemption']['id']), 1, Shell::QUIET);
				continue;
			}
			
			// mark exemption as notified
			$this->Exemption->id = $exemption['Exemption']['id'];
			$this->Exemption->saveField('notified', ($exemption['Exemption']['notified']+1));
		}
		
		return true;
	}
}