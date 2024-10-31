=== Multi Video Box ===
Contributors: skustes
Donate link: http://www.nuttymango.com/donate
Tags: video, youtube, vimeo, dailymotion, metacafe, vevo, player, embed, ajax, 
Requires at least: 3.3
Tested up to: 3.5.1
Stable tag: 1.5.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plug-in allows you to display groups of videos in a single box.

THIS PLUG-IN IS NO LONGER SUPPORTED BY THE DEVELOPER.

== Description ==

This plug-in allows you to display groups of videos in a single box.  It uses an AJAX-driven tab system to load selected videos.  The plug-in currently supports the use of videos hosted on YouTube, Vimeo, Daily Motion, Vevo, Metacafe, MuchShare, NowVideo, VideoMega, ShareVid, Flashx.TV, and Putlocker.  CSS is fully-customizable.

THIS PLUG-IN IS NO LONGER SUPPORTED BY THE DEVELOPER.

== Installation ==

1. Upload 'multi-video-box' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to the Multi Video Box menu and add your videos.
4. Create groups for your videos.
5. Use the [mvob] shortcode to output your video groups (or individual videos) in theme files, posts, and pages.  Full details of this shortcode are available at the bottom of this file, from the Multi Video Box instructions page, or here: http://www.nuttymango.com/tutorials/multi-video-box/

== Frequently asked questions ==

N/A

== Screenshots ==

1. http://www.nuttymango.com/wp-content/uploads/2013/05/mvob-admin-1.png
2. http://www.nuttymango.com/wp-content/uploads/2013/05/mvob-admin-2.png
3. http://www.nuttymango.com/wp-content/uploads/2013/05/mvob-top-tab.png
4. http://www.nuttymango.com/wp-content/uploads/2013/05/mvob-left-tab.png

== Changelog ==

= 1.5.2 =
* Updated instructions for shortcode

= 1.5.1 =
* Fixed bug on titles with quotes

= 1.5 = 
* jQuery conflict issue resolved
* Added support for not outputting Title and Description

= 1.4 =
* Fixed issue with default width/height settings

= 1.3 =
* Added missing function

= 1.2 =
* Removed AJAX from Video-to-Group screens due to browser compatibility issues
* Added support for videos on MuchShare, NowVideo, VideoMega, ShareVid, Flashx.TV, and Putlocker

= 1.1 =
* Fixed table structure issue that caused a bug when adding videos to groups
* Fixed Youtu.be embed code creation bug

= 1.0 =
* Initial release

== Upgrade notice ==

== [mvob] Shortcode Usage ==
The [mvob] shortcode requires one of two parameters:
group - The ID of the Group that you want to display, such as: [mvob group=1]
video - The ID of the individual Video that you want to display, such as: [mvob video=1]

If both group and video are passed, group will be used.

 - - - Additional Parameters - - - 
If you want to custom tailor the output of your Videos, the [mvob] shortcode gives you several options.  You can use any or all of these when you call the [mvob] shortcode.

* tab - Which side of the Video display do you want the tabs on?  Accepts: top (default), left, bottom, and right.  This parameter is ignored if you're only outputting a single Video.
* title - Where do you want the title of the Video displayed?  Accepts: top (default), bottom, or none
* description - Where do you want the description of the Video displayed?  Accepts: top (default), bottom, or none

== Shortcode Examples ==
* [mvob group=1] Outputs the videos for Group #1 with default settings
* [mvob group=12 tab="left" title="bottom"] Outputs videos for Group #12 with tabs on the left and the title below the video.  Description remains above the video (per default setting).
* [mvob video=2 tab="left" description="bottom"] Outputs Video #2 with the title above the video (per default setting) and description below the video.  tab setting is ignored because there is only one Video being output.