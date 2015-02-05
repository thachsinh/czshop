<!DOCTYPE html>
<html class="no-js " dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# rohlik: http://ogp.me/ns/fb/rohlik#">
    <meta charset="utf-8">
    <script type="text/javascript">try {
        } catch (err) {
            console.log(err);
        }</script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo $title; ?></title>
    <?php if ($description): ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php endif; ?>
    <?php if ($keywords): ?>
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php endif; ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if ($icon): ?>
    <link href="<?php echo $icon; ?>" rel="icon" />
    <?php endif; ?>
    <?php foreach ($links as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <meta name="author" content="Rohlik.cz tým">
    <meta name="revisit-after" content="1 Day">
    <meta property="og:site_name" content="Rohlik.cz">
    <meta property="og:title" content="Online supermarket Rohlik.cz">
    <meta property="og:image" content="https://www.rohlik.cz/images/social/share-huge.jpg">
    <?php if ($description): ?>
    <meta property="og:description" content="<?php echo $description; ?>">
    <?php endif; ?>
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.rohlik.cz/">
    <meta property="fb:app_id" content="615828701867578">
    <meta property="fb:admins" content="666678592">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/images/touch-icons/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="/images/touch-icons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/images/touch-icons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/touch-icons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/images/touch-icons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/images/touch-icons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/images/touch-icons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/touch-icons/apple-touch-icon-152x152.png">
    <link rel="canonical" href="https://www.rohlik.cz/">
    <meta name="viewport" content="width=980">
    <!--[if lte IE 8]>
    <script src="/js/html5shiv.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="static/js/main.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700,800&subset=latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="static/css/style.css">
</head>
<body class="next " data-basepath="" data-baseurl="https://www.rohlik.cz" data-cookie-domain=".rohlik.cz">
<script>document.body.className += ' js'</script>
<div id="snippet-requestAddress-"></div>
<!-- Header -->
<header>
    <div class="container">
        <h1><a title="tamdaexpress.eu" href="/">TamdaExpress</a></h1>

        <?php require_once 'header_user.tpl'; ?>

        <form class="search" id="grocerySearchField" action="/" method="post" novalidate="novalidate"
              data-name="productSearch-form">
            <fieldset>
                <input type="text" placeholder="Hledat název, kód nebo kategorii" data-warehouse-id="8791" name="query"
                       id="frm-productSearch-form-query" data-autocomplete-url="https://autocomplete.rohlik.cz/"
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
<!-- Nav -->
<nav>
    <ul>
        <li><a href="/me-oblibene" title="Mé oblíbené"><span class="fav">&hearts;</span></a></li>
        <li><a href="/akcni-zbozi" title="Akční zboží"><span class="disc">Akce</span></a></li>
        <li><a href="/c133137-farmarske-potraviny"> Farmářské<i></i>
                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c133233-maso-a-uzeniny" class="maso">Maso a uzeniny</a></li>
                    <li><a href="/c133237-napoje" class="napoje">Nápoje</a></li>
                    <li><a href="/c133239-trvanlive" class="trvanlive">Trvanlivé</a></li>
                    <li><a href="/c133149-mlecne-vyrobky-a-vejce" class="mlecne-vyrobky-a-vejce">Mléčné výrobky a
                            vejce</a></li>
                    <li><a href="/c133167-bio-produkty" class="bio-produkty">Bio produkty</a></li>
                    <li><a href="/c133219-bezlepkove-vyrobky" class="bezlepkove-vyrobky">Bezlepkové výrobky</a></li>
                    <li><a href="/c133187-raw" class="raw">Raw</a></li>
                    <li><a href="/c133189-superfood" class="superfood">Superfood</a></li>
                    <li><a href="/c133155-sladke-a-slane" class="sladke-a-slane">Sladké a slané</a></li>
                    <li><a href="/c133183-farmarske-lahudky" class="tofu-a-pomazanky">Farmářské lahůdky</a></li>
                </ul>
            </div>
        </li>
        <li><a href="/c75403-cerstve-potraviny"> Čerstvé<i></i>

                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c133285-ovoce-a-zelenina" class="ovoce-a-zelenina">Ovoce a zelenina</a></li>
                    <li><a href="/c75455-pecivo" class="pecivo">Pečivo</a></li>
                    <li><a href="/c75413-mlecne-vyrobky-a-vejce" class="mlecne-vyrobky-a-vejce">Mléčné výrobky a
                            vejce</a></li>
                    <li><a href="/c75437-maso-ryby" class="maso-ryby">Maso, ryby</a></li>
                    <li><a href="/c75447-masne-vyrobky-uzeniny" class="masne-vyrobky-uzeniny">Masné výrobky, uzeniny</a>
                    </li>
                    <li><a href="/c75435-lahudky" class="lahudky">Lahůdky</a></li>
                </ul>
            </div>
        </li>
        <li><a href="/c75471-trvanlive-potraviny"> Trvanlivé<i></i>

                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c133303-spajzka" class="spajzka">Špajzka</a></li>
                    <li><a href="/c133329-snidane" class="snidane">Snídaně</a></li>
                    <li><a href="/c75519-mrazene" class="mrazene">Mražené</a></li>
                    <li><a href="/c75699-konzervovane" class="konzervovane">Konzervované</a></li>
                    <li><a href="/c75493-mezinarodni-kuchyne" class="mezinarodni-kuchyne">Mezinárodní kuchyně</a></li>
                </ul>
            </div>
        </li>
        <li><a href="/c75591-sladke-a-slane"> Sladké a slané<i></i>

                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c133129-chipsy-a-snacky" class="chipsy-a-snacky">Chipsy a snacky</a></li>
                    <li><a href="/c133127-suche-plody-a-orisky" class="suche-plody-a-orisky">Suché plody a oříšky</a>
                    </li>
                    <li><a href="/c133327-susenky-a-cokotycinky" class="susenky-a-cokotycinky">Sušenky a čokotyčinky</a>
                    </li>
                    <li><a href="/c133281-cokolady" class="cokolady">Čokolády</a></li>
                    <li><a href="/c133311-bonbony-a-zvykacky" class="bonbony-a-zvykacky">Bonbóny a žvýkačky</a></li>
                    <li><a href="/c75593-bonboniery" class="bonboniery">Bonboniéry</a></li>
                </ul>
            </div>
        </li>
        <li><a href="/c133369-ochutnejte-svet" class="ochutnejte-svet"> Ochutnejte svět<i></i>

                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c133371-cukrovinky" class="cukrovinky">Cukrovinky</a></li>
                    <li><a href="/c133373-medy" class="medy-dzemy">Medy</a></li>
                    <li><a href="/c133375-nakladane-dobroty" class="nakladane">Nakládané dobroty</a></li>
                    <li><a href="/c133377-napoje" class="napoje">Nápoje</a></li>
                    <li><a href="/c133379-omacky-a-octy" class="octy-omacky">Omáčky a octy</a></li>
                    <li><a href="/c133381-testoviny" class="testoviny">Těstoviny</a></li>
                </ul>
            </div>
        </li>
        <li><a href="/c133331-specialni" class="specialni"> Speciální<i></i>

                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c133401-bio-vyrobky" class="bio-produkty">Bio výrobky</a></li>
                    <li><a href="/c133405-raw" class="raw">Raw</a></li>
                    <li><a href="/c133407-superfood" class="superfood">Superfood</a></li>
                    <li><a href="/c133411-bezlepkove-vyrobky" class="bezlepkove-vyrobky">Bezlepkové výrobky</a></li>
                    <li><a href="/c87537-sojove-produkty-a-zdrava-vyziva" class="sojove-produkty-a-zdrava-vyziva">Sójové
                            produkty a zdravá výživa</a></li>
                    <li><a href="/c87113-vitaminy-a-doplnky-stravy" class="vitaminy-a-doplnky-stravy">Vitamíny a doplňky
                            stravy</a></li>
                </ul>
            </div>
        </li>
        <li><a href="/c75533-napoje"> Nápoje<i></i>

                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c75537-mineralni-a-stolni-vody" class="mineralni-a-stolni-vody">Minerální a stolní
                            vody</a></li>
                    <li><a href="/c75543-limonady-energy-a-sirupy" class="limonady-energy-a-sirupy">Limonády, energy a
                            sirupy</a></li>
                    <li><a href="/c133125-dzusy-a-ovocne-napoje" class="dzusy-a-ovocne-napoje">Džusy a ovocné nápoje</a>
                    </li>
                    <li><a href="/c75553-caj" class="caj">Čaj</a></li>
                    <li><a href="/c75551-kava" class="kava">Káva</a></li>
                    <li><a href="/c79105-ostatni-teple-napoje" class="ostatni-teple-napoje">Ostatní teplé nápoje</a>
                    </li>
                </ul>
            </div>
        </li>
        <li><a href="/c75609-drogerie-a-domacnost"> Drogerie a domácnost<i></i>

                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c133323-pece-o-plet-a-telo" class="pece-o-plet-a-telo">Péče o pleť a tělo</a></li>
                    <li><a href="/c75617-vlasova-kosmetika" class="vlasova-kosmetika">Vlasová kosmetika</a></li>
                    <li><a href="/c133325-cistici-a-praci-prostredky" class="cistici-prostredky">Čistící a prací
                            prostředky</a></li>
                    <li><a href="/c75625-ustni-hygiena" class="ustni-hygiena">Ústní hygiena</a></li>
                    <li><a href="/c75663-hygienicke-potreby" class="hygienicke-potreby">Hygienické potřeby</a></li>
                    <li><a href="/c87107-domaci-potreby" class="domaci-potreby">Domácí potřeby</a></li>
                    <li><a href="/c75693-zvirata" class="zvirata">Zvířata</a></li>
                    <li><a href="/c133471-darky">Dárky</a></li>
                </ul>
            </div>
        </li>
        <li><a href="/c75685-dite"> Dítě<i></i>

                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c75691-pleny-a-ubrousky" class="pleny-a-ubrousky">Pleny a ubrousky</a></li>
                    <li><a href="/c75687-vyziva" class="vyziva">Výživa</a></li>
                    <li><a href="/c75689-detska-kosmetika" class="detska-kosmetika">Dětská kosmetika</a></li>
                </ul>
            </div>
        </li>
        <li><a href="/c133337-alkohol-a-tabak"> Alkohol a tabák<i></i>

                <div class="clr"></div>
            </a>

            <div class="category">
                <ul>
                    <li><a href="/c75569-pivo" class="pivo">Pivo</a></li>
                    <li><a href="/c75557-vino" class="vino">Víno</a></li>
                    <li><a href="/c75573-lihoviny" class="tvrdy-alkohol">Lihoviny</a></li>
                    <li><a href="/c89299-cigarety" class="cigarety">Cigarety</a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>