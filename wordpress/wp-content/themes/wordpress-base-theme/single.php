<?php get_header(); ?>
        
    <?php //get_headline_image(); ?>

    <div class="content container clearfix">

        <?php get_template_part('templates/title');

       if(have_posts()): while(have_posts()): the_post(); ?>
           
            <div class="left">
                <?php the_content(); ?>
            </div><!-- left -->
                
            <div class="sidebar">
                <?php dynamic_sidebar('primary'); ?>
            </div><!-- sidebar -->
    
        <?php endwhile; endif; ?>
    
    </div><!-- content -->

<?php get_footer(); ?>