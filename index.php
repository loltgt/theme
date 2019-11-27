<?php
/**
 * Index template
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
<?php if ( have_posts() ) : ?>
<div class="posts container">
<div class="card-columns">
<?php
while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/entry', get_post_format() );
endwhile;
?>
</div>
<?php the_posts_pagination(); ?>
</div>
<?php
else :
	get_template_part( 'template-parts/none' );
endif;
?>
<?php
if ( is_active_sidebar( 'page-bottom' ) ) :
	get_template_part( 'template-parts/widgets', 'page-bottom' );
endif;
?>
</main>

<?php
get_footer();