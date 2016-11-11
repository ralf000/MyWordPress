<!-- Slider Carousel
  ================================================== -->
<div class="span8">
    <?php $slider = new WP_Query([
        'post_type' => 'slide',
        'order' => 'asc'
    ]); ?>
    <?php if ($slider->have_posts()): ?>
        <div class="flexslider">
            <ul class="slides">
                <?php while ($slider->have_posts()): $slider->the_post() ?>
                    <li><a target="_blank" href="<?= post_custom('slide_link'); ?>"><?php the_post_thumbnail('full') ?></a></li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php else: ?>
        <p>Слайдов нет</p>
    <?php endif; ?>
</div>