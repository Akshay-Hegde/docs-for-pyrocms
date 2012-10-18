<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Docs
 *
 * Document your code.
 *
 * @package  Docs
 * @author   cmfolio
 */
class Plugin_docs extends Plugin {

	private $_anchor_prefixes;
	
	
	private function _anchor_prefix($type) {
		if ( ! isset($type) ) {
			$this->_anchor_prefixes = $this->config->item('docs.anchor_prefixes');
		}
		
		$prefix = '';
		
		if ( isset($type) && isset($this->_anchor_prefixes[$type]) ) {
			$prefix = $this->_anchor_prefixes[$type] . '_';
		}
		
		return $prefix;
	}
	
	
	/**
	 * Allows you to set page specific details in the page
	 */
	public function page() {
		$title = $this->attribute('title');
		$type = $this->attribute('type', 'page');
		$breadcrumb = $this->attribute('breadcrumb', TRUE);
		
		$this->template->title($title);
		
		return '';
	}

	
	/**
	 * Create a link within docs
	 */
	public function link()
	{
		$module = $this->attribute('module', $this->docs->get_module_by_url());
		$url = $module .'/'. $this->attribute('url', '');
		$text = $this->attribute('text', $url);
		//!TODO: add more attributes
		// add the docs module to URL
		$url = 'docs/' . $url;
		
		if (defined('ADMIN_THEME')) { //!TODO: find a better way to determine admin area
			$url = 'admin/' . $url;
		}
		
		return anchor($url, $text);
	}
	
	
	public function anchor() {
		$id = $this->attribute('id', NULL);
		$prefix = $this->attribute('prefix', 'true');
		$type = $this->attribute('type', '');
		
		if ( is_null($id) ) {
			return '';
		}
		
		if ($type !== '' || $prefix === 'true') {
			$id = $this->_anchor_prefix($type) . $id;
		}
		
		$data = array('id' => $id);
		
		return $this->docs->load_view('admin/partials/anchor', $data, TRUE);
	}
	
	
	/**
	 * Include a partial from docs folder
	 * 
	 * This is different than template:partial because it pulls from docs folder only
	 */
	public function partial() {
		$file = $this->attribute('file', NULL);
		
		if ( is_null($file) ) {
			return '';
		}
		
		$module = $this->attribute('module', $this->docs->get_module_by_url());
		
		// we don't want to autoconvert here
		// we do it this way to avoid double-conversion which could cause errors
		return $this->docs->load_file($file, $module, FALSE);
	}
	
	
	
	public function note() {
		$text = $this->attribute('text', $this->content());
		$title = $this->attribute('title', 'Note');
		$class = $this->attribute('class', 'note');
		
		$data = array(
			'title' => $title,
			'text' => $text,
			'class' => $class
		);
		
		return $this->docs->load_view('admin/partials/note', $data, TRUE);
	}
	
	
	public function important() {
		$text = $this->attribute('text', $this->content());
		$title = $this->attribute('title', 'Important');
		$class = $this->attribute('class', 'important');
		
		$data = array(
			'title' => $title,
			'text' => $text,
			'class' => $class
		);
		
		return $this->docs->load_view('admin/partials/important', $data, TRUE);
	}
	
	
	
	public function code() {
		$type = $this->attribute('type', $this->config->item('docs.default_code_brush'));
		$noparse = strtolower( $this->attribute('noparse', 'true') );
		$code = $this->content();
		
		$data = array(
			'type' => $type,
			'code' => htmlspecialchars($code, ENT_NOQUOTES)
		);
		
		if ($noparse === 'true') {
			$data['code'] = str_replace(array('{','}'), array('&#123;','&#125;'), $data['code']);
		}
		
		// apply brush
		
		return $this->docs->load_view('admin/partials/code', $data, TRUE);
	}
	
	
	
	public function fn() { //!TODO: make badass
		$code = trim($this->content());
		
		$data = array('fn' => $code);
		
		return $this->docs->load_view('admin/partials/fn', $data, TRUE);
	}
	
	//!TODO: consistently set under\_scores
	//  notes do not need \, but non-plugin content do
	
	
	public function next_topic() {
		return '';
	}
	
	public function prev_topic() {
		return '';
	}
	
	
	public function nav() {
		$module = $this->attribute('module');
		$view = $this->attribute('view', 'admin/partials/nav');
		
		$nav = $this->docs->get_toc($module);
		
		# load the helpers
		$this->load->helper('html');
		
		//echo '<pre>'; die(print_r($nav));
		
		$html = $this->docs->load_view($view, $nav);
		
		return $html;
	}

	
}

/* EOF */