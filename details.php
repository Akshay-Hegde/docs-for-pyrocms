<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Docs
 *
 * Document your code.
 *
 * @package  Docs
 * @author   cmfolio
 */
class Module_Docs extends Module {

	public $version = '0.5';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Docs'
			),
			'description' => array(
				'en' => 'A documentation tool for PyroCMS. Document your code.'
			),
			'frontend' => FALSE,
			'backend' => TRUE
		);
	}

	public function install()
	{
		// No Database interaction needed
		return TRUE;
	}

	public function uninstall()
	{
		return TRUE;
	}

	public function upgrade($old_version)
	{
		return TRUE;
	}

	public function help()
	{
		// We're trying to replace this.
		return 'View the documentation directly in your admin. <a href="/index.php/admin/docs/">View Docs Here</a>.';
	}
}
/* End of file details.php */
