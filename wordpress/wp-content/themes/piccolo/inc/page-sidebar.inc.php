<div class="span4 sidebar page-sidebar"><!-- Begin sidebar column -->

    <!--Tabbed Content-->
    <h5 class="title-bg">More Info</h5>
    <ul class="nav nav-tabs"></ul>

    <div class="tab-content">
        <?php
        if (!dynamic_sidebar('page_sidebar_tabs'))
            echo '<p>Виджеты правой колонки страниц для переключающейся панели</p>';
        ?>
    </div>

    <!--Testimonials-->
    <?php
    $quotes = new WP_Query([
        'post_type' => 'quote',
        'post_count' => 2,
        'orderby' => 'rand'
    ]);
    ?>
    <?php if ($quotes->have_posts()): ?>
        <h5 class="title-bg">Testimonials</h5>
        <?php while ($quotes->have_posts()): $quotes->the_post() ?>

            <p class="quote-text side">
            <?= get_the_content() ?>
                <cite><?= get_the_excerpt() ?></cite></p>

        <?php endwhile; ?>
    <?php else: ?>
        <p>Цитат пока нет</p>
    <?php endif; ?>
    <!-- Возвращаем оригинальные данные поста. Сбрасываем $post. -->
    <?php wp_reset_postdata(); ?>

    <?php
    if (!dynamic_sidebar('page_sidebar'))
        echo '<p>Виджеты правой колонки страницы</p>';
    ?>

</div><!-- End sidebar column -->
<script type="application/javascript" src="<?= get_template_directory_uri() ?>/js/sidebar_tabs.js"></script>