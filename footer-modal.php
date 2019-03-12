<?php
/**
 * Footer template (page mode: modal)
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?>
</div>
</div>
<?php
/**
 * theme_modal_end hook.
 *
 * @param int $post->ID - WP_Post
 */
do_action( 'theme_modal_end', $post->ID );
?>
<?php wp_footer(); ?>
</body>
</html>