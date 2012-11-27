=== Total Security ===
Plugin Name: Total Security
Contributors: fdoromo
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8DHY4NXW35T4Y
Tags: Security,scan,scanner,hack,hackers,htaccess,injection,exploit,secure,malware,phishing,SQL Injection,vulnerability,attack,anything suspicious,Total Security, Total      
Requires at least: 3.4.2
Tested up to: 3.4.2
Stable tag: 1.0
License: GPLv2 or later

Checks your WordPress installation and provides detailed reporting on discovered vulnerabilities, anything suspicious and how to fix them.

== Description ==
The [Total Security](http://fabrix.net/total-security/ "Total Security") plugin is the must-have tool when it comes security of your WordPress installation. The plugin monitors your website for security weaknesses that hackers might exploit and tells you how to easily fix them.

Best practices on security combined into one plugin! It does not remove or modify anything. That is left to the user to do. 

= Features: =

* Check your site for security vulnerabilities and holes.
* numerous installation parameters tests
* Apache and PHP related tests
* file permissions
* WP options tests
* scan WP core files with one click
* quickly identify problematic files
* restore modified files with one click
* great for removing exploits and fixing accidental file edits/deletes
* view files’ source to take a closer look
* fix broken WP auto-updates
* detailed help and description

= Usage =

For Vulnerability Scan: Once you click the `One Click Scan` button all tests will be run,

For Core Exploit Scanner: Once you click the `One Click Scanner` button all tests will be run,

depending on various parameters of your site this can take from ten seconds to 2-3 minutes. Please don’t reload the page until testing is done.

Each test comes with a detailed explanation which you should use to determine whether it affects your site or not. Most test have simple to follow instructions on how to strengthen your site’s security. 

Color-coded results separate files into categories:

* Items in green are fully secured. Good job!
* Items in orange are partially secured. Turn on more options to fully secure these areas
* Items in red are not secured. You should secure these items immediately

A warning to redo the scan will be informed every 15 days of last inspection.


= How To Contribute =
We'd love for you to get involved. Whatever your level of skill or however much time you can give, your contribution is greatly appreciated.

* **Users** - download the latest development version of the plugin, and submit bug/feature requests.

* **Non-English Speaking Users** - Contribute a translation using the GlotPress web interface - no technical knowledge required ([how to](http://translate.fabrix.net/projects/total-security/)).

* **Developers** - Fork the development version and submit a pull request, especially for any known issues


== Screenshots ==
1. Vulnerability Scan 1
2. Vulnerability Scan
3. Core Exploit Scanner
4. System Information

== Installation ==

1. Upload the `total-security` folder to your `/wp-content/plugins/` directory
1. Activate the `Total Security` plugin in your WordPress admin `Plugins`
1. That's it. You're ready to go!


== Frequently Asked Questions ==

= How do I change the file permissions on my WordPress installation?  =
[Changing File Permissions](http://codex.wordpress.org/Changing_File_Permissions)

= Why do I need to hide my version of WordPress?  =
Many attackers and automated tools will try and determine software versions
before launching exploit code. Removing your WordPress blog version may
discourage some attackers and certainly will mitigate virus and malware programs
that rely on software versions.

NOTE: Hiding your version of WordPress may break any plugins you have which
are version dependant.


== Changelog ==

* 1.0
    * Initial release