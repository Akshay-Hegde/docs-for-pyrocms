{{ docs:page
  title="Changelog File"
  description=""
}}

# Changelog

__v0.5 &mdash; In Development__ _Initial Public Release_

- Supports modules (non-module item support planned for future)
- Autoconverts Markdown (.md, .markdown) and Textile (.textile) files
- Plugins for __nav__, __link__, __partial__, __page__, __next\_topic__, __prev\_topic__, and auto-formatting like __code__, __fn__, __note__, __important__, __anchor__
- Caching on TOC

__BUGS__

- If page is indented with no parent, "type" error given

__TO DO__

- Support front end and back end documentation
- Allow "strict" page access rules (if it's not listed in TOC, it's not viewable)
- Add Nav caching (Check if we can forge navigation cache with docs TOC items)
- Allow categories to be "non-page" (users can't visit, it's just for nav categorization) section | group?
- Add TOC documentation
- Allow Page caching
- Can Template library load partials instead?