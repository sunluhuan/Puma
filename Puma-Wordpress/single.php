<?php get_header();?>
    <main class="main-content">
        <section class="section-body">
            <?php while ( have_posts() ) : the_post(); ?>
                <header class="section-header u-textAlignCenter">
                    <h2 class="grap--h2"><?php the_title();?></h2>
                    <div class="block-postMetaWrap">
                        <time><?php echo get_the_date('Y/m/d');?></time>
                    </div>
                </header>
                <div class="grap">
                    <?php the_content();?>
                </div>
                <div class="postFooterAction">
                    <?php if(function_exists('wp_postlike')) wp_postlike();?>
                </div>
                <div class="postFooterinfo u-textAlignCenter">
                    <?php echo get_avatar(get_the_author_meta('email'),64);?>
                    <h3 class="author-name"><?php the_author();?></h3>
                    <div class="author-description"><?php echo get_the_author_meta('description')?></div>
                </div>
                <?php
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>
            <?php endwhile; ?>
        </section>
    </main>
<?php get_footer();?>