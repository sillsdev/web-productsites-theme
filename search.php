<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package 	WordPress
 * @subpackage 	Timber
 * @since 		Timber 0.1
 */

$context = Timber::get_context();

$context['query'] = get_search_query();
$context['count'] = $wp_query->found_posts;
switch ($context['count']) {
	case 0:
		$context['results'] = '<div class="nosearchresults numResults">' . __('Your search for ','SILPS-Theme') . '<span class="keywords">' . $context['query'] . '</span>' . __(' did not return any results.','SILPS-Theme') . '</div>';
		break;
	case 1:
		$context['results'] = '<div class="numResults">' . __('Your search for ','SILPS-Theme'). '<span class="keywords">'. $context['query'] . '</span>' . __(' returned only ','SILPS-Theme') . '<span class="count">' . $context['count'] . '</span>' . __(' result.','SILPS-Theme') . '</div>';
		break;
	default:
		$context['results'] = '<div class="numResults">' . __('Your search for ','SILPS-Theme'). '<span class="keywords">'. $context['query'] . '</span>' . __(' returned ','SILPS-Theme') . '<span class="count">' . $context['count'] . '</span>' . __(' results.','SILPS-Theme') . '</div>';
		break;
}
$context['posts'] = Timber::get_posts();
$context['logo'] = Timber::get_widgets('logo');
$context['section_license'] = Timber::get_widgets('section_license');
$context['section_donate'] = Timber::get_widgets('section_donate');
$context['sidebar_main'] = Timber::get_widgets('sidebar_main');
$context['footer_sil'] = Timber::get_widgets('footer_sil');
$context['footer_software'] = Timber::get_widgets('footer_software');
$context['footer_fonts'] = Timber::get_widgets('footer_fonts');
$context['footer_contact'] = Timber::get_widgets('footer_contact');
$context['pagination'] = Timber::get_pagination();
$templates = array('search.twig');

Timber::render($templates, $context);
