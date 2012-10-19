# Docs Standards

We would like to make a standard for writing documentation so Core and Third-party documentation can blend together nicely. These are some rules we adhere to when we're writing out documentation. We hope you can do the same.

{{ docs:note }}These rules aren't made because of some pesky developer preferences. These were made to ensure the most readable documentation for the user. Docs shouldn't be so complex that you need to read multiple times.{{ /docs:note }}

## Formatting Types

We have a few rules for which keywords or types are wrapped in certain tags. This is so when you style your page, users can visually distinct that your term is referencing a specific type and not just test in the page.


### Code

Any code or words referencing source code should be wrapped in a `<code>` tag.

	Use the <code>page</code> function to load the page.

__Output:__ Use the <code>page</code> function to load the page.

When we are referencing a variable or parameter, please use `<var>` instead. This lets us know the value can vary. Functions can use the regular `<code>` tag still.

	<var>$autoconvert</var> -- Do we want to auto-convert the file?

__Output:__ <var>$autoconvert</var> -- Do we want to auto-convert the file?


### File Paths, Filenames, URI Strings

We mark file paths, file names, or URI strings with `<dfn>` tags since it is a path or location definition. This can be styled however you please. We suggest using italics or an alternate color to provide visual importance.

	The cache is stored in <dfn>system/cms/cache</dfn>.

__Output:__ The cache is stored in <dfn>system/cms/cache</dfn>.


### Notes and Importants

If you have something that the user really should know -- such as "gotchas" or unexpected behavior -- please include them as a Note or Important block.

	{{ noparse }}{{ docs:note title="NOTE" }}You probably shouldn't change the name of this file.{{ /docs:note }}
{{ docs:important title="IMPORTANT" }}Seriously, don't change the name of this file.{{ /docs:important }}{{ /noparse }}

__Output:__
{{ docs:note title="NOTE" }}You probably shouldn't change the name of this file.{{ /docs:note }}
{{ docs:important title="IMPORTANT" }}Seriously, don't change the name of this file.{{ /docs:important }}

