<?php
define('PUMA_VERSION','1.1.4');
function puma_setup() {
    register_nav_menu( 'angela', '主题菜单' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );
    add_theme_support( 'post-formats', array(
        'image',
        'status',
    ) );
}

add_action( 'after_setup_theme', 'puma_setup' );

function my_admin_notice(){

    echo '<div class="updated">

       <p>I am a little yellow notice.</p>

    </div>';

}

add_action('admin_notices', 'my_admin_notice');


function puma_load_static_files(){
    $dir = get_template_directory_uri() . '/static/';
    wp_enqueue_style('puma', $dir . 'css/main.css' , array(), PUMA_VERSION , 'screen');
    wp_enqueue_script( 'puma', $dir . 'js/main.js' , array( 'jquery' ), PUMA_VERSION , true );
    wp_localize_script( 'puma', 'PUMA', array(
        'ajax_url'   => admin_url('admin-ajax.php')
    ) );
}

add_action( 'wp_enqueue_scripts', 'puma_load_static_files' );

function puma_wp_title( $title, $sep ) {
    global $paged, $page;

    if ( is_feed() )
        return $title;

    // Add the site name.
    $title .= get_bloginfo( 'name', 'display' );

    // Add the site description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title = "$title $sep $site_description";

    // Add a page number if necessary.
    if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
        $title = "$title $sep " . sprintf( 'Page %s', max( $paged, $page ) );

    return $title;
}
add_filter( 'wp_title', 'puma_wp_title', 10, 2 );

function puma_get_ssl_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "cn.gravatar.com", $avatar);
    return $avatar;
}
add_filter('get_avatar', 'puma_get_ssl_avatar');

function link_to_menu_editor( $args )
{
    if ( ! current_user_can( 'manage_options' ) )
    {
        return;
    }

    extract( $args );

    $link = $link_before
        . '<a href="' .admin_url( 'nav-menus.php' ) . '">' . $before . 'Add a menu' . $after . '</a>'
        . $link_after;

    if ( FALSE !== stripos( $items_wrap, '<ul' )
        or FALSE !== stripos( $items_wrap, '<ol' )
    )
    {
        $link = "<li>$link</li>";
    }

    $output = sprintf( $items_wrap, $menu_id, $menu_class, $link );
    if ( ! empty ( $container ) )
    {
        $output  = "<$container class='$container_class' id='$container_id'>$output</$container>";
    }

    if ( $echo )
    {
        echo $output;
    }

    return $output;
}

function fa_get_the_term_list( $id, $taxonomy ) {
    $terms = get_the_terms( $id, $taxonomy );
    $term_links = "";
    if ( is_wp_error( $terms ) )
        return $terms;

    if ( empty( $terms ) )
        return false;

    foreach ( $terms as $term ) {
        $link = get_term_link( $term, $taxonomy );
        if ( is_wp_error( $link ) )
            return $link;
        $term_links .= '<a href="' . esc_url( $link ) . '" class="post--keyword" data-title="' . $term->name . '" data-type="'. $taxonomy .'" data-term-id="' . $term->term_id . '">' . $term->name . '<sup>['. $term->count .']</sup></a>';
    }

    return $term_links;
}

function puma_add_remove_contactmethods( $contactmethods ) {
    // Add Twitter
    $contactmethods['twitter'] = 'Twitter';
    //Add Facebook
    $contactmethods['sina-weibo'] = 'Weibo';

    $contactmethods['location'] = '位置';

    $contactmethods['instagram'] = 'Instagram';
    // Remove Contact Methods
    unset($contactmethods['aim']);
    unset($contactmethods['yim']);
    unset($contactmethods['jabber']);

    return $contactmethods;
}
add_filter('user_contactmethods','puma_add_remove_contactmethods',10,1);


function header_social_link(){
    $socials = array('twitter','sina-weibo','instagram');
    $output = '';
    foreach ($socials as $key => $social) {
        if( get_user_meta(1,$social,true) != '' ) { $output .= '<span class="social-link"><a href="' . get_user_meta(1,$social,true) .'" target="_blank"><svg class="icon icon-' . $social . '" height="16" width="16" viewBox="0 0 16 16"><use xlink:href="' . get_template_directory_uri() . '/static/img/svgdefs.svg#icon-' . $social . '"></use></svg></a></span>';
        }
    }
    $output .= '<span class="social-link"><a href="' . get_bloginfo('rss2_url'). '" target="_blank"><svg class="icon icon-feed2" height="16" width="16" viewBox="0 0 16 16"><use xlink:href="' . get_template_directory_uri() . '/static/img/svgdefs.svg#icon-feed2"></use></svg></a></span>';
    return $output;
}


add_action('wp_ajax_nopriv_ajax_comment', 'ajax_comment_callback');
add_action('wp_ajax_ajax_comment', 'ajax_comment_callback');
function ajax_comment_callback(){
    global $wpdb;
    $comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
    $post = get_post($comment_post_ID);
    $post_author = $post->post_author;
    if ( empty($post->comment_status) ) {
        do_action('comment_id_not_found', $comment_post_ID);
        ajax_comment_err('Invalid comment status.');
    }
    $status = get_post_status($post);
    $status_obj = get_post_status_object($status);
    if ( !comments_open($comment_post_ID) ) {
        do_action('comment_closed', $comment_post_ID);
        ajax_comment_err('Sorry, comments are closed for this item.');
    } elseif ( 'trash' == $status ) {
        do_action('comment_on_trash', $comment_post_ID);
        ajax_comment_err('Invalid comment status.');
    } elseif ( !$status_obj->public && !$status_obj->private ) {
        do_action('comment_on_draft', $comment_post_ID);
        ajax_comment_err('Invalid comment status.');
    } elseif ( post_password_required($comment_post_ID) ) {
        do_action('comment_on_password_protected', $comment_post_ID);
        ajax_comment_err('Password Protected');
    } else {
        do_action('pre_comment_on_post', $comment_post_ID);
    }
    $comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
    $comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
    $comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
    $comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
    $user = wp_get_current_user();
    if ( $user->exists() ) {
        if ( empty( $user->display_name ) )
            $user->display_name=$user->user_login;
        $comment_author       = esc_sql($user->display_name);
        $comment_author_email = esc_sql($user->user_email);
        $comment_author_url   = esc_sql($user->user_url);
        $user_ID              = esc_sql($user->ID);
        if ( current_user_can('unfiltered_html') ) {
            if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
                kses_remove_filters();
                kses_init_filters();
            }
        }
    } else {
        if ( get_option('comment_registration') || 'private' == $status )
            ajax_comment_err('Sorry, you must be logged in to post a comment.');
    }
    $comment_type = '';
    if ( get_option('require_name_email') && !$user->exists() ) {
        if ( 6 > strlen($comment_author_email) || '' == $comment_author )
            ajax_comment_err( 'Error: please fill the required fields (name, email).' );
        elseif ( !is_email($comment_author_email))
            ajax_comment_err( 'Error: please enter a valid email address.' );
    }
    if ( '' == $comment_content )
        ajax_comment_err( 'Error: please type a comment.' );
    $dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
    if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
    $dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
    if ( $wpdb->get_var($dupe) ) {
        ajax_comment_err('Duplicate comment detected; it looks as though you&#8217;ve already said that!');
    }
    if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
        $time_lastcomment = mysql2date('U', $lasttime, false);
        $time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
        $flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
        if ( $flood_die ) {
            ajax_comment_err('You are posting comments too quickly.  Slow down.');
        }
    }
    $comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
    $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

    $comment_id = wp_new_comment( $commentdata );


    $comment = get_comment($comment_id);
    do_action('set_comment_cookies', $comment, $user);
    $comment_depth = 1;
    $tmp_c = $comment;
    while($tmp_c->comment_parent != 0){
        $comment_depth++;
        $tmp_c = get_comment($tmp_c->comment_parent);
    }
    $GLOBALS['comment'] = $comment;
    //这里修改成你的评论结构
    ?>
    <li <?php comment_class(); ?>>
        <article class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php echo get_avatar( $comment, $size = '48')?>
                    <b class="fn">
                        <?php echo get_comment_author_link();?>
                    </b>
                </div>
                <div class="comment-metadata">
                    <?php echo get_comment_date(); ?>
                </div>
            </footer>
            <div class="comment-content">
                <?php comment_text(); ?>
            </div>
        </article>
    </li>
    <?php die();
}
function ajax_comment_err($a) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}

function puma_comment_nav() {
    // Are there comments to navigate through?
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
        ?>
        <nav class="navigation comment-navigation u-textAlignCenter" role="navigation">
            <div class="nav-links">
                <?php
                if ( $prev_link = get_previous_comments_link(  '上一页' ) ) :
                    printf( '<div class="nav-previous">%s</div>', $prev_link );
                endif;

                if ( $next_link = get_next_comments_link( '下一页' ) ) :
                    printf( '<div class="nav-next">%s</div>', $next_link );
                endif;
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .comment-navigation -->
        <?php
    endif;
}

function fa_load_postlist_button(){
    global $wp_query;
    if (2 > $GLOBALS["wp_query"]->max_num_pages) {
        return;
    } else {
        $button = '<button id="fa-loadmore" class="button button--primary button--more"';
        if (is_category()) $button .= ' data-category="' . get_query_var('cat') . '"';

        if (is_author()) $button .=  ' data-author="' . get_query_var('author') . '"';

        if (is_tag()) $button .=  ' data-tag="' . get_query_var('tag') . '"';

        if (is_search()) $button .=  ' data-search="' . get_query_var('s') . '"';

        if (is_date() ) $button .=  ' data-year="' . get_query_var('year') . '" data-month="' . get_query_var('monthnum') . '" data-day="' . get_query_var('day') . '"';

        $button .= ' data-paged="2" data-action="fa_load_postlist" data-total="' . $GLOBALS["wp_query"]->max_num_pages . '">load more</button>';

        return $button;
    }
}


function puma_post_section(){
    global $post;
    $post_section = '<article class="block block--inset block--list"><h2 class="block-title post-featured"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>
    <div class="block-postMetaWrap u-textAlignCenter">
        <time>' . get_the_date('Y/m/d') . '</time>
    </div>
    <div class="block-snippet block-snippet--subtitle grap" itemprop="about">';

    if(has_post_thumbnail()) {
        $post_section .= '<p class="with-img">' . get_the_post_thumbnail() . '</p><p>' . mb_strimwidth(strip_shortcodes(strip_tags(apply_filters('the_content', $post->post_content))), 0, 220,"...") . '</p>';
    } else {
        $post_section .= apply_filters('the_content', get_the_content(''));
    }

    $post_section .= '</div>
    <div class="block-footer">
        By ' . get_the_author() .' . In ' . get_the_category_list(',') .'.
        <div class="block-footer-inner">';
    if(function_exists('wpl_get_like_count')) $post_section .= wpl_get_like_count(get_the_ID()) . ' likes . ';
    $post_section .= get_comments_number() . ' replies.</div></div></article>';
    return $post_section;
}

add_action('wp_ajax_nopriv_fa_load_postlist', 'fa_load_postlist_callback');
add_action('wp_ajax_fa_load_postlist', 'fa_load_postlist_callback');
function fa_load_postlist_callback(){
    $postlist = '';
    $paged = $_POST["paged"];
    $total = $_POST["total"];
    $category = $_POST["category"];
    $author = $_POST["author"];
    $tag = $_POST["tag"];
    $search = $_POST["search"];
    $year = $_POST["year"];
    $month = $_POST["month"];
    $day = $_POST["day"];
    $query_args = array(
        "posts_per_page" => get_option('posts_per_page'),
        "cat" => $category,
        "tag" => $tag,
        "author" => $author,
        "post_status" => "publish",
        "post_type" => "post",
        "paged" => $paged,
        "s" => $search,
        "year" => $year,
        "monthnum" => $month,
        "day" => $day
    );
    $the_query = new WP_Query( $query_args );
    while ( $the_query->have_posts() ){
        $the_query->the_post();
        $postlist .= puma_post_section();
    }
    $code = $postlist ? 200 : 500;
    wp_reset_postdata();
    $next = ( $total > $paged )  ? ( $paged + 1 ) : '' ;
    echo json_encode(array('code'=>$code,'postlist'=>$postlist,'next'=> $next));
    die;
}