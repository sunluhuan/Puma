<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html class="no-js">
<head>
    <meta charset="<?php $this->options->charset(); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?></title>

    <!-- 使用url函数转换相关路径 -->
    <link rel="stylesheet" href="<?php $this->options->themeUrl('/static/css/main.css'); ?>">
    <!--[if lt IE 9]>
    <script src="http://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="http://cdn.staticfile.org/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- 通过自有函数输出HTML头部信息 -->
    <?php $this->header(); ?>
</head>
<body>
<div class="surface-content">
    <header class="site-header u-textAlignCenter hasImage">
        <h1 class="site-title">
            <a title="" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title() ?></a>
        </h1>
        <p class="site-description"><?php $this->options->description() ?></p>
        <div class="social-links">
        <span class="social-link">
<a target="_blank" href="#">
    <svg class="icon" viewBox="0 0 16 16" width="16" height="16">
        <use xlink:href="<?php $this->options->themeUrl('/static/img/svgdefs.svg'); ?>#icon-twitter">
    </svg>
</a>
</span>
<span class="social-link">
<a target="_blank" href="#">
    <svg class="icon" viewBox="0 0 16 16" width="16" height="16">
        <use xlink:href="<?php $this->options->themeUrl('/static/img/svgdefs.svg'); ?>#icon-instagram">
    </svg>
</a>
</span>
<span class="social-link">
<a target="_blank" href="#">
    <svg class="icon" viewBox="0 0 16 16" width="16" height="16">
        <use xlink:href="<?php $this->options->themeUrl('/static/img/svgdefs.svg'); ?>#icon-sina-weibo">
    </svg>
</a>
</span>
<span class="social-link">
<a target="_blank" href="<?php $this->options->siteUrl(); ?>/feed">
    <svg class="icon icon-feed2" viewBox="0 0 16 16" width="16" height="16">
        <use xlink:href="<?php $this->options->themeUrl('/static/img/svgdefs.svg'); ?>#icon-feed2">
    </svg>
</a>
</span>
        </div>
    </header>
    <nav id="nav-menu" class="topNav u-textAlignCenter" role="navigation">
        <ul id="menu-%e8%8f%9c%e5%8d%951" class="topNav-items">
            <li class="menu-item<?php if($this->is('index')): ?> current-menu-item<?php endif; ?>"> <a href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a></li>
            <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
            <?php while($pages->next()): ?>
                <li class="menu-item<?php if($this->is('page', $pages->slug)): ?> current-menu-item<?php endif; ?>"><a href="<?php $pages->permalink(); ?>" title="<?php $pages->title(); ?>"><?php $pages->title(); ?></a></li>
            <?php endwhile; ?>
        </ul>
    </nav>