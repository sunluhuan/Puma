<?php get_header();?>
    <main class="main-content">
        <section class="blockGroup">
            <?php if (have_posts()):

                while (have_posts()): the_post();

                    get_template_part('content', get_post_format());

                endwhile;
            endif;?>
        </section>
        <nav class="posts-nav u-textAlignCenter">
            <?php echo fa_load_postlist_button();?>
        </nav>
    </main>
<?php get_footer();?>