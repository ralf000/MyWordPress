<?php get_header(); ?>

<!-- Page Content
================================================== -->
<div class="row"><!--Container row-->

    <!-- Title Header -->
    <div class="span8"><!--Begin page content column-->


        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <h2><?php the_title() ?></h2>
                <?php the_content() ?>
            <?php endwhile; ?>
        <?php endif; ?>

    </div> <!--End page content column-->

    <!-- Blog Sidebar
    ================================================== -->
    <?php require_once "inc/page-sidebar.inc.php" ?>

</div><!-- End container row -->

</div> <!-- End Container -->

<script>
    //оборачиваем текст до тега more
    $(function () {
        $('.row p > span').parent('p').addClass('lead');
    });
</script>

<?php get_footer(); ?>
