<?php
/**
 * @package Puma
 * @author Bigfa
 * @version 1.0.4
 * @link http://fatesinger.com
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<main class="main-content">
    <section class="blockGroup">
        <?php while($this->next()): ?>
            <article class="block block--inset block--list" itemscope itemtype="http://schema.org/BlogPosting">
                <h2 class="block-title post-featured" itemprop="headline">
                    <a href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
                </h2>
                <div class="block-postMetaWrap u-textAlignCenter">
                    <time><?php $this->date('Y/m/d'); ?></time>
                </div>
                <div class="post-content grap" itemprop="articleBody">
                    <?php $this->content(''); ?>
                </div>
                <div class="block-footer">
                    By <a itemprop="name" href="<?php $this->author->permalink(); ?>" rel="author"><?php $this->author(); ?></a> . In <?php $this->category(','); ?> .
                    <div class="block-footer-inner"><?php $this->commentsNum('No relay', '1 reply', '%d replies'); ?>. </div>
                </div>
            </article>
        <?php endwhile; ?>
    </section>
    <?php $this->pageNav('<', '>'); ?>
</main>
<?php $this->need('footer.php'); ?>