<?php $blog = $this->getBlog($this->getBlogId()); ?>
<header><h1 class="sh_heading_main_b"><?php echo $blog['name']; ?></h1></header>
<div id="top_menu_wrap">
  <ul id="top_menu">
    <li class="main_btn_list touch"><a href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Entries','action'=>'create')); ?>" class="main_btn"><i class="editor_btn"></i><?php echo __('New article'); ?></a></li>
    <li class="main_btn_list touch"><a href="<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Entries', 'action'=>'index')); ?>" class="main_btn"><i class="entry_btn"></i><?php echo __('List of articles'); ?></a></li>
    <li class="main_btn_list touch"><a href="<?php echo \Fc2blog\Model\BlogsModel::getFullHostUrlByBlogId($blog['id'], \Fc2blog\Config::get('DOMAIN_USER')); ?>/<?php echo $this->getBlogId(); ?>/" target="_blank" class="main_btn"><i class="myblog_btn"></i><?php echo __('Checking the blog'); ?></a></li>
  </ul>
</div>

<header><h1 class="sh_heading_main_b"><?php echo __('Notice'); ?></h1></header>

<?php $is_notice = false; ?>
<ul class="link_list">
<?php if ($unread_count > 0) : ?>
  <?php $is_notice = true; ?>
  <li class="link_list_item">
    <a href="<?php echo \Fc2blog\Web\Html::url(array(
      'controller'   => 'Comments',
      'action'       => 'index',
      'reply_status' => \Fc2blog\Config::get('COMMENT.REPLY_STATUS.UNREAD'),
    )); ?>" class="common_next_link next_bg"><?php echo sprintf(__('There %d reviews unread comments'), $unread_count); ?></a>
  </li>
<?php endif ; ?>

<?php if ($unapproved_count > 0) : ?>
  <?php $is_notice = true; ?>
  <li class="link_list_item">
    <a href="<?php echo \Fc2blog\Web\Html::url(array(
      'controller'  => 'Comments',
      'action'      => 'index',
      'open_status' => \Fc2blog\Config::get('COMMENT.OPEN_STATUS.PENDING'),
    )); ?>" class="common_next_link next_bg"><?php echo sprintf(__('There %d reviews unapproved comment'), $unapproved_count); ?></a>
  </li>
<?php endif ; ?>

<?php if ($is_notice==false) : ?>
  <li class="no_item"><?php echo __('There is no notice'); ?></li>
<?php endif; ?>
</ul>
