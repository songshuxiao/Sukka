<?php
/**
 * Sukka 主题核心函数
 * 兼容 Typecho 1.3.0 + PHP 8.4
 *
 * @package Sukka
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

use Typecho\Db;
use Typecho\Widget;
use Typecho\Date;
use Typecho\Cookie;
use Typecho\Validate;
use Typecho\Exception;
use Typecho\Widget\Helper\Form\Element\Text;
use Typecho\Widget\Helper\Form\Element\Textarea;
use Typecho\Widget\Helper\Form\Element\Radio;

/**
 * 主题初始化
 */
function themeInit($archive)
{
    // Ajax 评论处理
    if ($archive->is('single') && $archive->request->isPost() && $archive->request->is('themeAction=comment')) {
        ajaxComment($archive);
    }
}

/**
 * 主题配置
 */
function themeConfig($form)
{
    $logoText = new Text('logoText', null, 'Mr.Seaning', _t('站点标识文字'), _t('显示在导航栏左侧的文字'));
    $form->addInput($logoText);

    $authorName = new Text('authorName', null, 'Mr.Seaning', _t('博主昵称'), _t('侧边栏显示的博主名称'));
    $form->addInput($authorName);

    $authorDesc = new Text('authorDesc', null, '一入IT深思海，从此妹子是路人', _t('博主描述'), _t('侧边栏显示的博主一句话描述'));
    $form->addInput($authorDesc);

    $avatarUrl = new Text('avatarUrl', null, null, _t('博主头像地址'), _t('留空则使用主题默认头像'));
    $form->addInput($avatarUrl);

    $alipayQr = new Text('alipayQr', null, null, _t('支付宝打赏二维码图片地址'), _t('在文章页打赏按钮弹窗中显示，留空则不显示支付宝选项'));
    $form->addInput($alipayQr);

    $wechatQr = new Text('wechatQr', null, null, _t('微信打赏二维码图片地址'), _t('在文章页打赏按钮弹窗中显示，留空则不显示微信选项'));
    $form->addInput($wechatQr);

    $donationText = new Textarea('donationText', null, '喜欢文章？打赏作者>>', _t('打赏提示文字'), _t('文章页打赏区域的提示文字'));
    $form->addInput($donationText);

    $icp = new Text('icp', null, null, _t('ICP 备案号'), _t('留空则不显示'));
    $form->addInput($icp);

    $policeIcp = new Text('policeIcp', null, null, _t('公安备案号'), _t('留空则不显示'));
    $form->addInput($policeIcp);

    $policeIcpUrl = new Text('policeIcpUrl', null, null, _t('公安备案链接'), _t('公安备案号跳转链接，留空则使用默认链接'));
    $form->addInput($policeIcpUrl);

    $randomImgCdn = new Text('randomImgCdn', null, 'https://cdn.jsdelivr.net/gh/MrSeaning/blogImg/randimg/', _t('随机图片 CDN 地址'), _t('用于文章列表缩略图，图片命名格式为 img1.jpg, img2.jpg ...'));
    $form->addInput($randomImgCdn);

    $enableHighlight = new Radio(
        'enableHighlight',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '1',
        _t('代码高亮'),
        _t('使用 highlight.js 实现代码高亮')
    );
    $form->addInput($enableHighlight);

    $enableToc = new Radio(
        'enableToc',
        array('1' => _t('启用'), '0' => _t('禁用')),
        '1',
        _t('文章目录'),
        _t('在文章页侧边栏显示文章目录')
    );
    $form->addInput($enableToc);

    echo '<div style="background:#f5f5f5;padding:15px;border-radius:5px;margin:10px 0;">
    <h3>Sukka 主题</h3>
    <p>本主题已适配 Typecho 1.3.0 + PHP 8.4</p>
    <p>支持功能：搜索、打赏、评论、夜间/明亮模式切换、文章目录、代码高亮/复制</p>
    </div>';
}

/**
 * 文章自定义字段
 */
function themeFields($layout)
{
    $img = new Text('img', null, null, _t('文章缩略图'), _t('在这里填入文章缩略图地址，留空则使用随机图片'));
    $layout->addItem($img);
}

/**
 * Gravatar 头像 URL（兼容 Typecho 1.3.0）
 * 替代已废弃的 Typecho_Common::gravatarUrl
 */
function getGravatarUrl($mail, $size, $rating, $isSecure)
{
    $url = $isSecure ? 'https://secure.gravatar.com' : 'http://www.gravatar.com';
    $hash = md5(strtolower($mail));
    return $url . '/avatar/' . $hash . '?s=' . $size . '&r=' . $rating . '&d=mm';
}

/**
 * 计算文章字数
 */
function art_count($cid)
{
    $db = Db::get();
    $rs = $db->fetchRow($db->select('table.contents.text')->from('table.contents')->where('table.contents.cid = ?', $cid)->order('table.contents.cid', Db::SORT_ASC)->limit(1));
    $text = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $rs['text']);
    echo mb_strlen($text, 'UTF-8');
}

/**
 * 计算阅读时间
 */
function art_time($cid)
{
    $db = Db::get();
    $rs = $db->fetchRow($db->select('table.contents.text')->from('table.contents')->where('table.contents.cid = ?', $cid)->order('table.contents.cid', Db::SORT_ASC)->limit(1));
    $text = preg_replace("/[^\x{4e00}-\x{9fa5}]/u", "", $rs['text']);
    $count = mb_strlen($text, 'UTF-8');
    echo ceil($count / 400);
}

/**
 * 判断文章是否过期（超过30天）
 */
function timeZoneold($time)
{
    $timeStamp = $time;
    $date = new Date($timeStamp);
    $now = new Date();
    $diff = $now->timeStamp - $timeStamp;
    return $diff > (30 * 24 * 60 * 60);
}

/**
 * 获取文章缩略图
 */
function thumbside($article)
{
    $options = Widget::widget('Widget_Options');
    $cdn = $options->randomImgCdn ? $options->randomImgCdn : 'https://cdn.jsdelivr.net/gh/MrSeaning/blogImg/randimg/';

    // 优先使用自定义字段
    if (isset($article->fields->img) && $article->fields->img != "") {
        return $article->fields->img;
    }

    // 尝试从文章内容中提取第一张图片
    $content = $article->content;
    $pattern = '/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i';
    if (preg_match($pattern, $content, $matches)) {
        return $matches[1];
    }

    // 使用随机图片
    $rand_num = 42;
    return $cdn . mt_rand(1, $rand_num) . ".jpg";
    
}

/**
 * 上一篇文章
 */
function thePrev($archive)
{
    $db = Db::get();
    $cid = $archive->cid;
    $created = $archive->created;

    $rs = $db->fetchRow($db->select('cid', 'title', 'slug', 'type', 'status')->from('table.contents')
        ->where('type = ?', 'post')
        ->where('status = ?', 'publish')
        ->where('created < ?', $created)
        ->order('created', Db::SORT_DESC)
        ->limit(1));

    if ($rs) {
        $url = buildPermalink($rs);
        echo '<a class="nav-link" href="' . $url . '"><span class="nav-direction">上一篇</span><br><span class="nav-title">' . htmlspecialchars($rs['title']) . '</span></a>';
    } else {
        echo '<span class="nav-empty">没有更早的文章了</span>';
    }
}

/**
 * 下一篇文章
 */
function theNext($archive)
{
    $db = Db::get();
    $cid = $archive->cid;
    $created = $archive->created;

    $rs = $db->fetchRow($db->select('cid', 'title', 'slug', 'type', 'status')->from('table.contents')
        ->where('type = ?', 'post')
        ->where('status = ?', 'publish')
        ->where('created > ?', $created)
        ->order('created', Db::SORT_ASC)
        ->limit(1));

    if ($rs) {
        $url = buildPermalink($rs);
        echo '<a class="nav-link" href="' . $url . '"><span class="nav-direction">下一篇</span><br><span class="nav-title">' . htmlspecialchars($rs['title']) . '</span></a>';
    } else {
        echo '<span class="nav-empty">没有更新的文章了</span>';
    }
}

/**
 * 生成文章永久链接（兼容 Typecho 1.3.0）
 */
function buildPermalink($post)
{
    $options = Widget::widget('Widget_Options');
    $siteUrl = $options->siteUrl;
    
    // 尝试使用路由系统生成 URL
    try {
        $router = \Typecho\Router::getInstance();
        $path = $router->url('post', $post);
        // 如果返回的是完整 URL 则直接使用
        if (strpos($path, 'http') === 0) {
            return $path;
        }
        return $siteUrl . ltrim($path, '/');
    } catch (\Throwable $e) {
        // 降级方案：使用 cid 构建 URL
        return $siteUrl . 'archives/' . $post['cid'] . '/';
    }
}

/**
 * 页面加载计时
 */
function timer_start()
{
    global $timestart;
    $mtime = explode(' ', microtime());
    $timestart = $mtime[1] + $mtime[0];
    return true;
}

function timer_stop($display = 0, $precision = 3)
{
    global $timestart, $timeend;
    $mtime = explode(' ', microtime());
    $timeend = $mtime[1] + $mtime[0];
    $timetotal = $timeend - $timestart;
    $r = number_format($timetotal, $precision);
    if ($display) {
        echo $r;
    }
    return $r;
}

timer_start();
/**
 * Ajax 评论提交
 * 兼容 Typecho 1.3.0 + PHP 8.4
 * 修复：直接使用上下文对象，解决 cid 丢失导致的“文章不允许评论”错误
 * Ajax 评论提交 (含 Cookie 记忆功能)
 */
function ajaxComment($archive)
{
    header('Content-Type: application/json;charset=utf-8');

    try {
        $options = Widget::widget('Widget_Options');
        $user = Widget::widget('Widget_User');
        $db = Db::get();

        // 1. 检查权限
        if (!$archive->is('single')) {
            throw new Exception('只能在文章页面提交评论');
        }
        $cid = $archive->cid;
        if (!$archive->allow('comment')) {
            throw new Exception('该文章不允许评论');
        }

        // 2. 组装数据
        $comment = [
            'cid'       => $cid,
            'created'   => time(),
            'agent'     => $archive->request->getAgent(),
            'ip'        => $archive->request->getIp(),
            'ownerId'   => $archive->authorId,
            'type'      => 'comment',
            'status'    => $options->commentsRequireModeration ? 'waiting' : 'approved',
        ];

        // 3. 获取用户输入
        if ($user->hasLogin()) {
            $comment['author'] = $user->screenName;
            $comment['mail'] = $user->mail;
            $comment['url'] = $user->url;
            $comment['authorId'] = $user->uid;
        } else {
            $comment['author'] = $archive->request->filter('trim')->get('author');
            $comment['mail'] = $archive->request->filter('trim')->get('mail');
            $comment['url'] = $archive->request->filter('trim', 'url')->get('url');
            $comment['authorId'] = 0;
        }
        $comment['text'] = $archive->request->get('text');

        $parent = $archive->request->filter('int')->get('parent');
        if ($parent) { $comment['parent'] = $parent; }

        // 4. 验证
        $validator = new Validate();
        $validator->addRule('author', 'required', _t('必须填写昵称'));
        $validator->addRule('author', 'xssCheck', _t('昵称不能包含特殊字符'));
        $validator->addRule('mail', 'required', _t('必须填写邮箱'));
        $validator->addRule('mail', 'email', _t('请输入合法的邮箱地址'));
        $validator->addRule('url', 'url', _t('请输入合法的网址'));
        $validator->addRule('text', 'required', _t('必须填写评论内容'));
        if ($error = $validator->run($comment)) {
            throw new Exception(implode('；', $error));
        }

        // 5. 插入数据库
        $insertId = $db->query($db->insert('table.comments')->rows($comment));
        if (!$insertId) {
            throw new Exception('评论写入数据库失败');
        }

        // 6. 更新统计
        $db->query($db->update('table.contents')
            ->expression('commentsNum', 'commentsNum + 1')
            ->where('cid = ?', $cid));

        // 7. 【关键步骤】写入 Cookie 记住用户信息
        if (!$user->hasLogin()) {
            $expire = time() + 30 * 24 * 3600;
            $path = '/';
            Cookie::set('__typecho_remember_author', $comment['author'], $expire, $path);
            Cookie::set('__typecho_remember_mail', $comment['mail'], $expire, $path);
            if (!empty($comment['url'])) {
                Cookie::set('__typecho_remember_url', $comment['url'], $expire, $path);
            }
        }

        // 8. 返回结果
        echo json_encode([
            'status' => 1,
            'msg'    => '评论成功',
            'comment' => [
                'author'  => $comment['author'],
                'text'    => $comment['text'],
                'avatar'  => getGravatarUrl($comment['mail'], 48, 'g', true)
            ]
        ]);

    } catch (Throwable $e) {
        echo json_encode(['status' => 0, 'msg' => $e->getMessage()]);
    }
    exit;
}
