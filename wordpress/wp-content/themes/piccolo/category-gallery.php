<?php get_header() ?>

<!-- Page Content
================================================== -->
<div class="row">

    <!-- Gallery Items
    ================================================== -->
    <div class="span12 gallery">

        <?php if ($tags = getAllTagsFromCategory(6)): ?>
            <ul id="filterOptions" class="gallery-cats clearfix">
                <li class="<?= isset($active) ? 'active' : $active = '' ?>"><a href="#" class="all">All</a></li>
                <?php foreach ($tags as $name => $link): ?>
                    <li><a href="<?= $link ?>" class="<?= strtolower($name) ?>"><?= ucfirst($name) ?></a></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <div class="row clearfix">
            <ul class="gallery-post-grid holder">
                <?php if (have_posts()) : ?>
                    <?php $cnt = 1; ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <!-- Gallery Item -->
                        <li class="span4 gallery-item" data-id="id-<?php the_ID() ?>"
                            data-type="<?= getTagsAsString() ?>">
                        <span class="gallery-hover-3col hidden-phone hidden-tablet">
                            <span class="gallery-icons">
                                <a href="<?php the_post_thumbnail_url('full') ?>" class="item-zoom-link lightbox"
                                   title="<?php the_title() ?>" data-rel="prettyPhoto"></a>
                                <a href="<?php the_permalink() ?>" class="item-details-link"></a>
                            </span>
                        </span>
                            <a href="<?php the_permalink() ?>">
                                <?php the_post_thumbnail('full') ?>
                            </a>
                            <span class="project-details">
                                <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                                <?php the_excerpt() ?>
                            </span>
                        </li>
                        <?php if ($cnt % 3 === 0): ?>
                            <div style="clear: both;"></div>
                        <?php endif; ?>
                        <?php $cnt++; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <span>Здесь пусто</span>
                <?php endif; ?>

            </ul>
        </div>

        <!-- Pagination -->
        <?php sitePagination() ?>

    </div><!-- End gallery list-->

</div><!-- End container row -->

</div> <!-- End Container -->

<?php get_footer() ?>
