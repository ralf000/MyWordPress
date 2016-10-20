<?php get_header(); ?>
        
    <?php //get_headline_image(); ?>

    <div class="content container clearfix">

        <?php get_template_part('templates/title'); ?>
           
            <div class="left">
                <h4>error 404</h4>
                <h3>Page Not Found</h3>
                <p>We're sorry but the page you are looking for cannot be found.<br/>
                Please check your spelling and try again or <a href="<?php bloginfo('url'); ?>">return to our home page</a>.</p>
            </div><!-- left -->
                
            <div class="sidebar">
                <?php dynamic_sidebar('primary'); ?>
            </div><!-- sidebar -->
    
    </div><!-- content -->

<?php get_footer(); ?>