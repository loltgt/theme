<?php
/**
 * 404 page template
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


get_header();
?>
<div id="container" class="site-container">
<div id="content" class="site-content">
<main id="main" class="site-main" role="main">
<?php
if ( is_active_sidebar( 'page-top' ) ) :
	get_template_part( 'template-parts/widgets', 'page-top' );
endif;
?>
<section class="error-404 not-found container">
<header class="page-header">
	<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'theme' ); ?></h1>
</header>
<div class="page-content">
	<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'theme' ); ?></p>
	<?php get_search_form(); ?>
</div>
</section>
<?php
if ( is_active_sidebar( 'page-bottom' ) ) :
	get_template_part( 'template-parts/widgets', 'page-bottom' );
endif;
?>
</main>
</div>
</div>

<?php
get_footer();