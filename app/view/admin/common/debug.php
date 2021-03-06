<section>
  <h2>Debug</h2>

  <?php $logs = \Fc2blog\Debug::getLogs(); ?>
  <h3>Logs</h3>
  <ul class="debug">
    <?php foreach ($logs as $log): ?>
      <li class="<?php echo $log['class']; ?>">
        <?php if(!empty($log['file'])): ?>
          <p class="debug-codicil"><?php echo $log['file']; ?>:<?php echo $log['line']; ?></p>
        <?php endif; ?>
        <p class="debug-message"><?php echo $log['msg']; ?></p>
        <?php if($log['params']): ?><pre><?php var_dump($log['params']); ?></pre><?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php \Fc2blog\Web\Session::start(); ?>
  <?php if(!empty($_SESSION)): ?>
    <h3 id="sys-debug-session">Session &gt;&gt;</h3>
    <pre style="display: none;"><?php var_dump($_SESSION); ?></pre>
  <?php endif; ?>

  <?php if(!empty($_COOKIE)): ?>
    <h3 id="sys-debug-cookie">Cookie &gt;&gt;</h3>
    <pre style="display: none;"><?php var_dump($_COOKIE); ?></pre>
  <?php endif; ?>

  <script>
  $(function(){
    $('#sys-debug-session').on('click', function(){
      $(this).next().toggle();
    });
    $('#sys-debug-cookie').on('click', function(){
      $(this).next().toggle();
    });
  });
  </script>

</section>

