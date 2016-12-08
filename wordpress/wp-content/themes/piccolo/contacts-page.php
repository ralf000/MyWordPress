<?php
/*
Template Name: Шаблон страницы контактов
*/
?>
<?php get_header() ?>

    <!-- Page Content
        ================================================== -->
    <div class="row"><!--Container row-->

        <div class="span8 contact"><!--Begin page content column-->

            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>

                    <h2><?php the_title() ?></h2>
                    <?php the_content() ?>

                <?php endwhile; ?>
            <?php else: ?>
                <p>Ничего не найдено</p>
            <?php endif; ?>

        </div> <!--End page content column-->

        <!-- Sidebar
        ================================================== -->
        <?php require_once "inc/contacts-sidebar.inc.php" ?>

    </div><!-- End container row -->

    </div> <!-- End Container -->

<?php get_footer() ?>