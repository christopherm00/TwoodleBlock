<?php

class block_twitter_edit_form extends block_edit_form {
	
	protected function specific_definition($mform) {
		$mform->addElement('header', get_string('config_header', 'block_twitter'));
		
		$mform->addElement('html', '<p>If fields are left blank the following defaults will be used:</p>');
		$mform->addElement('html', 
			'<ul>
				<li>Block Header: Twitter Username\'s Twitter Feed</li>
				<li>Twitter Username: Teacher\'s Twitter id or USCedu (Can be comma seperated list)</li>
				<li># of Tweets: 10 (Max of 50)</li>
			</ul>');
		
		// Block title
		$mform->addElement('text', 'config_title', get_string('block_header', 'block_twitter'));
		$mform->setType('config_title', PARAM_MULTILANG);
		
		// Search parameter
		$mform->addElement('text', 'config_search', get_string('search_string', 'block_twitter'));
		$mform->setType('config_search', PARAM_MULTILANG);
		
		// # of tweets to show
		$mform->addElement('text', 'config_count', get_string('count', 'block_twitter'));
		$mform->setType('config_count', PARAM_INT);
	}
	
}