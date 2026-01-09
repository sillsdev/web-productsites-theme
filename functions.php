<?php
	add_theme_support('post-formats', array());
	add_theme_support('post-thumbnails', array('post', 'page', 'custom-post-type-name'));
	add_theme_support('menus', array());
	$customerHeaderDefault = array(
			'default-image'          => '',
			'random-default'         => false,
			'width'                  => 0,
			'height'                 => 0,
			'flex-height'            => false,
			'flex-width'             => false,
			'default-text-color'     => '',
			'header-text'            => true,
			'uploads'                => true,
			'wp-head-callback'       => '',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
	);
	add_theme_support('custom-header', $customerHeaderDefault);
	add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
	add_theme_support('woocommerce', array());
	add_theme_support('custom-logo', array());

	add_filter('get_twig', 'add_to_twig');
	add_filter('timber_context', 'add_to_context');
	
	/*  Add responsive container to embeds
	/* ------------------------------------ */ 
	function alx_embed_html( $html ) {
			return '<div class="videoWrapper">' . $html . '</div>';
	}
	 
	add_filter( 'embed_oembed_html', 'alx_embed_html', 10, 3 );
	add_filter( 'video_embed_html', 'alx_embed_html' ); // Jetpack

	add_action('wp_enqueue_scripts', 'silps_scripts');
	add_action('wp_footer', 'silps_custom_inline_styles', 100);
	add_action('widgets_init', 'silps_sidebars');

	show_admin_bar(true); // Changed to true per James Posts' request.

	function silps_scripts() {
		wp_register_script('silps-bootstrap.min.js', get_template_directory_uri() . '/vendor/twbs/bootstrap/dist/js/bootstrap.min.js','jQuery','', false);
		wp_enqueue_script('silps-bootstrap.min.js');
		// site.js in the footer
		wp_register_script('silps-site.js', get_template_directory_uri() . '/js/site.js','jQuery','', true);
		wp_enqueue_script('silps-site.js');
	}

	$customInlineStyles = array();
	function silps_custom_inline_styles() {
		global $customInlineStyles;
		foreach ($customInlineStyles as $key => $value) {
?>
<style type="text/css" id="custom_inline_style_<?= $key ?>">
	<?= $value ?>
</style>
<?php
		}
	}

	function silps_enqueue_inline_style($style) {
		global $customInlineStyles;
		if ($style) {
			$customInlineStyles[] = $style;
		}
	}

	//	If this is NEWS ARCHIVES page or a single-page site (both CHILD PAGES of software.sil.org), then add CSS that brings sidebar quick links to top of page ONLY WHEN viewed on mobile devices.
	if (get_current_blog_id() == 1) {
		silps_enqueue_inline_style('@media (max-width: 759px) { #content_primary  > .container > .row { display: flex;	flex-flow: column-reverse; } }');
	}

	function add_to_context($data){
		/* this is where you can add your own data to Timber's context object */
		$data['header'] = get_custom_header();
		$data['menu'] = new TimberMenu('psnav');
		$data['home_url'] = home_url();
		$data['theme_url'] = get_template_directory_uri();
				
		return $data;
	}

	function add_to_twig($twig){
		/* this is where you can add your own functions to twig */
		$twig->addExtension(new Twig_Extension_StringLoader());
		$twig->addFilter('highlight', new Twig_Filter_Function('highlight', array('words')));
		return $twig;
	}

	function highlight($text, $words){
		if (!empty($words)){
			$reg_ex = implode('|', explode(' ', $words));
			if (preg_match('/^.*(?=<a.*$)/', $text, $matches)){
				$tmp_text = $matches[0];
				$tmp_text = preg_replace('/(' . $reg_ex . ')/iu', '<span class="search-highlight">\0</span>', $tmp_text);
				preg_match('/<a.*$/', $text, $matches);
				$tmp_text .= $matches[0];		
			}
			else {
				$tmp_text = preg_replace('/(' . $reg_ex . ')/iu', '<span class="search-highlight">\0</span>', $text);
			}
			return $tmp_text;
		} else {
			return $text;
		}
	}

	function silps_sidebars() {
		register_sidebar(array(
			'name' => 'Sidebar - Main',
			'id' => 'sidebar_main',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Sidebar - Home',
			'id' => 'sidebar_home',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Logo',
			'id' => 'logo',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		));
		register_sidebar(array(
			'name' => 'Subtitle',
			'id' => 'subtitle',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '',
			'after_title' => '',
		));
		register_sidebar(array(
			'name' => 'License',
			'id' => 'section_license',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Donate',
			'id' => 'section_donate',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Home Trio 1',
			'id' => 'home_trio_1',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Home Trio 2',
			'id' => 'home_trio_2',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Home Trio 3',
			'id' => 'home_trio_3',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Photo Credits',
			'id' => 'photo_credits',
			'before_widget' => '<div class="bannercc">',
			'after_widget' => '</div>',
			'before_title' => '<span class="title">',
			'after_title' => '</span>',
		));
		register_sidebar(array(
			'name' => 'Home Testimonial',
			'id' => 'home_testimonial',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'FAQ Leader',
			'id' => 'section_faq_leader',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Recent Blog Posts',
			'id' => 'section_blog_recent',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Footer - SIL',
			'id' => 'footer_sil',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Footer - Software',
			'id' => 'footer_software',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Footer - Fonts',
			'id' => 'footer_fonts',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
		register_sidebar(array(
			'name' => 'Footer - Contact',
			'id' => 'footer_contact',
			'before_widget' => '<div>',
			'after_widget' => '</div>',
			'before_title' => '<h2>',
			'after_title' => '</h2>',
		));
	}

//	### SIL WP SHORTCODES ###

/* SHORTCODE: [top]
 * 
 * RENDERS:		^ top ^ links to top of current page
 * 
 * PARAMS:		align			(optional, left | center | middle | right, default right, aligns link text)
 * 						border		(optional, 0 or 1, default 1, toggles thin gray line above link text)
 * 						class 		(optional, any CSS class)
 * 						text			(optional, default "top", can specify other text (e.g., other languages))
 * 						uparrows	(optional, 0 or 1, default 1, toggles ^ on both sides of link text)
 * 
 * EXAMPLES:	[top]
 * 						[top border=0 class="GentiumPlus-I"]
 * 						[top text="на верх" align="left" class="GentiumPlus-R"]
 * 						[top text="بالای صفحه" class="Scheherazade-Bold" uparrows=0]
 */

function back_to_top($atts) {
	$atts = shortcode_atts(
		array(
			'text' => 'top',
			'align' => '',
			'border' => 1,
			'uparrows' => 1,
			'class' => ''
		), $atts, 'top'
	);
	$css = 'top';
	switch (trim(strtolower($atts['align']))) {
		case 'left':
			$css = $css.' left';
			break;
		case 'middle':
		case 'center':
			$css = $css.' center';
			break;
		case 'right':
			$css = $css.' right';
			break;
		default:
			break;
	}
	$css = ($atts['border'] == 1) ? $css.' border' : $css;
	$css = (!empty($atts['class'])) ? $css.' '. $atts['class'] : $css;
	$css = ($atts['uparrows'] == 0) ? $css.' noarrows' : $css;
	return '<div class="'.$css.'"><a href="#top">'.((empty($atts['text'])) ? 'top' : $atts['text']).'</a></div>';
}
add_shortcode('top','back_to_top');

/* SHORTCODE: [font]
 * 
 * RENDERS:		@font-face and CSS rules containing font-feature-settings for minority-language and other custom fonts (WOFFs)
 * 
 * PARAMS:		id							(REQUIRED, your name for this web font)
 * 						face						(REQUIRED, name of WOFF file name for REGULAR font-family, MUST match name EXACTLY, case-sensitive, enclose value in single or double quotes)
 * 						bold 						(optional, name of WOFF file name for BOLD font-family, MUST match name EXACTLY, case-sensitive, CANNOT equal face, italic, light, bolditalic or lightitalic, enclose in single or double quotes)
 * 						italic					(optional, name of WOFF file name for ITALIC font-family, MUST match name EXACTLY, case-sensitive, CANNOT equal face, bold, light, bolditalic or lightitalic, enclose in single or double quotes)
 * 						light						(optional, name of WOFF file name for LIGHT font-family, MUST match name EXACTLY, case-sensitive, CANNOT equal face, bold, italic, bolditalic or lightitalic, enclose in single or double quotes)
 * 						bolditalic			(optional, name of WOFF file name for BOLD ITALIC font-family, MUST match name EXACTLY, case-sensitive, CANNOT equal face, bold, italic or lightitalic, enclose in single or double quotes)
 * 						lightitalic			(optional, name of WOFF file name for LIGHT ITALIC font-family, MUST match name EXACTLY, case-sensitive, CANNOT equal face, bold, italic or bolditalic, enclose in single or double quotes)
 * 						feats						(optional, string containing array of key-value pairs for font-feature-settings, separate each key-value pair by comma, enclose entire array in single or double quotes [EX: 'cv44 1, test 0'])
 * 						rtl							(optional, 1 or 0, default = 0, set to 1 for right-to-left language classes)
 * 						size						(optional, sets default font size of CSS class, can be px, em, or %)
 * 						lineheight			(optional, sets default line-height of CSS class, can be px, pt, em, or % {% preferred} )
 * 
 * EXAMPLES:	[font id='sch' face='Scheherazade-Regular' rtl=1]
 * 						[font id='sch' face='Scheherazade-Regular' bold='Scheherazade-Bold' rtl=1]
 * 						[font id='gentium' face='GentiumPlus-R' italic='GentiumPlus-I']
 * 						[font id='sch-cv44' face='Scheherazade-Regular' bold='Scheherazade-Bold' feats='cv44 1' rtl=1 size='200%' lineheight='180%']
 * 						[font id='andika' face='Andika-Regular' bold='Andika-Bold' italic='Andika-Italic' light='Andika-Light' bolditalic='Andika-BoldItalic' lightitalic='Andika-LightItalic']
 */
 
//	Allow uploading to WordPress Media Libraries of WOFFs and SVGs (for Nastaliq animations)
function my_mime_types($mime_types) {
//   $mime_types['woff'] = 'application/font-woff';
   $mime_types['woff']  = 'font/woff';
   $mime_types['woff2'] = 'font/woff2';
   $mime_types['svg']   = 'image/svg+xml';
   return $mime_types;
}
add_filter('upload_mimes', 'my_mime_types', 1, 1);

$cr = "\r\n";													//	generate carriage returns in PHP (formatting CSS)
$tab = "\t";													//	generate tabs in PHP (formatting CSS)

function load_NRSI_Fonts($atts) {			//	call this function when WP encounters [font] shortcode
	$atts = shortcode_atts(							//	grab user-defined parameter values
		array(
			'id' => '',
			'face' => '',
			'bold' => '',
			'light' => '',
			'italic' => '',
			'bolditalic' => '',
			'lightitalic' => '',
			'feats' => '',
			'rtl' => '',
			'size' => '',
			'lineheight' => ''
		), $atts, 'font');
	global $cr, $tab;

	//	Using 'face', 'bold', 'italic', 'light', 'bolditalic' and 'lightitalic' values, build WOFF file names, and grab their URLs from the pertinent Downloads folder
	$woffPATH = network_site_url() . 'downloads/r' . get_blog_details()->path;
	
	//	Using 'face' URL, write @font-face rule for REGULAR font
	$woffURL = $woffPATH . $atts['face'].'.woff';
	$fontfaces = $tab . '@font-face {' . $cr . $tab . $tab . 'font-family: ' . $atts['face'] . ';' . $cr . $tab . $tab . 'src: url("' . $woffURL . '") format("woff");' . $cr . $tab . '}';

	//	Now do the same for BOLD @font-face, if 'bold' was specified
	if ($atts['bold']) {
		$woffURL = $woffPATH . $atts['bold'].'.woff';
		$bold = $cr . $tab . '@font-face {' . $cr . $tab . $tab . 'font-family: ' . $atts['bold'] . ';' . $cr . $tab . $tab . 'src: url("' . $woffURL . '") format("woff");' . $cr . $tab . '}';
		$fontfaces .= $bold;
	}

	//	Now do the same for ITALIC @font-face, if 'italic' was specified
	if ($atts['italic']) {
		$woffURL = $woffPATH . $atts['italic'].'.woff';
		$italic = $cr . $tab . '@font-face {' . $cr . $tab . $tab . 'font-family: ' . $atts['italic'] . ';' . $cr . $tab . $tab . 'src: url("' . $woffURL . '") format("woff");' . $cr . $tab . '}';
		$fontfaces .= $italic;
	}

	//	Now do the same for LIGHT @font-face, if 'light' was specified
	if ($atts['light']) {
		$woffURL = $woffPATH . $atts['light'].'.woff';
		$light = $cr . $tab . '@font-face {' . $cr . $tab . $tab . 'font-family: ' . $atts['light'] . ';' . $cr . $tab . $tab . 'src: url("' . $woffURL . '") format("woff");' . $cr . $tab . '}';
		$fontfaces .= $light;
	}
	
	//	Now do the same for BOLD ITALIC @font-face, if 'bolditalic' was specified
	if ($atts['bolditalic']) {
		$woffURL = $woffPATH . $atts['bolditalic'].'.woff';
		$bolditalic = $cr . $tab . '@font-face {' . $cr . $tab . $tab . 'font-family: ' . $atts['bolditalic'] . ';' . $cr . $tab . $tab . 'src: url("' . $woffURL . '") format("woff");' . $cr . $tab . '}';
		$fontfaces .= $bolditalic;
	}
	
	//	Now do the same for LIGHT ITALIC @font-face, if 'lightitalic' was specified
	if ($atts['lightitalic']) {
		$woffURL = $woffPATH . $atts['lightitalic'].'.woff';
		$lightitalic = $cr . $tab . '@font-face {' . $cr . $tab . $tab . 'font-family: ' . $atts['lightitalic'] . ';' . $cr . $tab . $tab . 'src: url("' . $woffURL . '") format("woff");' . $cr . $tab . '}';
		$fontfaces .= $lightitalic;
	}
	
	//	Using 'feats' string array (if specified), parse out -moz, -webkit, and regular font-feature-settings rules
	$featsettings = '';
	if (!empty($atts['feats'])){
		if (strpos($atts['feats'], ',') !== false){
			$delim = ',';
			$arr_feats = explode($delim, preg_replace('/, /', ',', $atts['feats']));
			foreach($arr_feats as $featsparm){
				$featskvpair = explode(' ', $featsparm);
				$kv = '"' . str_replace(array('\'', '"'), '', $featskvpair[0]) . '" ' . str_replace(array('\'', '"'), '', $featskvpair[1]) . ', ';
				$featsettings .= $kv;
			}
			$featsettings = rtrim($featsettings, ', ');
		} else {
			$featskvpair = explode(' ', $atts['feats']);
			$kv = '"' . trim(str_replace(array('\'', '"'), '', $featskvpair[0])) . '" ' . trim(str_replace(array('\'', '"'), '', $featskvpair[1])) . ', ';
			$featsettings .= $kv;
			$featsettings = rtrim($featsettings, ', ');
		}
		$feats = 'font-feature-settings: ' . $featsettings . '; ';
		$mozfeats = '-moz-font-feature-settings: ' . $featsettings . '; ';
		$webkitfeats = '-webkit-font-feature-settings: ' . $featsettings . '; ';
		$fmtdrules = $cr . $tab . $tab . $webkitfeats . $cr . $tab . $tab . $mozfeats . $cr . $tab . $tab . $feats;
	} else {
		$fmtdrules = '';
	}
	
	//	Is this a right-to-left language?  *** NOTE: text-align set to right, but only works on block-level elements.
	$rtl = (trim(strtolower($atts['rtl'])) == 1) ? $cr . $tab . $tab . 'direction: rtl;' . $cr . $tab . $tab . 'text-align: right;' . $cr . $tab . $tab . 'unicode-bidi: bidi-override;' : '';
	
	//	Now parse out font-size rule, if 'size' was specified
	$fontsize = (!empty($atts['size'])) ? $cr . $tab . $tab . 'font-size: ' . $atts['size'] . '; ' : '';
	
	//	Now parse out line-height rule, if 'lineheight' was specified
	$lineheight = (!empty($atts['lineheight'])) ? $cr . $tab . $tab . 'line-height: ' . $atts['lineheight'] . '; ' : '';
	
	//	Apply font-feature-settings and font-size rules to CSS style for REGULAR font
	$fontfeaturesettings = $cr. $tab . '.' . $atts['id'] . '-R {' . $cr . $tab . $tab . 'font-family: ' . $atts['face'] . '; ' . $fmtdrules . $rtl . $fontsize . $lineheight . $cr . $tab . '}';

	//	Now do the same for BOLD @font-face, if 'bold' was specified
	if ($atts['bold']) {
		$fontfeaturesettings .= $cr. $tab . '.' . $atts['id'] . '-B {' . $cr . $tab . $tab . 'font-family: ' . $atts['bold'] . '; ' . $fmtdrules . $rtl . $fontsize . $cr . $tab . '}';
	}

	//	Now do the same for ITALIC @font-face, if 'italic' was specified
	if ($atts['italic']) {
		$fontfeaturesettings .= $cr. $tab . '.' . $atts['id'] . '-I {' . $cr . $tab . $tab . 'font-family: ' . $atts['italic'] . '; ' . $fmtdrules . $rtl . $fontsize . $cr . $tab . '}';
	}

	//	Now do the same for LIGHT @font-face, if 'light' was specified
	if ($atts['light']) {
		$fontfeaturesettings .= $cr. $tab . '.' . $atts['id'] . '-L {' . $cr . $tab . $tab . 'font-family: ' . $atts['light'] . '; ' . $fmtdrules . $rtl . $fontsize . $cr . $tab . '}';
	}
	
	//	Now do the same for BOLD ITALIC @font-face, if 'bolditalic' was specified
	if ($atts['bolditalic']) {
		$fontfeaturesettings .= $cr. $tab . '.' . $atts['id'] . '-BI {' . $cr . $tab . $tab . 'font-family: ' . $atts['bolditalic'] . '; ' . $fmtdrules . $rtl . $fontsize . $cr . $tab . '}';
	}
	
	//	Now do the same for LIGHT ITALIC @font-face, if 'lightitalic' was specified
	if ($atts['lightitalic']) {
		$fontfeaturesettings .= $cr. $tab . '.' . $atts['id'] . '-LI {' . $cr . $tab . $tab . 'font-family: ' . $atts['lightitalic'] . '; ' . $fmtdrules . $rtl . $fontsize . $cr . $tab . '}';
	}

	$cssrules = $cr . $fontfaces . $fontfeaturesettings . $cr;
	$cssrules = (!is_admin()) ? $cssrules . $tab . '.article-body > p:first-child > br { display: none; }' . $cr : $cssrules . '';
	silps_enqueue_inline_style($cssrules);
}
add_shortcode('font','load_NRSI_Fonts');

/* SHORTCODE: [logo]
 * 
 * RENDERS:		For PRODUCT SITES, the WP Site Logo.
 * 						For SINGLE-PAGE SITES, the WP page's Featured Image, since single-page sites are really just pages on the main software.sil.org site.
 * 
 * PARAMS:		None. It grabs the URL slug and generates the necessary code from there.
 * 
 * EXAMPLE:		[logo]
 */

function loadLogo(){
	$post = get_post();
	$bodyClasses = get_body_class();
	$isHomePage = in_array('home', $bodyClasses);
	$mainSitePages = array('news', 'products', 'about', 'support', 'contact');
	$slug = ($post) ? explode('/', esc_html(get_permalink()))[3] : '';
	$parents = ($post) ? get_post_ancestors($post->ID) : 1;
	$id = ($parents) ? $parents[count($parents)-1] : $post->ID;
	$parent = get_post($id);
	$psurl = home_url();
	$pstitle = get_bloginfo('name');	// full product site titles
	$singlesidehomepagetitle = (is_search()) ? get_bloginfo('name') : get_the_title($parent);	// single-page site titles
	$custom_logo_id = get_theme_mod('custom_logo');
	$image = (has_post_thumbnail($parent) && !is_search()) ? get_the_post_thumbnail_url($parent) : wp_get_attachment_image_src($custom_logo_id)[0];

	// FULL PRODUCT SITES: always display product logo
	
	if (!is_main_site() || (is_search() || is_404() || is_author() || in_array($slug, $mainSitePages))) {
		
		if ($isHomePage) {
		
			// HOME PAGE: just show and center logo

			$logo = '<img src="' . $image . '" title="' . $pstitle . '">';
		
		} else {
		
			// HOME PAGE: show, left-align, and link logo back to product site's HOME page

			$logo = '<a href="' . $psurl . '"><img src="' . $image . '" title="Back to ' . $pstitle . ' »"></a>';
		
		}
		
	} else {

	// SINGLE-PAGE SITES: if the product has its own logo, display it; otherwise SIL logo
	
			if (count($parents) < 1) {

				// TOP PAGE: just show the logo
				
				$logo = '<img src="' . $image . '" title="' . $singlesidehomepagetitle . '">';

			} else {

				// CHILD PAGES: show the logo and link back to TOP page
				
				$logo = '<a href="/' . $slug . '/"><img src="' . $image . '" title="Back to ' . $singlesidehomepagetitle . ' »"></a>';

			}
	}

	return str_replace('http://software', '//software', $logo);
}
add_shortcode('logo', 'loadLogo');

/* SHORTCODE: [LTNewsUpdates]
 * 
 * RENDERS:		News & Updates feed widget for http://software.sil.org/
 * 
 * PARAMS:		posts 					(optional, number of posts to display, if not specified displays ALL)
 * 						skipped					(optional, URL stubs of product sites to skip when collecting feed items)
 * 
 * EXAMPLE:		[LTNewsUpdates posts=4 skipped="adaptit daibanna fieldworks myworksafe sample soosl xlingpaper" startdate="1/1/2016" enddate="1/1/2017"]
 */

function LTNewsUpdates($atts){
	$atts = shortcode_atts(
		array(
			'posts' => '',
			'skipped' => '',
			'icons' => '',
			'startdate' => '',
			'enddate' => ''
		), $atts, 'LTNewsUpdates');
		$sites_skipped = explode(' ', $atts['skipped']);
		$sdate = ($atts['startdate'])	? strtotime($atts['startdate'])	: strtotime('1 Jan 1980');
		$edate = ($atts['enddate'])		? strtotime($atts['enddate'])		: strtotime('today');
		$LTFeed = '';

		$rsslist = array();
		$subsites = get_sites();
		global $id, $name, $path, $feed;
		foreach($subsites as $subsite){
			$id = get_object_vars($subsite)["blog_id"];
			$name = get_blog_details($id)->blogname;
			$path = preg_replace("/[^A-Za-z0-9 ]/", '', get_blog_details($id)->path);
			$feed = (empty($path)) ? 'http://' . get_blog_details($id)->domain . '/feed' : get_blog_details($id)->siteurl . '/feed';
			if (!in_array($path, $sites_skipped)){
				array_push($rsslist, $feed);
			}
		}
		
		$month_anchor = '';
		$rss = fetch_feed($rsslist);
		$maxitems = 0;
		if (!is_wp_error($rss)) {
			$maxitems = $rss->get_item_quantity((!empty($atts['posts'])) ? $atts['posts'] : '');
			$rss_items = $rss->get_items(0, $maxitems);
		} else {
			var_dump($rss);
		}
		if ($maxitems == 0) {
			$LTFeed = '<div class="em">No news feed items available.</div>';
		} else {
			$prev_month = '';
			foreach($rss_items as $item){
				$itemdate = strtotime($item->get_date());
				$datefiltered = true;
				$curr_month = strval(strtolower($item->get_date('M')));
				if ($atts['startdate']) {
					$datefiltered = (($itemdate >= $sdate) && ($itemdate <= $edate)) ? true : false; 
				}
				if ($datefiltered) {
					$slug = explode('/', esc_html($item->get_permalink()))[3];
					$blogID = get_id_from_blogname($slug);
					$blog_details = get_blog_details($blogID);
					$blogname = $blog_details->blogname;
					$blogURL = $blog_details-> siteurl;

					$title = esc_html($item->get_title());
					$skiptitles = array('New post', 'Hello world!');
					$bodyClasses = get_body_class();

					if (!in_array($title, $skiptitles)) {

						if (!empty($prev_month)) {
							if (($prev_month !== $curr_month) && (!in_array('home', $bodyClasses))) {
								$LTFeed .= '<div class="top border"><a href="#top">top</a></div>';
								$month_anchor = ' id="' . $curr_month . '"';
								$prev_month = $curr_month;
							}
						} else {
								$prev_month = $curr_month;
						}

						$LTFeed .= '<div' . $month_anchor .' class="rssItem">';
						if (function_exists('get_custom_logo') && has_custom_logo($blogID)){
							$LTFeed .= get_custom_logo($blogID);
						} else {
							$LTFeed .= get_custom_logo(1);
						}
						$LTFeed .= '<h3><div class="date">' . esc_html($item->get_date('j F Y')) . '</div> <div class="blogname">' . $blogname . '</div></h3>';
						$LTFeed .= '<p class="title"><a href="' . esc_html($item->get_permalink()) . '" target="_blank">' . $title . '</a></p>';
						$LTFeed .= '<p class="desc">' . esc_html(substr($item->get_description(),0,200)) . '&hellip; <a href="' . esc_html($item->get_permalink()) . '" target="_blank">read more</a></p>';
						$LTFeed .= '</div>';
					}
				}
			}
		}
		return $LTFeed;
}
add_shortcode('LTNewsUpdates', 'LTNewsUpdates');

/* SHORTCODE: [opencloseall]
 * 
 * RENDERS:		Open/close all links or buttons for accordions
 * 
 * PARAMS:		type 						(optional, MUST be either 'button' or 'link', default = 'button')
 * 						accID						(optional, ID of accordion whose items will be shown/hidden, if not specified then ALL accordion items on page are shown/hidden)
 * 						class						(optional, additional CSS classes to be applied to links or buttons generated)
 * 						opentext				(optional, text for Open All link or button, default = 'Open All »')
 * 						closetext				(optional, text for Close All link or button, default = 'Close All »')
 * 
 * EXAMPLE:		[opencloseall type='link' accID=2 class='normal' opentext='Afficher tout' closetext='Masquer tout']
 */

function open_close_all($atts){
	$atts = shortcode_atts(
		array(
			'type' => '',
			'rel' => '',
			'class' => '',
			'opentext' => '',
			'closetext' => ''
		), $atts, 'open_close_all');		

		$oca = ($atts['type'] == 'link') ? '<a class="open-all' : '<button class="open-all';
		
		if ($atts['class']) {
			$cls = ' ' . $atts['class'] . '" ';
		} else {
			$cls =	'"';
		}
		$oca .=	$cls;
		
		if ($atts['rel']) {
			$rel = ' rel="' . $atts['rel'] . '">';
		} else {
			$rel = '>';
		}
		$oca .=	$rel;
		
		if ($atts['opentext']) {
			$opn = $atts['opentext'];
		} else {
			$opn = 'Open All &raquo;';
		}
		$oca .=	$opn;
		
		if ($atts['type'] == 'link') {
			$typ = '</a> <a class="close-all';
		} else {
			$typ = '</button> <button class="close-all';
		}
		$oca .=	$typ;
		
		$oca .=	$cls;
		$oca .=	$rel;
				
		if ($atts['closetext']) {
			$clstxt = $atts['closetext'];
		} else {
			$clstxt = 'Close All &raquo;';
		}
		$oca .=	$clstxt;
		
		if ($atts['type'] == 'link') {
			$tpe = '</a>';
		} else {
			$tpe = '</button>';
		}
		$oca .=	$tpe;

		return $oca;
}
add_shortcode('opencloseall', 'open_close_all');

/* SHORTCODE: [ccatt]
 * 
 * RENDERS:		Provide Creative Commons attributions for images
 * 
 * PARAMS:		title		(optional, "PHOTO CREDITS:" or your localized translation)
 * 						src			(REQUIRED, used to generate popup image link)
 * 						imgtitle(REQUIRED, image title)
 * 						author	(REQUIRED, author or company being credited)
 * 						url			(optional, author's web link)
 * 						cctitle	(REQUIRED, CC link text)
 * 						ccurl		(REQUIRED, CC web link)
 * 
 * EXAMPLE:		[ccatt src='/media/siteXX/banner_image.jpg' title='PHOTO CREDITS' imgtitle='Banner Image' author='Eddie McHam' url='http://wp.3ddie.com/' cctitle='https://creativecommons.org/licenses/by-sa/3.0/us/' ccurl='CC-BY-SA 3.0']
 */

function cc_att($atts){
	$atts = shortcode_atts(
		array(
			'src'			=> '',
			'title'=> '',
			'author'	=> '',
			'url'			=> '',
			'cctitle' => '',
			'ccurl'		=> ''
		), $atts, 'cc_atts');

		$ccatts  = '<div>';
		$ccatts .= '<a href="' . $atts['src'] . '" class="imgtitle" rel="lightbox">' . $atts['title'] . '</a> / ';
		$ccatts .= '<a href="' . $atts['url'] . '" class="imgauthor" target="_blank">' . $atts['author'] . '</a> / ';
		$ccatts .= '<a href="' . $atts['ccurl'] . '" class="cctitle" target="_blank">' . $atts['cctitle'] . '</a>';
		$ccatts	.= '</div>';

		return $ccatts;
}
add_shortcode('ccatt', 'cc_att');

/* SHORTCODE: [announce]
 * 
 * RENDERS:		Generate special announcements
 * 
 * PARAMS:		title				(optional, announcement title)
 * 						text				(REQUIRED, announcement text)
 * 						contact			(REQUIRED IF email is specified, contact person or group's name)
 * 						email				(REQUIRED IF contact is specified, contact person or group's email address)
 * 						moreinfo		(REQUIRED IF link is specified, more info link text)
 * 						link				(REQUIRED IF moreinfo is specified, more info link URL)
 * 
 * EXAMPLE:		[announce title='NOTE:' text='This product is currently in beta development.' contact='LSDev' email='Language_Software_Support@sil.org' moreinfo='more info &raquo;' link='http://software.sil.org']
 */

function announce($atts){
	$atts = shortcode_atts(
		array(
			'title' 		=> '',
			'text'			=> '',
			'contact'		=> '',
			'email'			=> '',
			'moreinfo'	=> '',
			'link'			=> ''
		), $atts, 'announce');

		$announcement  = '<div class="announcement">';
		if ($atts['title']){
			$announcement .= '<p class="bold">' . $atts['title'] . '</p>';
		}
		$announcement .= '<p>' . $atts['text'];
		if ($atts['contact'] && $atts['email']){
			$announcement .= ' <a href="mailto:' . $atts['email'] . '">Contact</a> ' . $atts['contact'] . ' for more information.';
		}
		if ($atts['moreinfo'] && $atts['link']){
			$announcement .= ' <a href="' . $atts['link'] . '" target="_blank"> ' . $atts['moreinfo'] . '</a>';
		}
		$announcement	.= '</p>';
		$announcement	.= '</div>';

		return $announcement;
}
add_shortcode('announce', 'announce');

/* SHORTCODES:	[ListSILProducts], [SILProduct]
 * 
 * RENDERS:			Alphabetical list of SIL products (ListSILProducts]), where each list item ([SILProduct]) contains app icon (if it exists; otherwise SIL logo), title, brief description (if it exists),
 * 							and hyperlink to the pertinent Product Site or Single-Page Site. Meant primarily for the SIL Products page.
 * 
 * PARAMS:			[ListSILProducts][/ListSILProducts]		type					(optional, MUST BE 'list' or 'table', defaults to 'list')
 * 																										css						(optional, string of additional CSS classes, separated by spaces)
 * 
 * 							[SILProduct][/SILProduct]							title					(REQUIRED, product name)
 * 																										slug					(REQUIRED IF different from product name; otherwise defaults to title, all lowercase and no special characters. Used to build icon pathanme and site URL.)
 * 																										description		(optional, brief product description)
 * 
 * EXAMPLES:		[ListSILProducts css='normal special']
 * 							[SILProduct title='Abyssinica SIL' slug='abyssinica' description="Used for writing many of the languages of Ethiopia and Eritrea."]
 * 							[SILProduct title='Adapt It' url='http://www.adapt-it.org' description="Accelerates translation drafting for similar languages by building and tuning a translation memory."]
 * 							[SILProduct title='AMPLE' description='Given necessary information about the morphology of a language, AMPLE will analyze each word in a text and break it into morphemes.']
 * 							[SILProduct title='myWorkSafe' description='Smart and simple backup for language development workers.']
 * 							[SILProduct title='Document Preparation Aids' url='http://www-01.sil.org/computing/catalog/show_software.asp?id=10']
 * 							[/ListSILProducts]
 */

function ListSILProducts($atts, $content = null){
	extract(shortcode_atts(array(
		'css' => ''
	), $atts));

	$list = (!empty($css)) ? '<table class="products '. $css .'">' . do_shortcode($content) . '</table>' : '<table class="products">' . do_shortcode($content) . '</table>';
	return $list;
}
add_shortcode('ListSILProducts','ListSILProducts');

function SILProduct($atts, $content = null){
	extract(shortcode_atts(array(
		'title'				=> '',
		'icon'				=> 1,
		'slug'				=> '',
		'url'					=> '',
		'description' => ''
	), $atts));

	// Get the site slug, either by SLUG or TITLE attribute, all lowercased and all non-alphanumeric characters stripped out. This is how we know which icon to grab.

	$slug = (empty($slug)) ? trim(preg_replace('/ +/', '', preg_replace('/[^A-Za-z0-9 ]/', '', strtolower($title)))) : trim(preg_replace('/ +/', '', preg_replace('/[^A-Za-z0-9 ]/', '', strtolower($slug))));

	// PRODUCT SITE LOGOS (using WP site logo feature)

	$blogID = get_id_from_blogname($slug);
	$custom_logo_id = get_theme_mod('custom_logo');

	if (function_exists('get_custom_logo') && has_custom_logo($blogID)) {
		$img = get_custom_logo($blogID);
	}
	
	// SINGLE-PAGE SITE LOGOS (using WP featured image since single-page "sites" are really just pages within the main site)

	$page = get_page_by_path($slug);
	$pageID = ($page) ? $page->ID : 1;

	if (has_post_thumbnail($pageID)) {
		$img = '<a href="../' . $slug . '/" target="_blank"><img class="custom-logo" src="' . get_the_post_thumbnail_url($pageID) . '" /></a>';
	}

	// EXTERNAL SITE LOGO (using WP Media Library image whose title is 'icon_{productname}')
	
	if (!empty($url)) {
		$attachment = get_page_by_title('icon_' . $slug, OBJECT, 'attachment');
		if ($attachment) {
			$img = '<a href="' . $url . '" target="_blank"><img class="custom-logo" src="' . $attachment->guid . '" /></a>';
		}
	}
	
	$prod = '<tr>';	

	$img = str_replace('http://software', '//software', $img);
	if ($icon != 0) {
		$prod .= '<td>' . $img . '</td>';
	}

	$prod .= '<td>';
	if (empty($url)) {
		$prod .= '<div class="title"><a href="../' . $slug . '/" target="_blank">' . $title . '</a></div><br />';
	} else {
		$prod .= '<div class="title"><a href="' . $url . '" target="_blank">' . $title . '</a></div><br />';
	}	
	$prod .= '<div class="desc">' . $description . '</div></td></tr>';
	
	return $prod;
}
add_shortcode('SILProduct','SILProduct');

/* SHORTCODE: [spssidebar]
 * 
 * RENDERS:		FOR SINGLE-PAGE SITES ONLY. Generates single-page sidebar menu with dynamic links. Needed because typical WP sidebar menu's links won't work on child pages of single-page sites.
 * 
 * PARAMS:		None. It grabs the single-page site's URL slug and generates the necessary code from there.
 * 
 * EXAMPLE:		[spssidebar]
 */

function spssidebar(){
	$slug = explode('/', get_permalink())[3];
	$spsmenu =  '<div class="menu-single-page-site-menu-container">';
	$spsmenu .= '<ul id="menu-single-page-site-menu" class="menu">';
	$spsmenu .= '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="/' . $slug . '#about">About</a></li>';
	$spsmenu .= '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="/' . $slug . '#downloads">Downloads</a></li>';
	$spsmenu .= '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="/' . $slug . '#support">Support</a></li>';
	$spsmenu .= '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="/' . $slug . '#contact">Contact</a></li>';
	$spsmenu .= '</ul>';
	$spsmenu .= '</div>';
	return $spsmenu;
}
add_shortcode('spssidebar', 'spssidebar');

/* SHORTCODE: [rssLink]
 * 
 * RENDERS:		Dynamic HTML content for Sidebar - Main and Recent Blog Posts text widgets.
 * 
 * PARAMS:		None. It uses the theme directory and site's URL slug to generate the necessary code.
 * 
 * EXAMPLE:		[rsslink]
 */

function rssLink(){
        $temp =  get_the_permalink();
/* TEMPORARY FIX.  CLEAN UP
	$slug = explode('/', get_permalink())[3];
	$arymainpages = array('news','products','about');
	if (in_array($slug, $arymainpages) || is_search()) {
		$url = '/feed/';
	} else {
		$url = '/' . $slug . '/feed/';
	}
*/
	$url='/feed/';	
	$rss = '<div class="rssicon">';
	$rss .= '<a target="_new" href="' . $url . '" title="Subscribe to News">';
	$rss .= '<img src="'. get_template_directory_uri() . '/images/rss-feed.png" alt="RSS Feed" />';
	$rss .= '</a>';
	$rss .= '</div>';
	
	return $rss;
}
add_shortcode('rsslink', 'rssLink');

/* SHORTCODE: [prodSupport]
 * 
 * RENDERS:		Generate support blurbs for single-page sites
 * 
 * PARAMS:		product		(optional, product title to append to LSC icon's ALT attribute)
 * 						lscpath		(optional, path to append to base URL of Language Software Community)
 * 						category	(optional, if set to 'discontinued', show support blurb for discontinued products, otherwise default support blurb)
 * 
 * EXAMPLE:		[prodSupport product='myWorkSafe' lscpath='c/utilities/my-work-safe']
 */

function productSupportBlurb($atts){
	$atts = shortcode_atts(
		array(
			'product'		=> '',
			'lscpath'		=> '',
			'category'	=> ''
		), $atts, 'productSupportBlurb');

		if ($atts['category'] !== 'discontinued') {
			$url = 'https://community.software.sil.org/' . $atts['lscpath'];
			if (!empty($atts['product'])) {
				$blurb = '<a href="' . $url . '" target="_blank"><img class="lsc" src="/wp/wp-content/uploads/2017/02/LSC_icon_80x80.png" title="Go to Language Software Community for ' . $atts['product'] . ' &raquo;" /></a><p class="lsc">Support from other software users may be available through the <a href="' . $url . '" target="_blank">SIL Language Software Community</a>. This community will be growing to become the major source of software support.</p>';
			} else {
				$blurb = '<a href="' . $url . '" target="_blank"><img class="lsc" src="/wp/wp-content/uploads/2017/02/LSC_icon_80x80.png" title="Go to Language Software Community &raquo;" /></a><p class="lsc">Support from other software users may be available through the <a href="' . $url . '" target="_blank">SIL Language Software Community</a>. This community will be growing to become the major source of software support.</p>';
			}
		} else {
			$blurb = '<a href="https://community.software.sil.org/" target="_blank"><img class="lsc" src="/wp/wp-content/uploads/2017/02/LSC_icon_80x80.png" title="Go to Language Software Community &raquo;" /></a><p class="lsc">We no longer offer support for this product, but you can visit the <a href="https://community.software.sil.org/" target="_blank">SIL Language Software Community</a> to contact other users who may be able to help.</p>';
		}
		return $blurb;
}
add_shortcode('prodSupport', 'productSupportBlurb');

//	### OTHER SIL WP FUNCTIONS ###

//	Look in current site's Media Library for all image attachments whose TITLE contains 'banner_image', NOT case sensitive. (EX: banner_image, 01-BANNER_IMAGE-padauk, Andika_Banner_Image01)
//		* Unlike WOFF support (which relies on the actual file names), you *can* rename an image attachment's TITLE natively within the Media Library to contain 'banner_image'. No special plugin needed.
//		* Can be JPG or PNG format. Make sure it's wide and tall enough to not look pixilated / fuzzy on large monitors (recommended minimum: 1400 x 280).
//	If more than one 'banner_image' attachment is found, display the most recent (sorted by ID in descending order from WP DB) as the Home page's banner image.
//	If only one 'banner_image' attachment is found, display that as the Home page's banner image.
//	If no 'banner_image' attachment is found, fall back to the Sample site's banner image.

function get_banner_image(){
	global $wpdb;
	$query = "SELECT DISTINCT ID FROM {$wpdb->prefix}posts WHERE LOWER(post_title) LIKE '%banner_image%' AND post_type LIKE 'attachment' ORDER BY ID DESC LIMIT 1";
	$thumb_id = $wpdb->get_var($query);
	if (!is_null($thumb_id)){
		$att = wp_get_attachment_image_src($thumb_id,'full');
		$imgURL = $att[0];
	} else {
		$imgURL = '/media/sample/banner_image.jpg';
	}
	return $imgURL;
}

//	Disable Contact Form 7 plugin's configuration validator messages because they are misleading and distracting
add_filter( 'wpcf7_validate_configuration', '__return_false' );

//	Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');

//	Format post dates on RECENT POSTS widget to match software.sil.org NEWS feed

add_filter( 'widget_display_callback', function ( $instance, $widget_instance ) {
	if ($widget_instance->id_base === 'recent-posts' && $instance['show_date'] === true) {
		add_filter( 'get_the_date', function ( $the_date, $d, $post ) {

			// Set new date format
			$d = 'j F Y';

			// Set new value format to $the_date
			$the_date = mysql2date( $d, $post->post_date );

			return $the_date;

		}, 10, 3 );
	}

	return $instance;

}, 10, 2 );
