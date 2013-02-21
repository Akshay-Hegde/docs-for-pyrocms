<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Docs Public Controller
 *
 * @package  PyroCMS\Addons\Modules\Docs\Controllers
 * @author   cmfolio
 * @website  http://web.cmfolio.com
 */
class Public_Docs extends Public_Controller
{

	public function __construct()
	{
		parent::__construct();

		
	}


	public function _remap() {
		$this->load->library('docs/docs');
		
		// if no 2nd segment is set, that means we are at module homepage
		if (!$this->uri->segment(2)) {
			return $this->docs->build('index');
		}
		
		// automatically gets file by URL
		// if you want to load a specific file, add it as a parameter
		return $this->docs->build();
	}


}

/* EOF */