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

<section class="comments">
    <h4 class="title-bg"><a name="comments"></a><?= get_comments_number() ?> Comments so far</h4>
    <ul>
        <?php wp_list_comments($args); ?>
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
</section>