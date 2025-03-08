<?php if(isset($_SESSION["user_details"])): ?>
  <pre>
    <?php print_r($_SESSION["user_details"]) ?>
  </pre>
<?php endif; ?>