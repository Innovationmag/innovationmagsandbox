<?php if ( have_posts() ) : ?>

    <div id="loop" class="<?php if ($_COOKIE['mode'] == 'grid') echo 'grid'; else echo 'list'; ?> clear">

    <?php while ( have_posts() ) : the_post(); ?>

        <div <?php post_class('post clear'); ?> id="post_<?php the_ID(); ?>">
            <?php if ( has_post_thumbnail() ) :?>
            <a href=<?php the_permalink() ?> class="thumb"><?php the_post_thumbnail('thumbnail', array(
                        'alt'	=> trim(strip_tags( $post->post_title )),
                        'title'	=> trim(strip_tags( $post->post_excerpt )), //my change
                    )); ?></a>
            <?php endif; ?>

            <div class="post-category"><?php the_category(' / '); ?></div>
            <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>

            <div class="post-meta"> <!-- by <span class="post-author"><a
                    href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Posts by <?php the_author(); ?>"><?php the_author(); ?></a></span> -->
                                   on <span
                        class="post-date"><?php the_time(__('M j, Y')) ?></span> <em>&bull; </em><?php comments_popup_link(__('No Comments'), __('1 Comment'), __('% Comments'), '', __('Comments Closed')); ?> <!-- <?php edit_post_link( __( 'Edit entry'), '<em>&bull; </em>'); ?> -->
            </div>
            <div class="post-content"><?php $content = get_the_content(); //next three lines were my change
      $content = strip_tags($content);
      smart_excerpt($content, 55);  ?></div>
        </div>

    <?php endwhile; ?>

    </div>

<?php endif; ?>