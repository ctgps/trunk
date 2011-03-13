=== UPM Polls ===
Contributors: Artyom Chakhoyan
Donate link: http://profprojects.com/become-grateful-user/
Tags: polls, poll
Requires at least: 2.7.0
Tested up to: 3.0
Stable tag: 1.0.2

Best Plugin to create Polls for your site.

== Description ==
Best Plugin to create Polls for your site.
  
  * Poll Manager,
	* Ability to set general and post/page specific polls,
	* Ability to leaf over the polls
	* Ability to add certain poll in certain post content

== Installation ==

  * Extract "upm-polls.1.0.2.zip" archive.
  * Upload the upm-polls folder to the "/wp-content/plugins/" directory .
  * In your WordPress administration, go to the Plugins page.
  * Activate the upm-polls plugin through the 'Plugins' menu in WordPress and a menu "UPM Polls"  whith four submenus will appear in your admin panel menus.
  * Drag "UPM Polls" widgets from Admin->Appearance->Widgets to a sidebar on the right to activate it (IF YOU GOING TO ADD POLLS IN POST/PAGE CONTENT YOU SHOULD REMOVE UPM-POLL WIDGET FROM YOUR SINGLE POST/PAGE SIDEBAR). 

"Upgrade"

  * Deactivate "UPM Polls" plugin,
  * Rewrite files of new version to wp-content/plugins/upm-polls/ directory.
  * Activate the "UPM Polls" plugin through the 'Plugins' menu in WordPress admin panel.
  * Drag "UPM Polls" widgets from Admin->Appearance->Widgets to a sidebar on the right to activate it. 

== Frequently Asked Questions ==
Please visit [Universal Post Manager - Polls Plugin Support Forum](http://www.profprojects.com/forum/) for questions and answers.

== Screenshots ==

1. UPM Polls Widget
2. UPM Polls General Settings
3. UPM Polls Manager - Logs
4. UPM Polls Manager - Add/Edit & Templates


== Information ==

"UPM Polls" is a part of the "Universal Polst Manager" and only polling feature is available now. If you want to use complete version of this plugin you should deactivate ( not uninstall ) "UPM Polls" then install latest version of [Universal Post Manager](http://wordpress.org/extend/plugins/universal-post-manager/).

Please DO NOT use both plugins together , one of them should be deactivated !

All features of Universal Post Manager:
  * [-] HTML tag Manager ,
  * [-] Protocol Manager,
  * [-] Phrase filtering and shortcut Manager
  * [-] Long Phrase Manager
  * [-] Post and page Saving Manager ( Text, HTML, MS Word, PDF, XML )
  * [-] Share Manager
  	* [-] Social Bookmarks ( funny slider and simple types ),
    * [-] E-Mail This Post ( two screen types ) ,
    * [-] Subscribe via Feeds ( rdf, rss, rss2, atom )
  * [+] Poll Manager
  * [-] Print Manager

== Changelog ==

= 1.0.2 =

* Fixed Bug : Specific polls aren't beeing shown on widget
* Fixed Bug : No result after vote for specific polls.
* Fixed Bug : Infinity loading vote result 
* Fixed Bug : jQuery conflicts with WP Dashboard menus on Poll Manager admin page 
* Fixed Bug : Slow loading of Poll's Logs
* Added : Ability to add certain poll in certain post content (IF YOU GOING TO ADD POLLS IN POST/PAGE CONTENT YOU SHOULD REMOVE UPM-POLL WIDGET FROM YOUR SINGLE POST/PAGE SIDEBAR)
* Added : Ability to Turn on/off UPM jQuery framework loading (If you have already loaded jQuery latest vesrsion you can turn this off)
* Added : More Secure Voting