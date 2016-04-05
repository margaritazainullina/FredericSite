
<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage fr-theme
 * @since fr-theme 1.0
 */

get_header(); ?>

            <div id="main-content" role="main">
                <h1 class="page-title"><?php the_title(); ?></h1>
                <div class="width-page">
				<?php the_post(); ?>
                <?php the_content(); ?>
                </div>

			</div>
<?php get_footer(); ?>
			