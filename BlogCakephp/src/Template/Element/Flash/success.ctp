<?php
/* if ($message != '') {?>
  <div class="message success">
  <script type="text/javascript"> $(document).ready(function () {
  $(".message").delay(7000).slideUp("slow");
  });</script>
  <?php echo $message; ?></div>
  <?php
  }?> */

if ($message) {
    $message = (array) $message;
    foreach ($message as $m) {
        ?>
        <script >
            $(function () {
                successAlert('<?php echo h($m); ?>');
            });
        </script>
        <?php
    }
}	