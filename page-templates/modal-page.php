<?php
/**
 * Template Modal Name: Page
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


get_header();

get_template_part( 'template-parts/modal-page', \theme\get_page_template_name() );

get_footer();