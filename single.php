<?php
/**
 * Single post template
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


get_header();
?>
<main id="content" class="site-content" role="main">
<?php
if ( is_active_sidebar( 'post-top' ) ) :
	get_template_part( 'template-parts/widgets', 'post-top' );
endif;
?>
<div class="container">
<?php
while ( have_posts() ) : the_post();

	get_template_part( 'template-parts/post', get_post_format() );

	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

endwhile;
?>
</div>
<?php
if ( is_active_sidebar( 'post-bottom' ) ) :
	get_template_part( 'template-parts/widgets', 'post-bottom' );
endif;
?>
</main>

<?php
get_footer();