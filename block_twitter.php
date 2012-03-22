<?php

class block_twitter extends block_base {
	
	function init() {
		// Set title of block
		$this->title = get_string('pluginname', 'block_twitter');
	}
	
	function instance_allow_multiple() {
		return true;
	}
	
	function instance_allow_config() {
		return true;
	}
	
	function specialization() {
		global $COURSE, $DB;
		
		if(!empty($this->config->search)) {
			// If Twitter Search string is set then use that
			$this->search = $this->config->search; 
		} else { // TODO: at some point need to check if not in a course context and rework the default
			// If no Twitter Search string is set find the editingteacher
			$context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
			$roleid = $DB->get_record('role', array('shortname'=>get_string('teacher_role', 'block_twitter')), 'id');
			$teacher = get_role_users($roleid, $context, false, 'u.id, u.twitter'); 
			
			// If the editingteacher has a Twitter ID set in their profile use that
			if(!empty($teacher)) {
				foreach($teacher as $item) {
					$usernames .= (!empty($item->twitter)) ? $item->twitter.',' : '' ;
				}
				$this->search = (!empty($usernames)) ? substr($usernames, 0, -1) : get_string('default_twitter', 'block_twitter');
			} else {
				// If no Twitter ID set in profile use the deafult twitter ID
				$this->search = get_string('default_twitter', 'block_twitter');
			}
		}
		
		if(!empty($this->config->title)) {
			$this->title = $this->config->title;
		} else {
			$this->title = $this->search;
			$this->title .= strtolower((substr($this->title, -1))) === 's' ? '\' Twitter Feed' : '\'s Twitter Feed'; // Check if last char is 's' and make gramatically correct
		}
		
		if(!empty($this->config->count)) {
			$this->count = $this->config->count;
		} else {
			$this->count = 10;
		}
	}
	
	function get_content() {
		global $CFG, $COURSE, $SITE, $USER, $SCRIPT, $OUTPUT, $PAGE;
		
		// Include the needed JS
		$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/blocks/twitter/js/jquery.jtweetsanywhere-1.3.1.js'));
		$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/blocks/twitter/js/jquery.jtweetsanywhere.custom.js'));
		//$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/blocks/twitter/js/jquery.getUrlParam.js'));
		$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/blocks/twitter/js/jquery.cookie.js'));
		$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/blocks/twitter/css/jquery.jtweetsanywhere-1.3.1.css'));
		
		if ($this->content !== NULL) {
			return $this->content;
		}
		
		$this->content = new stdClass;
		
		// Create hidden element to hold twitter username
		$this->content->text .= html_writer::empty_tag('input', array('type'=>'hidden', 'value'=>$this->search, 'id'=>'twittername'));
		
		// Create hidden element to hold count for # of tweers
		$this->content->text .= html_writer::empty_tag('input', array('type'=>'hidden', 'value'=>$this->count, 'id'=>'tweetcount'));
		
		// Create a container for the tweets
		$this->content->text .= html_writer::tag('div', null, array('id'=>'tweets'));
		
		//$this->content->text .= $this->search; // Test code to make sure the correct username is being sent
		
		return $this->content;
	}
	
}