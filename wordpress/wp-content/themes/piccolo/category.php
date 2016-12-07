<?php get_header() ?>

    <!-- Blog Content
     ================================================== -->
    <div class="row">

        <!-- Blog Posts
        ================================================== -->
        <div class="span8 blog">

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <!-- Category Post -->
                    <article>
                        <h3 class="title-bg"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
                        <div class="post-summary">
                            <a href="blog-single.htm">
                                <?php the_post_thumbnail('medium') ?>
                            </a>
                            <?php the_excerpt() ?>
                            <div class="post-summary-footer">
                                <button class="btn btn-small btn-inverse"
                                        onclick="location.href='<?php the_permalink() ?>'" type="button">Read more
                                </button>
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
                <?php endwhile; ?>
            <?php else: ?>
                <span>Здесь пусто</span>
            <?php endif; ?>


            <!-- Pagination -->
            <?php sitePagination() ?>

        </div>

        <!-- Blog Sidebar
        ================================================== -->
        <?php require_once "inc/post-sidebar.inc.php" ?>

    </div>

    </div> <!-- End Container -->

<?php get_footer() ?>