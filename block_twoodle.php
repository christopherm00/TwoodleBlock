<?php

/*
* Created Mar 2011
*
* Block uses the jTweetsAnywhere jQuery plugin to get and display tweets from a specific user
* 
* Twoodle is designed to be used on Course pages, however, it can be used on other pages, though unforseen issues may occur
* 
* This program is free software; you can redistribute it and/or modify  
* it under the terms of the GNU General Public License as published by  
* the Free Software Foundation; either version 3 of the License, or     
* (at your option) any later version.                                   
*                                                                         
* This program is distributed in the hope that it will be useful,       
* but WITHOUT ANY WARRANTY; without even the implied warranty of        
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         
* GNU General Public License for more details: http://www.gnu.org/copyleft/gpl.html        
* 
* Prerequisites
* 
* 	- Must have jQuery 1.4 or higher on the site (otherwise uncomment the include for the jQuery on this page.. line 72)
*                                                                       
* @author Matt Burica
*
*/

class block_twoodle extends block_base {
	
	function init() {
		// Set title of block
		$this->title = get_string('pluginname', 'block_twoodle');
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
		} else { 
			// If no Twitter ID set in profile use the deafult twitter ID
			$this->search = get_string('default_twitter', 'block_twoodle');
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
		$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/blocks/twoodle/js/jquery.js'));
		$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/blocks/twoodle/js/jquery.jtweetsanywhere-1.3.1.js'));
		$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/blocks/twoodle/js/jquery.jtweetsanywhere.custom.js'));
		$PAGE->requires->js(new moodle_url($CFG->wwwroot.'/blocks/twoodle/js/jquery.cookie.js'));
		$PAGE->requires->css(new moodle_url($CFG->wwwroot.'/blocks/twoodle/css/jquery.jtweetsanywhere-1.3.1.css'));
		
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