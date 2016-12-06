<?php get_header(); ?>
<!-- Blog Content
  ================================================== -->
<div class="row"><!--Container row-->

    <!-- Blog Full Post
    ================================================== -->
    <div class="span8 blog">


        <?php if (have_posts()) : ?>
        <?php while (have_posts()) :
        the_post(); ?>

        <!-- Article Post -->
        <article>
            <h3 class="title-bg"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
            <div class="post-content">
                <?php the_post_thumbnail('full') ?>

                <div class="post-body">
                    <?php the_content() ?>
                </div>

                <div class="post-summary-footer">
                    <ul class="post-data">
                        <li><i class="icon-calendar"></i> <?php the_time('d/m/y') ?></li>
                        <li><i class="icon-user"></i> <a
                                href="<?php the_author_link() ?>"><?php the_author() ?></a></li>
                        <li><i class="icon-comment"></i> <a
                                href="<?php comment_link() ?>"><?php comments_number() ?></a></li>
                        <li><i class="icon-tags"></i>
                            <?= getTags(get_the_tag_list()) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </article>

        <!-- About the Author -->
        <?php if (get_the_author_meta('description')): ?>
            <section class="post-content">
                <div class="post-body about-author">
                    <?= get_avatar(get_the_author_meta('ID')) ?>
                    <h4>About <?php the_author() ?></h4>
                    <?= get_the_author_meta('description') ?>
                </div>
            </section>
        <?php endif; ?>

        <?php
        function comments_callback($comment, $args, $depth){
        $GLOBALS['comment'] = $comment; ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
            <?= get_avatar($comment, $size = '45') ?>

            <span class="comment-name"><?php echo get_comment_author_link() ?></span>
            <span class="comment-date"><?= get_comment_date('M d, Y') ?> |
                <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </span>

            <div class="comment-content">
                <?php comment_text() ?>
            </div>

            <?php } ?>

            <?php
            $args = array(
                'walker' => null,
                'max_depth' => '',
                'style' => 'ul',
                'callback' => comments_callback,
                'end-callback' => null,
                'type' => 'all',
                'reply_text' => 'Reply',
                'page' => '',
                'per_page' => '',
                'avatar_size' => 45,
                'reverse_top_level' => null,
                'reverse_children' => '',
                'format' => 'html5', // или xhtml, если HTML5 не поддерживается темой
                'short_ping' => false,    // С версии 3.6,
                'echo' => true,     // true или false
            );
            ?>
            <!-- Post Comments
            ================================================== -->
            <section class="comments">
                <h4 class="title-bg"><a name="comments"></a><?= get_comments_number() ?> Comments so far</h4>
                <ul>
                    <?php wp_list_comments($args, get_comments()); ?>
                </ul>

                <?php

                $commenter = wp_get_current_commenter();
                $args = [
                    'fields' => [
                        'author' => '<div class="input-prepend">
                            <span class="add-on"><i class="icon-user"></i></span>
                            <input class="span4" name="author" id="prependedInput" size="16" type="text" placeholder="Name" 
                                value="' . esc_attr($commenter['comment_author']) . '">
                        </div>',
                        'email' => '<div class="input-prepend">
                            <span class="add-on"><i class="icon-envelope"></i></span>
                            <input class="span4" name="email" id="prependedInput" size="16" type="text" placeholder="Email Address"
                                value="' . esc_attr($commenter['comment_author_email']) . '">
                        </div>',
                        'url' => '<div class="input-prepend">
                            <span class="add-on"><i class="icon-globe"></i></span>
                            <input class="span4" name="url" id="prependedInput" size="16" type="text" placeholder="Website URL"
                                value="' . esc_attr($commenter['comment_author_url']) . '">
                        </div>'
                    ],
                    'comment_field' => '<textarea class="span6" name="comment"></textarea>',
                    'id_form' => 'comment-form',
                    'class_submit' => 'btn btn-inverse',
                    'label_submit' => 'Post My Comment',
                    'submit_field' => '<div class="row"><div class="span2">%1$s %2$s</div></div>',
                ];

                ?>

                <!-- Comment Form -->
                <div class="comment-form-container">
                    <?php comment_form($args); ?>
                </div>
            </section><!-- Close comments section-->

            <?php endwhile; ?>
            <?php else: ?>
                <span>Здесь пусто</span>
            <?php endif; ?>

    </div><!--Close container row-->

    <!-- Blog Sidebar
    ================================================== -->
    <?php require_once "inc/sidebar.inc.php" ?>

</div>

</div> <!-- End Container -->

<?php get_footer(); ?>
