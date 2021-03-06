<?php
  $blog_id = $this->getBlogId();
  $request = \Fc2blog\Web\Request::getInstance();

  $entry_categories = $request->get('entry_categories', array('category_id' => array()));
  $categories = \Fc2blog\Model\Model::load('Categories')->getList($blog_id);
?>

<table>
  <tbody>
    <tr>
      <th><?php echo __('Categories'); ?></th>
      <td>
        <ul id="sys-category-list">
          <?php $level = 1; ?>
          <?php foreach($categories as $category): ?>

            <?php if ($level<$category['level']): ?>
              <?php $level = $category['level']; ?>
              <li><ul>
            <?php endif; ?>

            <?php if ($level>$category['level']): ?>
              <?php for (;$level>$category['level'];$level--): ?>
                </ul>
              <?php endfor; ?>
            <?php endif; ?>

            <?php if ($level==$category['level']): ?>
              <li <?php if (in_array($category['id'], $entry_categories['category_id'])) : ?>class="active"<?php endif; ?>>
                <input id="sys-entry-categories-id-<?php echo $category['id']; ?>"
                       type="checkbox" name="entry_categories[category_id][]"
                       value="<?php echo $category['id']; ?>"
                       <?php if (in_array($category['id'], $entry_categories['category_id'])) : ?>checked="checked"<?php endif; ?>
                       onclick="categoryChange(this);"
                />
                <label for="sys-entry-categories-id-<?php echo $category['id']; ?>"><?php echo h($category['name']); ?></label>
              </li>
            <?php endif; ?>

          <?php endforeach; ?>
          <?php for (;$level>1;$level--): ?>
            </li></ul>
          <?php endfor; ?>
        </ul>
      </td>
    </tr>
    <tr>
      <th><?php echo __('Category add'); ?></th>
      <td>
        <dl class="form_item">
          <dt><label><?php echo __('Parent category'); ?></label></dt>
          <dd>
            <select id="sys-category-add-parent">
              <option value="0"><?php echo __('Not selected'); ?></option>
                <?php foreach ($categories as $category): ?>
                  <?php echo '<option value="' . $category['id'] . '"' . ($category['id']==1 ? ' disabled="disabled"' : '') . '>' . str_repeat('&nbsp;&nbsp;&nbsp;', $category['level']-1) . h($category['name']) . '</option>'; ?>
                <?php endforeach; ?>
            </select>
          </dd>
          <dt><label><?php echo __('Category name'); ?></label></dt>
          <dd>
            <input type="text" id="sys-category-add-name" placeholder="<?php echo __('Category name'); ?>" />
            <input id="sys-category-add" type="button" value="<?php echo __('Add'); ?>" />
          </dd>
        </dl>
      </td>
    </tr>
  </tbody>
</table>

<script>
// カテゴリーのチェックボックスクリック時 強調表示と親階層のチェック処理
function categoryChange(target){
  var target = $(target);
  target.prop('checked') ? target.parent().addClass('active') : target.parent().removeClass('active');

  // 以下の処理は親階層のチェック処理を行う
  var parent = target.parent('li');

  // 親全てチェック
  if (target.prop('checked')) {
    while((parent=parent.parent('ul').parent('li').prev('li')).length){
      parent.find('input[type=checkbox]').prop('checked', true).parent().addClass('active');
    }
    return ;
  }

  // 同一階層にチェック済みがない場合 １つ上の親のチェックを外す
  while(!parent.siblings().children('input[type=checkbox]:checked').length){
    parent = parent.parent('ul').parent('li').prev('li');
    if (!parent.length) {
      return ;
    }
    parent.find('input[type=checkbox]').prop('checked', false).parent().removeClass('active');
  }
}
$(function(){
  // セレクトボックスにカテゴリを追加
  var addSelect = function(category){
    if (category.parent_id == 0){
      // 末尾に追加
      $('#sys-category-add-parent').append($('<option>').html(category.name).val(category.id));
      return ;
    }
    // 親の子供の末尾に追加(目印になるものが無いので空白の個数便りに探しだしている)
    var option = $('#sys-category-add-parent > option[value=' + category.parent_id + ']');
    var spaceCount = option.html().split('&nbsp;&nbsp;&nbsp;').length;
    var space = '';
    for(var i = 0;i < spaceCount;i++){
      space += '&nbsp;&nbsp;&nbsp;';
    }
    do{
      if (option.next('option').length == 0) {
        $('#sys-category-add-parent').append($('<option>').html(space + category.name).val(category.id));
        break;
      }
      option = option.next('option');
      if (option.html().split(space).length - 1 == 0) {
        option.prev().after($('<option>').html(space + category.name).val(category.id));
        break ;
      }
    } while (option.length);
  };

  // カテゴリ一覧にカテゴリを追加
  var addList = function(category){
    var html = '<li class="active">';
    html    += '  <input id="sys-entry-categories-id-' + category.id + '" type="checkbox" name="entry_categories[category_id][]" value="' + category.id + '" checked="checked" onclick="categoryChange(this);" />';
    html    += '  <label for="sys-entry-categories-id-' + category.id + '">' + category.name + '</label>';
    html    += '</li>';
    if (category.parent_id == 0){
      $('#sys-category-list').append($(html).hide());
      $('#sys-entry-categories-id-' + category.id).parent().slideDown('fast');
    } else {
      var li = $('#sys-entry-categories-id-' + category.parent_id).parent();
      if (!li.next().children('ul').length) {
        // 子無しの場合は子を入れるulを追加する
        li.after('<li><ul></ul></li>');
      }
      // 末尾に追加する
      li.next().children('ul').append($(html).hide());
      $('#sys-entry-categories-id-' + category.id).parent().slideDown('fast');
    }
  };

  // Enterキーでカテゴリ追加
  $('#sys-category-add-name').keypress(function(e){
    if ((e.which && e.which===13) || (e.keyCode && e.keyCode===13)) {
      $('#sys-category-add').click();
    }
  });

  // カテゴリ追加処理
  $('#sys-category-add').click(function(){
    var parent_id = $('#sys-category-add-parent').val();
    var name = $('#sys-category-add-name').val();
    if (name=='') {
      alert('<?php echo __('Please enter the category name'); ?>');
      return ;
    }
    if ($('#sys-category-add').prop('disabled')) {
      return ;
    }

    $('#sys-category-add').attr('disabled', 'disabled');
    $('#sys-category-add').val('通信中');

    $.ajax({
      url: '<?php echo \Fc2blog\Web\Html::url(array('controller'=>'Categories', 'action'=>'ajax_add')); ?>',
      type: 'POST',
      data: {category: {parent_id: parent_id, name: name}, sig: "<?php echo \Fc2blog\Web\Session::get('sig'); ?>"},
      dataType: 'json',
      success: function(json){
        if (json.status!=1) {
          alert(json.error.name);
        }else{
          addSelect(json.category);    // セレクトボックスにカテゴリを追加
          addList(json.category);      // 一覧にカテゴリを追加
        }

        $('#sys-category-add-name').val('');
        $('#sys-category-add').removeAttr('disabled');
        $('#sys-category-add').val('<?php echo __('Add'); ?>');
      }
    });
    return false;
  });
});
</script>

