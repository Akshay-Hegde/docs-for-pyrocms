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
$config['docs.docs_theme'] = 'docs';

/**
 * Default filename for subdirectories
 */
$config['docs.default_filename'] = 'index';

/**
 * Allowed file extensions
 * 
 * Changing the order changes loading preference
 */
$config['docs.allowed_extentions'] = array('.md','.html','.textile');

/**
 * Table of Contents Filename
 * 
 * Include extension if possible. Only change if conflicts with page name.
 * If you want a page dedicated to the Table of Contents,
 * we recommend naming that page 'table_of_contents'
 * 
 * This file will not be accessible as a page
 */
$config['docs.toc_filename'] = 'toc.txt';

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
