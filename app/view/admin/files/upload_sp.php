<header><h1 class="in_menu sh_heading_main_b"><span class="h1_title"><?php echo __('File upload'); ?></span><span class="accordion_btn"><i class="search_icon btn_icon"></i></span></h1></header>
<div id="entry_search" class="accordion_contents" style="display:none;">
  <form method="GET" id="sys-search-form">
    <input type="hidden" name="<?php echo \Fc2blog\Config::get('ARGS_CONTROLLER'); ?>" value="Files" />
    <input type="hidden" name="<?php echo \Fc2blog\Config::get('ARGS_ACTION'); ?>" value="upload" />
    <?php echo \Fc2blog\Web\Html::input('limit', 'hidden', array('default'=>\Fc2blog\App::getPageLimit('FILE'))); ?>
    <?php echo \Fc2blog\Web\Html::input('page', 'hidden', array('default'=>0)); ?>
    <?php echo \Fc2blog\Web\Html::input('order', 'hidden', array('default'=>'created_at_desc')); ?>
    <dl class="input_search">
      <dt class="lineform_text_wrap common_input_text"><?php echo \Fc2blog\Web\Html::input('keyword', 'text'); ?></dt>
      <dd class="lineform_btn_wrap"><button type="submit" class="lineform_btn touch"><?php echo __('Search'); ?></button></dd>
    </dl>
  </form>
</div>

<form method="POST" id="sys-file-form" class="admin-form" enctype="multipart/form-data">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo \Fc2blog\Config::get('FILE.MAX_SIZE'); ?>" />
  <input type="hidden" name="sig" value="<?php echo \Fc2blog\Web\Session::get('sig'); ?>" />
  <div class="btn_area">
    <div class="up_file_btn">
      <?php echo \Fc2blog\Web\Html::input('file[name]', 'text', array('id'=>'sys-file-name')); ?>
      <?php echo \Fc2blog\Web\Html::input('file[file]', 'file', array('style'=>'opacity: 0; position: absolute; width: 120px;', 'onchange'=>"$('#sys-file-name').val($(this).val().split('\\\\').pop());")); ?>
      <button type="button" class="lineform_btn touch" onclick="$(this).prev().click();" style="width: 120px;" /><?php echo __('File selection'); ?></button>
    </div>
    <?php if (isset($errors['file']['ext'])): ?>
      <p class="error"><?php echo $errors['file']['ext']; ?></p>
    <?php elseif (isset($errors['file']['file'])): ?>
      <p class="error"><?php echo $errors['file']['file']; ?></p>
    <?php endif; ?>
    <?php if (isset($errors['file']['name'])): ?><p class="error"><?php echo $errors['file']['name']; ?></p><?php endif; ?>
  </div>
  <div class="enter_btn_area">
    <button type="submit" class="enter_btn touch"><?php echo __('Upload'); ?></button>
  </div>
</form>

<?php if (!$request->get('file')): ?>
  <h2><span class="h2_inner"><?php echo __('File List'); ?></span></h2>
  <div class="file_table">
    <?php if (count($files)) : ?>
      <?php foreach($files as $file): ?>
        <a href="<?php echo \Fc2blog\Web\Html::url(array('action'=>'edit', 'id'=>$file['id'])); ?>">
          <div class="file_table_cell">
            <!--<input type="checkbox" />-->
            <?php if (in_array($file['ext'], array('jpeg', 'jpg', 'png', 'gif'))) : ?>
              <img src="<?php echo \Fc2blog\App::getUserFilePath($file, false, true); ?>" />
            <?php endif; ?>
            <span class="img_name"><?php echo th($file['name'], 30); ?></span>
          </div><!--/file_table_cell-->
        </a>
      <?php endforeach; ?>
    <?php else: ?>
      <?php echo __('The target file does not exist'); ?>
    <?php endif; ?>
  </div>

  <?php $this->display('Common/paging.php', array('paging' => $paging)); ?>
<?php endif; ?>

