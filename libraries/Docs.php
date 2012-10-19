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
	
	/**
	 * Look at you, all pretty and shit.
	 */
	private $ci;
	
	/**
	 * Holds custom theme path
	 */
	private $_theme_path;
	
	/**
	 * Holds all the Table of Contents data
	 */
	private $_toc = array();

	/**
	 * Default constructor. Gets CI instance.
	 */
	public function __construct() {
		$this->ci =& get_instance();
		
		// we need some config
		$this->ci->load->config('docs/docs');
		$this->ci->lang->load('docs/docs');
		$this->ci->load->helper('docs/docs');
		
		// some unchangeable config items
		$this->ci->config->set_item('docs.docs_folder', 'docs');
		$this->ci->config->set_item('docs.toc_filename', 'toc.txt');
		$this->ci->config->set_item('docs.default_filename', 'index');
	}
	
	
	/**
	 * Load a Documentation file.
	 *
	 * This can load a documentation file from any module
	 * 
	 * If no `file_path` is given, we use current URL.
	 * If no `module` is given, we assume the first segment of `file_path` is a module name
	 *
	 * @param   string  $file_path        The file path within the docs folder (Ex: page/subpage)
	 * @param   string  $module           The module to look into?
	 * @param   bool    $autoconvert      Automatically convert based on file type?
	 * @param   bool    $return_filepath  Return the correct filepath instead?
	 * @return  string                    The content loaded from the view
	 */
	public function load_docs_file($file_path = null, $module = null, $autoconvert = true, $return_filepath = false)
	{	
		$docs_folder = config_item('docs.docs_folder');
		$default_filename = config_item('docs.default_filename');
		$allowed_extensions = config_item('docs.allowed_extensions');
		$toc_file = config_item('docs.toc_filename');
		$toc_filename = pathinfo($toc_file, PATHINFO_FILENAME);
		// tbd
		$module_path = null;
		$file_ext = null;
		// final file path
		$file = null;
		
		// automatically get path by url if needed
		if ( is_null($file_path) ) {
			$file_path = $this->get_page_url(); // gets the corrected url
			$module = $this->get_module_by_url(); // automatically get the module too
		}
		
		// if you don't provide a module, we assume the first segment of `file_path` is the module
		if ( is_null($module) ) {
			$segments = explode('/', $file_path);
			$module = array_shift($segments);
			$file_path = implode('/', $segments);
		}
		
		// if filename.ext, allow file type first
		if (preg_match('#\.#', $file_path)) {
			$file_path = explode('.', $file_path);
			$file_ext = array_pop($file_path);
			$file_path = $file_path[0];
			array_unshift($allowed_extensions, '.' . $file_ext);
		}
		
		
		// if we are on the homepage, set file to default_filename
		if ($home = ($file_path === '' or $file_path == $default_filename)) {
			$file_path = $default_filename;
		}
		
		// search each module location
		foreach (config_item('modules_locations') as $modules_path) {
			$module_path = FCPATH . str_replace('../', '', $modules_path) .  $module;
			
			// allow multiple file types
			foreach ($allowed_extensions as $ext) {
				
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
		if ( !file_exists($file) ) { //!TODO: better error handling
			show_error('Sorry, we can\'t find that page' . ((ENVIRONMENT !== PYRO_PRODUCTION) ? ' -- File: ' . $file : ''));
		}
		
		# return filepath if desired
		// allows us to do what we want with the file
		if ($return_filepath) {
			return $file;
		}
		
		# load the file and process
		$this->ci->load->helper('file');
		$content = read_file($file);
		
		// if it's the TOC, return it meow!
		if ( end(explode('/', $file)) == $toc_file ) {
			return $content;
		}
		
		// send it through Lex for fLEXability
		$content = $this->ci->parser->parse_string($content, null, true);
		
		// clean up any emptiness to prevent conversion errors
		$content = trim($content);
		
		# auto-conversion
		if ($autoconvert) {		
			// detect if markdown and process
			if (preg_match('#\.(md|markdown)#i', $file_ext)) { // matches .md.html too!
				$this->ci->load->helper('markdown');
				$content = parse_markdown($content);
			}
			
			// detect if textile and process
			if (preg_match('#\.(textile)#i', $file_ext)) {
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
	public function load_theme_view($view = null, $data = array(), $return = true, $parse = true) {
		
		$content = $this->ci->load->_ci_load(array(
			'_ci_path' => $this->get_theme_path() . 'views/' . $view . '.php',
			'_ci_vars' => ($parse === true) ? $data : array(), //!TODO: fix this trash
			'_ci_return' => $return
		));
				
		if ($parse === true) {
			$content = $this->ci->parser->parse_string($content, $data, true);
		}
				
		return $content;
	}
	
	
	
	public function build($file = null) {
		// adds the Docs theme page and sets the Docs theme
		$this->set_theme();
		
		// get the TOC
		$this->get_toc();
		
		// we autoconvert this and it subsequently converts all partials included
		// we do it this way to avoid double-conversion which could cause errors
		$content = $this->load_docs_file($file, null, true);
		
		// add some vars
		$data = $this->get_module_details($this->get_module_by_url());
		
		return $this->ci->template->enable_parser(true)->set('module', $data)->build('index', array('docs_body' => $content)); //!TODO: get rid of docs_body
	}
	
	
	
	/**
	* Set the Docs theme
	*/
	public function set_theme($path = null) {
		$name = config_item('docs.docs_theme');
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
	 * Get the current module by URL
	 * 
	 * BACKEND URLs:
	 * If the URL starts with "admin" we will
	 * pull the third segment.
	 * Ex: admin/docs/comments/page/subpage
	 * 
	 * FRONTEND URLs:
	 * If the URL starts with "docs" we will
	 * pull the second segment.
	 * Ex: docs/comments/page/subpage
	 * 
	 * @param   string  $url  A custom URL to test
	 * @return  string        The module slug
	 */
	public function get_module_by_url($url = null) {
		// if no URL given, get the current URL
		$url or $url = $this->ci->uri->uri_string();
		$segments = explode('/', strtolower($url));
		
		// admin URLs
		if ($segments[0] === 'admin' and isset($segments[2])) {
			$module = $segments[2];
		}
		// frontend URLs
		elseif ($segments[0] === 'docs' and isset($segments[1])) { // TODO: docs || documentation
			$module = $segments[1];
		}
		else {
			show_error('Cannot get module by URL: ' . $url);
		}
		
		return $module;
	}
	
	
	/**
	* Get a modules details
	*/
	public function get_module_details($slug = null) {
		$this->ci->load->model('modules/module_m');
		
		return $this->ci->module_m->get($slug);
	}
	
	
	
	/**
	 * Get the correct page URL
	 * 
	 * Strips out the 'admin/docs/module' or
	 * 'docs/module' at the beginning of the URL 
	 * 
	 * @param   string  $url  A custom URL to test
	 * @return  string        The page URL
	 */
	public function get_page_url($url = null) {
		// if no URL given, get the current URL
		$url or $url = $this->ci->uri->uri_string();
		$segments = explode('/', strtolower($url));
		$page_uri = null;
		
		// ERROR
		if (count($segments) == 1) {
			show_error('Cannot get page URL. Must be at least two segments long: ' . $url);
		}
		
		// frontend root
		if (count($segments) == 2) {
			$page_uri = '';
		}
		// URL is formed correctly, break it down now.
		if (count($segments) > 2) {
			// gotta be 'admin'
			if ($segments[0] === 'admin') {
				$segments = array_slice($segments, 3);
			}
			// gonna be front end
			elseif ($segments[0] === 'docs') { // TODO: docs || documentation
				$segments = array_slice($segments, 2);
			}
			
			// if we stripped everything, we're at the root
			if (count($segments) == 0) {
				$page_uri = '';
			}
		}
		
		// this means it ran into one of the rules,
		// so merge it back together now
		if ( is_null($page_uri) ) {
			$page_uri = implode('/', $segments);
		}
		
		return $page_uri;
	}
	
	
	/*! TOC Functions */
	
	public function get_toc($module = null) {
		return $this->_parse_toc($module);
	}


	/**
	 * Parse out the TOC from our custom format
	 * 
	 * See documentation for expected formats and more info
	 * 
	 * @param  array  $module  The module slug
	 * @return array
	 */
	private function _parse_toc($module = null) {
		/*
		  FORMAT
			
			by_uri => Array (
				page_uri => page_data,
				page_uri => page_data,
				page_uri => page_data
			),
			
			// {{ navigation:links }} compatible
			nav => Array (
				id => page_data,
				id => page_data,
				id => page_data
			),
			
			// this is used to determine cache busting
			updated => time()
		  
		*/
		
		# get the module if needed
		if ( is_null($module) ) {
			$module = $this->get_module_by_url();
		}
		
		# get cached TOC if possible
		if ( isset($this->_toc[$module]) ) {
			log_message('debug', 'TOC Cache: Internal');
			return $this->_toc[$module];
		}
		
		// check the actual cache for the file
		$this->ci->load->library('pyrocache');
		$pyrocache_file = 'docs_m/' . $module . '_toc';
		// if it does, get it instead
		if ($toc_cache = $this->ci->pyrocache->get($pyrocache_file)) {
			$toc_cache = unserialize($toc_cache);
			
			if ($this->_toc_last_updated($module) <= $toc_cache['updated']) {
				log_message('debug', 'TOC Cache: PyroCache');
				$this->_toc[$module] = $toc_cache;
				return $this->_toc[$module];
			}
		}
		
		// if we grabbed the cache but make it here,
		// it was out of date.
		($toc_cache) ? log_message('debug', 'TOC Cache: TOC Updated') : log_message('debug', 'TOC Cache: None');
		
		# load the toc file
		$content = $this->load_docs_file(config_item('docs.toc_filename'), $module);
		
		preg_match_all('/^(\s*)([a-z|][^:]*):\s*(.*)$/uim', $content, $matches);
		// $matches[1] = spaces (2 per level; 0 = root, 1 = child, 2 = grandchild, etc.)
		// $matches[2] = type (supported: category|page|anchor|redirect)
		// $matches[3] = params (pipe separated)
		
		$toc = array(
			'by_uri' => array(),
			'nav' => array(),
			'updated' => time()
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
			$current_uri = $this->get_page_url();
			$uri = ($params['uri'] == config_item('docs.default_filename')) ? '' : $params['uri'];
			$uri_path = $prev['uri_path'];
			$category = $prev['category'];
			$category_uri = $prev['category_uri'];
			# anchor support
			// takes old page and adds #anchor = page1#anchor NOT page1/#anchor
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
						$uri_segments = array_slice($uri_segments, 0, count($uri_segments) - ($move_level * -1), true);
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
				
				# MOVE LEVEL
				// parent level(s), revert back
				if ($move_level < 0) {
					// some fancy work to parse off the last x amount of segments
					$uri_segments = explode('/', $prev['uri_path']);
					$uri_segments = array_slice($uri_segments, 0, count($uri_segments) - ($move_level * -1), true);
					$key = implode('/', $uri_segments);
					$tmp_prev = ($key === '') ? $core_prev : $toc['by_uri'][$key];
					
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
				
				// generate the full uri
				$full_uri = $uri_path . $anchor_sep . $uri;
				
				// no category and no uri path means it's parent is root level
				// so we don't want to start with a /
				if ($category === '' and $uri_path === '') {
					$full_uri = $uri_path . $uri;
				}
			} // end if type
			
			
			$page = array_merge($params, array(
			  'module' => $module,
				'category' => $category,
				'category_uri' => $category_uri,
				'type' => $type,
				'uri' => $uri,
				'uri_path' => $uri_path,
				'full_uri' => $full_uri,
				'full_url' => docs_base_url($module . '/' . $full_uri),
				'level' => $level,
				// bools
			  'is' => array(
				  'current' => ($current_uri == $full_uri),
				  'home' => ($uri === ''),
				  'root' => $level === 0,
				  'root_category' => ($type === 'category' && $level === 0),
					'root_page' => ($type === 'page' && $level === 0),
					$type => true,
					'subcategory' => ($type === 'category' && $level > 0),
				)
			));
			
			// create classes for `is` categories
			$page['is']['classes'] = '';
			foreach ($page['is'] as $key => $bool) {
				if ($bool) {
					$page['is']['classes'] .= ' is_' . $key;
				}
			}
			
			// if redirect, add some more data
			if ( isset($page['is']['redirect']) ) {
				$page['redirect_uri'] = $redirect_uri;
				$page['redirect_type'] = $redirect_type;
				
				// redirect if current page
				if ($this->get_page_url() == $page['full_uri']) {
					redirect(docs_base_url($module . '/' . $redirect_uri), 'location', $redirect_type);
				}
			}
			
			//!TODO: add subcategory support
			
			# generating nav array
			$nav_array = &$toc['nav'];
			
			$nav_page = array_merge(array(
        'id' => 1,
        //'title' => $page['title'], // Merged later
        'parent' => $page['category_uri'],
        'link_type' => $page['type'],
        'page_id' => $page['uri'],
        'module_name' => $page['module'],
        'url' => $page['full_url'],
        'uri' => $page['uri'],
        'navigation_group_id' => $module . '_docs',
        'position' => null, // unsupported
        'target' => null, // unsupported
        'restricted_to' => null, // unsupported
        'current' => $page['is']['current'],
        'class' => $page['is']['classes'],
        'is_home' => $page['is']['home'],
        'children' => array ()
      ), $page);
			
			// it's NOT root item, add it as a child
			if ( !$page['is']['root'] ) {
				// add it to its parent category
				$segments = explode('/', $page['category_uri']);
				
				// loops down to parent so we can set it as a child
				while ($key = array_shift($segments)) {
					// this means its a new category
					if ( !array_key_exists($key, $nav_array) ) {
						// we'll create the category later
						continue;
					}
					
					$nav_array = &$nav_array[$key]['children'];
				}
			}
			
			// all items go through this
			// category acting as id's
			if ( isset($page['is']['category']) ) {
				$nav_array[$page['category']] = $nav_page;
			}
			elseif ( isset($page['is']['redirect']) ) {
				// we don't add redirects to the nav
			}
			// no id, just give it a num
			else {
				$nav_array[] = $nav_page;
			}
			
			# set prev
			$prev = $page;
			
			# sorted by URLs
			$toc['by_uri'][$full_uri] = $page;
		} // end foreach toc item
		
		//!!debug
		#echo '<pre>'; die(print_r($toc));
		
		# cache it
		$this->_toc[$module] = $toc;
		
		// and cache for later
		// we set expiring to far future because we check alternatively
		$this->ci->pyrocache->write(serialize($toc), $pyrocache_file, 123456789);
		
		return $toc;
	}
	
	
	public function _extract_toc_params($type, $string) {
		// we can use this regex if performance is better
		//$regex = '/(.[^\||\n]*)(?:\|)?/ui';
		// TODO: test performance
		
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
			
			isset($params[1]) or $params[1] = ucwords(trim(preg_replace('/_|\#/ui', ' ', $params[0])));
			
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


	private function _toc_last_updated($module = null) {
		// default to module by URL
		!is_null($module) or $module = $this->get_module_by_url();
		
		// return just the filepath
		$file = $this->load_docs_file(config_item('docs.toc_filename'), $module, false, true);
		
		return filemtime($file);
	}

}

/* EOF */