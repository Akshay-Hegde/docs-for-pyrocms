<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Docs
 *
 * Document your code.
 *
 * @package  Docs
 * @author   cmfolio
 */
class Docs {

	private $ci;
	
	private $_theme_path;
	
	/**
	* Holds all the Table of Contents data
	*/
	private $_toc = array();

	/**
	 * Default constructor. Gets CI instance.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		$this->ci =& get_instance();
		
		// we need some config
		$this->ci->load->config('docs/docs');
		$this->ci->lang->load('docs/docs');
	}
	
	
	/**
	 * Load a Documentation file.
	 *
	 * This can load a documentation file from any module
	 *
	 * @access	public
	 * @param   string   The file path within the docs folder
	 * @return	string   The content loaded from the view
	 */
	public function load_file($file_path = NULL, $module = NULL, $autoconvert = TRUE)
	{	
		$docs_folder = $this->ci->config->item('docs.docs_folder');
		$default_filename = $this->ci->config->item('docs.docs_default_filename');
		$allowed_extentions = $this->ci->config->item('docs.allowed_extentions');
		// tbd
		$module_path = NULL;
		$file_ext = NULL;
		// final file path
		$file = NULL;
		
		// automatically get path by url if needed
		if (is_null($file_path)) {
			$segments = array_slice($this->ci->uri->segment_array(), 2); // first two are trash. `admin/docs`
			$file_path = implode('/', $segments);
		}
		
		// if you don't provide a module, we assume the first segment is the module
		if (is_null($module)) {
			$module = $this->get_module_by_url($file_path);
			$file_path = end(explode('/', $file_path, 2)); // returns module name if only one level; we fix later
		}
		
		// if filename.ext, allow file type first
		if (preg_match('#\.#', $file_path)) {
			$file_path = explode('.', $file_path);
			$file_ext = array_pop($file_path);
			$file_path = $file_path[0];
			array_unshift($allowed_extentions, '.' . $file_ext);
		}
		
		
		// if we are on the homepage, set file to default_filename
		if ($home = ($file_path == $module)) {
			$file_path = $default_filename;
		}
		
		// search each module location
		foreach ($this->ci->config->item('modules_locations') as $modules_path) {
			$module_path = FCPATH . str_replace('../', '', $modules_path) .  $module;
			
			// allow multiple file types
			foreach ($allowed_extentions as $ext) {
				
				// normal file location: docs/page
				if (file_exists($module_path . '/' . $docs_folder . '/' . $file_path . $ext)) {
					$file_ext = $ext;
					break;
				}
				
				// now with default filename in subfolder: docs/page/index
				if (file_exists($module_path . '/' . $docs_folder . '/' . $file_path . '/' . $default_filename . $ext)) {
					$file_path = $file_path . '/' . $default_filename;
					$file_ext = $ext;
					break;
				}
				
			} // end each file type
			
		} // end each module location
		
		// full file path and filename.ext
		$file = $module_path . '/' . $docs_folder . '/' . $file_path . $file_ext;
		
		// file check
		if (! file_exists($file)) { //!TODO: better error handling
			show_error('Sorry, we can\'t find that page' . ((ENVIRONMENT !== PYRO_PRODUCTION) ? ' -- File: ' . $file : ''));
		}
		
		// load the file
		$this->ci->load->helper('file');
		$content = read_file($file);
		
		// send it through Lex for fLEXability
		$content = $this->ci->parser->parse_string($content, NULL, TRUE);
		
		// clean up any emptiness to prevent conversion errors
		$content = trim($content);
		
		// allow us to override conversion
		if ($autoconvert) {		
			// detect if markdown and process
			if (preg_match('#\.(md|markdown)#', $file_ext)) { // matches .md.html too!
				$content = parse_markdown($content);
			}
			
			// detect if textile and process
			if (preg_match('#\.(textile)#', $file_ext)) {
				$this->ci->load->library('textile');
				$content = $this->ci->textile->TextileThis($content);
			}
		}
		
		return $content;
	}
	
	
	/**
	 * Load a view file from your theme folder.
	 *
	 * I can't figure out how to load it any other way?
	 *
	 * @access	public
	 * @param   string   The file path within your theme views folder
	 * @param   array    The data array to pass
	 * @param   bool     Whether or not to return content
	 * @param   bool     Whether or not to parse with LEX
	 * @return	string   The content loaded from the view
	 */
	public function load_view($view = NULL, $data = array(), $return = TRUE, $parse = TRUE) {
		
		$content = $this->ci->load->_ci_load(array(
			'_ci_path' => $this->get_theme_path() . 'views/' . $view . '.php',
			'_ci_vars' => ($parse === TRUE) ? $data : array(), //!TODO: fix this trash
			'_ci_return' => $return
		));
				
		if ($parse === TRUE) {
			$content = $this->ci->parser->parse_string($content, $data, TRUE);
		}
				
		return $content;
	}
	
	
	
	public function build($file = NULL) {
		// adds the Docs theme page and sets the Docs theme
		$this->set_theme();
		
		// get the TOC
		$this->get_toc();
		
		// we autoconvert this and it subsequently converts all partials included
		// we do it this way to avoid double-conversion which could cause errors
		$content = $this->load_file($file, NULL, TRUE);
		
		// add some vars
		$data = $this->get_module_details($this->get_module_by_url());
		
		return $this->ci->template->enable_parser(TRUE)->set('module', $data)->build('index', array('docs_body' => $content)); //!TODO: get rid of docs_body
	}
	
	
	
	/**
	* Set the Docs theme
	*/
	public function set_theme($path = NULL) {
		$name = $this->ci->config->item('docs.docs_theme');
		$path OR $path = $this->ci->module_details['path']; //!TODO: Get module path?

		// add to Template locations and set as theme
		$this->ci->template->add_theme_location($path . '/themes/');
		$this->ci->template->set_theme($name);
		$this->_theme_path = $path . '/themes/' . $name . '/';
		$this->ci->asset->add_path('theme', array('path' => $this->_theme_path));
	}
	
	
	/**
	* Get the theme path
	* 
	* Supports non-standard theme paths
	* 
	* Ex: addons/shared_addons/modules/docs/themes/docs/
	*/
	public function get_theme_path() {
		return $this->_theme_path;
	}
	
	
	/**
	* Get the current module
	*/
	public function get_module() {
		return $this->ci->router->fetch_module();
	}
	
	
	/**
	* Get the current module by URL
	*/
	public function get_module_by_url($url = NULL) {
		if (! is_null($url)) {
			$segments = explode('/', $url);
			return $segments[0];
		}
		
		return $this->ci->uri->segment(3); //!TODO: if admin...
	}
	
	
	/**
	* Get a modules details
	*/
	public function get_module_details($slug = NULL) {
		$this->ci->load->model('modules/module_m');
		
		return $this->ci->module_m->get($slug);
	}
	
	
	/*! TOC Functions */
	
	public function get_toc($module = NULL) {
		return $this->_parse_toc($module);
	}
	
	
	
	private function _parse_toc($module = NULL) {
		/*
		  FORMAT
		  
		  by_category => Array (
		  
				category_uri => Array (
					page_uri => page_data,
					page_uri => page_data,
					page_uri => page_data,
				),
				category_uri => Array (
					page_uri => page_data,
					page_uri => page_data,
					page_uri => page_data,
				)
			
			),
			
			by_uri => Array (
				page_uri => page_data,
				page_uri => page_data,
				page_uri => page_data,
			)
		  
		*/
		
		# get the module if needed
		if ( is_null($module) ) {
			$module = $this->get_module_by_url();
		}
		
		# get cached TOC if possible
		if ( isset($this->_toc[$module]) ) {
			return $this->_toc[$module];
		}
		
		// TODO: make config prop
		$content = $this->load_file('toc.txt', $module);
		
		preg_match_all('/^(\s*)([^:]*):\s*(.*)$/uim', $content, $matches);
		// $matches[1] = spaces (2 per level; 0 = root, 1 = child, 2 = grandchild, etc.)
		// $matches[2] = type (supported: category|page|anchor|redirect)
		// $matches[3] = params (pipe separated)
		
		#echo '<pre>';die(print_r($matches));
		$toc = array(
			'by_uri' => array(),
			'by_category' => array()
		);
		// holds the previous TOC entry
		$prev = array(
			'category' => '', // holds current category
			'category_uri' => '', // holds current category uri (full)
			'uri_path' => '', // holds all but last segment
			'full_uri' => '',
			'level' => 0 // holds current parent child relationship
		);
		$core_prev = $prev; // used to reset when needed
		
		# loop through each toc item
		foreach ($matches[2] as $i => $type) {
			$type = strtolower($type);
			$params = $this->_extract_toc_params($type, $matches[3][$i]);
			$level = (substr_count($matches[1][$i], ' ') / 2); // generates level
			$move_level = $level - $prev['level'];
			$uri = $params['uri'];
			$uri_path = $prev['uri_path'];
			$category = $prev['category'];
			$category_uri = $prev['category_uri'];
			# anchor support
			// simple takes old page and adds #anchor = page1#anchor NOT page1/#anchor
			$anchor_sep = ($type === 'anchor') ? '' : '/';
			
			# REDIRECT
			// if it's a redirect, we have to set additional values first
			if ($type === 'redirect') {
				$uri = $params['old_uri'];
				$redirect_uri = $params['uri'];
				$redirect_type = $params['type'];
			}
			
			# ERRORS
			if (preg_match('#/+#', $uri)) {
				show_error('Your TOC is malformed: No slashes allowed in URI.');
			}
			if (is_float($level % 2)) {
				show_error('Your TOC is malformed: Indent spaces are only allowed in sets of two (2).');
			}
			
			// if it's a category, we want to update it's category
			if ($type == 'category') {
				
				// if it's a subcategory
				if ($level > 0) {
					// act like a new level, even though we aren't
					// this allows us to ... ?
					$move_level = ($level + 1) - $prev['level'];
					
					// parent level(s), revert back
					if ($move_level < 0) {
						// some fancy work to parse off the last x amount of segments
						$uri_segments = explode('/', $prev['uri_path']);
						$uri_segments = array_slice($uri_segments, 0, count($uri_segments) - ($move_level * -1), TRUE);
						$tmp_prev = $toc['by_uri'][implode('/', $uri_segments)];
						
						$category = $uri;
						$category_uri = $tmp_prev['uri_path'] . '/' . $category;
						$uri_path = $category_uri;
					}
					// go level(s) deeper, add uri
					if ($move_level > 0) {
						$category = $uri;
						$category_uri = $prev['uri_path'] . '/' . $category;
						$uri_path = $category_uri;
					}
				}
				// root category
				else {
					$category = $uri;
					$category_uri = $uri;
					$uri_path = $uri;
				}
				
				$full_uri = $uri_path;
			}
			// it's something else (page | redirect ...)
			else {
				
				// MOVE LEVEL
				// parent level(s), revert back
				if ($move_level < 0) {
					// some fancy work to parse off the last x amount of segments
					$uri_segments = explode('/', $prev['uri_path']);
					$uri_segments = array_slice($uri_segments, 0, count($uri_segments) - ($move_level * -1), TRUE);
					$key = implode('/', $uri_segments);
					$tmp_prev = ($key === '') ? $core_prev : $toc['by_uri'][$key];
					
					//die(print_r($toc['by_uri']));
					// remove # from uri_segments
					//$uri = $tmp_prev['uri'] . $anchor_sep . $uri;
					
					// resets some data
					$category_uri = $tmp_prev['category_uri'];
					$category = $tmp_prev['category'];
					$uri_path = $tmp_prev['full_uri'];
					//die(print_r($tmp_prev));
				}
				// go level(s) deeper, add more uri
				elseif ($move_level > 0) {
					
					// page/subpage support
					if ($prev['type'] !== 'category') {
						$uri_path = $prev['full_uri'];
					}

				}
				
				// uri path
				
				// generate the full uri
				$full_uri = $uri_path . $anchor_sep . $uri;
				
				// if the category is blank, don't add a front slash (ROOT page)
				if ($category === '') {
					$full_uri = $uri_path . $uri;
				}
			} // end if type
			
			
			$page = array_merge($params, array(
				'category' => $category,
				'category_uri' => $category_uri,
				'type' => $type,
				'uri' => $uri,
				'uri_path' => $uri_path,
				'full_uri' => $full_uri,
				'level' => $level,
				
				'-' => '------------------------------',
				// bools
				'is_category' => ($type === 'category'),
				'is_root_category' => ($type === 'category' && $level === 0),
				'is_subcategory' => ($type === 'category' && $level > 0),
				'is_page' => ($type === 'page'),
				'is_root_page' => ($type === 'page' && $level === 0),
				'is_redirect' => ($type === 'redirect'),
			));
			
			// if redirect, add some more data
			if ($page['is_redirect']) {
				$page['redirect_uri'] = $redirect_uri;
				$page['redirect_type'] = $redirect_type;
				
				//!TODO: redirect if current page
				if ($this->ci->uri->uri_string() == $page['uri']) {
					redirect($redirect_uri, 'location', $redirect_type);
				}
			}
			
			//!TODO: add subcategory support
			// if it's a category, create a new array element
			if ($page['is_root_category']) {
				//$toc['by_category'][$category] = array();
			}
			// otherwise make it a child of the category
			else {
				//$toc['by_category'][$category][$uri] = $page;
			}
			
			// set prev
			$prev = $page;
			
			// sorted by URLs
			$toc['by_uri'][$full_uri] = $page;
		} // end foreach toc item
		
		//!!debug
		//echo '<pre>'; die(print_r($toc));
		
		// set it
		$this->_toc[$module] = $toc;
		
		return $toc;
	}
	
	
	public function _extract_toc_params($type, $string) {
		$regex = '/(.[^\||\n]*)(?:\|)?/ui';
		
		# split
		$params = explode('|', $string);
		
		// and trim
		foreach ($params as &$param) {
			$param = trim($param);
		}
		
		$return = array();
		
		# now for each type
		
		if ( in_array($type, array('category', 'page', 'anchor')) ) {
			// uri | title = null
			
			isset($params[1]) or $params[1] = ucwords(preg_replace('/_|\#/ui', ' ', $params[0]));
			
			$return = array(
				'uri' => $params[0],
				'title' => $params[1]
			);
		}
		
		if ($type == 'redirect') {
			// old_uri | uri | type = 301
			
			isset($params[2]) or $params[2] = 301;
			
			$return = array(
				'old_uri' => $params[0],
				'uri' => $params[1], // acts as new_uri
				'type' => (int) $params[2]
			);
		}
		
		return $return;
	}

}

/* EOF */