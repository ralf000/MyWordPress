<?php
/*
WP Post Template: Шаблон категории "Галерея"
*/
?>
<?php get_header() ?>
    <!-- Page Content
    ================================================== -->
    <div class="row">

        <!-- Gallery Items
        ================================================== -->
        <div class="span12 gallery-single">

            <div class="row">

                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>

                        <div class="span6">
                            <img src="<?php the_post_thumbnail_url('full') ?>" class="align-left thumbnail"
                                 alt="image">
                        </div>
                        <div class="span6">
                            <h2><?php the_title() ?></h2>
                            <p class="lead"><?= get_the_excerpt() ?></p>
                            <?php the_content() ?>

                            <ul class="project-info">
                                <?php if (checkCustomField('date')): ?>
                                    <li><h6>Date:</h6> <?= post_custom('date') ?></li>
                                <?php endif; ?>
                                <?php if (checkCustomField('client')): ?>
                                    <li><h6>Client:</h6> <?= post_custom('client') ?></li>
                                <?php endif; ?>
                                <?php if (checkCustomField('services')): ?>
                                    <li><h6>Services:</h6> <?= post_custom('services') ?></li>
                                <?php endif; ?>
                                <?php if (checkCustomField('art_director')): ?>
                                    <li><h6>Art Director:</h6> <?= post_custom('art_director') ?></li>
                                <?php endif; ?>
                                <?php if (checkCustomField('designer')): ?>
                                    <li><h6>Designer:</h6> <?= post_custom('designer') ?></li>
                                <?php endif; ?>
                            </ul>

                            <?php if (checkCustomField('website')): ?>
                                <button onclick="location.href='<?= post_custom('website') ?>'"
                                        class="btn btn-inverse pull-left" type="button">
                                    Visit Website
                                </button>
                            <?php endif; ?>
                            <a href="<?= get_category_link(6); ?>" class="pull-right"><i class="icon-arrow-left"></i>Back
                                to <?= get_the_category_by_ID(6) ?></a>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <span>Здесь пусто</span>
                <?php endif; ?>

            </div>

        </div><!-- End gallery-single-->

    </div><!-- End container row -->

    </div> <!-- End Container -->

<?php get_footer() ?>