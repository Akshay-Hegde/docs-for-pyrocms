# Docs Plugin

The Docs Plugin can help produce common HTML elements for you such as links, code blocks, etc. Using the Docs Plugin will save you tons of time in document creation.

## Methods

---

{{ docs:fn }}
{{ docs:page title="" description="" }} 
{{ /docs:fn }}

Sets the `<meta>` title and description tags for the current page.

<var>title</var>  
The title of the page. Will override anything else. _(Optional)_  

<var>description</var>  
The meta description of the page. _(Optional)_

---

{{ docs:fn }}
{{ docs:link module="" uri="" text="" }} 
{{ /docs:fn }}

Generates a link within your documentation somewhere. This function has a few built in features to help improve reliability of your docs.

<var>module</var>  
The name of the module you are linking to. If you are linking within same module, this can be blank since the current module by URL is the default value. _(Optional)_  

<var>uri</var>  
The URI path to the page without beginning or ending slashes.  
_Ex:_ <dfn>some/page/uri</dfn>  

<var>text</var>  
The link text to be displayed. Defaults to URI path.

__This function will act like a regular link__

- If you leave the <var>uri</var> blank, it will use the current page.
- If you link to an anchor such as `uri="#anchor_on_page"` it will use your current URL as the base.
- You can use relative URLs to go back a level: `uri="../parent"`
- If you leave the <var>text</var> blank it will lookup the title of the link in the TOC and use that.
- If the page you are linking to does not exist in the TOC, it will log an error message.

__With these exceptions__

- We strip the leading slash from URIs: `uri="/child/page"` becomes `child/page`
- Relative URLs cannot go higher than the current module homepage
- We only check the current modules TOC for correct links to avoid excess errors
- This method is not intended for external links

---

{{ docs:fn }}
{{ docs:anchor id="" prefix="" type="" }} 
{{ /docs:fn }}

This creates an anchor element to link to within the page. You can change the code output in the <dfn>anchor.php</dfn> view.

<var>id</var>  
The ID of the element.  

<var>prefix</var>  
Set a prefix so similar IDs don't collide. We default the prefix by <var>type</var>. _(Optional)_  

<var>type</var>  
We have a specific set of prefix types to create a common standard. Please see those [below](#prefix_types). _(Optional)_

{{ docs:anchor id="anchor_example" }}
{{ docs:example }}
	[Click here](#anchor_example) to link to this example directly.
{{ /docs:example }}

---

{{ docs:fn }}
{{ docs:partial file="" module="" }} 
{{ /docs:fn }}

This loads a partial file __from your docs folder__. This is different than including a theme view which can be done using the normal `theme:partial` method. This function is meant to allow you to include common items such as recurring warnings or instructions.

<var>file</var>  
The path to the partial relative to your documentation folder.  

<var>module</var>  
The module to look in. This defaults to the current module by URL.

---

{{ docs:fn }}
{{ docs:note text="" title="" class="" }} 
{{ /docs:fn }}

Create the code for a _note_ item. You can change the output code in the <dfn>note.php</dfn> view.

<var>text</var>  
The text to display inside the note.  

<var>title</var>  
The bolded title of the note. Defaults to _Note_.  

<var>class</var>  
Change or add class(es) to the element. Defaults to _note_.


{{ docs:example noparse="false" }}
{{ docs:note }}
This is some long content
    
because sometimes you need more, ya know?
{{ /docs:note }}
{{ /docs:example }}

---

{{ docs:fn }}
{{ docs:important text="" title="" class="" }} 
{{ /docs:fn }}

Almost identical to the `docs:note` method. Create the code for an _important_ item. You can change the output code in the <dfn>important.php</dfn> view.

<var>text</var>  
The text to display inside the important note.  

<var>title</var>  
The bolded title of the note. Defaults to _Important_.  

<var>class</var>  
Change or add class(es) to the element. Defaults to _important_.


{{ docs:example noparse="false" }}
{{ docs:important }}
This is some long content
    
because sometimes you need more, ya know?
{{ /docs:important }}
{{ /docs:example }}

---

{{ docs:fn }}
{{ docs:code type="" noparse="" }}...{{ /docs:code }}
{{ /docs:fn }}

Use this method to create a code block with syntax highlighting. We highlight with [Highlight.js](http://softwaremaniacs.org/soft/highlight/en/). This tag must be used with opening and closing tags and is not meant for inline code. For inline code, simply use a `<code>` tag.

<var>type</var>  
This is the language of the code that needs to match up with a [brush from Highlight.js](http://softwaremaniacs.org/media/soft/highlight/test.html). Defaults to the <var>docs.default\_code\_brush</var> config setting.  

<var>noparse</var>  
Apply the `noparse` tag around this content? Defaults to `true` which means to skip parsing. This is because you will most likely need to write code samples including LEX code that should not be parsed.

{{ docs:note title="Tip" }}You might want to turn off the `noparse` tag to include a docs file that holds a common code snippet. This would mean you can edit that code snippet in only one place.{{ /docs:note }}

{{ docs:example noparse="false" }}
{{ docs:code type="html" }}
<div id="content">
	{{ lex:tags allowed="true" }}
</div>
{{ /docs:code }}
{{ /docs:example }}

---

{{ docs:fn }}
&#123;&#123; docs:fn noparse="" &#125;&#125;...&#123;&#123; /docs:fn &#125;&#125;
{{ /docs:fn }}

This function creates the default function template as seen in the <dfn>fn.php</dfn> view. This method needs to be called with an open / closing tag pair.

<var>noparse</var>  
Apply the `noparse` tag around this content? Defaults to `true` which means to skip parsing. This is because you will possibly be including LEX code that should not be parsed.

---

{{ docs:fn }}
{{ docs:nav module="" }}
{{ /docs:fn }}

A nav generation method based on the traditional `navigation:links` method. This will accept any of the params as `navigation:links` plus has a few of it's own. This function can also be used as an open / closing tag pair.

<var>module</var>  
The name of the module to pull the nav from. Defaults to the current module by URL. 
