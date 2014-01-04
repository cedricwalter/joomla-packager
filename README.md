joomla-packager
===============

joomla-packager is a generic phing build script to build any 3rd extension, being a set of components, modules, plugins and or libraries, packages in a simple way.

Why?
* a single script to rule all your need of joomla packaging: multi modules/ multi plugins/multi components/multi library is supported
* If you respect Joomla's conventions, this packager will be able to create a build of any of your extensions or set of extensions
* just configure one file (like for example in buildCedThumbnails.xml), mainly listing what your extension is made of and run
* file all look the same = repeatability = convention over configuration

Support for Premium and Free extensions

This packager use preprocessing functions in all modules, component and plugins (anywhere in php, js, css, xml files). To use them you can put anywhere


```#if premium
...
#endif```

or 
```#if free
...
#endif
```

More functions are supported If / Else if:
```
#if DEFINITION : succeeds if DEFINITION is set and is true (in the PHP way)
#ifdef DEFINITION : succeeds if DEFINITION is set
#ifndef DEFINITION : succeeds if DEFINITION is not set
#elif DEFINITION
#elifdef DEFINITION
#elifndef DEFINITION
#endif
```

Usage
------------
Download https://raw.github.com/cedricwalter/joomla-packager/master/joomla-packager.xml locally 
Download a copy of buildCedThumbnails.xml You only need to rename and touch this file, it is self documented. 
Download a copy of pkg_cedthumbnails.xml You only need to update this file if you alter the structure. (later Joomla-packager will create this file on the fly) 
Download the directory preprocessor also, it must be in the same directory as the file joomla-packager.xml
Run the main target build of buildCedThumbnails.xml 


Remarks are welcomed
+ http://www.waltercedric.com/
+ http://www.cedricwalter.com/
 

Conventions
------------
This script use standard joomla conventions to perform the packaging, here is what is implemented

Modules
* 1. Code is located in /modules/mod_${xxxxx} and self contained
* 2. Languages files are optional but if they exist are located at /language/xx-yy/mod_${xxxxx}.ini /language/xx-yy/mod_${xxxxx}.sys.ini
* 3. Media files (css,js,images) are optionnal but if they exist are located at /media/mod_${xxxxx}
* 4. name of zip files as follow:
 * mod_{yourextension1}.zip
 
Plugins
* 1. Code is located in /plugins/${type}/${xxxxx} and self contained
* 2. Languages files are optional but if they exist are located at /plugins/${type}/${xxxxx}/language/ or anywhere else in directory at step 1.
* 3. Media files (css,js,images) are optionnal but if they exist are located at /media/plg_${type}_${xxxxx}
* 4. name of zip files as follow:
 * plg_${type}_{yourextension}.zip

Components
* 1. Code is located in /administrator/components/${xxxxx} and /components/${xxxxx} and is self contained 
* 2. Languages files are optional but if they exist are located at /administrator/language/xx-yy/${xxxxx}.ini and /language/xx-yy/${xxxxx}.ini and 
* 3. Media files (css,js,images) are optionnal but if they exist are located at /media/${xxxxx}

Packages
* 1. one zip file which contains 1 to n other zip file +  a manifest file named pkg_{yourextension}.xml
* 2. manifest file named pkg_{yourextension}.xml has to be written manually for now and place beside your build{yourextension}.xml
* 4. name of zip files as follow:
 * com_{yourextension}.zip 

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

