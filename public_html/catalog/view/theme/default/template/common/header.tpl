<!-- Header -->
<header>
    <div class="container">
        <h1><a title="tamdaexpress.eu" href="/">TamdaExpress</a></h1>

        <?php require_once 'header_user.tpl'; ?>

        <form class="search" id="grocerySearchField" action="/" method="post" novalidate="novalidate"
              data-name="productSearch-form">
            <fieldset>
                <input type="text" placeholder="Hledat název, kód nebo kategorii" data-warehouse-id="8791" name="query"
                       id="frm-productSearch-form-query" data-autocomplete-url="/?route=product/search/autocomplete"
                       class="onClickSelect">
                <button type="submit" name="search" id="frm-productSearch-form-search">Hledat</button>
            </fieldset>
            <div><input type="hidden" name="do" value="productSearch-form-submit"><!--[if IE]><input type=IEbug disabled
                                                                                                     style="display:none">
                <![endif]--></div>
        </form>

        <div class="form close form-login">
            <a href="/?do=facebookLogin&amp;backlink=6zhyo" class="fb" rel="nofollow">Přihlásit přes facebook</a>

            <p class="center">nebo</p>

            <form action="/" method="post" novalidate="novalidate" id="frm-loginForm" data-name="loginForm">
                <label for="frm-loginForm-email"><input type="email" name="email" placeholder="Váš e-mail"
                                                        title="Váš e-mail" id="frm-loginForm-email" required
                                                        data-nette-rules='[{"op":":filled","msg":"Pole \"E-mail\" je povinné."},{"op":":email","msg":"Pole \"E-mail\" neobsahuje platný e-mail."}]'
                                                        class="user"></label>
                <label for="frm-loginForm-password"><input type="password" name="password" placeholder="Heslo"
                                                           title="Heslo" id="frm-loginForm-password" required
                                                           data-nette-rules='[{"op":":filled","msg":"Pole \"Heslo\" je povinné."}]'
                                                           class="password"></label>

                <p class="login-btn c">
                    <a href="javascript:;" id="forgottenPassword-opener">Zapomenuté heslo?</a>
                    <button type="submit" class="btn">Přihlásit se</button>
                </p>
                <div><input type="hidden" name="do" value="loginForm-submit"></div>
            </form>

        </div>
        <div class="form close form-registration">
            <a href="/?do=facebookLogin&amp;backlink=6zhyo" class="fb" rel="nofollow">Zaregistrovat přes facebook</a>

            <p class="center">nebo</p>

            <form class="registration-form" action="/" method="post" novalidate="novalidate"
                  id="frm-userRegistrationForm" data-name="userRegistrationForm">
                <label for="frm-userRegistrationForm-email"><input class="user" type="email" name="email"
                                                                   placeholder="E-mail" title="E-mail"
                                                                   id="frm-userRegistrationForm-email" required
                                                                   data-nette-rules='[{"op":":filled","msg":"Pole \"E-mail\" je povinné."},{"op":":email","msg":"Pole \"E-mail\" neobsahuje platný e-mail."}]'></label>
                <label for="frm-userRegistrationForm-password"><input class="password" type="password" name="password"
                                                                      autocomplete="off" placeholder="Heslo"
                                                                      title="Heslo"
                                                                      id="frm-userRegistrationForm-password" required
                                                                      data-nette-rules='[{"op":":filled","msg":"Pole \"Heslo\" je povinné."},{"op":":minLength","msg":"Pole \"Heslo\" musí mít minimální délku 4 znaky.","arg":4}]'></label>
                <label for="frm-userRegistrationForm-passwordVerify"><input class="password" type="password"
                                                                            name="passwordVerify" autocomplete="off"
                                                                            placeholder="Heslo pro kontrolu"
                                                                            title="Heslo pro kontrolu"
                                                                            id="frm-userRegistrationForm-passwordVerify"
                                                                            data-nette-rules='[{"op":":filled","rules":[{"op":":equal","msg":"Hesla se musí shodovat.","arg":{"control":"password"}}],"control":"password"}]'></label>

                <p class="login-btn c">
                    <button type="submit" class="btn">Zaregistrovat se</button>
                </p>
                <div><input type="hidden" name="do" value="userRegistrationForm-submit"></div>
            </form>
        </div>
    </div>
</header>
<!-- End Header -->