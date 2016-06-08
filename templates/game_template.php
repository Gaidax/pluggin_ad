<?php

/** 
 * The game adding template
 */

get_header();
?>
<?php if ( have_posts() ) { while ( have_posts() ) : the_post(); ?>
    <?php
        $post_layout = get_post_meta( get_the_ID(), '_deliver_page_settings_post_view', true );
        $is_full = ( $post_layout == 'full' && deliver_has_post_thumbnail() );
    ?>
    <?php if ( $is_full ) :
    ?>
        <div class="post-image full">
            <div class="post-media parallax skrollable skrollable-between no-parallax" data-bottom-top="top: -50%;" data-top-bottom="top: 0%;">
                <?php echo deliver_post_thumbnail( get_the_ID(), false, 'full' ); ?>
            </div>
            <div class="post-figure">
                <div class="post-header">
                    <h1 class="entry-title" itemprop="headline"><?php the_title(); ?></h1>
                    <h5 class="post-meta">
                        <?php echo deliver_get_post_meta( 'basic' ); ?>
                    </h5>
                </div>
                <h6 class="post-action">
                    <?php if ( deliver_get_option( 'blog_post_show_post_share_button', true ) ) : ?>
                        <span class="post-share">
                            <a href="javascript:void(0)" class="button"><i class="linea-icon-basic-share"></i>Share</a>
                            <?php echo deliver_display_share_buttons(); ?>
                        </span>
                    <?php endif; ?>

                    <?php if ( deliver_get_option( 'blog_post_show_post_like_button', true ) ) : ?>
                        <?php echo deliver_post_like( 'button' ); ?>
                    <?php endif; ?>

                    <?php if ( comments_open() ) : ?>
                        <a href="<?php echo esc_url( get_comments_link() ); ?>" class="button post-comment"><i class="linea-icon-basic-message"></i><?php echo get_comments_number(); ?></a>
                    <?php endif; ?>
                </h6>
            </div>
        </div>
    <?php endif; ?>
    <div id="content">
        <div class="<?php echo deliver_get_content_classes( false, 'content-wrapper' ); ?>">
            <div id="main" role="main">
                <?php deliver_get_template( 'content-single', $post_layout ); 
				$script_meta = get_post_meta(get_the_ID(), 'wp_attached_file', false);
                echo "<p id = 'script_place2'>". $script_meta['file'] ."</p>";
                echo "<br>";
				echo "<p id = 'script_place3'>d</p>";			
				?>
            </div>
            <?php get_sidebar(); ?>
        </div>
    </div>
<?php endwhile; } ?>


<?php get_footer(); ?>