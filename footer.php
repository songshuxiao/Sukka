 <!--底部s-->
 <footer class="footer">
     <div class="footer-container">

         <div>Copyright © 2018 - <span id="copy-year"><?php echo date('Y'); ?></span> <a class="footer-link" href="<?php $this->options->siteUrl(); ?>"><?php echo $this->options->logoText ? $this->options->logoText : 'Mr.Seaning'; ?></a>
             <?php if ($this->options->icp) : ?>
             <span class="dot"></span>
             <a class="footer-link" href="https://beian.miit.gov.cn/" rel="external nofollow noopener noreferrer" target="_blank"><?php $this->options->icp(); ?></a>
             <?php endif; ?>
             <?php if ($this->options->policeIcp) : ?>
             <span class="dot"></span>
             <a href="<?php echo $this->options->policeIcpUrl ? $this->options->policeIcpUrl : 'http://www.beian.gov.cn/'; ?>" rel="external nofollow noopener noreferrer" target="_blank" class="footer-link"><?php $this->options->policeIcp(); ?></a>
             <?php endif; ?>
             <span class="dot"></span>
             <span>耗时<?php timer_stop(1) ?>s</span>
         </div>
         <div>Powered by <a class="footer-link" href="https://www.typecho.org/" rel="external nofollow noopener noreferrer" target="_blank">Typecho</a>
             <span class="dot"></span>Designed by&nbsp;<a class="footer-link" href="https://blog.skk.moe/" rel="external nofollow noopener noreferrer" target="_blank">Sukka</a>
         </div>
     </div>
 </footer>
 <!--底部e-->

 <!--Top-->
 <div class="fab"><a aria-label="回到顶部" class="fab-btn" id="back-to-top" href="#"></a></div>

 <!-- highlight.js -->
 <?php if ($this->options->enableHighlight != '0') : ?>
 <script src="https://cdn.jsdelivr.net/npm/@highlightjs/cdn-assets@11.9.0/highlight.min.js"></script>
 <?php endif; ?>

 <!-- 主题脚本 -->
 <script src="<?php $this->options->themeUrl('assets/js/main.js'); ?>"></script>

 </body>

 </html>
