<?php

if ( ! function_exists('docs_base_url') ) {
	/**
	 * Generate Docs base URL
	 * 
	 * @param string  $url  The URL to append
	 * @return string
	 */
	function docs_base_url($url = null) {
		if ( ! is_null($url) and $url !== '') {
			$url = '/' . $url;
		}
		$docs_path = is_admin_panel() ? 'admin/docs' : 'docs'; //TODO: docs || documentation
		
		return trim(base_url(), '/') . '/' . $docs_path . $url;
	}
}


if ( ! function_exists('is_admin_panel') ) {
	/**
	 * Determine if admin panel or not
	 * 
	 * @return bool
	 */
	function is_admin_panel() {
		return defined('ADMIN_THEME');
	}
}


if ( ! function_exists('website_area') ) {
	/**
	 * Determine if we are on admin facing or public facing
	 * 
	 * @return  string  admin|public
	 */
	function website_area() {
		return is_admin_panel() ? 'admin' : 'public';
	}
}


// TODO: move to String or Array helper
if ( !function_exists('array_query') ) {
	/**
	 * Query an array in the form of dot notation: `some.deep.array`
	 * @param array   $array
	 * @param string  $query
	 * @param mixed   $set_value
	 * @return array
	 */
	function array_query(&$array, $query, $set_value = null) {
	
	  $keys = explode('.', $query);
	
	  // extract the last key
	  $last_key = array_pop($keys);
	
	  // walk/build the array to the specified key
	  while ($arr_key = array_shift($keys)) {
	    if (!array_key_exists($arr_key, $array)) {
	      $array[$arr_key] = array();
	    }
	    $array = &$array[$arr_key];
	  }
	
	  // set the final key
		if ( is_null($set_value) ){
			return $array[$last_key];
		}
	  return $array[$last_key] = $set_value;
	}
}

/* EOF */