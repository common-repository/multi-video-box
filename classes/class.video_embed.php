<?php
// ------------------------------------------------------------------------
// This file declares the video_embed class
// This class creates the proper embed code for a given video URL
// This class currently supports the following external sites:
// 	YouTube
//	Vimeo
//	Dailymotion
//	Vevo
//	Metacafe
//	MuchShare
// 	NowVideo
//	VideoMega
//	ShareVid
//	Flashx.TV
//	Putlocker
// Future:
//	Blip
// 	Break
// ------------------------------------------------------------------------

if ( !class_exists( 'video_embed' ) ) {
class video_embed {
/* - - - - - - - - - - - - - - - - - - - 
Attributes
- - - - - - - - - - - - - - - - - - - - */
	protected $supported_domains;	// An associative array of the supported domains
	protected $domain_defaults;	// An associative array of the default attributes for each domain
	//protected $valid_mime_types;	// An associative array of file extensions and MIME types
	protected $url;			// The URL to use for embedding the video
	protected $domain;		// The domain of the URL
	protected $pretty_domain;	// The pretty version of the domain (i.e., YouTube for youtube.com)
	protected $video_id;		// The ID of the video
	//protected $file_extension;	// The file extension for videos that are not on a supported site
	//protected $mime_type;		// The mime type for $this->file_extension

/* - - - - - - - - - - - - - - - - - - - 
Constructor & Retrieval Functions
- - - - - - - - - - - - - - - - - - - - */
	public function __construct( $url = '' ) {
		$this->url = $url;

		$this->supported_domains = array(
			'youtube.com' => 'YouTube',
			'youtu.be' => 'YouTube',
			'vimeo.com' => 'Vimeo',
			'dailymotion.com' => 'Dailymotion',
			/*'blip.tv' => 'Blip',*/
			'vevo.com' => 'Vevo',
			'metacafe.com' => 'Metacafe',
			'muchshare.net' => 'MuchShare',
			'nowvideo.eu' => 'NowVideo',
			'videomega.tv' => 'VideoMega',
			'sharevid.co' => 'Sharevid',
			'flashx.tv' => 'flashX',
			'putlocker.com' => 'PutLocker'
		);

		$this->domain_defaults = array(
			'YouTube' => array(
				'width' => 480,
				'height' => 360
			),
			'Vimeo' => array(
				'width' => 480,
				'height' => 360
			),
			'Dailymotion' => array(
				'width' => 480,
				'height' => 270
			),
			/*'Blip' => array(
				'width' => 480,
				'height' => 289
			),*/
			'Vevo' => array(
				'width' => 575,
				'height' => 324
			),
			'Metacafe' => array(
				'width' => 440,
				'height' => 248
			),
			'MuchShare' => array(
				'width' => 500,
				'height' => 315
			),
			'NowVideo' => array(
				'width' => 500,
				'height' => 400
			),
			'VideoMega' => array(
				'width' => 500,
				'height' => 400
			),
			'Sharevid' => array(
				'width' => 645,
				'height' => 344
			),
			'flashX' => array(
				'width' => 620,
				'height' => 400
			),
			'PutLocker' => array(
				'width' => 600,
				'height' => 360
			),
			'generic' => array(
				'width' => 480,
				'height' => 360
			)
		);

		/*$this->valid_mime_types = array(
			"flv" => "video/x-flv",
			"avi" => "video/x-msvideo",
			"mov" => "video/quicktime",
			"mp4" => "video/mp4",
			"mpg" => "video/mpeg",
			"wmv" => "video/x-ms-wmv",
			"3gp" => "video/3gpp",
			"asf" => "video/x-ms-asf",
			"rm" => "application/vnd.rn-realmedia",
			"swf" => "application/x-shockwave-flash"
		);*/
	}

	/* This function creates the embed code for a given URL
	Parameters:
	$url - The URL of the video
	$width - The width for video display
	$height - The height for video display

	Returns:
	$str_embed_html - The HTML to embed the video
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	public function get_embed_code( $url , $width = 0 , $height = 0 ) {
		// Set the URL into $this
		$this->url = $url;

		// Detect the domain
		$this->domain = $this->get_domain();

		// Determine if it's a supported domain
		if ( $this->is_domain_supported() ) {
			// Get the pretty name of the domain
			$this->pretty_domain = $this->get_user_friendly_domain();

			// Retrieve the video ID
			$this->video_id = call_user_func( array( $this , 'get_video_id_' . strtolower( $this->pretty_domain ) ) );

			// If $width or $height are 0, get the defaults for this domain
			if ( $width == 0 )
				$width = $this->domain_defaults[$this->pretty_domain]['width'];
			if ( $height == 0 )
				$height = $this->domain_defaults[$this->pretty_domain]['height'];

			// Create the embed code
			$embed_html = call_user_func( array( $this , 'create_embed_code_' . strtolower( $this->pretty_domain ) ) , $width , $height );
		}
		/*else {
			// If $width or $height are 0, get the defaults for this domain
			if ( $width == 0 )
				$width = $this->domain_defaults['generic']['width'];
			if ( $height == 0 )
				$height = $this->domain_defaults['generic']['height'];

			// Get the file extension and MIME type
			$this->file_extension = $this->get_file_extension();
			$this->mime_type = $this->get_mime_type();

			// If the file is a supported MIME type, create the generic HTML5 video embed
			if ( $this->mime_type !== false )
				$embed_html = $this->create_embed_code_generic( $width , $height );
		}*/

		return $embed_html;
	}

	/* This function retrieves the domain from the URL passed
	Returns:
	$domain - The Domain from the URL
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function get_domain() {
		return str_replace( "www." , "" , parse_url( $this->url , PHP_URL_HOST ) );
	}

	/* These functions determines if the domain is a supported domain
	Returns:
	True or False
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function is_domain_supported() {
		return array_key_exists( $this->domain , $this->supported_domains );
	}
	public function is_domain_supported_public() {
		// Detect the domain
		$this->domain = $this->get_domain();

		return array_key_exists( $this->domain , $this->supported_domains );
	}

	/* This function returns the user-friendly output for an output
	Returns:
	True or False
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function get_user_friendly_domain() {
		return $this->supported_domains[$this->domain];
	}

	/* This function gets the file extension from a video URL
	Returns:
	String - File extension
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	/*private function get_file_extension() {
		$ary_url = explode( "/" , $this->url );
		$file_extension = end( explode( "." , end( $ary_url ) ) );

		return $file_extension;
	}*/

	/* This function gets the MIME type for a given file extension
	Returns:
	String - MIME Type or false if MIME type not found
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	/*private function get_mime_type() {
		$mime_type = $this->valid_mime_types[$this->file_extension];

		if ( $mime_type != "" )
			return $mime_type;
		else
			return false;
	}*/

/* - - - - - - - - - - - - - - - - - - - 
Create Embed Code Functions
- - - - - - - - - - - - - - - - - - - - */
	/* These functions uses the embed URL to create the embed code
	Returns:
	$embed_code - The code to embed a given $url into a page
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function create_embed_code_youtube( $width , $height ) {
		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $this->create_embed_url_youtube() . '" frameborder="0" allowfullscreen></iframe>';

		return $embed_code;
	}
	private function create_embed_code_vimeo( $width , $height ) {
		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $this->create_embed_url_vimeo() . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

		return $embed_code;
	}
	private function create_embed_code_dailymotion( $width , $height ) {
		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $this->create_embed_url_dailymotion() . '" frameborder="0"></iframe>';

		return $embed_code;
	}
	/*private function create_embed_code_blip( $width , $height ) {
		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $this->create_embed_url_blip() . '" frameborder="0" allowfullscreen></iframe><embed type="application/x-shockwave-flash" src="http://blip.tv/api.swf#' . $blip_video_id . '" style="display:none"></embed>';
// http://stackoverflow.com/questions/7078165/programatically-embed-blip-tv-videos
		return $embed_code;
	}*/
	private function create_embed_code_vevo( $width , $height ) {
		$embed_code = '<object width="' . $width . '" height="' . $height . '"><param name="movie" value="' . $this->create_embed_url_vevo() . '" "></param><param name="wmode" value="transparent"></param><param name="bgcolor" value="#000000"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="' . $this->create_embed_url_vevo() . '" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="' . $width . '" height="' . $height . '" bgcolor="#000000" wmode="transparent"></embed></object>';

		return $embed_code;
	}
	private function create_embed_code_metacafe( $width , $height ) {
		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $this->create_embed_url_metacafe() . '" frameborder="0" allowfullscreen></iframe>';

		return $embed_code;
	}
	private function create_embed_code_muchshare( $width , $height ) {
		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $this->create_embed_url_muchshare() . '" frameborder="0" scrolling="no"></iframe>';

		return $embed_code;
	}
	private function create_embed_code_nowvideo( $width , $height ) {
		$nowvideo_url = str_replace( '%%WIDTH%%' , $width , $this->create_embed_url_nowvideo() );
		$nowvideo_url = str_replace( '%%HEIGHT%%' , $height , $nowvideo_url );

		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $nowvideo_url . '" frameborder="0" scrolling="no"></iframe>';

		return $embed_code;
	}
	private function create_embed_code_videomega( $width , $height ) {
		$videomega_url = str_replace( '%%WIDTH%%' , $width , $this->create_embed_url_videomega() );
		$videomega_url = str_replace( '%%HEIGHT%%' , $height , $videomega_url );

		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $videomega_url . '" frameborder="0" scrolling="no"></iframe>';

		return $embed_code;
	}
	private function create_embed_code_sharevid( $width , $height ) {
		$sharevid_url = str_replace( '%%WIDTH%%' , $width , $this->create_embed_url_sharevid() );
		$sharevid_url = str_replace( '%%HEIGHT%%' , $height , $sharevid_url );

		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $sharevid_url . '" frameborder="0" marginwidth="0" marginheight="0" scrolling="no"></iframe>';

		return $embed_code;
	}
	private function create_embed_code_flashx( $width , $height ) {
		$flashx_url = str_replace( '%%WIDTH%%' , $width , $this->create_embed_url_flashx() );
		$flashx_url = str_replace( '%%HEIGHT%%' , $height , $flashx_url );

		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $flashx_url . '" frameborder="0" scrolling="no"></iframe>';

		return $embed_code;
	}
	private function create_embed_code_putlocker( $width , $height ) {
		$embed_code = '<iframe width="' . $width . '" height="' . $height . '" src="' . $this->create_embed_url_putlocker() . '" frameborder="0" scrolling="no"></iframe>';

		return $embed_code;
	}
	/*private function create_embed_code_generic( $width , $height ) {
		$embed_code = '<video width="' . $width . '" height="' . $height . '" controls>
				<source src="' . $this->url . '" type="' . $this->mime_type . '">
					Your browser does not support the video tag.
				</video>';

		return $embed_code;
	}*/

/* - - - - - - - - - - - - - - - - - - - 
Create Embed URL Functions
- - - - - - - - - - - - - - - - - - - - */
	/* These functions use the Video ID to create the embed URL
	Returns:
	$embed_url - The embed URL for the video
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function create_embed_url_youtube() {
		$embed_url = 'http://www.youtube.com/embed/' . $this->video_id;

		return $embed_url;
	}
	private function create_embed_url_vimeo() {
		$embed_url = 'http://player.vimeo.com/video/' . $this->video_id;

		return $embed_url;
	}
	private function create_embed_url_dailymotion() {
		$embed_url = 'http://www.dailymotion.com/embed/video/' . $this->video_id;

		return $embed_url;
	}
	/*private function create_embed_url_blip() {
		$blip_rss = 'http://blip.tv/rss/view/' . $this->video_id;
		$blip_xml = new SimpleXMLElement( $blip_rss , null , true );
		$embed = $blip_xml->xpath('channel/item');

		echo var_dump( $embed ); exit;
		//$embed_url = 'http://blip.tv/play/' . $this->video_id . '.x?p=1';

		return $embed_url;
	}*/
	private function create_embed_url_vevo() {
		// http://www.vevo.com/watch/belinda/vevo-go-sh
		$embed_url = 'http://videoplayer.vevo.com/embed/Embedded?videoId=' . $this->video_id . '&playlist=false&autoplay=0&playerType=embedded&env=0&cultureName=en-US&cultureIsRTL=False';

		return $embed_url;
	}
	private function create_embed_url_metacafe() {
		$embed_url = 'http://www.metacafe.com/embed/' . $this->video_id . '/';

		return $embed_url;
	}
	private function create_embed_url_muchshare() {
		$embed_url = 'http://muchshare.net/embed-' . $this->video_id . '.html';

		return $embed_url;
	}
	private function create_embed_url_nowvideo() {
		$embed_url = 'http://embed.nowvideo.eu/embed.php?v=' . $this->video_id . '&width=%%WIDTH%%&height=%%HEIGHT%%';

		return $embed_url;
	}
	private function create_embed_url_videomega() {
		$embed_url = 'http://videomega.tv/iframe.php?ref=' . $this->video_id . '&width=%%WIDTH%%&height=%%HEIGHT%%';

		return $embed_url;
	}
	private function create_embed_url_sharevid() {
		$embed_url = 'http://sharevid.co/embed-' . $this->video_id . '-%%WIDTH%%x%%HEIGHT%%.html';

		return $embed_url;
	}
	private function create_embed_url_flashx() {
		$embed_url = 'http://play.flashx.tv/player/embed.php?hash=' . $this->video_id . '&width=%%WIDTH%%&height=%%HEIGHT%%';

		return $embed_url;
	}
	private function create_embed_url_putlocker() {
		$embed_url = 'http://www.putlocker.com/embed/' . $this->video_id;

		return $embed_url;
	}

/* - - - - - - - - - - - - - - - - - - - 
Video ID Functions
- - - - - - - - - - - - - - - - - - - - */
	/* These functions retrieve the Video ID for a given domain
	Returns:
	$video_id - The Video ID
	- - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
	private function get_video_id_youtube() {
		// Youtu.be URLs require a different method for getting the ID
		if ( $this->domain != "youtu.be" ) {
			// Get the ID from the YouTube URL
			$id_position = strpos( $this->url , 'v=' ) + 2;
			$next_var_position = strpos( $this->url , '&' , $id_position );

			// If there is a query string variable after the ID, get the ID from $id_position to the next variable
			if ( is_numeric( $next_var_position ) )
				$video_id = substr( $this->url , $id_position , $next_var_position - $id_position );
			// Otherwise, grab from v= to the end of the string
			else
				$video_id = substr( $this->url , $id_position );
		}
		else {
			// Get the ID from the URL
			$ary_url = explode( "/" , $this->url );
			$last_node = end( $ary_url );

			// If the last node is empty, get the 2md to last node.
			if ( trim( $last_node ) == "" ) {
				$video_id = prev( $ary_url );
			}
			// Otherwise, get the last node
			else
				$video_id = $last_node;
		}

		return $video_id;
	}
	private function get_video_id_vimeo() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		return end( $ary_url );
	}
	private function get_video_id_dailymotion() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		$ary_video_id = explode( "_" , end( $ary_url ) );
		$video_id = $ary_video_id[0];

		return $video_id;
	}
	/*private function get_video_id_blip() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		$video_id = end( explode( "-" , end( $ary_url ) ) );

		return $video_id;
	}*/
	private function get_video_id_vevo() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		$video_id = end( $ary_url );

		return $video_id;
	}
	private function get_video_id_metacafe() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		$last_node = end( $ary_url );

		// If the last node is empty, get the 3rd to last node.
		if ( trim( $last_node ) == "" ) {
			$video_name = prev( $ary_url );
			$video_id = prev( $ary_url );
		}
		// Otherwise, get the 2nd to last node
		else
			$video_id = prev( $ary_url );

		return $video_id;
	}
	private function get_video_id_muchshare() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		$video_id = end( $ary_url );

		return $video_id;
	}
	private function get_video_id_nowvideo() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		$video_id = end( $ary_url );

		return $video_id;
	}
	private function get_video_id_videomega() {
		// Get the ID from the VideoMega URL
		$id_position = strpos( $this->url , 'ref=' ) + 4;
		$next_var_position = strpos( $this->url , '&' , $id_position );

		// If there is a query string variable after the ID, get the ID from $id_position to the next variable
		if ( is_numeric( $next_var_position ) )
			$video_id = substr( $this->url , $id_position , $next_var_position - $id_position );
		// Otherwise, grab from ref= to the end of the string
		else
			$video_id = substr( $this->url , $id_position );

		return $video_id;
	}
	private function get_video_id_sharevid() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		$video_id = str_replace( ".html" , "" , end( $ary_url ) );

		return $video_id;
	}
	private function get_video_id_flashx() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		end( $ary_url );
		$video_id = prev( $ary_url );

		return $video_id;
	}
	private function get_video_id_putlocker() {
		// Get the ID from the URL
		$ary_url = explode( "/" , $this->url );
		$video_id = end( $ary_url );

		return $video_id;
	}
}
}
?>