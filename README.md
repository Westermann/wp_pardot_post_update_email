=== Wordpress Pardot Post Update Email Plugin ===

Contributors: jonas@zappistore.com

Tags: pardot, newsletter, updates

Version: 1.1

This is a very basic plugin that uses the pardot API to send an email out to prospects in a pardot list whenever any post is set from 'Pending Review' to 'Published'.


== Description ==

This is a very basic plugin that uses the pardot API to send an email out to prospects in a pardot list whenever any post is set from 'Pending Review' to 'Published'. The plugin is very useful if you want to manage your blog-subscribers and thus your organization's prospects through pardot as opposed to Wordpress's user system. 


== Installation ==

Can only be installed manually on the server at the moment, e.g.:

1. `cd wordpress/wp-content/plugins`

2. `git clone https://github.com/Westermann/wp_pardot_post_update_email`

3. Should be set to go.


== Roadmap ==

* The plugin is not currently live in the Wordpress repository, but that will hopefully happen some time soon.


== Changelog ==

== 1.1 *Current* ==

* Implements a `text_content` field to populate the pardot test version of the email

* No longer relies on a pardot email template

== 1.0 ==

* First usable version, sends out emails but relies on a pardot email template


