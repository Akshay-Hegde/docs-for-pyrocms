# Docs Library

__Docs__ Library is the only library that comes with the module. It handles all interaction with Docs and can be loaded outside of the Docs module when you want to include doc files somewhere else in your module.

{{ docs:fn }}
load_docs_file($file_path = null, $module = null, $autoconvert = true)
{{ /docs:fn }}

`load_docs_file` is used to load files from within the __docs__ folder of your module. This cannot be used to load views, which is the purpose of the `load_theme_view` function.

<var>$file\_path</var> &ndash; File path from inside modules docs folder. Defaults to `$this->get_page_url()`. Ex: <dfn>category/page\_name</dfn>  
<var>$module</var> &ndash; The module to look in. If no module is given, we assume the first segment of <var>$file_path</var> is a module name. Ex: <dfn>MODULE/page/subpage</dfn>  
<var>$autoconvert</var> &ndash; Automatically convert file if Markdown or Textile?


{{ docs:fn }}
load_theme_view($view = null, $data = array(), $return = true, $parse = true)
{{ /docs:fn }}

`load_theme_view` is used to load view files from within your theme. We use this instead of the basic `$this->load->view()` because that function seems to have problems with loading files from non-standard places like a module folder. Please use this function to load your theme view files instead.

{{ docs:note }}
You only have to use the <code>load_theme_view</code> function when you are loading views from your docs theme. Otherwise, you can use the normal <code>$this->load->view()</code> or whatever else you were using.
{{ /docs:note }}



{{ docs:fn }}
build($file = null)
{{ /docs:fn }}

This function is used to generate the Theme output like you would with `$this->template->build()`. We only need to call this __once__. It also handles some other tasks with setting the Docs theme and paths for you.

<var>$file</var> &ndash; The view of your current documentation page. This is automatically filled in with the `$this->get_page_url()`.


{{ docs:fn }}
set_theme($path = null)
{{ /docs:fn }}

This function will set the theme for your Docs template. You will have a theme specifically for your Docs output since it acts like a full website itself.


{{ docs:fn }}
get_theme_path()
{{ /docs:fn }}

This function will get the path to the themes folder we are using. You can call this if you ever need the path for any reason, but we only included it because we use it when setting the theme.


{{ docs:fn }}
get_module_by_url($url = null)
{{ /docs:fn }}

Allows you to get the module by the current URL or the URL you pass in. We use this often internally.

__Rules:__

- If the URL starts with "admin" we will pull the third segment. _(Backend URLs)_  
Ex: <dfn>admin/docs/comments/page/subpage</dfn>
- If the URL starts with "docs" we will pull the second segment. _(Frontend URLs)_  
Ex: <dfn>docs/comments/page/subpage</dfn>


{{ docs:fn }}
get_module_details($slug = null)
{{ /docs:fn }}

Returns an __Array__ of the module details specified. This Array is the same as you set in your modules <dfn>details.php</dfn> file, plus additional information stored in the database.


{{ docs:fn }}
get_page_url($url = null)
{{ /docs:fn }}

Returns the correct URI string to a TOC item. Strips out the <dfn>admin/docs/MODULE</dfn> or <dfn>docs/MODULE</dfn> at the beginning of the URL.

<var>$url</var> &ndash; A specific URL to process. Defaults to current URL.


{{ docs:fn }}
get_toc($module = null)
{{ /docs:fn }}

Get the Table of Contents and parse it out. You can call this function whenever you need to get the TOC. All results will be cached for future use if necessary. You can also use this to get the TOC for other modules if you need to.

<var>$module</var> &ndash; The slug of the module you wish to get TOC of. Defaults to pulling the module by current URL.