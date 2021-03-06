<?php header("Content-Type: text/html; charset=UTF-8"); ?>
<!DOCTYPE html>
<html lang="<?php echo \Fc2blog\Config::get('LANG'); ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
  <meta content="telephone=no" name="format-detection" />
  <meta name="robots" content="noindex, nofollow, noarchive" />
  <title><?php echo \Fc2blog\Web\Session::get('blog_id'); ?></title>
  <link rel="icon" href="https://static.fc2.com/share/image/favicon.ico">
  <link rel="stylesheet" href="/css/sp/blog_sp_admin.css" type="text/css" media="all">
  <link rel="stylesheet" href="/css/sp/side_menu.css" type="text/css" media="all">

  <?php echo $this->includeCSS(); ?>

  <script type="text/javascript" src="/js/jquery/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="/js/jquery/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="/js/jquery/jquery-ui/1.9.2/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/js/jquery/jquery.ui.touch-punch.min.js"></script>
  <link rel="stylesheet" href="/css/jquery/jquery-ui/1.9.2/themes/ui-lightness/jquery-ui.css" type="text/css" media="screen" charset="utf-8">
  <script type="text/javascript" src="/js/common_design.js"></script>

  <script type="text/javascript" src="/js/common.js"></script>
  <script>
    // フレームワーク用のjsの設定
    <?php if(\Fc2blog\Config::get('URL_REWRITE')): ?>
      common.isURLRewrite = true;
    <?php endif; ?>
    common.baseDirectory = '<?php echo \Fc2blog\Config::get('BASE_DIRECTORY'); ?>';
    common.deviceType = <?php echo $this->getDeviceType(); ?>;
    common.deviceArgs = '<?php echo \Fc2blog\App::getArgsDevice(); ?>';
  </script>

  <script type="text/javascript" src="/js/sp/jquery.slideMenu.js"></script>
  <script type="text/javascript" src="/js/sp/jquery.accordion.js"></script>

  <script type="text/javascript">
    $(function(){
      $.slideMenu();

      $('#left_menu_btn,#right_menu_btn').click(function() {
        $('#editor_btn_area').hide();
      });

      $('#overlay').click(function() {
        $('#editor_btn_area').show();
      });

      // submenu
      $('.accordion_head').fc2Accordion({
        contents: '.accordion_inner',
        classOpen: 'cursol_up' 
      });

      // search form
      $('.accordion_btn').fc2Accordion({
        contents: '.accordion_contents',
        classOpen: 'cursol_up' 
      });

      // 保存完了、エラー等のメッセージ
      $('.popup_btn').click(function(){
        $('.popup_message').addClass('hidden');
      });

      // ボタンをタップしたときにクラスの追加
      var touchNormalBtn = function(e) {
        $('.btn_contents_touch').removeClass('btn_contents_touch');
        if (e.type === 'touchstart') {
          $(this).addClass('btn_contents_touch');
        }
      };
      $(document).on('touchstart.btnContents', '.touch', touchNormalBtn).on('touchmove.btnContents touchcancel.btnContents touchend.btnContents', touchNormalBtn);
    });
  </script>

  <?php echo $this->includeJS(); ?>

</head>
<body>

<div id="wrapper_all">
<div id="wrapper">

  <header id="global_header">
    <div><span id="left_menu_btn"><i class="leftmenu"></i></span></div>
    <?php if ($this->isLogin()): ?>
      <div><span id="right_menu_btn"><i class="rightmenu"></i></span></div>
    <?php endif; ?>
    <h1><?php echo \Fc2blog\Web\Session::get('blog_id'); ?></h1>
  </header>

  <div id="contents">
    <?php $this->display('Common/flash_message.php', array('messages'=>$this->removeMessage())); ?>
    <?php $this->display($fw_template); ?>
  </div>

  <footer id="site_footer">
      <?php $lang = \Fc2blog\Config::get('LANG'); ?>
      <div id="switch_lang" class="sh_langselect">
        <select id="sys-language-setting" onchange="location.href=common.fwURL('common', 'lang', {lang: $(this).val()});">
          <option value="ja" <?php if ($lang=='ja') : ?>selected="selected"<?php endif; ?>>日本語</option>
          <option value="en" <?php if ($lang=='en') : ?>selected="selected"<?php endif; ?>>English</option>
        </select>
      </div>
  </footer>

</div><!--/wrapper-->

      <?php if ($this->isLogin()): ?>
        <div id="left_menu" class="sidemenu">
          <ul>
        <?php if ($this->isSelectedBlog()) : ?>
            <li <?php if (\Fc2blog\App::isActiveMenu('common/notice')) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Common','action'=>'notice')); ?>"><span><i class="sidemenu_home"></i></span><span><?php echo __('Notice'); ?></span></a></li>
            <li><a class="touch" href="<?php echo \Fc2blog\App::userURL(array('controller'=>'entries', 'action'=>'index', 'blog_id'=>$this->getBlogId(), 'sp'=>1)); ?>" target="_blank"><span><i class="sidemenu_myblog"></i></span><span><?php echo __('Checking the blog'); ?></span></a></li>
            <li <?php if (\Fc2blog\App::isActiveMenu(array('entries/create', 'entries/edit'))) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Entries','action'=>'create')); ?>"><span><i class="sidemenu_editor"></i></span><span><?php echo __('New article'); ?></span></a></li>
            <li <?php if (\Fc2blog\App::isActiveMenu('entries/index')) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Entries', 'action'=>'index')); ?>"><span><i class="sidemenu_entry"></i></span><span><?php echo __('List of articles'); ?></span></a></li>
            <li <?php if (\Fc2blog\App::isActiveMenu(array('comments/index', 'comments/reply'))) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Comments', 'action'=>'index')); ?>"><span><i class="sidemenu_comment"></i></span><span><?php echo __('List of comments'); ?></span></a></li>
            <li <?php if (\Fc2blog\App::isActiveMenu(array('files/upload', 'files/edit'))) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Files', 'action'=>'upload')); ?>"><span><i class="sidemenu_file"></i></span><span><?php echo __('Upload file'); ?></span></a></li>
        <?php endif; ?>
        <?php if ($this->isSelectedBlog()) : ?>
            <li class="list_titile"><?php echo __('Setting'); ?></li>
            <li <?php if (\Fc2blog\App::isActiveMenu('blog_templates/index')) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'BlogTemplates', 'action'=>'index', 'device_type'=>\Fc2blog\Config::get('DEVICE_SP'))); ?>"><span><i class="sidemenu_template"></i></span><span><?php echo __('Template management'); ?></span></a></li>
            <li <?php if (\Fc2blog\App::isActiveMenu('blog_plugins/index')) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'blog_plugins', 'action'=>'index', 'device_type'=>\Fc2blog\Config::get('DEVICE_SP'))); ?>"><span><i class="sidemenu_plugin"></i></span><span><?php echo __('Plugin management'); ?></span></a></li>
            <li <?php if (\Fc2blog\App::isActiveMenu(array('categories/create', 'categories/edit'))) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Categories','action'=>'create')); ?>"><span><i class="sidemenu_category"></i></span><span><?php echo __('Category management'); ?></span></a></li>
            <li <?php if (\Fc2blog\App::isActiveMenu(array('tags/index', 'tags/edit'))) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Tags', 'action'=>'index')); ?>"><span><i class="sidemenu_tag"></i></span><span><?php echo __('List of tags'); ?></span></a></li>
            <li <?php if (\Fc2blog\App::isActiveMenu(array('blogs/edit', 'blog_settings/entry_edit', 'blog_settings/comment_edit', 'blog_settings/etc_edit', 'blogs/delete'))) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Blogs', 'action'=>'edit')); ?>"><span><i class="sidemenu_setting"></i></span><span><?php echo __('Blog setting'); ?></span></a></li>
        <?php endif; ?>
          </ul>
        </div>
      <?php else: ?>
        <div id="left_menu" class="sidemenu">
          <ul>
            <?php if (\Fc2blog\Config::get('USER.REGIST_SETTING.FREE') == \Fc2blog\Config::get('USER.REGIST_STATUS')): ?>
              <li <?php if (\Fc2blog\App::isActiveMenu('users/register')) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Users', 'action'=>'register')); ?>"><span><i class="sidemenu_editor"></i></span><span><?php echo __('User registration'); ?></span></a></li>
            <?php endif; ?>
            <li <?php if (\Fc2blog\App::isActiveMenu('users/login')) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Users', 'action'=>'login')); ?>"><span></span><span><?php echo __('Login'); ?></span></a></li>
          </ul>
        </div>
      <?php endif; ?>

  <?php if ($this->isLogin()): ?>
      <div id="right_menu" class="sidemenu">
        <ul>
            <li class="list_titile"><?php echo __('User Menu'); ?></li>
            <li <?php if (\Fc2blog\App::isActiveMenu('blogs/index')) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Blogs', 'action'=>'index')); ?>"><span><i class="sidemenu_myblog"></i></span><span><?php echo __('List of blogs'); ?></span></a></li>
            <li <?php if (\Fc2blog\App::isActiveMenu(array('users/edit', 'users/withdrawal'))) echo 'class="selected"'; ?>><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Users', 'action'=>'edit')); ?>"><span><i class="sidemenu_setting"></i></span><span><?php echo __('User setting'); ?></span></a></li>
            <li><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Users', 'action'=>'logout')); ?>"><span><i class="sidemenu_logout"></i></span><span><?php echo __('Logout'); ?></span></a></li>
            <?php if (\Fc2blog\Config::get('DEBUG')!=0): ?>
              <li class="list_titile">デバッグ用</li>
              <li><a class="touch" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Users', 'action'=>'index')); ?>"><span><i class="sidemenu_history"></i></span><span>ユーザー一覧</span></a></li>
            <?php endif; ?>
          <li class="list_titile"><?php echo __('Blog name'); ?></li>
          <?php $blogSelectList = \Fc2blog\Model\Model::load('Blogs')->getSelectList($this->getUserId()); ?>
          <?php foreach ($blogSelectList as $key => $value) : ?>
            <li<?php if($key==$this->getBlogId()): ?> class="selected"<?php endif; ?>><a href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Blogs', 'action'=>'choice', 'blog_id'=>$key)); ?>"><span><i class="sidemenu_home"></i></span><span><?php echo $value; ?></span></a></li>
          <?php endforeach; ?>
          <li <?php if (\Fc2blog\App::isActiveMenu('blogs/create')) echo 'class="selected"'; ?>><a href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Blogs', 'action'=>'create')); ?>"><span><i class="sidemenu_myblog"></i></span><span><?php echo __('Create a new blog'); ?></span></a></li>
        </ul>
      </div>
  <?php endif; ?>
</div><!--#wrapper_all-->

<div id="main-overlay" style=""></div>
</body>
</html>
