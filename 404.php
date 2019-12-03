<?php
/**
 * 404 page template
 *
 * @package theme
 * @version 2.0
 */

namespace theme;


get_header();
?>
<main id="content" class="site-content">
<?php
if ( is_active_sidebar( 'page-top' ) ) :
	get_template_part( 'template-parts/widgets', 'page-top' );
endif;
?>
<div class="error-404 not-found container">
<header class="page-header">
	<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'theme' ); ?></h1>
</header>
<div class="page-content">
	<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'theme' ); ?></p>
	<?php get_search_form(); ?>
</div>
</div>
<?php
if ( is_active_sidebar( 'page-bottom' ) ) :
	get_template_part( 'template-parts/widgets', 'page-bottom' );
endif;
?>
</main>

<?php
get_footer();