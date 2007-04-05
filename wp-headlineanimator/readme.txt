=== WP-HeadlineAnimator ===
Contributors: Stargazer
Donate link: http://my.stargazer.at/wishlist/
Tags: post, image, signature
Requires at least: 2.0
Tested up to: 2.2-bleeding
Stable tag: 1.1


WP-Headlineanimator generates an animated GIF File to be displayed in signatures or wherever you like to advertise for your Blog.

== Description ==
 WP-Headlineanimator is a Plugin for Wordpress. It generates an animated 
 GIF File to be displayed in signatures or wherever you like to advertise
 for your Blog.

== Requirements ==
* Wordpress > 2.0
* some time
* Access to your webserver
* A background picture for your signature
* PHP with exif support for image detection

== Features ==
* As a forum signature can be called _very_often_, it is pointless generating the picture from scratch via PHP
* The gif image is updated everytime you WRITE, DELETE or MODIFY a post.
* The Plugin is Polyglot-Compatible (this is a plugin for multilingual blogs)

== Installation ==
1. Upload the contents of the wp-headlineanimator directory into your wordpress plugin directory.
2. make sure that the file you want to write your animator to is writeable for php
3. Activate the Plugin
4. Configure the Plugin via the admin interface
5.  Enjoy and be so kind giving some feedback to me on my blog

== Frequently Asked Questions ==
= The plugin shows the StarBlog background =
Just draw your own background image, upload and use the new filename in the config

== Screenshots ==
None avaliable. If you want to see it in action, look at http://my.stargazer.at/starblog.gif

== Todo ==
* internal upload for the background image
* a generic template for everyone as default

== Bugs ==
* no error handler on if output file is not writeable

== Changes ==
= Version 1.1 =
- Ripped out Polyglot detection as this is already handled by wordpress with get_posts()
- Added initialisation routine for default options
- Preview only if file exists
- Only write file if we got a font and picture

= Version 1.0 =
- Let's assume I got my wishlist completed on that topic and the code isn't too ugly.

= Version 0.6 =
- Got rid of tmp path; we just write out our image
- cleanup
- picture format support

= Version 0.5 =
- Upgrade to new GifMerge Class
- eliminating of direct DB Queries
- code cleanups

= Version 0.4 =
- make use of polyglot_filter() for better Polyglot Support -> Multilanguage!!
- minior design fixes

= Version 0.3 =
- Use 5 Headlines, generate animated GIF
- Make use of GifMerge.class.php

= Version 0.2 =
- Added date() to image and made it optional (thx to pcDummy)
- Administrative interface
- Code cleanup

= Version 0.1 =
- Added basic Support for Polyglot (hack)
- Initial release
