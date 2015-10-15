<?php get_header();?>
    <main class="main-content">
        <section class="blockGroup">
            <?php if (have_posts()):

                while (have_posts()): the_post();

                    get_template_part('content', 'list');

                endwhile;
            endif;?>
        </section>
        <nav class="posts-nav u-textAlignCenter">
            <?php the_posts_pagination( array(
                'prev_next'          =>  0,
                'before_page_number' => '',
            ) );?>
        </nav>
    </main>
<?php get_footer();?>