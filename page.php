<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>


<div class="container">
    <div class="cols">
        <main class="main-col">
            <article class="card">
                <div class="card-image thumb-post">
                    <img alt="<?php $this->title() ?>" class="thumb-img" crossorigin="" src="<?php $this->options->themeUrl("assets/img/lazyload.gif") ?>" data-original="<?php echo thumbside($this) ?>">
                </div>
                <header class="post-header">
                    <h1 class="post-title"><?php $this->title() ?></h1>
                    <div class="post-meta"><time datetime="<?php $this->date('c'); ?>"><?php $this->date(); ?></time>
                        <span class="dot"></span>
                        <?php $categories = $this->categories; ?>
                        <?php foreach ($categories as $cate) { ?>
                            <?php echo '<a class="post-meta_a" href="' . $cate['permalink'] . '">' . $cate['name'] . '</a>'; ?>
                        <?php } ?>
                        <span class="dot"></span> 文章有<?php echo art_count($this->cid); ?> 字
                        阅读完大约需要 <?php echo art_time($this->cid) ?> 分钟
                    </div>
                </header>

                <section id="post">
                    <?php $this->content(); ?>


                    <div class="license">
                        <div class="license-title"><?php $this->title() ?></div>
                        <div class="license-link"><a href="<?php $this->permalink() ?>" rel="external nofollow noopenner" target="_blank"><?php $this->permalink() ?></a>
                        </div>
                        <div class="license-meta">
                            <div class="license-meta-item">
                                <div class="license-meta-title">本文作者</div>
                                <div class="license-meta-text"><?php $this->author(); ?></div>
                            </div>
                            <div class="license-meta-item">
                                <div class="license-meta-title">发布于</div>
                                <div class="license-meta-text"><?php $this->date(); ?></div>
                            </div>
                            <div class="license-meta-item">
                                <div class="license-meta-title">许可协议</div>
                                <div class="license-meta-text"><a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh" rel="external nofollow noopener noopenner noreferrer" target="_blank">CC
                                        BY-NC-SA 4.0</a></div>
                            </div>
                        </div>
                        <div>转载或引用本文时请遵守许可协议，注明出处、不得用于商业用途！</div>
                    </div>
                    <?php if (timeZoneold($this->date->timeStamp)) : ?>
                        <div id="article-expire">本文最后更新于 <span id="article-expire-day">30</span>
                            天前，文中所描述的信息可能已发生改变</div>
                    <?php endif ?>
                </section>
                <footer class="article-footer">
                    <?php $categories = $this->categories; ?>
                    <?php foreach ($categories as $cate) { ?>
                        <?php echo '<a class="w-tag" href="' . $cate['permalink'] . '">' . '# ' . $cate['name'] . '</a>'; ?>
                    <?php } ?>

                    <?php $tags = $this->tags; ?>
                    <?php foreach ($tags as $tag) { ?>
                        <?php echo '<a class="s-tag" href="' . $tag['permalink'] . '">' . '# ' . $tag['name'] . '</a>'; ?>
                    <?php } ?>　

                </footer>
            </article>
            <?php if ($this->options->alipayQr || $this->options->wechatQr) : ?>
            <div class="card donations">
                <div class="donation-label"><?php echo $this->options->donationText ? $this->options->donationText : '喜欢这篇文章？为什么不考虑打赏一下作者呢？'; ?> </div>
                <a id="donation-btn" class="button btn-donation">打赏</a>
            </div>
            <?php endif; ?>
            <nav class="nav" role="navigation">
                <div class="nav-prev">
                    <?php thePrev($this); ?>
                </div>
                <div class="nav-next">
                    <?php theNext($this); ?>
                </div>
            </nav>
            <div class="c-card" id="comment">
                <?php $this->need('comments.php'); ?>
            </div>
        </main>
        <?php $this->need('sidebar.php'); ?>
    </div>
</div>
<!--主体e-->

<!--打赏弹窗s-->
<?php if ($this->options->alipayQr || $this->options->wechatQr) : ?>
<div class="donation-modal" id="donation-modal">
    <div class="donation-modal-mask"></div>
    <div class="donation-modal-box">
        <span class="donation-modal-close" id="donation-close">&times;</span>
        <div class="donation-modal-title">请作者喝杯咖啡 ☕</div>
        <div class="donation-modal-content">
            <?php if ($this->options->alipayQr) : ?>
            <div class="donation-item">
                <img src="<?php $this->options->alipayQr(); ?>" alt="支付宝打赏" class="donation-qr" />
                <div class="donation-item-label">支付宝</div>
            </div>
            <?php endif; ?>
            <?php if ($this->options->wechatQr) : ?>
            <div class="donation-item">
                <img src="<?php $this->options->wechatQr(); ?>" alt="微信打赏" class="donation-qr" />
                <div class="donation-item-label">微信</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<!--打赏弹窗e-->

<?php $this->need('footer.php'); ?>
