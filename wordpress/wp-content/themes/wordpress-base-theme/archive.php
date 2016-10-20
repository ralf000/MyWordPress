<?php get_header(); ?>

<?php //get_headline_image(); ?>

<div class="content container clearfix">

<?php get_template_part('templates/title'); ?>

    <div class="left">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div class="article article-news clearfix">
        <?php if(has_post_thumbnail()) the_post_thumbnail('thumbnail'); ?>
        <h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
        <?php the_excerpt(); ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="btn">Read More</a>
    </div><!-- article -->

    <?php endwhile; else: ?>

        <p>Sorry, no posts matched your criteria.</p>

    <?php endif; ?>

    </div><!-- end left -->

     <div class="sidebar">
        <?php dynamic_sidebar('primary'); ?>
    </div><!-- sidebar -->

</div><!-- content -->


<?php get_footer(); ?>