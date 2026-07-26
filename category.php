<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>
<!--主体s-->
<div class="container">
    <div class="cols">
        <main class="main-col">
            <?php if ($this->have()) : ?>
                <div class="card card-content"><span class="mr2 page-title">分类 : <?php $this->category(",", false, "none") ?></span></div>
                <?php while ($this->next()) : ?>
                    <div class="card">
                        <a aria-label="缩略图" class="card-image thumb-index" href="<?php $this->permalink() ?>">
                            <img alt="<?php $this->title() ?>" src="<?php $this->options->themeUrl("assets/img/lazyload.gif")
                                                                    ?>" class="thumb-img" data-original="<?php echo thumbside($this); ?>">

                        </a>
                        <article class="card-content">
                            <h1 class="post-title">
                                <a class="post-title_a" href="<?php $this->permalink() ?>">
                                    <?php $this->title() ?>
                                </a>
                            </h1>
                            <div class="mb3">
                                <?php $this->excerpt(160, '...'); ?>
                            </div>
                            <div class="post-entry-footer">
                                <div class="post-meta">
                                    <time datetime="<?php $this->date('c'); ?>"><?php $this->dateWord(); ?></time>
                                    <span class="dot"></span>
                                    <?php $categories = $this->categories; ?>
                                    <?php foreach ($categories as $cate) { ?>
                                        <?php echo '<a class="post-meta_a" href="' . $cate['permalink'] . '">' . $cate['name'] . '</a>'; ?>
                                    <?php } ?>

                                </div>
                                <a href="<?php $this->permalink() ?>">继续阅读</a>
                            </div>
                        </article>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="card">
                    <article class="card-content">
                        别看啦，这里啥都没有，换个试试！！！！
                    </article>
                </div>
            <?php endif ?>

            <nav class="navi" role="navigation">
                <?php //$this->pageLink('上一页'); 
                ?>

                <?php $this->pageNav('上一页', '下一页', 5, '...', array('wrapTag' => 'ul', 'wrapClass' => 'navi-list', 'itemTag' => 'li', 'textTag' => 'span', 'currentClass' => 'current', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>

                <?php //$this->pageLink('下一页', 'next'); 
                ?>
            </nav>

        </main>
        <?php $this->need('sidebar.php'); ?>
    </div>
</div>
<!--主体e-->
<?php $this->need('footer.php'); ?>