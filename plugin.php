<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Docs
 *
 * Document your code.
 *
 * @package  Docs
 * @author   cmfolio
 */
class Plugin_Docs extends Plugin {

	private $_anchor_prefixes;
	
	
	private function _anchor_prefix($type) {
		if ( ! isset($type) ) {
			$this->_anchor_prefixes = config_item('docs.anchor_prefixes');
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
		$description = $this->attribute('description');
		//$breadcrumb = $this->attribute('breadcrumb', true);
		
		$this->template->title($title);
		
		return '';
	}

	
	/**
	 * Create a link within docs
	 */
	public function link()
	{
		$current_module = $this->docs->get_module_by_url();
		$current_url = $this->docs->get_page_url();
		$module = $this->attribute('module', $current_module);
		$module_path = $module .'/';
		$uri = trim($this->attribute('uri', ''), '/');
		$text = $this->attribute('text', $uri);
		$is = null;
		//!TODO: add more attributes
		
		# act like a normal <a> tag
		// if anchor, just append to current uri
		if ( $uri == '' or preg_match('/^\#/i', $uri) ) {
			($uri == '') or $is = 'anchor';
			$uri = $this->docs->get_page_url() . $uri;
		}
		// if relative urls ../ we fix it
		if ( preg_match('/^\.\.\//i', $uri) ) {
			$is = 'relative';
			$old_uri = $uri;
			$uri_segments = explode('../', $uri);
			$levels = count($uri_segments) - 1;
			$segments = array_slice(explode('/', $current_url), 0, ($levels * -1));
			$uri = (count($segments) > 0 ? implode('/', $segments) . '/' : '') . end($uri_segments);
			// reset the text if necessary
			if ($text == $old_uri) {
				$text = $uri;
			}
		}
		
		# does the item exist?
		// we only check for current module since we know it exists
		if ($current_module == $module and $is != 'anchor') { // anchors don't always exist in the TOC
			$toc = $this->docs->get_toc($module);
			if ( !isset($toc['by_uri'][$uri]) ) {
				log_message('error', 'Docs Link: `' . $uri . '` does not exist in the `' . $module . '` TOC. Linked from `' . $current_module . '/' . $current_url . '`.');
			}
			elseif ($text == '' or $text == $uri) {
				$text = $toc['by_uri'][$uri]['title'];
			}
		}
		
		# generate some text
		if ($text == '' or $text == $uri) {
			$text = ($current_module != $module ? $module_path : '') . $uri;
		}
		
		return anchor(docs_base_url($module_path . $uri), $text);
	}
	
	
	public function anchor() {
		$id = $this->attribute('id');
		$prefix = strtobool( $this->attribute('prefix', true) );
		$type = $this->attribute('type', '');
		
		if ( is_null($id) ) {
			return '';
		}
		
		if ($type !== '' || $prefix == true) {
			$id = $this->_anchor_prefix($type) . $id;
		}
		
		$data = array('id' => $id);
		
		return $this->docs->load_theme_view('admin/partials/anchor', $data, true);
	}
	
	
	/**
	 * Include a partial from docs folder
	 * 
	 * This is different than `template:partial` because it pulls from docs folder only
	 */
	public function partial() {
		$file = $this->attribute('file');
		
		if ( is_null($file) ) {
			return '';
		}
		
		$module = $this->attribute('module', $this->docs->get_module_by_url());
		
		// we don't want to autoconvert here
		// we do it this way to avoid double-conversion which could cause errors
		return $this->docs->load_docs_file($file, $module, false);
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
		
		return $this->docs->load_theme_view('admin/partials/note', $data, true);
	}
	
	
	public function small() {
		$text = $this->attribute('text', $this->content());
		$class = $this->attribute('class', 'disclaimer');
		
		$data = array(
			'text' => $text,
			'class' => $class
		);
		
		return $this->docs->load_theme_view('admin/partials/small', $data, true);
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
		
		return $this->docs->load_theme_view('admin/partials/important', $data, true);
	}
	
	
	
	public function code() {
		$type = $this->attribute('type', config_item('docs.default_code_brush'));
		$noparse = strtobool( $this->attribute('noparse', true) );
		$code = $this->content();
		
		$data = array(
			'type' => $type,
			'code' => htmlspecialchars($code, ENT_NOQUOTES)
		);
		
		if ($noparse) {
			$data['code'] = noparse($data['code']);
		}
		
		// apply brush
		
		return $this->docs->load_theme_view('admin/partials/code', $data, true);
	}
	
	
	public function fn() {
		$code = trim($this->content());
		
		$parse = strtobool( $this->attribute('noparse', true) );
		
		if ($parse) {
			$code = noparse($code);
		}
		
		$data = array('fn' => $code);
		
		return $this->docs->load_theme_view('admin/partials/fn', $data, true);
	}
	
	
	public function next_topic() {
		return '';
	}
	
	public function prev_topic() {
		return '';
	}
	
	
	public function nav() {
		$module = $this->attribute('module');
		//$view = $this->attribute('view');
		
		$nav = $this->docs->get_toc($module);
		
		return $this->_build_links($nav['nav'], $this->content());
	}
	
	
	
	
	/**
	 * Temporary fix for Navigation plugin integration
	 */
	
	/**
	 * Navigation
	 *
	 * Creates a list of menu items
	 *
	 * Usage:
	 * {{ navigation:links group="header" }}
	 * Optional:  indent="", tag="li", list_tag="ul", top="text", separator="", group_segment="", class="", more_class="", wrap=""
	 * @param	array
	 * @return	array
	 */
	private function nav_links()
	{
		$group			= $this->attribute('group');
		$group_segment	= $this->attribute('group_segment');

		is_numeric($group_segment) and $group = $this->uri->segment($group_segment);

		// We must pass the user group from here so that we can cache the results and still always return the links with the proper permissions
		$params = array(
			$group,
			array(
				'user_group' => ($this->current_user AND isset($this->current_user->group)) ? $this->current_user->group : false,
				'front_end' => true,
				'is_secure' => IS_SECURE,
			)
		);
		
		$links = $this->pyrocache->model('navigation_m', 'get_link_tree', $params, Settings::get('navigation_cache'));

		return $this->_build_links($links, $this->content());
	}

	private function _build_links($links = array(), $return_arr = true)
	{
		static $current_link	= false;
		static $level		= 0;

		$top			= $this->attribute('top', false);
		$separator		= $this->attribute('separator', '');
															//deprecated
		$link_class		= $this->attribute('link-class', $this->attribute('link_class', ''));
															//deprecated
		$more_class		= $this->attribute('more-class', $this->attribute('more_class', 'has_children'));
		$current_class	= $this->attribute('class', 'current');
		$first_class	= $this->attribute('first-class', 'first');
		$last_class		= $this->attribute('last-class', 'last');
		$output			= $return_arr ? array() : '';
		$wrap			= $this->attribute('wrap');
		$i		= 1;
		$total	= sizeof($links);

		if ( ! $return_arr)
		{
			$tag		= $this->attribute('tag', 'li');
														//deprecated
			$list_tag	= $this->attribute('list-tag', $this->attribute('list_tag', 'ul'));

			switch ($this->attribute('indent'))
			{
				case 't':
				case 'tab':
				case '	':
					$indent = "\t";
					break;
				case 's':
				case 'space':
				case ' ':
					$indent = "    ";
					break;
				default:
					$indent = false;
					break;
			}

			if ($indent)
			{
				$ident_a = repeater($indent, $level);
				$ident_b = $ident_a . $indent;
				$ident_c = $ident_b . $indent;
			}
		}

		foreach ($links as $link)
		{
			$item		= array();
			$wrapper	= array();

			// attributes of anchor
			$item['url']					= $link['url'];
			$item['title']					= $link['title'];
			if($wrap)
			{
				$item['title']  = '<'.$wrap.'>'.$item['title'].'</'.$wrap.'>';
			}
			
			$item['attributes']['target']	= $link['target'] ? 'target="' . $link['target'] . '"' : null;
			$item['attributes']['class']	= $link_class ? 'class="' . $link_class . '"' : '';

			// attributes of anchor wrapper
			$wrapper['class']		= $link['class'] ? explode(' ', $link['class']) : array();
			$wrapper['children']	= $return_arr ? array() : null;
			$wrapper['separator']	= $separator;

			// is single ?
			if ($total === 1)
			{
				$wrapper['class'][] = 'single';
			}

			// is first ?
			elseif ($i === 1)
			{
				$wrapper['class'][] = $first_class;
			}

			// is last ?
			elseif ($i === $total)
			{
				$wrapper['class'][]		= $last_class;
				$wrapper['separator']	= '';
			}

			// has children ? build children
			if ($link['children'])
			{
				++$level;
				$wrapper['class'][] = $more_class;
				$wrapper['children'] = $this->_build_links($link['children'], $return_arr);
				--$level;
			}

			// is this the link to the page that we're on?
			if (preg_match('@^'.current_url().'/?$@', $link['url']) OR ($link['link_type'] == 'page' AND $link['is_home']) AND site_url() == current_url())
			{
				$current_link = $link['url'];
				$wrapper['class'][] = $current_class;
			}

			// is the link we're currently working with found inside the children html?
			if ( ! in_array($current_class, $wrapper['class']) AND 
				isset($wrapper['children']) AND 
				$current_link AND 
				((is_array($wrapper['children']) AND in_array($current_link, $wrapper['children'])) OR 
				(is_string($wrapper['children']) AND strpos($wrapper['children'], $current_link))))
			{
				// that means that this link is a parent
				$wrapper['class'][] = 'has_' . $current_class;
			}

			++$i;

			if ($return_arr)
			{
				$item['target']		=& $item['attributes']['target'];
				$item['class']		=& $item['attributes']['class'];
				$item['children']	= $wrapper['children'];

				if ($wrapper['class'] && $item['class'])
				{
					$item['class'] = implode(' ', $wrapper['class']) . ' ' . substr($item['class'], 7, -1);
				}
				elseif ($wrapper['class'])
				{
					$item['class'] = implode(' ', $wrapper['class']);
				}

				if ($item['target'])
				{
					$item['target'] = substr($item['target'], 8, -1);
				}

				// assign attributes to level family
				$output[] = $item;
			}
			else
			{
																							//deprecated
				$add_first_tag = $level === 0 && ! in_array($this->attribute('items-only', $this->attribute('items_only', 'true')), array('1','y','yes','true'));

				// render and indent or only render inline?
				if ($indent)
				{
					// remove all empty values so we don't have an empty class attribute
					$classes = implode(' ', array_filter($wrapper['class']));

					$output .= $add_first_tag ? "<{$list_tag}>" . PHP_EOL : '';
					$output .= $ident_b . '<' . $tag . ($classes > '' ? ' class="' . $classes . '">' : '>') . PHP_EOL;
					$output .= $ident_c . ((($level == 0) AND $top == 'text' AND $wrapper['children']) ? $item['title'] : anchor($item['url'], $item['title'], trim(implode(' ', $item['attributes'])))) . PHP_EOL;

					if ($wrapper['children'])
					{
						$output .= $ident_c . "<{$list_tag}>" . PHP_EOL;
						$output .= $ident_c . $indent . str_replace(PHP_EOL, (PHP_EOL . $indent),  trim($ident_c . $wrapper['children'])) . PHP_EOL;
						$output .= $ident_c . "</{$list_tag}>" . PHP_EOL;
					}

					$output .= $wrapper['separator'] ? $ident_c . $wrapper['separator'] . PHP_EOL : '';
					$output .= $ident_b . "</{$tag}>" . PHP_EOL;
					$output .= $add_first_tag ? $ident_a . "</{$list_tag}>" . PHP_EOL : '';
				}
				else
				{
					// remove all empty values so we don't have an empty class attribute
					$classes = implode(' ', array_filter($wrapper['class']));

					$output .= $add_first_tag ? "<{$list_tag}>" : '';
					$output .= '<' . $tag . ($classes > '' ? ' class="' . $classes . '">' : '>');
					$output .= (($level == 0) AND $top == 'text' AND $wrapper['children']) ? $item['title'] : anchor($item['url'], $item['title'], trim(implode(' ', $item['attributes'])));

					if ($wrapper['children'])
					{
						$output .= "<{$list_tag}>";
						$output .= $wrapper['children'];
						$output .= "</{$list_tag}>";
					}

					$output .= $wrapper['separator'];
					$output .= "</{$tag}>";
					$output .= $add_first_tag ? "</{$list_tag}>" : '';
				}
			}
		}

		return $output;
	}

	
}

/* EOF */