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
                    <?php $i = 0 ?>
                    <?php while ($blog->have_posts()) : $blog->the_post(); ?>
                        <div class="<?= !$i ? 'active' : '' ?> item">
                            <a href="<?php the_permalink() ?>">
                                <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_title() ?>"
                                     class="align-left blog-thumb-preview"/></a>
                            <div class="post-info clearfix">
                                <h4><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h4>
                                <ul class="blog-details-preview">
                                    <li><i class="icon-calendar"></i><strong>Posted
                                            on:</strong> <?php the_date('M j, Y') ?>
                                    <li>
                                    <li><i class="icon-user"></i><strong>Posted by:</strong>
                                        <a href="<?php the_author_link() ?>" title="Link"><?php the_author() ?></a>
                                    <li>
                                    <li><i class="icon-comment"></i><strong>Comments:</strong>
                                        <a href="<?php comment_link() ?>" title="Link"><?php comments_number() ?></a>
                                    <li>
                                    <li><i class="icon-tags"></i>
                                    <?= trim(str_replace('</a>', '</a> ', get_the_tag_list())) ?>
                                </ul>
                            </div>
                            <p class="blog-summary"><?= strip_tags(get_the_excerpt()) ?> <a href="<?php the_permalink() ?>">Read more</a>
                            <p>
                        </div>
                        <?php $i++; ?>
                    <?php endwhile; ?>

                    <!-- Blog Item 2 -->
                    <div class="item">
                        <a href="blog-single.htm"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/gallery/blog-med-img-1.jpg" alt=""
                                class="align-left blog-thumb-preview"/></a>
                        <div class="post-info clearfix">
                            <h4><a href="blog-single.htm">A great artist is always before his time</a></h4>
                            <ul class="blog-details-preview">
                                <li><i class="icon-calendar"></i><strong>Posted on:</strong> Sept 4, 2015
                                <li>
                                <li><i class="icon-user"></i><strong>Posted by:</strong> <a href="#"
                                                                                            title="Link">Admin</a>
                                <li>
                                <li><i class="icon-comment"></i><strong>Comments:</strong> <a href="#"
                                                                                              title="Link">12</a>
                                <li>
                                <li><i class="icon-tags"></i> <a href="#">photoshop</a>, <a href="#">tutorials</a>, <a
                                        href="#">illustration</a>
                            </ul>
                        </div>
                        <p class="blog-summary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In interdum
                            felis fermentum ipsum molestie sed porttitor ligula rutrum. Vestibulum lectus tellus,
                            aliquet et iaculis sed, volutpat vel erat. <a href="#">Read more</a>
                        <p>
                    </div>

                    <!-- Blog Item 3 -->
                    <div class="item">
                        <a href="blog-single.htm"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/gallery/blog-med-img-1.jpg" alt=""
                                class="align-left blog-thumb-preview"/></a>
                        <div class="post-info clearfix">
                            <h4><a href="blog-single.htm">Is art everything to anybody?</a></h4>
                            <ul class="blog-details-preview">
                                <li><i class="icon-calendar"></i><strong>Posted on:</strong> Sept 4, 2015
                                <li>
                                <li><i class="icon-user"></i><strong>Posted by:</strong> <a href="#"
                                                                                            title="Link">Admin</a>
                                <li>
                                <li><i class="icon-comment"></i><strong>Comments:</strong> <a href="#"
                                                                                              title="Link">12</a>
                                <li>
                                <li><i class="icon-tags"></i> <a href="#">photoshop</a>, <a href="#">tutorials</a>, <a
                                        href="#">illustration</a>
                            </ul>
                        </div>
                        <p class="blog-summary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. In interdum
                            felis fermentum ipsum molestie sed porttitor ligula rutrum. Vestibulum lectus tellus,
                            aliquet et iaculis sed, volutpat vel erat. <a href="#">Read more</a>
                        <p>
                    </div>

                </div>
            </div>
        <? endif; ?>
        <!-- Возвращаем оригинальные данные поста. Сбрасываем $post. -->
        <?php wp_reset_postdata(); ?>
    </div>

    <!-- Client Area
    ================================================== -->
    <div class="span6">

        <h5 class="title-bg">Recent Clients
            <small>That love us</small>
            <button id="btn-client-next" class="btn btn-inverse btn-mini" type="button">&raquo;</button>
            <button id="btn-client-prev" class="btn btn-inverse btn-mini" type="button">&laquo;</button>
        </h5>

        <!-- Client Testimonial Slider-->
        <div id="clientCarousel" class="carousel slide no-margin">
            <!-- Carousel items -->
            <div class="carousel-inner">

                <div class="active item">
                    <p class="quote-text">"Lorem ipsum dolor sit amet, consectetur adipiscing elit. In interdum
                        felis fermentum ipsum molestie sed porttitor ligula rutrum. Morbi blandit ultricies
                        ultrices. Vivamus nec lectus sed orci molestie molestie."<cite>- Client Name, Big
                            Company</cite></p>
                </div>

                <div class="item">
                    <p class="quote-text">"Adipiscing elit. In interdum felis fermentum ipsum molestie sed porttitor
                        ligula rutrum. Morbi blandit ultricies ultrices. Vivamus nec lectus sed orci molestie
                        molestie."<cite>- Another Client Name, Company Name</cite></p>
                </div>

                <div class="item">
                    <p class="quote-text">"Mauris eget tellus sem. Cras sollicitudin sem eu elit aliquam quis
                        condimentum nulla suscipit. Nam sed magna ante. Ut eget suscipit mauris."<cite>- On More
                            Client, The Company</cite></p>
                </div>

            </div>
        </div>

        <!-- Client Logo Thumbs-->
        <ul class="client-logos">
            <li><a href="#" class="client-link"><img
                        src="<?php echo get_template_directory_uri(); ?>/img/gallery/client-img-1.png" alt="Client"></a>
            </li>
            <li><a href="#" class="client-link"><img
                        src="<?php echo get_template_directory_uri(); ?>/img/gallery/client-img-2.png" alt="Client"></a>
            </li>
            <li><a href="#" class="client-link"><img
                        src="<?php echo get_template_directory_uri(); ?>/img/gallery/client-img-3.png" alt="Client"></a>
            </li>
            <li><a href="#" class="client-link"><img
                        src="<?php echo get_template_directory_uri(); ?>/img/gallery/client-img-4.png" alt="Client"></a>
            </li>
            <li><a href="#" class="client-link"><img
                        src="<?php echo get_template_directory_uri(); ?>/img/gallery/client-img-5.png" alt="Client"></a>
            </li>
        </ul>

    </div>

</div><!-- End Bottom Section -->

</div> <!-- End Container -->

<? get_footer() ?>
