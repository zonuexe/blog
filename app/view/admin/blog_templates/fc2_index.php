<header><h2><?php echo __('FC2 Template list'); ?>[<?php echo \Fc2blog\Config::get('DEVICE_NAME.' . $request->get('device_type')); ?>]</h2></header>

<?php if (!empty($templates)): ?>
  <?php foreach ($templates as $template) : ?>
    <table class="templates"><tbody>
      <tr>
        <td rowspan="3" class="thumb"><img src="<?php echo $template['image']; ?>" /></td>
        <td><?php echo __('Name'); ?> : <?php echo $template['name']; ?></td>
      </tr>
      <tr><td><?php echo __('Summary'); ?> : <?php echo $template['discription']; ?></td></tr>
      <tr><td class="btn">
        <a class="admin_common_btn create_btn" href="<?php echo \Fc2blog\App::userURL(array('controller'=>'Entries', 'action'=>'preview', 'blog_id'=>$this->getBlogId(), 'fc2_id'=>$template['id'], 'device_type'=>$request->get('device_type')), false, true); ?>" target="_blank"><?php echo __('Preview'); ?></a>
        <a class="admin_common_btn create_btn" href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'blog_templates', 'action'=>'create', 'fc2_id'=>$template['id'], 'device_type'=>$request->get('device_type'), 'sig'=>\Fc2blog\Web\Session::get('sig'))); ?>"><?php echo __('Download'); ?></a>
      </td></tr>
    </tbody></table>
  <?php endforeach; ?>
<?php else: ?>
  <p><?php echo __('FC2 template can not be found'); ?></p>
<?php endif; ?>

<?php $this->display('Common/paging.php', array('paging' => $paging)); ?>

