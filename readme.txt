=== MP Stacks + Widgets ===
Contributors: johnstonphilip
Donate link: http://mintplugins.com/
Tags: message bar, header
Requires at least: 3.5
Tested up to: 4.3
Stable tag: 1.0.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add-On Plugin for MP Stacks “Widgetizes” any brick. That is, a new “Sidebar” is created on the “Appearance” > “Widgets” page automatically when a new brick is created and the Content-Type is set to be “Widget”.

== Description ==

Extremely simple to set up - allows you to show widgets on any page, at any time, anywhere on your website. Just put make a brick using “MP Stacks”, put the stack on a page, and set the brick’s Content-Type to be “Widgets”.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the 'mp-stacks-widgets’ folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Build Bricks under the “Stacks and Bricks” menu. 
4. Publish your bricks into a “Stack”.
5. Put Stacks on pages using the shortcode or the “Add Stack” button.

== Frequently Asked Questions ==

See full instructions at http://mintplugins.com/doc/mp-stacks

== Screenshots ==


== Changelog ==

= 1.0.1.0 = September 17, 2015
* Make widget ajax in admin fire on "mp_stacks_content_type_change_complete" instead of "mp_stacks_content_type_change"
* Admin Meta Scripts now enqueued only when needed.
* Brick Metabox controls now load using ajax.
* Set proper default for widgets per row to 3. Was previously inccorectly set to 2 per row was default.
* Make Widgets Content Type alignment default to centered when new Bricks created or Widgets selected.

= 1.0.0.9 = May 17, 2015
* Make widget properly create it's ID for stack template based on time (when duplicated or created as part of an installed Stack Template)

= 1.0.0.8 = May 16, 2015
* Added functions which remove a Brick's Sidebar information from the Wp_options table when the Brick is deleted so that it isn't registered anymore.

= 1.0.0.7 = April 5, 2015
* Move Sidebar ID back to JS.

= 1.0.0.6 = April 5, 2015
* Set Sidebar ID's using time() through metabox and PHP not js.
* Make Sidebar ID meta field hidden.

= 1.0.0.5 = March 1, 2015
* Added 20px of spacing between widgets when vertically stacked on screens less than 600w areas.
* Fixed typo in main defined version variables (was MP_STACKS_WIDGETSP).
* Added versioning to all enqueues.

= 1.0.0.4 = February 10, 2015
* Bullet point control added for widget lists.
* Made sidebar ids based on the time and stored in the ‘mp_stacks_widgets_brick_sidebar_id’
* This release fixes the previously broken issue of having multiple sidebars per page.

= 1.0.0.3 = January 22, 2015
* Remove admin notice about refreshing widget page.
* Meta improvements and better defaults.
* List item spacing control added
* Better aligned with mp stacks developer
* Only Show Link Groups in customizer if MP Links is enabled
* Move Logo Image into Header Area
* Added Font and Font Size Control for the Header Navigation

= 1.0.0.2 = June 18, 2014
* Show widget area in iframe on Brick’s edit page.

= 1.0.0.1 = June 2, 2014
* Make sure that the sidebar is registered when the user changes the content type to widgets. We use ajax and a jquery trigger in MP Stacks (mp_stacks_content_type_change) to handle this.
* Sidebars are also de-registered if the user changes from a widget content type to something else.

= 1.0.0.0 = June 1, 2014
* Original release
