=== Cooked - Recipes for WordPress ===
Contributors: boxystudio
Donate link: https://boxystudio.com/#coffee
Tags: recipe, recipes, food, cooking, chef, culinary, nutrition, seo
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 1.2.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The best way to create & display recipes with WordPress. SEO optimized (rich snippets), galleries, cooking timers, printable recipes and much more.

== Description ==

The best way to create & display recipes with WordPress. SEO optimized (rich snippets), photo galleries, cooking timers, printable recipes and so much more. Check out the full list below.

**Be sure to check the [Cooked Documentation](http://docs.cooked.pro/collection/1-cooked) if you need some help!**

**Check out the demo: [demos.boxystudio.com/cooked/](https://demos.boxystudio.com/cooked/)**

= High quality design and usability =

Using the drag & drop recipe builder, you can create your recipes quickly and without limitations. Add ingredients, directions—and then add a gallery, nutrition facts, cooking times and much more.

= Loads of premium features packed into a free plugin =

* Drag & drop ingredients and directions.
* SEO Optimized - Google Structured Data and Schema support.
* Beautiful grid-based masonry recipe lists.
* Prep & Cooking Times
* Photo Galleries
* Nutrition Facts
* Difficulty Levels
* Powerful recipe search with a text search, categories & sorting options.
* Author template to list recipes by a single author.
* Cooking times with clickable, interactive timers.
* Very developer-friendly with loads of hooks & filters.
* Servings switcher to adjust ingredient amounts.

= Developers love it =

Cooked has a whole slew of hooks and filters to customize Cooked as much as you need to (documentation for developers is coming soon!).

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/cooked` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Recipes > Settings screen to configure the plugin.
4. Go to Recipes > Add New to start adding your recipes!
5. Head over to the [Cooked Documentation](http://docs.cooked.pro/collection/1-cooked) for more help.

== Frequently Asked Questions ==

= Having issues with the plugin? =

Be sure to check the [Cooked Documentation](http://docs.cooked.pro/collection/1-cooked) for guides and documentation. If you're still having issues, create a new support topic and let me know what's going on. I'm happy to help! Please don't post a bad review without discussing here first, I really appreciate it!

= I purchased Cooked on CodeCanyon.net, is this the same thing? =

This version is **not** the same plugin. It has been completely rebuilt from the ground up and will soon replace the original (now called Cooked Classic). For more information on Cooked and the upcoming Cooked Pro, check out [https://cooked.pro](https://cooked.pro).

== Screenshots ==

1. Adding Ingredients
2. Adding Directions
3. Recipe Template
4. Nutrition Facts
5. Interactive Cooking Timers
5. Gallery Builder
6. Recipe Shortcodes

== Changelog ==

= 1.2.0 =
* **NEW** New Widget: "Cooked - Recipe Search" is here! If you were using the old Cooked Widget, you will need to replace that with one of the new ones.
* **NEW** Added REST API support to recipes and recipe categories.
* **NEW** Added a `[cooked-search]` shortcode so you can display the search bar anywhere. Use `compact="true"` for sidebars and other small-width areas. You can also hide the browse and sorting fields. Check the documentation for more details.
* **TWEAK** Added the same "search" shortcode options to `[cooked-browse]` so you can customize the recipe search bar from that shortcode as well. Check the documentation for more details.
* **TWEAK** Added some hooks and filters to the welcome screen to add the ability to include the Cooked Pro changelog information there as well.
* **TWEAK** Direction images are formatted much better now (inline with the text and some margin below).
* **TWEAK** Converted all CSS "em" values to "rem" values.
* *FIX* Fixed a bug where posts were being duplicated when embedding "draft" recipes using the shortcode.
* *FIX* Disabling Public Recipes will now work as intended. Recipes will be hidden from search results, recipe URLs redirected to the homepage, etc.
* *FIX* Added some missing language strings.

= 1.1.13 =
* **NEW** Added kg (kilograms) as a measurement option.
* *FIX* Fixed an issue where zeros were being removed from large numbers.
* *FIX* Recipes will now 404 if "Disable Public Recipes" is active.
* *FIX* Minor CSS adjustments throughout.

= 1.1.12 =
* Adjusted some code to support the upcoming Cooked Pro features.
* Some minor text changes in the Settings panel.

= 1.1.11 =
* *FIX* Fixed an issue with ingredient amounts getting rounded up to 1.
* *FIX* Fixed some theme compatibiltiy issues.
* *FIX* Re-enabled structured data for recipes. Didn't mean to disable this, sorry!

= 1.1.10 =
* **NEW** Ingredient amounts will now display as entered (fractions or decimals) in the number format based on your language settings.
* **NEW** Added taxonomy filter dropdowns to the admin recipe list page.
* **NEW** Added developer filters for customizing the "Percent Daily Value" calculations.
* *FIX* Added compatibility for the "Bridge" theme.

= 1.1.9 =
* *FIX* Added "1/5" support to measurements.
* *FIX* Other minor bug fixes throughout.
* *FIX* Fixed an edge-case issue where private Vimeo videos would not show up within recipe content.

= 1.1.8 =
* **NEW** HTML is allowed in all ingredient/direction fields.
* *FIX* Fixed some redirect issues.
* *FIX* Some adjustments to support the upcoming Cooked Pro.

= 1.1.7 =
* *FIX* Fixed an issue with the Cooked settings screens if a non-English language is enabled.
* *FIX* Fixed an issue for when the "Browse Recipe Page" and "Single Recipe Post" slugs were the same (i.e. /recipes/). You can now use the same slug for both!

= 1.1.6 =
* **NEW** Tested and working in WordPress 4.8!
* **NEW** Custom checkbox toggles on the Settings page.
* *FIX* Fixed an issue with category redirects. There was a double slash being added that has now been resolved. Huge thanks to **@travelnlass** and **@kitcatsz** for finding this one!

= 1.1.5 =
* *FIX* A lot more fixes for the [cooked-recipe] shortcode. Huge thanks to Zoe and Mariana for donating their time and websites to help me work out these issues!
* **NEW** Added an advanced ability to "Disable Cooked `<meta>` Tags" when needed.
* **NEW** Added an advanced ability to "Disable Public Recipes" when needed.

= 1.1.4 =
* *FIX* Several fixes for the `[cooked-recipe]` shortcode.
* *FIX* Fixed some issue with printing recipes.
* *FIX* Applied selected servings to print view.

= 1.1.3 =
* *FIX* Fixed an issue with using decimals on Nutrition Facts.

= 1.1.2 =
* *FIX* Fixed an error on the recipe author template.
* *FIX* More minor tweaks to support the upcoming Cooked Pro plugin.

= 1.1.1 =
* *FIX* Compatibility improvements with the Yoast SEO plugin.
* *FIX* Some minor tweaks to support the upcoming Cooked Pro plugin.

= 1.1.0 =
* **NEW** **Full-Screen Mode:** Just include "fullscreen" in the `[cooked-info]` shortcode. Really shines on mobile devices!
* **NEW** **Printable Recipes:** Just include "print" in the `[cooked-info]`shortcode. Includes some handy "a-la-carte" print options.
* *FIX* Some adjustments for layouts on smaller devices (responsive fixes).
* *FIX* Fixed an issue where quantities and amounts would not show up without a "Servings" setting. Now it works no matter what!
* *FIX* Minor code adjustments to better support Cooked Pro.

= 1.0.0 =
* **NEW** *Everything is new!*
* **NEW** Drag & drop ingredients and directions.
* **NEW** Beautiful grid-based masonry recipe lists.
* **NEW** Powerful recipe search with a text search, categories & sorting options.
* **NEW** Author template to list recipes by a single author.
* **NEW** Cooking times with clickable, interactive timers.
* **NEW** Very developer-friendly with loads of hooks & filters.
* **NEW** Servings switcher to adjust ingredient amounts.
* **NEW** SEO Optimized - Google Structured Data and Schema support.
* **NEW** Prep & cooking times.
* **NEW** Nutrition facts.
* **NEW** Difficulty levels.
* **NEW** Photo galleries.
