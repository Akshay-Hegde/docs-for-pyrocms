<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Docs
 *
 * Document your code.
 *
 * @package  Docs
 * @author   cmfolio
 */

/**
 * Default Docs theme.
 * 
 * A default theme is packaged inside the Docs module.
 * You can overwrite this with your own theme in the
 * other theme folders.
 */
$config['docs.docs_theme'] = 'docs_default';

/**
 * Allowed file extensions
 * 
 * Changing the order changes loading preference
 */
$config['docs.allowed_extensions'] = array('.md','.html','.textile');

/**
 * Default code highlighter type
 */
$config['docs.default_code_brush'] = 'php';


$config['docs.anchor_prefixes'] = array(
	'page' => 'page',
	'section' => 'section',
	'subsection' => 'sub',
	'function' => 'fn',
	'method' => 'fn',
	
	// overkill?
	'note' => 'note',
	'important' => 'important'
);
