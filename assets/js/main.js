/**
 * Sukka 主题主脚本
 * 兼容 Typecho 1.3.0 + PHP 8.4
 */

(function () {
    'use strict';

    // ============================================================
    // 1. 暗色模式切换
    // ============================================================
    function updateHljsTheme() {
        var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        var lightLink = document.getElementById('hljs-light');
        var darkLink = document.getElementById('hljs-dark');
        if (lightLink && darkLink) {
            lightLink.disabled = isDark;
            darkLink.disabled = !isDark;
        }
    }

    function initDarkMode() {
        var btn = document.getElementById('dark-mode-btn');
        if (!btn) return;

        updateHljsTheme();

        btn.addEventListener('click', function () {
            var current = document.documentElement.getAttribute('data-theme');
            var newTheme = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            try {
                localStorage.setItem('theme', newTheme);
            } catch (e) {}
            updateHljsTheme();
        });
    }

    // ============================================================
    // 2. 搜索弹窗
    // ============================================================
    function initSearch() {
        var btn = document.getElementById('search-btn');
        var modal = document.getElementById('search-modal');
        var close = document.getElementById('search-close');
        var input = document.querySelector('.search-modal-input');
        if (!btn || !modal) return;

        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            if (input) {
                setTimeout(function () { input.focus(); }, 100);
            }
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        btn.addEventListener('click', openModal);
        if (close) close.addEventListener('click', closeModal);

        // 点击遮罩关闭
        var mask = modal.querySelector('.search-modal-mask');
        if (mask) mask.addEventListener('click', closeModal);

        // ESC 关闭
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
            // Ctrl+K / Cmd+K 打开搜索
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                openModal();
            }
        });
    }

    // ============================================================
    // 3. 打赏弹窗
    // ============================================================
    function initDonation() {
        var btn = document.getElementById('donation-btn');
        var modal = document.getElementById('donation-modal');
        var close = document.getElementById('donation-close');
        if (!btn || !modal) return;

        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        btn.addEventListener('click', openModal);
        if (close) close.addEventListener('click', closeModal);

        var mask = modal.querySelector('.donation-modal-mask');
        if (mask) mask.addEventListener('click', closeModal);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    }

    // ============================================================
    // 4. 图片懒加载
    // ============================================================
    function initLazyLoad() {
        var imgs = document.querySelectorAll('img[data-original]');
        if (!imgs.length) return;

        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var img = entry.target;
                        if (img.dataset.original) {
                            img.src = img.dataset.original;
                            img.removeAttribute('data-original');
                            img.classList.add('loaded');
                        }
                        observer.unobserve(img);
                    }
                });
            }, { rootMargin: '50px' });

            imgs.forEach(function (img) {
                observer.observe(img);
            });
        } else {
            imgs.forEach(function (img) {
                if (img.dataset.original) {
                    img.src = img.dataset.original;
                    img.removeAttribute('data-original');
                }
            });
        }
    }

    // ============================================================
    // 5. 回到顶部
    // ============================================================
    function initBackToTop() {
        var btn = document.getElementById('back-to-top');
        if (!btn) return;

        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 300) {
                btn.classList.add('show');
            } else {
                btn.classList.remove('show');
            }
        });

        btn.addEventListener('click', function (e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ============================================================
    // 6. 代码高亮 + 复制按钮
    // ============================================================
    function initCodeHighlight() {
        var codeBlocks = document.querySelectorAll('pre code');
        if (!codeBlocks.length) return;

        // 调用 highlight.js
        if (typeof hljs !== 'undefined') {
            codeBlocks.forEach(function (block) {
                try {
                    hljs.highlightElement(block);
                } catch (e) {
                    // 忽略高亮错误
                }
            });
        }

        // 添加复制按钮
        var pres = document.querySelectorAll('pre');
        pres.forEach(function (pre) {
            // 避免重复添加
            if (pre.querySelector('.code-copy-btn')) return;

            // 标记父元素为 relative
            pre.style.position = 'relative';

            var btn = document.createElement('button');
            btn.className = 'code-copy-btn';
            btn.textContent = '复制';
            btn.setAttribute('aria-label', '复制代码');

            btn.addEventListener('click', function () {
                var code = pre.querySelector('code');
                if (!code) return;

                var text = code.textContent;

                function showSuccess() {
                    btn.textContent = '已复制 ✓';
                    btn.classList.add('copied');
                    setTimeout(function () {
                        btn.textContent = '复制';
                        btn.classList.remove('copied');
                    }, 2000);
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(showSuccess).catch(function () {
                        fallbackCopy(text, showSuccess);
                    });
                } else {
                    fallbackCopy(text, showSuccess);
                }
            });

            pre.appendChild(btn);
        });
    }

    function fallbackCopy(text, callback) {
        var textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            if (callback) callback();
        } catch (e) {
            // 忽略
        }
        document.body.removeChild(textarea);
    }

  // ============================================================
    //  7. 文章目录 (TOC) - 自动生成（支持多级标题缩进）
    //  改进点：
    //  1. 包含 h1 ~ h6 所有标题
    //  2. 为每个 li 添加 toc-level-N 类，便于 CSS 精确控制缩进
    //  3. 嵌套 ul 结构天然实现层级缩进（配合 CSS .toc-ul .toc-ul）
    // ============================================================
    function initTOC() {
        var tocContainer = document.getElementById('catalog');
        var postContent = document.getElementById('post');
        if (!tocContainer || !postContent) return;

        // ★ 改进 1：包含 h1 ~ h6，让 h2 可以比 h1 缩进
        var headings = postContent.querySelectorAll('h1, h2, h3, h4, h5, h6');
        if (!headings.length) {
            var tocCard = document.getElementById('toc');
            if (tocCard) tocCard.style.display = 'none';
            return;
        }

        // 为每个标题添加 id（如果没有的话）
        headings.forEach(function(h, index) {
            if (!h.id) {
                h.id = 'heading-' + (index + 1);
            }
        });

        // 清空并构建嵌套列表
        tocContainer.innerHTML = '';
        var root = document.createElement('ul');
        root.className = 'toc-ul';
        tocContainer.appendChild(root);

        // 栈：记录层级路径，用于构建正确的嵌套结构
        var stack = [{ level: 0, element: root }];

        headings.forEach(function(h) {
            var level = parseInt(h.tagName.substring(1)); // 1 ~ 6
            var text = h.textContent.trim();
            var href = '#' + h.id;

            var li = document.createElement('li');
            // ★ 改进 2：添加级别类，方便 CSS 精确控制每个级别的缩进
            li.className = 'toc-li toc-level-' + level;

            var a = document.createElement('a');
            a.className = 'menu_a';
            a.href = href;
            a.textContent = text;
            li.appendChild(a);

            // 找到合适的父级（根据层级）
            while (stack.length > 1 && stack[stack.length - 1].level >= level) {
                stack.pop();
            }

            var parent = stack[stack.length - 1].element;

            // 如果父级是 LI，则在其内部创建/获取 ul 来容纳子项
            if (parent.tagName === 'LI') {
                var ul = parent.querySelector(':scope > ul.toc-ul');
                if (!ul) {
                    ul = document.createElement('ul');
                    ul.className = 'toc-ul';
                    parent.appendChild(ul);
                }
                ul.appendChild(li);
            } else {
                // 父级是 ul 直接添加 li
                parent.appendChild(li);
            }

            stack.push({ level: level, element: li });
        });

        // ===== 滚动监听：高亮当前目录项 =====
        var tocLinks = tocContainer.querySelectorAll('a');

        function updateActiveTOC() {
            var scrollPos = window.pageYOffset + 100;
            var currentId = '';

            headings.forEach(function(h) {
                if (h.offsetTop <= scrollPos) {
                    currentId = h.id;
                }
            });

            tocLinks.forEach(function(link) {
                if (link.getAttribute('href') === '#' + currentId) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        window.addEventListener('scroll', updateActiveTOC);
        updateActiveTOC();

        // ===== 平滑滚动 =====
        tocLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var targetId = link.getAttribute('href').substring(1);
                var target = document.getElementById(targetId);
                if (target) {
                    var offset = target.offsetTop + 565;
                    window.scrollTo({ top: offset, behavior: 'smooth' });
                }
            });
        });
    }

    // 页面加载完毕后初始化
    document.addEventListener('DOMContentLoaded', initTOC);

    // ============================================================
    // 8. 图片点击放大
    // ============================================================
    function initImageViewer() {
        var postContent = document.getElementById('post');
        if (!postContent) return;

        var imgs = postContent.querySelectorAll('img');
        imgs.forEach(function (img) {
            // 跳过头像等小图标
            if (img.classList.contains('author-avatar') || img.classList.contains('donation-qr')) return;

            img.style.cursor = 'zoom-in';
            img.addEventListener('click', function () {
                showImageViewer(img.src);
            });
        });
    }

    function showImageViewer(src) {
        var overlay = document.createElement('div');
        overlay.className = 'image-viewer-overlay';
        overlay.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.9);z-index:9999;display:flex;align-items:center;justify-content:center;cursor:zoom-out;';

        var img = document.createElement('img');
        img.src = src;
        img.style.cssText = 'max-width:90%;max-height:90%;object-fit:contain;';
        overlay.appendChild(img);

        overlay.addEventListener('click', function () {
            document.body.removeChild(overlay);
        });

        document.body.appendChild(overlay);
    }

    // ============================================================
    // 9. 文章过期提醒
    // ============================================================
    function initArticleExpire() {
        var expireDiv = document.getElementById('article-expire');
        if (!expireDiv) return;

        var daySpan = document.getElementById('article-expire-day');
        if (daySpan) {
            // 从 time 元素获取发布日期
            var timeEl = document.querySelector('.post-meta time');
            if (timeEl) {
                var publishDate = new Date(timeEl.getAttribute('datetime'));
                var now = new Date();
                var days = Math.floor((now - publishDate) / (1000 * 60 * 60 * 24));
                if (days > 30) {
                    daySpan.textContent = days;
                } else {
                    expireDiv.style.display = 'none';
                }
            }
        }
    }

    // ============================================================
    // 10. 移动端导航菜单
    // ============================================================
    function initMobileNav() {
        var burger = document.querySelector('.navbar-burger');
        var menu = document.querySelector('.navbar-menu');
        if (!burger || !menu) return;

        burger.addEventListener('click', function () {
            menu.classList.toggle('active');
            burger.classList.toggle('active');
        });
    }

    // ============================================================
    // 初始化
    // ============================================================
    function init() {
        initDarkMode();
        initSearch();
        initDonation();
        initLazyLoad();
        initBackToTop();
        initCodeHighlight();
        initTOC();
        initImageViewer();
        initArticleExpire();
        initMobileNav();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
