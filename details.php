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
			'frontend' => false,
			'backend' => true
		);
	}

	public function install()
	{
		// No Database interaction needed
		return true;
	}

	public function uninstall()
	{
		return true;
	}

	public function upgrade($old_version)
	{
		return true;
	}

	public function help()
	{
		// We're trying to replace this.
		return 'View the documentation directly in your admin. <a href="/index.php/admin/docs/docs/">View Docs Here</a>.';
	}
}
/* End of file details.php */
