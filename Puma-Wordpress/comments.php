<?php
if ( post_password_required() )
    return;
?>
<div id="comments" class="responsesWrapper">
    <meta content="UserComments:<?php echo number_format_i18n( get_comments_number() );?>" itemprop="interactionCount">
    <h3 class="comments-title">共有 <span class="commentCount"><?php echo number_format_i18n( get_comments_number() );?></span> 条评论</h3>
    <ol class="commentlist">
        <?php
        wp_list_comments( array(
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 48,
        ) );
        ?>
    </ol>
    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
        <div class="commentnavholder v-textAlignCenter"><?php echo get_loadmorecomment_button(); ?></div>
    <?php endif; ?>
    <?php if(comments_open()) : ?>
        <?php comment_form();?>
    <?php endif; ?>
</div>