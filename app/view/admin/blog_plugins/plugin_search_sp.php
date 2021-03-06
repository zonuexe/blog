<header><h1 class="sh_heading_main_b"><?php echo __('Plugin search'); ?></h1></header>

<?php $user_id = $this->getUserId(); ?>
<?php $devices = \Fc2blog\Config::get('DEVICE_NAME'); ?>
<?php $device_key = \Fc2blog\Config::get('DEVICE_FC2_KEY.' . $request->get('device_type')); ?>
<h2><span class="h2_inner"><?php echo $devices[$request->get('device_type')]; ?></span></h2>

<ul class="link_list">
  <?php foreach($plugins as $plugin): ?>
<!--
  <li class="link_list_item common_next_link"><?php echo th($plugin['title'], 20); ?><div><a class="btn_contents touch" href="<?php echo \Fc2blog\Web\Html::url(array('action'=>'download', 'id'=>$plugin['id'], 'category'=>$request->get('category'))); ?>"><?php echo __('Add'); ?></a></div></li>
-->
  <li class="link_list_item common_next_link">
    <h4><?php echo th($plugin['title'], 20); ?></h4>
    <p><?php echo th($plugin['body'], 20); ?></p>
    <div class="parallel_btn">
      <a class="btn_contents touch" href="<?php echo \Fc2blog\Web\Html::url(array('action'=>'download', 'id'=>$plugin['id'], 'category'=>$request->get('category'), 'sig'=>\Fc2blog\Web\Session::get('sig'))); ?>"><?php echo __('Add'); ?></a>
      <a class="btn_contents touch" href="<?php echo \Fc2blog\App::userURL(array('controller'=>'entries', 'action'=>'preview', 'blog_id'=>$this->getBlogId(), 'plugin_id'=>$plugin['id'], 'category'=>$request->get('category'), $device_key=>1), false, true); ?>" target="_blank"><?php echo __('Preview'); ?></a>
    </div>
  </li>
  <?php endforeach; ?>
</ul>

<?php $this->display('Common/paging.php', array('paging' => $paging)); ?>

