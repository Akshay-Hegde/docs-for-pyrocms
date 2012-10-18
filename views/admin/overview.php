<!--

/**
 * Docs
 *
 * Document your code.
 *
 * @package  Docs
 * @author   cmfolio
 * @license  Public Domain
 * @desc     This file is specific to the Docs module and not meant to be edited.
 *           If you are trying to edit the theme, please see the themes folder instead.
 */

-->

<section class="title">
	<h4 id="tagline"><?php echo lang('docs.tagline'); ?></h4>
</section>

<section class="item">

	<p id="easy">It's easy.<br/><small>Follow these steps:</small></p>
	
	<div id="steps">
		<ol>
			<li><span>Add a new <strong>docs</strong> folder in your module<br/>
					<small>Example: <?php echo SHARED_ADDONPATH ?>modules/<em>module_name</em>/<strong>docs</strong>/</small>
			</span></li>
			<li><span>
				Create pages like you would in the <em>views</em> folder<br/>
				<small></small>
			</span></li>
			<li><span>
				View your Docs in the admin<br/>
				<small><?php echo BASE_URL . $this->uri->uri_string() ?>/<em>module_name</em>/</small>
			</span></li>
		</ol>
	</div>
	
	<p id="learn">Want to learn more? &mdash; <a href="<?php echo $this->uri->uri_string() ?>/docs">Read the docs &raquo;</a></p>

	<span class="clear"></span>
</section>
