joomla-packager
===============

joomla-packager is a generic phing build script to build any 3rd extension, being a set of components, modules, plugins and or libraries, packages in a simple way.


Usage
------------
see file buildCedThumbnails.xml for building cedthumbnails one of my extensions
You only need to copy and touch this file.
Download https://raw.github.com/cedricwalter/joomla-packager/master/joomla-packager.xml locally

Remarks are welcomed
+ http://www.waltercedric.com/
+ http://www.cedricwalter.com/


OPEN
------------

[ ] any feedback?
[ ] Reference joomla-packager.xml through an URL not through file system

[ ] Creation of manifest.xml in package is not generated through the script (but i am trying to)
so you still need to put it in the resulting zip created, for buildCedThumbnails.xml it would look like

```xml

<?xml version="1.0" encoding="UTF-8" ?>
<extension type="package" version="3.0">
    <name>cedthumbnails</name>
    <packagename>cedthumbnails</packagename>
    <creationDate>01.04.2013</creationDate>
    <version>2.6.2</version>
    <url>http://www.waltercedric.com</url>
    <packager>Cedric Walter</packager>
    <copyright>(C)2008-2013 Cedric Walter. All rights reserved.</copyright>
    <packagerurl>http://www.waltercedric.com</packagerurl>
    <description>Related/popular/latest posts with thumbnails for Joomla. Use the library WideImage for PHP. 3 modules and 1 plugin Extensions.</description>
    <files>
        <file type="library" id="lib_wideimage">lib_wideimage.zip</file>
        <file type="component" id="com_cedthumbnails">com_cedthumbnails.zip</file>
        <file type="module" id="mod_articles_latest_thumb" client="site">mod_articles_latest_thumb.zip</file>
        <file type="module" id="mod_articles_popular_thumb" client="site">mod_articles_popular_thumb.zip</file>
        <file type="module" id="mod_related_items_thumb" client="site">mod_related_items_thumb.zip</file>
        <file type="plugin" id="relatedthumbarticles" group="content">plg_content_relatedthumbitems.zip</file>
    </files>
</extension>
{code}
```

