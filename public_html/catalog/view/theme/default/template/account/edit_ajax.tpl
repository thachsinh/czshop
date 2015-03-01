<form action="/index.php?route=account/ajax/edit" method="post" novalidate="novalidate" id="frm-userForm" data-name="userForm" class="editForm ajax">
  <div class="row">
    <label class="name" for="frm-userForm-firstname"><?php echo $entry_firstname; ?><br><input type="text" name="firstname" placeholder="<?php echo $entry_firstname; ?>" title="<?php echo $entry_firstname; ?>" id="frm-userForm-name" required data-nette-rules='[{"op":":length","msg":"<?php echo $error_firstname; ?>","arg":[1,32]}]' value="<?php echo $firstname; ?>"></label>
  </div>
  <div class="row">
    <label class="name" for="frm-userForm-lastname"><?php echo $entry_lastname; ?><br><input type="text" name="lastname" placeholder="<?php echo $entry_lastname; ?>" title="<?php echo $entry_lastname; ?>" id="frm-userForm-lastname" required data-nette-rules='[{"op":":length","msg":"<?php echo $error_lastname; ?>", "arg":[1,32]}]' value="<?php echo $lastname; ?>"></label>
  </div>
  <div class="row">
    <label class="name" for="frm-userForm-email"><?php echo $entry_email; ?><br><input type="text" name="email" placeholder="<?php echo $entry_email; ?>" title="<?php echo $entry_email; ?>" id="frm-userForm-email" required data-nette-rules="{op:':filled',rules:[{op:':email',msg:'<?php echo $error_email; ?>'}],control:'email'}" value="<?php echo $email; ?>"></label>
  </div>
  <div class="row">
    <label for="frm-userForm-phone"><?php echo $entry_telephone; ?><br><input type="text" name="telephone" id="frm-userForm-phone" required data-nette-rules='[{"op":":filled","msg":"<?php echo $error_telephone; ?>"},{"op":":filled","rules":[{"op":"FrontModule\\Forms\\UserForm::checkPhoneNumber","msg":"<?php echo $error_telephone_pattern; ?>"}],"control":"telephone"}]' value="<?php echo $telephone ?>"></label>
  </div>
  <button type="submit" name="send" id="frm-userForm-send" value="save"><?php echo $entry_save; ?></button>
  <p class="back"><a class="ajax" href="/index.php?route=account/ajax/view">« <?php echo $entry_cancel; ?></a></p>
</form>