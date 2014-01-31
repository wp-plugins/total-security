=== Total Security ===
Plugin Name: Total Security
Contributors: fdoromo
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8DHY4NXW35T4Y
Tags: security, scan ,scanner, hack, exploit, secure, malware, phishing, vulnerability, scours, unsafe, total, 404 log, error 404, stealth login, hidden login       
Requires at least: 3.8.1
Tested up to: 3.8.1
Stable tag: 2.9.8.1
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
 
* Detects unknown file found in WP core => |*any file|
* Detects suspicious or potentially malicious files => |`*.exe`|`*.com`|`*.scr`|`*.bat`|`*.msi`|`*.vb`|`*.cpl`|
* Detects compressed files => |`*.zip`|`*.rar`|`*.7z`|`*.gz`|`*.tar`|`*.bz2`|
* Detects log, binary, data and temporary files => |`*.log`|`*.dat`|`*.bin`|`*.tmp`|


= Error 404 Log =

* Logs 404 (Page Not Found) errors on your site, this also gives the added benefit of helping you find hidden problems causing 404 errors on unseen parts of your site as all errors will be logged.


= Secure Hidden Login =

* Allows you to create custom URLs for user's login, logout and admin's login page, without editing any `.htaccess` files. 
* Those attempting to gain access to your login form will be automatcally redirected to a customizable URL.
* Hide "wp-admin" folder.


> Best practices on security combined into one plugin! 

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
* Spanish (**es_ES**) translation by **Juan Pablo Poblacion Paredes**


= How To Contribute =
We'd love for you to get involved. Whatever your level of skill or however much time you can give, your contribution is greatly appreciated.

* **Users** - download the latest development version of the plugin, and submit bug/feature requests.
* **Non-English Speaking Users** - Contribute a translation using the GlotPress web interface - no technical knowledge required ([how to](https://poeditor.com/join/project?hash=d9fb1f32a0dd2734ac1052c6a12b6be9)).
* **Developers** - Fork the development version and submit a pull request.


== Screenshots ==
1. Dashboard
2. Vulnerability Scan
3. Unsafe Files Search
4. Core Exploit Scanner
5. Secure Hidden Login - Setup
6. Error 404 Log

== Installation ==

1. Upload the `total-security` folder to your `/wp-content/plugins/` directory
1. Activate the `Total Security` plugin in your WordPress admin `Plugins`
1. That's it. You're ready to go!

> You can install **Total Security** directly from the WordPress admin! Visit the Plugins - > Add New page and search for **Total Security**. Click to install.

== Frequently Asked Questions ==

= How do i uninstall completely this plugin =

* If deactivate the plugin on the plugins page, the plugin should clean up most of the files created and modified.
* The uninstall function is manage by "uninstall.php" file, the plugin is completely removed when actively deleted (not just deactivated) through the WordPress Admin.


== Changelog ==
* 2.9.8.1
    * Cosmetic fixes

* 2.9.8
    * Compatibility with WordPress 3.8.1

* 2.9.7   
    * Compatibility with WordPress 3.8

* 2.9.6   
    * Compatibility with WordPress 3.7.1

* 2.9.5   
    * Compatibility with WordPress 3.7

* 2.9.4
      * Fixing: `wpdb::escape` Deprecated Function
      * "Secure Hidden Login" and "Dangerous PHP Functions" change of risk status
      * Add Spanish (es_ES) translation by  Juan Pablo Población Paredes

* 2.9.3   
    * Compatibility with WordPress 3.6.1

* 2.9.2   
    * Compatibility with WordPress 3.6

* 2.9.1   
    * Compatibility with WordPress 3.5.2

* 2.9
    * Performance improvements
    * Minor interface tweaks

* 2.8.1
    * Performance improvements
    * Minor bug fixes

* 2.8
    * Add new feature: Secure Hidden Login
    * Cosmetic fixes

* 2.7
    * Add new feature: Error 404 Log
    * Cosmetic fixes

* 2.6.351
    * Performance improvements

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
    * Add new feature: Unsafe Files Search
    * Performance improvements.

* 1.1
    * Bug Fix

* 1.0
    * Initial release