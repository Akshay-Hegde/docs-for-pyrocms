<?php

if ( !function_exists('docs_base_url') ) {
	function docs_base_url($url = null) {
		if ( !is_null($url) and $url !== '') {
			$url = '/' . $url;
		}
		$docs_path = defined('ADMIN_THEME') ? 'admin/docs' : 'docs'; //TODO: docs || documentation
		
		return trim(base_url(), '/') . '/' . $docs_path . $url;
	}
}


// TODO: move to String or Array helper
if ( !function_exists('array_query') ) {
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