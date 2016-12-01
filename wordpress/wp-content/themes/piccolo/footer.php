<!-- Footer Area
    ================================================== -->

<div class="footer-container"><!-- Begin Footer -->
    <div class="container">
        <div class="row footer-row">

            <?php
            if (!dynamic_sidebar('footer')) {
                echo '<div class="span3 footer-col">';
                echo '<h5>Виджеты подвала</h5>';
                echo '</div>';
            }
            ?>

        </div>

        <div class="row"><!-- Begin Sub Footer -->
            <div class="span12 footer-col footer-sub">
                <div class="row no-margin">
                    <div class="span6"><span class="left">Copyright <?= date('Y') ?>. All rights reserved.</span>
                    </div>

                    <div class="span6">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu',
                        'menu' => 'Footer Menu',
                        'container' => '',
                        'container_class' => '',
                        'container_id' => '',
//                        'menu_class' => 'menu',
//                        'menu_id' => '',
                        'echo' => true,
                        //'fallback_cb' => 'wp_page_menu',
                        'before' => '',
                        'after' => '</span>',
                        'link_before' => '',
                        'link_after' => '',
                        'items_wrap' => '<span class="right"><ul class="footer_menu">%3$s</ul></span>',
                        'depth' => 0,
                        'walker' => '',
                    ));
                    ?>
                    </div>

<!--                                        <div class="span6">-->
<!--                                                <span class="right">-->
<!--                                                <a href="#">Home</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#">Features</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a-->
<!--                                                        href="#">Gallery</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#">Blog</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a-->
<!--                                                        href="#">Contact</a>-->
<!--                                                </span>-->
<!--                                        </div>-->
                </div>
            </div>
        </div><!-- End Sub Footer -->

    </div>
</div><!-- End Footer -->

<!-- Scroll to Top -->
<div id="toTop" class="hidden-phone hidden-tablet">Back to Top</div>

<?php wp_footer(); ?>
</body>
</html>