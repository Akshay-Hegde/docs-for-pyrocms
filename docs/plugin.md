# Docs Plugin

The Docs Plugin can help produce common HTML elements for you such as links, code blocks, etc. Using the Docs Plugin will save you tons of time in document creation. 



## Methods

{{ docs:fn }}
{{ docs:page title="" description="" }} 
{{ /docs:fn }}

<var>title</var> &ndash; The title of the page. Will override anything else. (Optional)  
<var>description</var> &ndash; The meta description of the page. (Optional)



{{ docs:fn }}
{{ docs:link module="" uri="" text="" }} 
{{ /docs:fn }}

<var>module</var> &ndash; The name of the module you are linking to. If you are linking within same module, this can be blank since the current module by URL is the default value. (Optional)  
<var>uri</var> &ndash; The URI path to the page without beginning or ending slashes. _Ex: some/page/uri_  
<var>text</var> &ndash; The link text to be displayed. Defaults to URI path.



{{ docs:fn }}
{{ docs:anchor id="" prefix="" type="" }} 
{{ /docs:fn }}

This creates an anchor element to link to within the page. You can change the code output in the <dfn>anchor.php</dfn> view.

<var>id</var> &ndash; The ID of the element.  
<var>prefix</var> &ndash; Set a prefix so similar IDs don't collide. We default the prefix by <var>type</var>. (Optional)  
<var>type</var> &ndash; We have a specific set of prefix types to create a common standard. Please see those {{ docs:link uri="#prefix_types" text="below" }}. (Optional)

{{ docs:anchor id="anchor_example" }}
## Example


{{ docs:link uri="#anchor_example" text="Click here" }} to link to this example directly.



{{ docs:fn }}
{{ docs:partial file="" module="" }} 
{{ /docs:fn }}

This loads a partial file __from your docs folder__. This is different than including a theme view which can be done using the normal `theme:partial` method. This function is meant to allow you to include common items such as recurring warnings or instructions.

<var>file</var> &ndash; The path to the partial relative to your documentation folder.  
<var>module</var> &ndash; The module to look in. This defaults to the current module by URL.



{{ docs:fn }}
{{ docs:note text="" title="" class="" }} 
{{ /docs:fn }}

Create the code for a _note_ item. You can change the output code in the <dfn>note.php</dfn> view.

<var>text</var> &ndash; The text to display inside the note.  
<var>title</var> &ndash; The bolded title of the note. Defaults to _Note_.  
<var>class</var> &ndash; Change or add class(es) to the element. Defaults to _note_.

## Example

{{ docs:note }}
This is some long content
<br/><br/>
because sometimes you need more, ya know?
{{ /docs:note }}



{{ docs:fn }}
{{ docs:important text="" title="" class="" }} 
{{ /docs:fn }}

Almost identical to the `docs:note` method. Create the code for an _important_ item. You can change the output code in the <dfn>important.php</dfn> view.

<var>text</var> &ndash; The text to display inside the important note.  
<var>title</var> &ndash; The bolded title of the note. Defaults to _Important_.  
<var>class</var> &ndash; Change or add class(es) to the element. Defaults to _important_.

## Example

{{ docs:important }}
This is some long content
<br/><br/>
because sometimes you need more, ya know?
{{ /docs:important }}



{{ docs:fn }}
{{ docs:code type="" noparse="" }}...{{ /docs:code }}
{{ /docs:fn }}

Use this method to create a code block with syntax highlighting. We highlight with [Highlight.js](http://softwaremaniacs.org/soft/highlight/en/). This tag must be used with opening and closing tags and is not meant for inline code. For inline code, simply use a `<code>` tag.

<var>type</var> &ndash; This is the language of the code that needs to match up with a [brush from Highlight.js](http://softwaremaniacs.org/media/soft/highlight/test.html). Defaults to the <var>docs.default\_code\_brush</var> config setting.  
<var>noparse</var> &ndash; Apply the `noparse` tag around this content? Defaults to `true` which means to skip parsing. This is because you will most likely need to write code samples including LEX code that should not be parsed.

{{ docs:note title="Tip" }}You might want to turn off the `noparse` tag to include a docs file that holds a common code snippet. This would mean you can edit that code snippet in only one place.{{ /docs:note }}

### Example

{{ docs:code type="html" }}
<div id="content">
	{{ lex:tags allowed="true" }}
</div>
{{ /docs:code }}



{{ docs:fn }}
&#123;&#123; docs:fn noparse="" &#125;&#125;...&#123;&#123; /docs:fn &#125;&#125;
{{ /docs:fn }}

This function creates the default function template as seen in the <dfn>fn.php</dfn> view. This method needs to be called with an open / closing tag pair.

<var>noparse</var> &ndash; Apply the `noparse` tag around this content? Defaults to `true` which means to skip parsing. This is because you will possibly be including LEX code that should not be parsed.



{{ docs:fn }}
{{ docs:nav module="" }}
{{ /docs:fn }}

A nav generation method based on the traditional `navigation:links` method. This will accept any of the params as `navigation:links` plus has a few of it's own. This function can also be used as an open / closing tag pair.

<var>module</var> &ndash; The name of the module to pull the nav from. Defaults to the current module by URL. 
