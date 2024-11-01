=== Plugin Name ===
Contributors: Alex Coles
Tags: british summer time, bst, daylight saving time, gmt, greenwich mean time, time zone, utc
Requires at least: 2.0
Tested up to: 2.5.1
Stable tag: 1.2

Plugin to automatically adjust for British Summer Time.

== Description ==

The UK Time plugin reads the modification time of each post and comment being displayed and adds an hour if it falls inside British Summer Time.

* No need to adjust the UTC offset ever again
* Works with existing posts and comments, even if you used to adjust the UTC offset
* No times or dates are permanently changed

Requires the web server's time zone to be UTC.

== Installation == 

1. Unzip and upload the `uktime.php` file to the `/wp-content/plugins` directory
1. Activate the plugin from the `Plugins` menu in WordPress
1. Ensure your theme uses `<?php the_time() ?>` to display the time and `<?php the_date()` to display the date.*

* Note: The default theme supplied with WordPress uses its own parameters to control the format of the date and time: `<?php the_time('F jS, Y') ?>`. The UK Time plugin will not modify any times and dates which do not use the global date and time format. To adjust the global date and time format, go to the `Settings` menu in WordPress.