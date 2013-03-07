# Docs Standards

We would like to make a standard for writing documentation so Core and Third-party documentation can blend together nicely. These are some rules we adhere to when we're writing out documentation. We hope you can do the same.

{{ docs:note title="Psychology stuff" }}These rules aren't made because of some pesky developer preferences. These were made to ensure the most readable documentation for the user. Docs shouldn't be so complex that you need to read multiple times. Over time the reader will correlate certain styles to their correct context.{{ /docs:note }}

## Formatting Types

We have a few rules for which keywords or types are wrapped in certain tags. This is so when you style your page, users can visually distinct that your term is referencing a specific type and not just test in the page.


### Code

Any code or words referencing source code should be wrapped in a `<code>` tag.

	Use the <code>page</code> function to load the page.

{{ docs:example title="Output" }}Use the <code>page</code> function to load the page.{{ /docs:example }}

When we are referencing a variable or parameter, please use `<var>` instead. This lets us know the value can vary. Functions can use the regular `<code>` tag still.

	<var>$autoconvert</var> -- Do we want to auto-convert the file?

{{ docs:example title="Output" }}<var>$autoconvert</var> -- Do we want to auto-convert the file?{{ /docs:example }}


### File Paths, Filenames, URI Strings

We mark file paths, file names, or URI strings with `<dfn>` tags since it is a path or location definition. This can be styled however you please. We suggest using italics or an alternate color to provide visual importance.

	The cache is stored in <dfn>system/cms/cache</dfn>.

{{ docs:example title="Output" }}The cache is stored in <dfn>system/cms/cache</dfn>.{{ /docs:example }}


### Notes and Importants

If you have something that the user really should know -- such as "gotchas" or unexpected behavior -- please include them as a Note or Important block.

	{{ noparse }}{{ docs:note title="NOTE" }}You probably shouldn't change the name of this file.{{ /docs:note }}
{{ docs:important title="IMPORTANT" }}Seriously, don't change the name of this file.{{ /docs:important }}{{ /noparse }}

{{ docs:example title="Output" noparse="false" }}
{{ docs:note title="NOTE" }}You probably shouldn't change the name of this file.{{ /docs:note }}
{{ docs:important title="IMPORTANT" }}Seriously, don't change the name of this file.{{ /docs:important }}
{{ /docs:example }}


### Examples or Output

In the cases where you want to show the example output of something, you can wrap it in a `{{noparse}}{{ docs:example }}...{{ /docs:example }}{{/noparse}}` block which will generate a pretty boxed container. This helps the user understand what is documentation code vs. what is sample output code.

{{ docs:example }}
If this text wasn't inside an "example" box, how would you know this is sample text output?
{{ /docs:example }}

### Separation

Separate "sections" or visual blocks with horizontal rules. These can be written in HTML with `<hr/>` or in Markdown / Textile with `---`. We use them between function or method documentation as seen on the [Plugin page](plugin).