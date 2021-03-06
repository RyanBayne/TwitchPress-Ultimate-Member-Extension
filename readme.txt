=== TwitchPress UM Extension ===
Contributors: Ryan Bayne
Donate link: https://www.patreon.com/ryanbayne
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Tags: Twitch, TwitchPress, Twitch.tv, TwitchPress Extension, TwitchPress Boilerplate
Requires at least: 4.4
Tested up to: 4.8
Stable tag: 1.2.1
Minimum TwitchPress version: 1.6.1
Requires PHP: 5.6
                        
Integrate the Ultimate Member plugin with a TwitchPress system.
                       
== Description ==

Ultimate Member and TwitchPress integration for UM roles. Apply your Twitch.tv channel subscriber
plans to your WordPress user accounts. Makes it possible to begin limiting WP access based on Twitch
subscriber level. 

This extension requires TwitchPress and it allows you to integrate the fantastic Ultimate Member plugin
and control memberships in sync with Twitch.tv subscriptions. When your viewers change their subscription
status, their membership level (role) will be updated. 

Currently requires the following plugins:
* TwitchPress Core Plugin     - Use to build a custom Twitch suite with extension ability.
* TwitchPress Sync Extension  - Focuses on updating locally stored Twitch data in a controlled manner which is required for busy sites.
* TwitchPress Login Extension - Provides safe login and automatic registration that other extensions can expand further to suit different systems.

== Developer Comment ==

With the TwitchPress project being so new the exact approach to take for each feature may not
work first time around. The requirement of a core plugin and another two extensions might seem
a lot but there is good reason for it. The TwitchPress project is being developed to allow the
growth of an advanced Twitch suite. That can only happen in a highly extendable environment that 
allows you to replace an official extension with a third-party one. An extendable environment
requires individual systems and large features to be separated from the core plugin. 

The key during this early stage is to let me know what you need your Twitch suite to do for you
or for your visitors. 

= Links =                                                                
*   <a href="https://twitchpress.wordpress.com" title="">Blog</a>
*   <a href="https://github.com/RyanBayne/TwitchPress" title="">GitHub</a>       
*   <a href="https://twitter.com/ryan_r_bayne" title="Follow the projects Tweets.">Developers Twitter</a>     
*   <a href="https://twitter.com/twitchpress" title="Follow the projects Tweets.">Plugins Twitter</a>     
*   <a href="https://www.twitch.tv/zypherevolved" title="Follow my Twitch channel.">Authors Twitch</a>     
*   <a href="https://discord.gg/NaRB3wE" title="Chat about TwitchPress on Discord.">Discord Chat</a>          
*   <a href="https://www.patreon.com/ryanbayne" title="">Patreon Donations</a>     
*   <a href="https://www.paypal.me/zypherevolved" title="">PayPal Donations</a>       

= Features List = 

* Shortcode for adding Connect to Twitch button to Ultimate Member login form. 
* Hooks into the TwitchPress Sync Extension subscription data management and reacts to a visitors change in Twitch channel subscription.

== Installation ==

1. Method 1: Move folder inside the .zip file into the "wp-content/plugins/" directory if your website is stored locally. Then upload the new plugin folder using your FTP program.
1. Method 2: Use your hosting control panels file manager to upload the plugin folder (not the .zip, only the folder inside it) to the "wp-content/plugins/" directory.
1. Method 3: In your WordPress admin click on Plugins then click on Add New. You can search for your plugin there and perform the installation easily. This method does not apply to premium plugins.

== Frequently Asked Questions ==

= Can I hire you to customize the plugin for me? =
Yes you can pay the plugin author to improve the plugin to suit your needs. Many improvements will be done free so
post your requirements on the plugins forum first. 

== Screenshots ==


== Languages ==

Translator needed to localize our Channel Solution for Twitch: TwitchPress, and it's extensions.

== Upgrade Notice ==

No special upgrade instructions this time. 

== Changelog ==
= 1.2.1 = 
* DEV - Now adds required scopes to the core for telling users what scopes are required for this plugin to operate. 
* FIX - Fixed bug preventing subscription update when viewing or updated user profile.   

= 1.2.0 = 
* DEV - New scopes() function for holding the scopes needed by this extension.

= 1.1.1 =
* DEV - A get_option() now has a default value. 
* FIX - Established which hooks pass user object and which pass user ID to the set_twitch_subscribers_um_role() function. 

= 1.0.10 =
* DEV - More logging added, still debugging the UM role fault with a remote developer. 

== Version Numbers and Updating ==

Explanation of versioning used by myself Ryan Bayne. The versioning scheme I use is called "Semantic Versioning 2.0.0" and more
information about it can be found at http://semver.org/ 

These are the rules followed to increase the TwitchPress plugin version number. Given a version number MAJOR.MINOR.PATCH, increment the:

MAJOR version when you make incompatible API changes,
MINOR version when you add functionality in a backwards-compatible manner, and
PATCH version when you make backwards-compatible bug fixes.
Additional labels for pre-release and build metadata are available as extensions to the MAJOR.MINOR.PATCH format.



                  