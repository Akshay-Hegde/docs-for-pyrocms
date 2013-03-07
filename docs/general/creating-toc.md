# Creating Table of Contents

Here we will teach you how to create your table of contents. Your TOC is a single file that is basically a site map to your documentation pages.

## TOC Format

Your Table of Contents is a file called <dfn>\_toc.txt</dfn> inside your <dfn>docs</dfn> folder. The TOC has a specific formatting to it. This format provides flexibility for you to have sections or categories and pages / subpages. There are a few basic rules to the navigation structure. First lets take look at a sample of a completed TOC file.

    page: index | Home
    category: getting-started | Getting Started
      page: requirements
        page: server | Server Requirements
      page: installation 
        anchor: #upgrading | Upgrading
    category: api
      category: libraries
        page: formatting | Formatting Library
        page: generation | Generation Library
      category: controllers
        page: admin | Admin Controller
        page: public | Public Controller
    page: contributing
    page: about

This TOC file will generate the following URL structure which is exactly how your folder structure should look inside your <dfn>docs</dfn> folder:

    index  (homepage)
    getting-started
    getting-started/requirements
    getting-started/requirements/server
    getting-started/installation
    api
    api/libraries
    api/libraries/formatting
    api/libraries/generation
    api/controllers
    api/controllers/admin
    api/controllers/public
    contributing
    about

Index pages for category or subcategories can be files named <dfn>index.ext</dfn> inside the matching folder, or one level up with the same name as the folder.

### Structure

There are syntax rules for each line. Here is a quick sample:

    [type]: [uri] | [params]

__Structure Rules__

- Every line is a new navigation item
- Each line has a `type`, `uri`, and `params`. Params can be omitted.
- An indentation is two spaces. Each indentation acts as a new segment or level in the URI and folder structure.  
_For example:_ <dfn>grandparent/parent/child</dfn>
- Any line can be commented out by adding a `#` to the beginning of the line.
- The first parameter is the __Page Title__. If left blank, it will be auto generated based on URI.
- You only need to include the <dfn>index</dfn> page if you want a link to your homepage displayed in the nav.


### Types

As you can see, there are a few types of triggers in the file. Each type means something to the system. Here is a definition of each.

`page`  
A normal page, can be on any level of the navigation. If pages are nested in pages, they will still act as parents and children.

`category`  
A category means any links indented past it will be under that "category". They are mainly used to act as organizers for sections of your documentation. However, they behave the same as pages.

`anchor`  
An anchor is simply a link to a specific anchor on the page. We give you the option to include them in your navigation if you would like to put all documentation on one page, but want to show them broken into separate navigation items. If you have anchors on your page, it is not required to put them here.

`redirect` {{ docs:small text="beta" class="flag" }}  
We have an advanced feature for users who wish to apply redirect changes from old pages. We created this trigger in order to keep them with your documentation and not force users to create a redirect in their admin panel. {{ docs:link uri="#redirect_trigger" text="See full documentation below &raquo;" }}


### URIs

Every URI will match the file name. You may use dashes or underscores, but we recommend dashes to be consistent with the rest of the site.

### Params

For the time being, we only offer one param which is the __Page Title__. It is the first param for every type and if omitted, it will be generated automatically. We have the option to allow more when we see an opportunity for one.

## Steps to create your TOC

__Preparation__  
We are assuming you have already setup your <dfn>docs</dfn> folder in your project. If you haven't done so, please follow {{ docs:link text="this guide" }} to get started.

__Step 1: Create the file__  
Inside your <dfn>docs</dfn> folder create a file called <dfn>\_toc.txt</dfn>.
 
__Step 2: Fill in the file__  
You can copy the one we have above and modify it to your liking, or you can write it from scratch if you're comfortable.

__Step 3: Check__  
Check your documentation URL and see if all is working correctly.

{{ docs:note text="You only need one TOC file in the root of your <dfn>docs</dfn> folder and do not need to include one on each level of the docs." }}


## Beta Features

Here is a beta feature we use but would like to see if other users find it useful before fully adopting it.

{{ docs:anchor id="redirect_trigger" }}
### Redirect Trigger

There is an additional hidden trigger for redirects. It looks like this:

    redirect: [old_uri] | [new_uri] | [redirect_type]

<var>old_uri</var>  
The old URI that will be redirected. This will be the same URI style as any other trigger.

<var>new\_uri</var>  
The full URI path to redirect to. This will be something like <dfn>some/new/page</dfn>.

<var>redirect_type</var>  
The redirect header to send to the browser. Can be set to `301` or `302`. Defaults to `301` and can be ommited.

__Usage Example__

Let's say we have moved our repo from Google Code to Github. We will want to redirect an old page to reflect this change.

    page: contributing
      redirect: google-code | contributing/github | 301
      page: github

This will redirect <dfn>contributing/google-code</dfn> to <dfn>contributing/github</dfn> with a 301 header.