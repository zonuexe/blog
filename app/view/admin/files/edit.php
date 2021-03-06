<header><h2><?php echo __('Edit File'); ?></h2></header>

<form method="POST" id="sys-file-form" class="admin-form" enctype="multipart/form-data">

  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo \Fc2blog\Config::get('FILE.MAX_SIZE'); ?>" />
  <input type="hidden" name="id" value="<?php echo $request->get('id'); ?>" />
  <input type="hidden" name="sig" value="<?php echo \Fc2blog\Web\Session::get('sig'); ?>" />

  <a href="<?php echo \Fc2blog\App::getUserFilePath($file, false, true); ?>" target="_blank"><img src="<?php echo \Fc2blog\App::getThumbnailPath(\Fc2blog\App::getUserFilePath($file, false, true), 600, 'w'); ?>" alt="<?php echo $file['name']; ?>" /></a>

  <?php if (isset($errors['file']['ext'])): ?>
    <p class="error"><?php echo $errors['file']['ext']; ?></p>
  <?php elseif (isset($errors['file']['file'])): ?>
    <p class="error"><?php echo $errors['file']['file']; ?></p>
  <?php endif; ?>
  <?php if (isset($errors['file']['name'])): ?><p class="error"><?php echo $errors['file']['name']; ?></p><?php endif; ?>

  <dl class="form_item">
    <dt><?php echo __('Upload file'); ?>：</dt>
    <dd>
      <?php echo \Fc2blog\Web\Html::input('file[name]', 'text', array('id'=>'sys-file-name')); ?>
      <?php echo \Fc2blog\Web\Html::input('file[file]', 'file', array('style'=>'opacity: 0; position: absolute; width: 120px;', 'onchange'=>"$('#sys-file-name').val($(this).val().split('\\\\').pop());")); ?>
      <input type="button" value="<?php echo __('File selection'); ?>" onclick="$(this).prev().click();" style="width: 120px;" />
    </dd>
  </dl>

  <p class="form-button">
    <input type="submit" value="<?php echo __('Upload'); ?>" />
  </p>

</form>


<script>
$(function(){
  // form内でEnterしてもsubmitさせない
  common.formEnterNonSubmit('sys-file-form');
});
</script>
