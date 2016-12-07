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

            <!-- Post Comments
            ================================================== -->
            <?php comments_template()  ?>
            <!-- Close comments section-->

            <?php endwhile; ?>
            <?php else: ?>
                <span>Здесь пусто</span>
            <?php endif; ?>

    </div><!--Close container row-->

    <!-- Blog Sidebar
    ================================================== -->
    <?php require_once "inc/post-sidebar.inc.php" ?>

</div>

</div> <!-- End Container -->

<?php get_footer(); ?>
