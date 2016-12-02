<?php get_header(); ?>

<div class="row headline"><!-- Begin Headline -->

    <?php require_once 'inc/slider.inc.php' ?>

    <!-- Headline Text
    ================================================== -->
    <div class="span4">
        <? $about = get_post(5, OBJECT, 'display'); ?>
        <?php if ($about): ?>
            <h3><?= $about->post_title; ?></h3>
            <p class="lead"><?= getIndexPageText($about->post_content) ?></p>
            <a href="<?= $about->guid ?>"><i class="icon-plus-sign"></i>Read More</a>
        <?php else: ?>
            <h3>Здесь пусто</h3>
        <?php endif; ?>
    </div>
</div><!-- End Headline -->

<?php
$gallery = new WP_Query([
    'post_type' => 'post',
    'cat' => 6,
    'post_count' => 12
]);
?>
<? if ($gallery->have_posts()): ?>
    <div class="row gallery-row"><!-- Begin Gallery Row -->

        <div class="span12">
            <h5 class="title-bg">
                <? if (category_description(6)) echo titleGenerator(category_description(6)) ?>
                <button onclick="location.href='<?= get_category_link(6); ?>'"
                        class="btn btn-mini btn-inverse hidden-phone"
                        type="button"><?= get_the_category_by_ID(6) ?></button>
            </h5>

            <!-- Gallery Thumbnails
            ================================================== -->
            <div class="row clearfix no-margin">
                <ul class="gallery-post-grid holder">

                    <!-- Gallery Item 1 -->
                    <?php while ($gallery->have_posts()) : $gallery->the_post(); ?>
                        <li class="span3 gallery-item" data-id="id-<? the_ID() ?>" data-type="illustration">
                        <span class="gallery-hover-4col hidden-phone hidden-tablet">
                            <span class="gallery-icons">
                                <a href="<?php the_post_thumbnail_url('full') ?>" class="item-zoom-link lightbox"
                                   title="<?php the_title() ?>" data-rel="prettyPhoto"></a>
                                <a href="<?php the_permalink() ?>" class="item-details-link"></a>
                            </span>
                        </span>
                            <a href="<?php the_permalink() ?>"><?php the_post_thumbnail('medium') ?></a>
                            <span class="project-details">
                                <a href="<?php the_permalink() ?>">Custom Illustration</a>
                                <?php the_excerpt(); ?>
                            </span>
                        </li>
                    <?php endwhile; ?>

                </ul>
            </div>
        </div>

    </div><!-- End Gallery Row -->
<?php else: ?>
    <p>Записей пока нет</p>
<?php endif; ?>
<!-- Возвращаем оригинальные данные поста. Сбрасываем $post. -->
<?php wp_reset_postdata(); ?>

<div class="row"><!-- Begin Bottom Section -->

    <!-- Blog Preview
    ================================================== -->
    <div class="span6">

        <?php
        $blog = new WP_Query([
            'post_type' => 'post',
            'cat' => 2,
            'post_count' => 6
        ]);
        ?>
        <? if ($blog->have_posts()): ?>
            <h5 class="title-bg">
                <? if (category_description(2)) echo titleGenerator(category_description(2)) ?>
                <button id="btn-blog-next" class="btn btn-inverse btn-mini" type="button">&raquo;</button>
                <button id="btn-blog-prev" class="btn btn-inverse btn-mini" type="button">&laquo;</button>
            </h5>

            <div id="blogCarousel" class="carousel slide ">

                <!-- Carousel items -->
                <div class="carousel-inner">

                    <!-- Blog Item -->
                    <?php while ($blog->have_posts()) : $blog->the_post(); ?>
                        <!--первая запись блока помечается классом active-->
                        <div class="<?php if (!isset($flag)) echo 'active';
                        $flag = true; ?> item">
                            <a href="<?php the_permalink() ?>">
                                <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_title() ?>"
                                     class="align-left blog-thumb-preview"/></a>
                            <div class="post-info clearfix">
                                <h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
                                <ul class="blog-details-preview">
                                    <li><i class="icon-calendar"></i><strong>Posted
                                            on:</strong> <?php the_time('M j, Y') ?>
                                    <li>
                                    <li><i class="icon-user"></i><strong>Posted by:</strong>
                                        <a href="<?php the_author_link() ?>" title="Link"><?php the_author() ?></a>
                                    <li>
                                    <li><i class="icon-comment"></i><strong>Comments:</strong>
                                        <a href="<?php comment_link() ?>" title="Link"><?php comments_number() ?></a>
                                    <li>
                                    <li><i class="icon-tags"></i>
                                        <?= getTags(get_the_tag_list()) ?>
                                </ul>
                            </div>
                            <p class="blog-summary"><?= strip_tags(get_the_excerpt()) ?> <a
                                    href="<?php the_permalink() ?>">Read more</a>
                            <p>
                        </div>
                    <?php endwhile; ?>
                    <!-- /Blog Item -->

                </div>
            </div>
        <?php else: ?>
            <p>Записей пока нет</p>
        <? endif; ?>
        <!-- Возвращаем оригинальные данные поста. Сбрасываем $post. -->
        <?php wp_reset_postdata(); ?>
    </div>

    <!-- Client Area
    ================================================== -->
    <div class="span6">

        <h5 class="title-bg">
            <?= titleGenerator(get_option('quotes_title')); ?>
            <button id="btn-client-next" class="btn btn-inverse btn-mini" type="button">&raquo;</button>
            <button id="btn-client-prev" class="btn btn-inverse btn-mini" type="button">&laquo;</button>
        </h5>

        <!-- Client Testimonial Slider-->
        <?php
        $quotes = new WP_Query([
            'post_type' => 'quote',
            'post_count' => 6
        ]);
        ?>
        <?php if ($quotes->have_posts()): ?>

            <div id="clientCarousel" class="carousel slide no-margin">
                <!-- Carousel items -->
                <div class="carousel-inner">

                    <?php while ($quotes->have_posts()): $quotes->the_post() ?>


                        <div class="<?php if (!isset($flag2)) echo 'active';
                        $flag2 = true; ?> item">
                            <p class="quote-text"><?= get_the_content() ?><cite><?= get_the_excerpt() ?></cite></p>
                        </div>

                        <div class="item">
                            <p class="quote-text">"Adipiscing elit. In interdum felis fermentum ipsum molestie sed
                                porttitor
                                ligula rutrum. Morbi blandit ultricies ultrices. Vivamus nec lectus sed orci molestie
                                molestie."<cite>- Another Client Name, Company Name</cite></p>
                        </div>

                        <div class="item">
                            <p class="quote-text">"Mauris eget tellus sem. Cras sollicitudin sem eu elit aliquam quis
                                condimentum nulla suscipit. Nam sed magna ante. Ut eget suscipit mauris."<cite>- On More
                                    Client, The Company</cite></p>
                        </div>


                    <?php endwhile; ?>

                </div>
            </div>

        <?php else: ?>
            <p>Цитат пока нет</p>
        <?php endif; ?>
        <!-- Возвращаем оригинальные данные поста. Сбрасываем $post. -->
        <?php wp_reset_postdata(); ?>


        <!-- Client Logo Thumbs-->
        <?php
        if (!dynamic_sidebar('clients')) {
            echo '<ul class="client-logos">';
            echo '<li>Клиенты компании</li>';
            echo '</ul>';
        }
        ?>


    </div>

</div><!-- End Bottom Section -->

</div> <!-- End Container -->

<? get_footer() ?>
