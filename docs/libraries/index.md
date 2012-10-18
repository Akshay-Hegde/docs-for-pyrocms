# Libraries

## Docs

__Docs__ Library is the only library that comes with the module. It handles all interaction with __Docs__ and can be loaded outside of the __Docs__ module when you want to include doc files somewhere else in your module.

{{ docs:fn }}
load_docs_file($file_path = NULL, $module = NULL, $autoconvert = TRUE)
{{ /docs:fn }}

<dfn>load\_file</dfn> is used to load files from within the __docs__ folder of your module. This cannot be used to load views, which is the purpose of the <dfn>load\_view</dfn> function.


{{ docs:fn }}
load_theme_view($view = NULL, $data = array(), $return = TRUE, $parse = TRUE)
{{ /docs:fn }}

<dfn>load\_view</dfn> is used to load view files from within your theme. We use this instead of the basic <dfn>$this->load->view()</dfn> because that function seems to have problems with loading files from non-standard places like a module folder. Please use this function to load your theme view files instead.

{{ docs:note }}
You only have to use this <dfn>load_view</dfn> function when you are loading views from your docs theme. Otherwise, you can use the normal <dfn>$this->load->view()</dfn> or whatever else you were using.
{{ /docs:note }}



{{ docs:fn }}
build($file = NULL)
{{ /docs:fn }}

This function is used to generate the Theme output like you would with <dfn>$this->template->build()</dfn>. We only need to call this __once__. It also handles some other tasks with setting the Docs theme and paths for you.


{{ docs:fn }}
set_theme($path = NULL)
{{ /docs:fn }}

This function will set the theme for your Docs template. You will have a theme specifically for your Docs output since it acts like a full website itself.


{{ docs:fn }}
get_theme_path()
{{ /docs:fn }}

This function will get the path to the themes folder we are using. You can call this if you ever need the path for any reason, but we only included it because we use it when setting the theme.

{{ docs:fn }}
get_module()
{{ /docs:fn }}

Returns the _slug_ of the current module you are running. If you call <dfn>$this->docs->_*_()</dfn> it will return the module that called it. Otherwise, it will most likely return the __docs__ module and you will want to use <dfn>get\_module\_by\_url()</dfn> instead.

{{ docs:fn }}
get_module_by_url($url = NULL)
{{ /docs:fn }}

Allows you to get the module by the current URL or the URL you pass in. We use this often internally. It currently __DOES NOT__ account for the admin segment in the URL.


{{ docs:fn }}
get_module_details($slug = NULL)
{{ /docs:fn }}

Returns an Array of the module details specified. This Array is the same as you set in your modules <var>details.php</var> file.
