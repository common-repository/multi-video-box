<div class="wrap">
	<div id="main_instructions">
		<?php /* - - - - - - - - - - - - Video Tutorials - - - - - - - - - - - - */ ?>
		<div class="center"><h2>Video Tutorials</h2></div>
		<div class="center">Video tutorials for using the "Multi Video Box" plugin can be found here:</div>
		<div class="center"><a href="http://www.nuttymango.com/tutorials/multi-video-box/" target="_blank"><h2>Multi Video Box Tutorial</h2></a></div>

		<div class="hr"></div>

		<?php /* - - - - - - - - - - - - Usage Instructions - - - - - - - - - - - - */ ?>
		<div class="center"><h2>Using The Multi Video Box Plugin</h2></div>
		<div class="inst_step">
			<div class="inst_title" onclick="show_hide_inst( 'step-1' );">Step 1: Upload Your Videos To YouTube, etc.</div>
			<div class="inst_content" id="step-1">
				<p>Before you can link to your videos, they have to be uploaded.  This plug-in currently supports videos hosted on: YouTube, Vimeo, Daily Motion, Vevo, Metacafe, MuchShare, NowVideo, VideoMega, ShareVid, Flashx.TV, and Putlocker.  Please let me know if there are additional video sites that you would like to see supported.</p>
				<p><strong>Why doesn't the Multi Video Box support videos that you've uploaded to your site?</strong>  Because cross-browser support of the various video file types is spotty and tempermental.  These video hosting sites allow for viewing by virtually everyone on the internet.  If there is a strong need for use of videos on your own site instead of through YouTube, etc, let me know and I'll prioritize this feature.</p>
			</div>
		</div>
		<div class="inst_step">
			<div class="inst_title" onclick="show_hide_inst( 'step-2' );">Step 2: Add Your Videos</div>
			<div class="inst_content" id="step-2">
				<p>Once your videos are uploaded, <a href="admin.php?page=mvob_videos&action=add">add them to your Videos section</a> so they can be embedded.</p>
			</div>
		</div>
		<div class="inst_step">
			<div class="inst_title" onclick="show_hide_inst( 'step-3' );">Step 3: Group Your Videos</div>
			<div class="inst_content" id="step-3">
				<p>The real power of the Multi Video Box plugin is the ability to output multiple videos in a single tabbed display.  <a href="admin.php?page=mvob_groups">Use the Groups feature</a> to create groups that can be displayed with the <a href="admin.php?page=mvob&action=shortcode">[mvob] shortcode</a>.</p>
			</div>
		</div>
		<div class="inst_step">
			<div class="inst_title" onclick="show_hide_inst( 'step-4' );">Step 4: Display Your Videos</div>
			<div class="inst_content" id="step-4">
				<p>Use the <a href="admin.php?page=mvob&action=shortcode">[mvob] shortcode</a> in your posts, pages, and templates.</p>
			</div>
		</div>

		<div class="hr"></div>

		<?php /* - - - - - - - - - - - - Contact Me Instructions - - - - - - - - - - - - */ ?>
		<div class="center"><h2>Contact Me With Issues</h2></div>
		<p>If you're having trouble getting "Multi Video Box" to work properly with your system, please email me at scott@nuttymango.com and I will help.</p>
	</div>
	<?php 
		global $mvob_paypal;
		echo $mvob_paypal->paypal_donation_form();
	?>
</div>