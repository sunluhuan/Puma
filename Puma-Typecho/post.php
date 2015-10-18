<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<main class="main-content">
    <section class="section-body">
        <header class="section-header u-textAlignCenter">
            <h2 class="grap--h2"><?php $this->title() ?></h2>
            <div class="block-postMetaWrap">
                <time><?php $this->date('F j, Y'); ?></time>
            </div>
        </header>
        <div class="grap" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>
        <p itemprop="keywords" class="post-tags"><?php $this->tags('', true, ''); ?></p>
        <div class="postFooterinfo u-textAlignCenter">
            <?php $this->author->gravatar(60); ?>
            <h3 class="author-name"><?php $this->author() ?></h3>
            <div class="author-description">作者描述</div>
            <div class="author-meta">
    <span class="author-meta-item">
<svg class="icon icon-instagram" viewBox="0 0 14 14" width="14" height="14">
    <use xlink:href="<?php $this->options->themeUrl('/static/img/svgdefs.svg'); ?>#icon-location">
</svg>
Shenzhen
</span>
<span class="author-meta-item">
<svg class="icon icon-twitter" viewBox="0 0 14 14" width="14" height="14">
    <use xlink:href="<?php $this->options->themeUrl('/static/img/svgdefs.svg'); ?>#icon-link">
</svg>
<a href="http://fatesinger.com">http://fatesinger.com</a>
</span>
            </div>
        </div>
    </section>
    <?php $this->need('comments.php'); ?>
</main><!-- end #main-->


<?php $this->need('footer.php'); ?>
