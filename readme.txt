=== Product Redirection for WooCommerce ===
Contributors: polyplugins
Tags: woocommerce, woocommerce redirect, woocommerce redirection, seo, search engine optimization, product redirect, product redirection, product, redirect, redirection
Requires at least: 4.0
Tested up to: 5.7
Stable tag: 1.1.6
Requires PHP: 5.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Instead of deleting products which is bad for SEO, redirect them to their parent category or a custom url.

== Description ==

[youtube https://youtu.be/WCCaI83Bw5c]

Deleting products without any redirects can result in a negative impact on your Search Engine Optimization or SEO. To prevent product deletion a notice is displayed on clicking trash to inform that they should be redirecting.

Free Version:

* Notice in product manager to not delete products
* Option in product to redirect to parent category or a custom url
* Setting: Remove product deletion notice
* Setting: Disable trash/delete on product manager

[Pro Version](https://www.polyplugins.com/product/product-redirection-for-woocommerce/ "Poly Plugins"):

* Notices on product page that the product is no longer in stock
* Automatic out of stock product handling
* Landing pages
* Product recommendations

Check out our [Roadmap](https://trello.com/b/yCyf2WYs/free-product-redirection-for-woocommerce-roadmap "Roadmap") to see our upcoming features!

== Installation ==


1. Backup WordPress
1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the WooCommerce->Redirection page to configure the plugin

== Frequently Asked Questions ==

= Why should I use this? =

Deleting products is bad for Search Engine Optimization (SEO) and setting redirects through apache can lead to a huge htaccess.

= Why are fields on products not loading? =

If you have ACF installed already, you may need to sync the fields, as they are stored via json to reduce db calls. We built this around ACF to reduce development times and to prevent adding unnecessary code if you already have it installed.

= Why does this use ACF? =

Mainly to reduce the amount of time in development, but also to save resources. If you already have Advanced Custom Fields installed, then you don't have to worry about additional code executing on product admin pages.


== Screenshots ==

1. Set the redirects via the product.
2. Show a notice to help prevent trashing products.
3. Plugin settings

== Changelog ==

= 1.1.6 =
* Optimization: Refactored code in preparation for Advanced Custom Fields standalone dependancy
* Added: Additional Error Handling
* Fix: Deprecations

= 1.1.5 =
* Fix: Versioning

= 1.1.4 =
* Added: Notice to add Advanced Custom Fields as we will be removing it as a dependancy from our plugin
* Enhancement: Optimized code

= 1.1.3 =
* Updated: Dependencies
* Fix: Deprecations. We plan to rewrite the entire plugin in a future release to remove ACF dependency and further optimize code.

= 1.1.2 =
* Bugfix: ACF Header Error

= 1.1.1 =
* Improved: Trash Deletion Prevention
* Bugfix: ACF fields showing on posts
* Bugfix: Trash Can Disable message showing on trashed products
* Added: Roadmap CTA on plugins page
* Adjustment: Disable trash is now set to default enabled

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

