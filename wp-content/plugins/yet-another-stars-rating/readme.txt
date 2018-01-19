=== Yasr - Yet Another Stars Rating ===
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AXE284FYMNWDC
Tags:  5 star, admin, administrator, AJAX, five-star, javascript, jquery, post rating, posts, rate, rating, rating platform, rating system, ratings, review, reviews, rich snippets, seo, star, star rating, stars, vote, Votes, voting, voting contest, schema, serp
Requires at least: 4.3.0
Contributors: Dudo
Tested up to: 4.9
Stable tag: 1.4.9
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Yet Another Stars Rating is a simple plugin which allows you and / or your visitor to rate a post or element. Ideal for review's website

== Description ==
Yet Another Stars Rating (YASR) is a new system review based on jquery plugin RateIT.
With YASR you can make your own review or let your visitors vote, and you can even create multiple sets (a set of stars for each aspect to
rate). Review scores or visitor ratings will be indexed by search engines through snippets .

= How To use =

= Reviewer Vote =
Once YASR is installed, when you create or update a page or a post, a box (metabox) will be available in the upper right corner where you'll
be able to insert the overall rating. You can either place the overall rating automatically at the beginning or the end of a post (look in "Settings"
-> "Yet Another Stars Rating: Settings"), or wherever you want in the page using the shortcode [yasr_overall_rating] (easily added through the visual editor).

= Visitor Votes =
You can give your users the ability to vote, pasting the shortcode [yasr_visitor_votes] where you want the stars to appear.
Again, this can be placed automatically at the beginning or the end of each post; the option is in "Settings" -> "Yet Another Stars Rating: Settings".
This may not works if you use a caching plugin.

= Multi Set =
Multisets give the opportunity to score different aspects for each review: for example, if you're reviewing a videogame, you can create the aspects "Graphics",
"Gameplay", "Story", etc.


= Supported Languages =

Check [here](https://translate.wordpress.org/projects/wp-plugins/yet-another-stars-rating/dev) to see if your translation is up to date.
Write on the [forum](https://wordpress.org/support/plugin/yet-another-stars-rating) to ask to become a validator :)

In this video I'll show you the "Auto Insert" feature and manual placement of YASR basic shortcodes.
[youtube https://www.youtube.com/watch?v=M47xsJMQJ1E]

= Related Link =
* News and doc at [Yasr Official Site](https://yetanotherstarsrating.com/)
* [Demo page for Overall Rating and Vistor Rating](https://yetanotherstarsrating.com/yasr-basics-shortcode/)
* [Demo page for Multi Sets](https://yetanotherstarsrating.com/yasr-multi-sets/)
* [Demo page for Rankings](https://yetanotherstarsrating.com/yasr-rankings/)

= Press =
* [WPMUDEV](http://premium.wpmudev.org/blog/free-wordpress-ratings-testimonials-subscriber-count-plugins/)
* [BRIANLI.COM](http://brianli.com/yet-another-stars-rating-wordpress-plugin-review/)
* [WPEXPLORER](http://www.wpexplorer.com/google-rich-snippets-wordpress/)
* [SOURCEWP](http://www.sourcewp.com/best-post-voting-plugins-wordpress/)
* [HOWSHOST](https://howshost.com/add-post-rating-system-in-wordpress/)

Do you want more feature? [Check out Yasr Extensions!](https://yetanotherstarsrating.com/#yasr-pro-anchor)

== Installation ==
1. Install Yet Another Stars Rating either via the WordPress.org plugin directory, or by uploading the files to your server
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Yet Another Star Rating menu in Settings and set your options.

== Frequently Asked Questions ==

= What is "Overall Rating"? =
It is the vote given by who writes the review: readers are able to see this vote in read-only mode. Reviewer can vote using the box on the top rigth when writing a new article or post (he or she must have at least the "Author" role). Remember to insert this shortcode **[yasr_overall_rating]** to make it appear where you like. You can choose to make it appear just in a single post/page or in archive pages too (e.g. default Index, category pages, etc).

= What is "Visitor Rating"? =
It is the vote that allows your visitors to vote: just paste this shortcode **[yasr_visitor_votes]** where you want the stars to appear. This may not works if you use a caching plugin.

[Demo page for Overall Rating and Vistor Rating](https://yetanotherstarsrating.com/yasr-basics-shortcode/)


= What is "Multi Set"? =
It is the feature that makes YASR awesome. Multisets give the opportunity to score different aspects for each review: for example, if you're reviewing a videogame, you can create the aspects "Graphics", "Gameplay", "Story", etc. and give a vote for each one. To create a set, just go in "Settings" -> "Yet Another Stars Rating: Settings" and click on the "Multi Sets" tab. To insert it into a post, just paste the shortcode that YASR will create for you.

[Demo page for Multi Sets](https://yetanotherstarsrating.com/yasr-multi-sets/)

= What is "Ranking reviews" ? =
It is the 10 highest rated item chart by reviewer. In order to insert it into a post or page, just paste this shortcode **[yasr_top_ten_highest_rated]**

= What is "Users' ranking" ? =
This is 2 charts in 1. Infact, this chart shows both the most rated posts/pages or the highest rated posts/pages.
For an item to appear in this chart, it has to be rated twice at least.
Paste this shortcode to make it appear where you want **[yasr_most_or_highest_rated_posts]**

= What is "Most active reviewers" ? =
If in your site there are more than 1 person writing reviews, this chart will show the 5 most active reviewers. Shortcode is **[yasr_top_5_reviewers]**

= What is "Most active users" ? =
When a visitor (logged in or not) rates a post/page, his rating is stored in the database. This chart will show the 10 most active users, displaying the login name if logged in or "Anonymous" otherwise. The shortcode : **[yasr_top_ten_active_users]**

[Demo page for Rankings](https://yetanotherstarsrating.com/yasr-rankings/)

= Wait, wait! Do I need to keep in mind all this shortcode? =
Of course not: you can easily add it on the visual editor just by clicking the "Yasr Shortcode" button just above the editor



== Screenshots ==
1. Example of yasr in a videogame review
2. Another example of a restaurant review
3. User's ranking showing most rated posts
4. User's ranking showing highest rated posts
5. Ranking reviews

== Changelog ==

The full changelog can be found in the plugin's directory. Recent entries:

= 1.4.9 =
* Added support to litespeed caching plugin (kudos to pako69) 

= 1.4.8 =
* Minor changes (Thx to pako69)

= 1.4.7 =
* Removed freemius sdk. 

= 1.4.6 =
* Jquery ui css is now loaded locally

= 1.4.4 =
* Added freemius sdk.

= 1.4.3 =
* If Blogposting is selected as itemtpye, and no featured image is set, use the logo url 

= 1.4.2 =
* TWEAKED: progressbars and tooltip classes have been fixed

= 1.4.1 =
* TWEAKED: Yasr now supports ajax added content
* TWEAKED: RateIt updated to version 1.0.24

= 1.4.0 =
* Fixed post title get echeod 

= 1.3.9 =
* Dropped import support for old gd star

= 1.3.8 =
* FIXED: Shortcode creator finally works even in text mode
* TWEAKED: Added a link to the settings in the plugin list under the plugin name
* TWEAKED: minor changes

= 1.3.7 =
* FIXED: Rankings yasr_top_ten_highest_rated, yasr_most_or_highest_rated_posts and yasr_top_5_reviewers has been fixed (broken in version 1.3.6)
* FIXED: js errors on yasr-front.js, thanks to jg88

= 1.3.6 = 
* TWEAKED: yasr_visitor_votes doesn't use anymore yasr_votes table. It use only yasr_log instead. From this version, yasr_votes is not created anymore. If after this update everything is ok, if you wish you can drop yasr_votes table
* TWEAKED: add the link to the post on yasr recent ratings widget
* TWEAKED: huge code cleanup

= 1.3.5 =
* TWEAKED: READ CAREFULLY: this is the first step of an important yasr database change: the main goal is to switch from yasr_votes table to wordpress default post_meta.
A database backup is strongly suggested.
* NEW FEATURE: new widget that shows the last 5 recent ratings.



