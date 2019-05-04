=== WP GDPR Cookie Notice ===

Contributors:      flixos90
Requires at least: 4.9.6
Tested up to:      5.1
Requires PHP:      7.0
Stable tag:        1.0.0-beta.1
License:           GNU General Public License v2 (or later)
License URI:       http://www.gnu.org/licenses/gpl-2.0.html
Tags:              gdpr, cookie notice, cookie consent, granular cookie control, customizer

Simple performant cookie consent notice that supports AMP, granular cookie control and live preview customization.

== Description ==

This plugin adds a simple performant cookie consent notice to your WordPress site that supports AMP, granular cookie control and live preview customization.

Not only does the notice allow you to provide the regular message that your site uses cookies, you can also optionally grant your site visitors permission to granularly allow which cookie types are allowed, supporting groups of functional (always required), preferences, analytics and marketing cookies. This aims towards compliance with how the new GDPR regulations recommend implementing cookie control for your site.

In addition to the Privacy Policy page setting that WordPress core provides, you also get a settings to optionally set an extra Cookie Policy page, and you can easily link to either of them from the cookie consent notice.

The cookie notice content and appearance can easily be tweaked using the Customizer, with an immediate live-preview of what your changes will look like.

Last but not least, another important thing that this plugin takes care of, other than most other cookie consent plugins, is that it actually ensures cookies are only placed if the respective cookie type has been allowed by the visitor. The plugin does this by implementing cookie rules for WordPress itself, and also for the following plugins:

* [AMP](https://wordpress.org/plugins/amp/)
* [Jetpack](https://wordpress.org/plugins/jetpack/)
* [Google Analytics for WordPress by MonsterInsights](https://wordpress.org/plugins/google-analytics-for-wordpress/)
* [Simple Analytics](https://wordpress.org/plugins/simple-analytics/)

More plugins will be supported in the future. If you are a developer though, it's also very easy to add cookie rules for other code, by using the flexible cookie rule component the plugin provides as an extension point.

= Feature Summary =

* **Lightweight and easy-to-use:** Simply activate the plugin, and the notice will appear.
* **Live Preview:** Use the Customizer to adjust the notice to your needs, with an instant live preview.
* **Customizable Appearance:** Specify the notice position, colors, border, button size and more.
* **Customizable Content:** Adjust the notice heading, text and button label to your preferences. You can easily link to your cookie policy page or privacy policy page, and even give visitors granular control about which cookie types they allow.
* **Cookie Policy Support:** Define an optional cookie policy page if your site has one, or alternatively provide an ID attribute to the cookie section in your privacy policy.
* **Cookie Integrations:** Supported cookies are only set once the visitor has given their consent. The cookie rules implemented also respect the more granular cookie control.
* **JavaScript-driven:** The cookie notice is inserted into the page as necessary via JavaScript, but at the same time provides easy access to whether it should be displayed via its PHP API.
* **AMP Support:** The notice is fully AMP-compatible using `<amp-consent>`, integrating seamlessly with the [AMP plugin](https://wordpress.org/plugins/amp/).
* **Coding Best Practices:** The plugin is fully object-oriented and is coded after best practices, such as using interfaces, traits, dependency injection or the single responsibility principle. It also implements modern coding features requiring PHP 7, such as scalar type hints or return type hints.

= Disclaimer =

This plugin does not provide any legal protection. You as a site administrator are required to ensure that it meets legal standards. This plugin is a technical tool, not a lawyer.

== Installation ==

1. Upload the entire `wp-gdpr-cookie-notice` folder to the `/wp-content/plugins/` directory or download it through the WordPress backend.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Where are the plugin settings? =

Since the settings of the plugin are mostly of visual nature, the plugin does not have any settings page, but uses exclusively the Customizer. You can easily reach the plugin's Customizer panel either by using the link in the Settings menu or the link in the plugin's row on the Plugins screen.

= Which filters are available? =

You can use the following filters:

* `wp_gdpr_cookie_notice_max_content_width`: Filters the maximum width of the cookie notice content. By default, the `$content_width` global is used to determine this value, with a fallback of '640px' if none is defined.
* `wp_gdpr_cookie_notice_heading_level`: Filters the heading level to use for the cookie notice heading. Default is 'h2'.

= Where should I submit my support request? =

For regular support requests, please use the [wordpress.org support forums](https://wordpress.org/support/plugin/wp-gdpr-cookie-notice). If you have a technical issue with the plugin where you already have more insight on how to fix it, you can also [open an issue on Github instead](https://github.com/felixarntz/wp-gdpr-cookie-notice/issues).

= How can I contribute to the plugin? =

If you have some ideas to improve the plugin or to solve a bug, feel free to raise an issue or submit a pull request in the [Github repository for the plugin](https://github.com/felixarntz/wp-gdpr-cookie-notice). Please stick to the [contributing guidelines](https://github.com/felixarntz/wp-gdpr-cookie-notice/blob/master/CONTRIBUTING.md).

You can also contribute to the plugin by translating it. Simply visit [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/wp-gdpr-cookie-notice) to get started.

== Screenshots ==

1. The cookie notice with its default content and appearance
2. Customizing the cookie notice behavior and content
3. Customizing the cookie notice appearance

== Changelog ==

= 1.0.0-beta.2 =

* Fix incompatibility with [PWA plugin](https://wordpress.org/plugins/pwa/), previously causing the service worker to be broken.

= 1.0.0-beta.1 =

* Initial beta release.
