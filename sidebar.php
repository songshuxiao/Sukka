<aside class="left-col">
    <div class="c-card widget-author">
        <div class="author-image">
            <img alt="Seaning Avatar'" class="author-avatar" src="<?php echo $this->options->avatarUrl ? $this->options->avatarUrl : $this->options->themeUrl('assets/img/tx.jpg'); ?>">
        </div>
        <div class="mb3 title"><?php echo $this->options->authorName ? $this->options->authorName : 'Mr.Seaning'; ?></div>
        <div class="mb3"><?php echo $this->options->authorDesc ? $this->options->authorDesc : '一入IT深思海，从此妹子是路人'; ?></div>
        <div class="author-level">
            <?php $this->widget('Widget_Stat')->to($stat); ?>
            <div class="author-level-item">
                <div class="author-count"><?php $stat->publishedPostsNum() ?></div>
                <div class="author-item">文章</div>
            </div>
            <div class="author-level-item">
                <div class="author-count"><?php $stat->categoriesNum() ?></div>
                <div class="author-item">分类</div>
            </div>
            <div class="author-level-item">
                <div class="author-count"><?php $stat->publishedCommentsNum() ?></div>
                <div class="author-item">评论</div>
            </div>
            <!-- <div class="author-level-item">
                <div class="author-count"></div>
                <div class="author-item"></div>
            </div> -->
        </div>
    </div>
    <div class="sticky-tablet">
        <div class="c-card">
            <div class="card-label">分类</div>

            <?php $this->widget('Widget_Metas_Category_List')
                ->parse('<a class="menu_a menu_flex" href="{permalink}">{name}<span class="widget-count">{count}</span></a>'); ?>

        </div>


        <?php if ($this->is('post')) : ?>
            <div class="c-card" id="toc">
                <div class="card-label">文章目录</div>
                <ul class="toc-ul" id="catalog">
                    <!-- <li class="toc-li"><a class="menu_a" href="#一级目录">序言1</a></li>
                    <li class="toc-li"><a class="menu_a" href="#一级目录">一级目录</a>
                        <ul class="toc-ul">
                            <li class="toc-li"><a class="menu_a" href="#二级目录">二级目录</a></li>
                            <li class="toc-li"><a class="menu_a" href="#二级目录">二级目录</a></li>
                        </ul>
                    </li>

                    <li class="toc-li"><a class="menu_a" href="#一级目录">一级目录</a>
                        <ul class="toc-ul">

                            <li class="toc-li"><a class="menu_a" href="#二级目录">二级目录</a>
                                <ul class="toc-ul">
                                    <li class="toc-li"><a class="menu_a" href="#三级目录">三级目录</a></li>
                                    <li class="toc-li"><a class="menu_a" href="#三级目录">三级目录</a></li>
                                </ul>
                            </li>

                        </ul>
                    </li>
                    <li class="toc-li"><a class="menu_a" href="#一级目录">一级目录</a></li> -->
                </ul>
            </div>

            <div class="c-card">
                <div class="card-label">热门标签</div>
                <div class="widget-tags">

                    <?php $this->widget('Widget_Metas_Tag_Cloud', 'ignoreZeroCount=1&limit=20')->to($tags); ?>
                    <?php while ($tags->next()) : ?>
                        <a href="<?php $tags->permalink(); ?>" class="w-tag"># <?php $tags->name(); ?></a>
                    <?php endwhile; ?>

                </div>
            </div>
        <?php endif; ?>
    </div>
</aside>
<?php if (!$this->is('post') && !$this->is("page", "about")) : ?>
    <aside class="right-col">
        <div class="sticky-widescreen">
            <div class="c-card">
                <div class="card-label">最近文章</div>
                <?php
                $this->widget('Widget_Contents_Post_Recent', 'pageSize=5')->to($recent);
                if ($recent->have()) :
                    while ($recent->next()) :
                ?>
                        <div class="small-post-item"><time><?php $recent->date() ?></time><br><a class="widget-title" href="<?php $recent->permalink() ?>"><?php $recent->title() ?></a></div>
                <?php endwhile;
                endif; ?>


            </div>
            <!-- <div class="c-card">
                <div class="card-label">归档</div><a class="menu_a menu_flex" href="/archives/2020/">2020<span class="widget-count">38</span></a> <a class="menu_a menu_flex" href="/archives/2019/">2019<span class="widget-count">28</span></a> <a class="menu_a menu_flex" href="/archives/2018/">2018<span class="widget-count">23</span></a>
            </div> -->
            <div class="c-card">
                <div class="card-label">热门标签</div>
                <div class="widget-tags">

                    <?php $this->widget('Widget_Metas_Tag_Cloud', 'ignoreZeroCount=1&limit=28')->to($tags); ?>
                    <?php while ($tags->next()) : ?>
                        <a href="<?php $tags->permalink(); ?>" class="w-tag"># <?php $tags->name(); ?></a>
                    <?php endwhile; ?>

                </div>
            </div>
        </div>
    </aside>
<?php endif; ?>