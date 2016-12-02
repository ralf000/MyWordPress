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
        <div class="span4 sidebar">

            <!--Search-->
            <section>
                <div class="input-append">
                    <form action="#">
                        <input id="appendedInputButton" size="16" name="s" type="text" placeholder="Search">
                        <button class="btn" type="submit"><i class="icon-search"></i></button>
                    </form>
                </div>
            </section>

            <?php
            $tags = get_terms([
                'taxonomy' => 'post_tag',
                'fields' => 'id=>name'
            ]);
            ?>

            <?php if ($tags): ?>
                <!--Categories-->
                <h5 class="title-bg">Categories</h5>
                <ul class="post-category-list">
                    <?php foreach ($tags as $id => $name): ?>
                        <li><a href="<?= get_tag_link($id) ?>"><i class="icon-plus-sign"></i><?= $name ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <? endif; ?>

            <?php
            if (!dynamic_sidebar('category_sidebar')) {
                echo '<p>Виджеты правой колонки категории записей</p>';
            }
            ?>

            <!--Tabbed Content-->
            <h5 class="title-bg">More Info</h5>
            <ul class="nav nav-tabs">
                <!--                <li class="active"><a href="#comments" data-toggle="tab">Comments</a></li>-->
                <!--                <li><a href="#tweets" data-toggle="tab">Tweets</a></li>-->
                <!--                <li><a href="#about" data-toggle="tab">About</a></li>-->
            </ul>

            <script>
                function makeTabs() {
                    var output = '';
                    $.each($('.tab-content .tab-pane'), function (key, val) {
                        var t = $(this);
                        var id = t.attr('id');
                        var title = t.find('.widgettitle').text();
                        output +=
                            '<li>' +
                            '<a href="#' + id + '" data-toggle="tab">' + title + '</a>' +
                            '</li>';
                    });
                    return output;
                }

                $(function () {
                    var tabs = makeTabs();
                    $('ul.nav.nav-tabs').append(tabs);
                    $('.nav-tabs a:first').tab('show');
                });
            </script>

            <div class="tab-content">
                <?php
                if (!dynamic_sidebar('category_sidebar_bottom')) {
                    echo '<p>Виджеты правой колонки категории записей для переключающейся панели</p>';
                }
                ?>


                <!--                <div class="tab-pane active" id="comments">-->
                <!--                    <ul>-->
                <!--                        <li><i class="icon-comment"></i>admin on <a href="#">Lorem ipsum dolor sit amet</a></li>-->
                <!--                        <li><i class="icon-comment"></i>admin on <a href="#">Consectetur adipiscing elit</a></li>-->
                <!--                        <li><i class="icon-comment"></i>admin on <a href="#">Ipsum dolor sit amet consectetur</a></li>-->
                <!--                        <li><i class="icon-comment"></i>admin on <a href="#">Aadipiscing elit varius elementum</a></li>-->
                <!--                        <li><i class="icon-comment"></i>admin on <a href="#">ulla iaculis mattis lorem</a></li>-->
                <!--                    </ul>-->
                <!--                </div>-->
                <!--                <div class="tab-pane" id="tweets">-->
                <!--                    <ul>-->
                <!--                        <li><a href="#"><i class="icon-share-alt"></i>@room122</a> Vivamus tincidunt sem eu magna varius-->
                <!--                            elementum. Maecenas felis tellus, fermentum vitae laoreet vitae, volutpat et urna.-->
                <!--                        </li>-->
                <!--                        <li><a href="#"> <i class="icon-share-alt"></i>@room122</a> Nulla faucibus ligula eget ante-->
                <!--                            varius-->
                <!--                            ac euismod odio placerat.-->
                <!--                        </li>-->
                <!--                        <li><a href="#"> <i class="icon-share-alt"></i>@room122</a> Pellentesque iaculis lacinia leo.-->
                <!--                            Donec-->
                <!--                            suscipit, lectus et hendrerit posuere, dui nisi porta risus, eget adipiscing-->
                <!--                        </li>-->
                <!--                        <li><a href="#"> <i class="icon-share-alt"></i>@room122</a> Vivamus augue nulla, vestibulum ac-->
                <!--                            ultrices posuere, vehicula ac arcu.-->
                <!--                        </li>-->
                <!--                        <li><a href="#"> <i class="icon-share-alt"></i>@room122</a> Sed ac neque nec leo condimentum-->
                <!--                            rhoncus. Nunc dapibus odio et lacus.-->
                <!--                        </li>-->
                <!--                    </ul>-->
                <!--                </div>-->
                <!--                <div class="tab-pane" id="about">-->
                <!--                    <p>Enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non-->
                <!--                        cupidatat-->
                <!--                        skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor,-->
                <!--                        sunt-->
                <!--                        aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim-->
                <!--                        keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan-->
                <!--                        excepteur butcher vice lomo.</p>-->
                <!---->
                <!--                    Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda-->
                <!--                    shoreditch et.-->
                <!--                </div>-->
            </div>

        </div>

    </div>

    </div> <!-- End Container -->

<?php get_footer() ?>