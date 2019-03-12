<?php
/**
 * Child page template part
 *
 * @package theme
 * @version 1.0
 */

namespace theme;


?>
<div <?php post_class(); the_data_extras( 'child_page' ); ?>>
<div class="container">
<div class="page-content">
<?php the_content(); ?>
</div>
</div>
</div>
