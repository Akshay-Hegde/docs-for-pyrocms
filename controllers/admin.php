<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Docs
 *
 * Document your code.
 *
 * @package  Docs
 * @author   cmfolio
 */
class Admin extends Admin_Controller {

	
	/**
	* _remap
	*
	* Take over all incoming calls.
	*/
	public function _remap() {
		$this->load->library('docs/docs');
		
		// if no 3rd segment is set, that means we are at module homepage
		if (!$this->uri->segment(3)) {
			return $this->overview();
		}
		
		// automatically gets file by URL
		// if you want to load a specific file, add it as a parameter
		return $this->docs->build();
	}
	
	
	/**
	* Overview
	*
	* Except this one, of course. This will show our quick instructions on how to use Docs.
	*/
	public function overview() {
		return $this->template->append_css('module::admin/docs.css')->build('admin/overview');
	}


}