<div id="forgotten-password" class="popup lostPassword forgotten-password">
  <a href="javascript:;" class="close" title="zavřít">&times;</a>

  <div class="content overlay-on-ajax">
    <strong class="heading">Zapomenuté heslo na Rohlik.cz</strong>

    <p>Pro obnovení hesla zadejte email na který jste se zaregistrovali.</p>

    <form action="/" method="post" novalidate="novalidate" id="frm-forgottenPasswordForm" data-name="forgottenPasswordForm" class="ajax">
      <div class="input">
        <input type="text" name="email" placeholder="E-mail" title="E-mail" id="frm-forgottenPasswordForm-email" required data-nette-rules='[{"op":":filled","msg":"Vyplňte prosím svůj email"},{"op":":email","msg":"Pole \"E-mail\" neobsahuje platný e-mail."}]'>
        <input type="submit" name="_submit" id="frm-forgottenPasswordForm-submit" value="Odeslat">
      </div>
    <div><input type="hidden" name="_token_" id="frm-forgottenPasswordForm-_token_" value="z0k6vga6by9eQ+Y8iNjcNUmsN1eAMCwgaNz3M="><input type="hidden" name="do" value="forgottenPasswordForm-submit"><!--[if IE]><input type=IEbug disabled style="display:none"><![endif]--></div>
</form>

  </div>
</div>