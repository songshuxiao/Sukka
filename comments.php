<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php function threadedComments($comments, $options)
{
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }

    $commentLevelClass = $comments->levels > 0 ? ' comment-child' : ' comment-parent';
?>
    <li id="li-<?php $comments->theId(); ?>" class="comment-body<?php
                                                                if ($comments->levels > 0) {
                                                                    echo ' comment-child';
                                                                    $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
                                                                } else {
                                                                    echo ' comment-parent';
                                                                }
                                                                $comments->alt(' comment-odd', ' comment-even');
                                                                echo $commentClass;
                                                                ?>">
        <div id="<?php $comments->theId(); ?>">
            <div class="comment-author">
                <?php $comments->gravatar('40', ''); ?>
                <cite class="fn"><?php $comments->author(); ?></cite>
            </div>
            <div class="comment-meta">
                <a href="<?php $comments->permalink(); ?>"><?php $comments->date('Y-m-d H:i'); ?></a>
                <span class="comment-reply"><?php $comments->reply(); ?></span>
            </div>
            <?php $comments->content(); ?>
        </div>
        <?php if ($comments->children) { ?>
            <div class="comment-children">
                <?php $comments->threadedComments($options); ?>
            </div>
        <?php } ?>
    </li>
<?php } ?>
<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()) : ?>
        <h3><?php $this->commentsNum(_t('暂无评论'), _t('仅有一条评论'), _t('已有 %d 条评论')); ?></h3>

        <?php $comments->listComments(); ?>

        <?php $comments->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>

    <?php endif; ?>

    <?php if ($this->allow('comment')) : ?>
        <div id="<?php $this->respondId(); ?>" class="respond">
            <div class="cancel-comment-reply">
                <?php $comments->cancelReply(); ?>
            </div>

            <h3 id="response"><?php _e('添加新评论'); ?></h3>
            <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
                <?php if ($this->user->hasLogin()) : ?>
                    <p><?php _e('登录身份: '); ?><a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>. <a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> &raquo;</a></p>
                <?php else : ?>
                    <p>
                        <label for="author" class="required"><?php _e('称呼'); ?></label>
                        <input type="text" name="author" id="author" class="input" value="<?php $this->remember('author'); ?>" required />
                    </p>
                    <p>
                        <label for="mail" <?php if ($this->options->commentsRequireMail) : ?> class="required" <?php endif; ?>><?php _e('邮箱'); ?></label>
                        <input type="email" name="mail" id="mail" class="input" value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail) : ?> required<?php endif; ?> />
                    </p>
                    <p>
                        <label for="url" <?php if ($this->options->commentsRequireURL) : ?> class="required" <?php endif; ?>><?php _e('网站'); ?></label>
                        <input type="url" name="url" id="url" class="input" placeholder="<?php _e('http://'); ?>" value="<?php $this->remember('url'); ?>" <?php if ($this->options->commentsRequireURL) : ?> required<?php endif; ?> />
                    </p>
                <?php endif; ?>
                <p>
                    <label for="textarea" class="required"><?php _e('内容'); ?></label>
                    <textarea name="text" id="textarea" class="textarea" required><?php $this->remember('text'); ?></textarea>
                </p>
                <p>
                    <button type="submit" class="button btn-comment"><?php _e('提交评论'); ?></button>
                    <span class="comment-ajax-tip" id="comment-ajax-tip"></span>
                </p>
            </form>
        </div>
    <?php else : ?>
        <h3><?php _e('评论已关闭'); ?></h3>
    <?php endif; ?>
</div>

<script>
// Ajax 评论提交
(function () {
    var form = document.getElementById('comment-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var tip = document.getElementById('comment-ajax-tip');
        var btn = form.querySelector('.btn-comment');

        var formData = new FormData(form);
        var params = new URLSearchParams();
        formData.forEach(function (value, key) {
            params.append(key, value);
        });
        params.append('themeAction', 'comment');

        btn.disabled = true;
        btn.textContent = '提交中...';
        if (tip) tip.textContent = '';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: params.toString()
        })
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            if (data.status === 1) {
                if (tip) {
                    tip.style.color = '#3273dc';
                    tip.textContent = '评论成功！';
                }
                // 刷新页面以显示新评论
                setTimeout(function () {
                    window.location.reload();
                }, 1500);
            } else {
                btn.disabled = false;
                btn.textContent = '提交评论';
                if (tip) {
                    tip.style.color = '#e06c75';
                    tip.textContent = data.msg || '评论失败';
                }
            }
        })
        .catch(function (err) {
            btn.disabled = false;
            btn.textContent = '提交评论';
            if (tip) {
                tip.style.color = '#e06c75';
                tip.textContent = '网络错误，请重试';
            }
        });
    });
})();
</script>
