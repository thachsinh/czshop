<form action="/index.php?route=account/ajax/password" method="post" novalidate="" id="frm-changePasswordForm" data-name="changePasswordForm" class="editForm ajax">

  <div class="row">
    <label class="name" for="frm-changePasswordForm-newPassword"><font><font><?php echo $entry_password; ?></font></font><br><input type="password" name="newPassword" id="frm-changePasswordForm-newPassword" required="" data-nette-rules='[{"op":":length","msg":"<?php echo $error_password; ?>","arg":[4,20]}]'></label>
  </div>

  <div class="row">
    <label class="name" for="frm-changePasswordForm-newPassword2"><font><font><?php echo $entry_confirm; ?></font></font><br><input type="password" name="newPassword2" id="frm-changePasswordForm-newPassword2" required="" data-nette-rules='[{"op":":length","msg":"<?php echo $error_password; ?>","arg":[4,20]},{"op":":equal","msg":"<?php echo $error_confirm; ?>","arg":{"control":"newPassword"}}]'></label>
  </div>

  <button type="submit" name="send" class="btn" id="frm-changePasswordForm-send" value="save"><font><font><?php echo $entry_save; ?></font></font></button>
  <p class="back"><a class="ajax" href="/index.php?route=account/ajax/view"><font><font>«<?php echo $entry_cancel; ?></font></font></a></p>
</form>