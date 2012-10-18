# Docs

Docs is a PyroCMS module that allows you to easily include documentation bundled up with your module.

## How ?

__Docs__ will add a _Help_ link in your admin interface which will allow users to get help on any page they are on, provided that help exists. __Docs__ will find the documentation based on the admin URL by searching through your modules or generic `docs` folder. This will allow you to bundle a folder named `docs` with your modules which can simply be ignored by users who have the __Docs__ module disabled or unavailable. Worse case is they open it as a text file, or manually point to it in their browser if needed. Also, you can fall back to a generic `docs` folder if you are documenting a plugin, library, or other non-bundled item.

## Installation Instructions

Install __Docs__ like you would any other module.