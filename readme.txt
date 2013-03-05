=== Total Security ===
Plugin Name: Total Security
Contributors: fdoromo
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8DHY4NXW35T4Y
Tags: security, scan ,scanner, hack, exploit, secure, malware, phishing, vulnerability, scours, unsafe, total       
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 2.5.351
License: GPLv2 or later

Checks your WordPress installation and provides detailed reporting on discovered vulnerabilities, anything suspicious and how to fix them.

== Description ==
The **Total Security** plugin is the must-have tool when it comes security of your WordPress installation. The plugin monitors your website for security weaknesses that hackers might exploit and tells you how to easily fix them.

= Vulnerability Scan =

* Check your site for security vulnerabilities and holes.
* numerous installation parameters tests
* Apache and PHP related tests
* file permissions
* WP options tests
* detailed help and description


= Core Exploit Scanner =

* scan WP core files with one click
* quickly identify problematic files
* great for removing exploits and fixing accidental file edits/deletes
* view files source to take a closer look
* fix broken WP auto-updates
* restore modified files with one click


= Unsafe Files Search =

Scours your file system by suspicious or potentially malicious files, compressed, log, binary, data, and temporary files. And any unknown file in WP core.
 
* Detects unknown file found in WP core => |any file|
* Detects suspicious or potentially malicious files => |`.exe`|`.com`|`.scr`|`.bat`|`.msi`|`.vb`|`.cpl`|
* Detects compressed files => |`.zip`|`.rar`|`.7z`|`.gz`|`.tar`|`.bz2`|
* Detects log, binary, data and temporary files => |`.log`|`.dat`|`.bin`|`.tmp`|

> Best practices on security combined into one plugin! It does not remove or modify anything. That is left to the user to do. 

= Usage =

For Vulnerability Scan: Once you click the `One Click Scan` button all tests will be run,

For Core Exploit Scanner: Once you click the `One Click Scanner` button all tests will be run,

depending on various parameters of your site this can take from ten seconds to 2-3 minutes. Please don't reload the page until testing is done.

Each test comes with a detailed explanation which you should use to determine whether it affects your site or not. Most test have simple to follow instructions on how to strengthen your site's security. 

Color-coded results separate files into categories:

* Items in green are fully secured. Good job!
* Items in orange are partially secured. Turn on more options to fully secure these areas
* Items in red are not secured. You should secure these items immediately

A warning to redo the scan will be informed every 15 days of last inspection.

= Languages Available =
* English (default)
* German (**de_DE**) translation by **Silvio Paschke**


= How To Contribute =
We'd love for you to get involved. Whatever your level of skill or however much time you can give, your contribution is greatly appreciated.

* **Users** - download the latest development version of the plugin, and submit bug/feature requests.
* **Non-English Speaking Users** - Contribute a translation using the GlotPress web interface - no technical knowledge required ([how to](http://translate.fabrix.net/projects/total-security/)).
* **Developers** - Fork the development version and submit a pull request.


== Screenshots ==
1. Vulnerability Scan
2. Core Exploit Scanner
3. Unsafe Files Search
4. System Information

== Installation ==

1. Upload the `total-security` folder to your `/wp-content/plugins/` directory
1. Activate the `Total Security` plugin in your WordPress admin `Plugins`
1. That's it. You're ready to go!

> You can install **Total Security** directly from the WordPress admin! Visit the Plugins - > Add New page and search for **Total Security**. Click to install.

== Frequently Asked Questions ==

= How do I change the file permissions on my WordPress installation?  =
[Changing File Permissions](http://codex.wordpress.org/Changing_File_Permissions)

= Why do I need to hide my version of WordPress?  =
Many attackers and automated tools will try and determine software versions before launching exploit code. Removing your WordPress blog version may discourage some attackers and certainly will mitigate virus and malware programs that rely on software versions.
NOTE:Hiding your version of WordPress may break any plugins you have which are version dependant.

= Why does Total Security require WordPress the latest version? =
One of the best practices a WordPress site owner can do to keep their site secure is to keep your software up to date. Because of this fact I do not test this plugin in anything but the latest stable version of WordPress and will only guarantee it works in the latest version.


== Changelog ==
* 2.5.351
    * Performance improvements

* 2.4.351
    * Minor bug fixes

* 2.3.351
    * Compatibility with WordPress 3.5.1

* 2.3.350
    * Performance improvements
    * Fix [Guidelines](http://wordpress.org/extend/plugins/about/guidelines/)

* 2.2.350
    * Performance improvements
    * Detect `.cpl` and `.db`
    * Cosmetic fixes  

* 2.1.350
    * Add German (de_DE) translation by Silvio Paschke

* 2.0.350
    * Compatibility with WordPress 3.5

* 2.0.342
    * New version notation (x.y.zzz) |new feature|improvements or bug fixes|last WP core|
    * Add new feature: Unsafe Files Search
    * Performance improvements.

* 1.1
    * Bug Fix

* 1.0
    * Initial release