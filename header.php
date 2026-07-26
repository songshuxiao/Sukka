<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1,viewport-fit=cover" name="viewport">
    <meta content="on" http-equiv="x-dns-prefetch-control">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="windows-Target" contect="_top">
    <meta name="robots" content="all">
    <meta name="format-detection" content="telephone=no">
    <link crossorigin="" href="https://cdn.jsdelivr.net" rel="preconnect">
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <title>
        <?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ' - '); ?><?php $this->options->title(); ?>
    </title>

    <link rel="stylesheet" href="<?php $this->options->themeUrl('assets/css/main.css'); ?>">

    <?php if ($this->options->enableHighlight != '0') : ?>
    <!-- highlight.js 主题 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@highlightjs/cdn-assets@11.9.0/styles/atom-one-light.min.css" id="hljs-light" disabled>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@highlightjs/cdn-assets@11.9.0/styles/atom-one-dark.min.css" id="hljs-dark">
    <?php endif; ?>

    <!--[if lt IE 9]>
    <script src="https://cdn.jsdelivr.net/npm/html5shiv@3.7.3/dist/html5shiv.min.js"></script>
    <![endif]-->

    <!-- 暗色模式防闪烁：在 CSS 加载前应用主题 -->
    <script>
        (function () {
            try {
                var theme = localStorage.getItem('theme');
                if (!theme) {
                    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                        theme = 'dark';
                    } else {
                        theme = 'light';
                    }
                }
                document.documentElement.setAttribute('data-theme', theme);
                // 同步设置 highlight.js 主题
                var lightLink = document.getElementById('hljs-light');
                var darkLink = document.getElementById('hljs-dark');
                if (lightLink && darkLink) {
                    lightLink.disabled = (theme === 'dark');
                    darkLink.disabled = (theme !== 'dark');
                }
            } catch (e) {}
        })();
    </script>

    <?php $this->header('commentReply=1&description=0&keywords=0'); ?>
</head>

<body>
    <!--导航s-->
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-container">
            <div class="navbar-brand"><a class="navbar-item" href="<?php $this->options->siteUrl(); ?>"><span><?php echo $this->options->logoText ? $this->options->logoText : 'Mr.Seaning'; ?></span> </a></div>
            <div class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" href="<?php $this->options->siteUrl(); ?>">首页</a>
                    <?php $this->widget('Widget_Contents_Page_List')->to($pages); ?>
                    <?php while ($pages->next()) : ?>
                        <a class="navbar-item" href="<?php $pages->permalink(); ?>"><?php $pages->title(); ?></a>
                    <?php endwhile; ?>
                </div>
                <div class="navbar-end">
                    <a aria-label="搜索" class="navbar-item" id="search-btn"><i id="i-search"></i></a>
                    <a aria-label="切换暗色模式" class="navbar-item" id="dark-mode-btn"><i id="i-dark"></i></a>
                </div>
            </div>
        </div>
    </nav>
    <!--导航e-->

    <!--搜索弹窗s-->
    <div class="search-modal" id="search-modal">
        <div class="search-modal-mask"></div>
        <div class="search-modal-box">
            <form class="search-modal-form" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
                <input type="text" name="s" class="search-modal-input" placeholder="输入关键字搜索..." autocomplete="off" />
                <button type="submit" class="search-modal-submit">搜索</button>
                <span class="search-modal-close" id="search-close">&times;</span>
            </form>
        </div>
    </div>
    <!--搜索弹窗e-->
