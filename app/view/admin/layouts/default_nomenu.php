<?php header("Content-Type: text/html; charset=UTF-8"); ?>
<!DOCTYPE html>
<html lang="<?php echo \Fc2blog\Config::get('LANG'); ?>">
<head>
  <meta charset="utf-8">
  <title><?php echo \Fc2blog\Web\Session::get('blog_id'); ?></title>
  <link rel="icon" href="https://static.fc2.com/share/image/favicon.ico">
  <link rel="stylesheet" href="/css/normalize.css" type="text/css" media="all">
  <link rel="stylesheet" href="/css/admin-fc2.css" type="text/css" media="all">
  <link rel="stylesheet" href="/css/admin-form.css" type="text/css" media="all">
  <link rel="stylesheet" href="/css/common.css" type="text/css" media="all">
  <link rel="stylesheet" href="/css/main.css" type="text/css" media="all">
  <link rel="stylesheet" href="/css/admin_style.css" type="text/css" media="all">

  <?php echo $this->includeCSS(); ?>

  <script type="text/javascript" src="/js/jquery/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="/js/jquery/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="/js/jquery/jquery-ui/1.9.2/jquery-ui.min.js"></script>
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

  <?php echo $this->includeJS(); ?>

</head>
<body>

  <header class="clear">
    <div>
      <?php $lang = \Fc2blog\Config::get('LANG'); ?>
      <div id="switch_lang">
        <select id="sys-language-setting" onchange="location.href=common.fwURL('common', 'lang', {lang: $(this).val()});">
          <option value="ja" <?php if ($lang=='ja') : ?>selected="selected"<?php endif; ?>>日本語</option>
          <option value="en" <?php if ($lang=='en') : ?>selected="selected"<?php endif; ?>>English</option>
        </select>
      </div>
    </div>
  </header>

  <article>
    <article id="main-contents" style="margin-left: auto; margin-right: auto; float: none;">
      <?php $this->display('Common/flash_message.php', array('messages'=>$this->removeMessage())); ?>
      <?php $this->display($fw_template); ?>
    </article>
  </article>

</body>
</html>
