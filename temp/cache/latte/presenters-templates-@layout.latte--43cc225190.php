<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Template43cc225190 extends Latte\Runtime\Template
{

	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Maturitní projekty</title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 12 */ ?>/mdb/css/bootstrap.min.css" rel="stylesheet">
        <!-- Material Design Bootstrap -->
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 14 */ ?>/mdb/css/mdb.min.css" rel="stylesheet">
        <!-- Your custom styles (optional) -->
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 16 */ ?>/mdb/css/style.css" rel="stylesheet">
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 17 */ ?>/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 18 */ ?>/basic.css" rel="stylesheet">
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 19 */ ?>/dropzone.css" rel="stylesheet">

        <link rel="icon" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 21 */ ?>/maturita_gif.gif" type="image/gif">
    </head>

    <body>

        <!-- navbar-->
        <nav class="mb-1 navbar navbar-expand-lg navbar-dark blue-gradient sticky-top">
            <a class="navbar-brand" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">Maturitní projekty</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav active-setter">
                    <li<?php if ($_tmp = array_filter([$presenter->isLinkCurrent('Homepage:default') ? 'active' : NULL,'nav-item'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>
                        <a class="nav-link " href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">Domů <span class="sr-only">(current)</span></a>
                    </li>
                    <li<?php if ($_tmp = array_filter([$presenter->isLinkCurrent('Addproject:add') ? 'active' : NULL,'nav-item'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>
<?php
		if ($user->isInRole('administrator')) {
			?>                        <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Addproject:add")) ?>">Přidat projekt</a>
<?php
		}
?>
                    </li>
                    <li <?php if ($_tmp = array_filter([$presenter->isLinkCurrent('Login:register') ? 'active' : NULL,'nav-item'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>
<?php
		if ($user->isInRole('administrator')) {
			?>                        <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Login:register")) ?>">Registrovat uživatele</a>
<?php
		}
?>
                    </li>
                    <li <?php if ($_tmp = array_filter([$presenter->isLinkCurrent('Admin:projects') ? 'active' : NULL,'nav-item'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>
<?php
		if ($user->isInRole('administrator')) {
			?>                        <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Admin:projects")) ?>">Přehled projektu</a>
<?php
		}
?>
                    </li>
                    <li <?php if ($_tmp = array_filter([$presenter->isLinkCurrent('Admin:users') ? 'active' : NULL,'nav-item'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>
<?php
		if ($user->isInRole('administrator')) {
			?>                        <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Admin:users")) ?>">Přehled uživatelů</a>
<?php
		}
?>
                    </li>
                </ul>
            </div>

            <!--login or logged settings-->
            <div class="navbar-collapse collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
<?php
		if ($user->isLoggedIn()) {
?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-light" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="fa fa-user"></i><?php echo LR\Filters::escapeHtmlText($user->getRoles()[0]) /* line 59 */ ?> <?php
			echo LR\Filters::escapeHtmlText($user->getIdentity()->username) /* line 59 */ ?></a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-info" aria-labelledby="navbarDropdownMenuLink-4">
                                <a class="dropdown-item waves-effect waves-light" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Userpage:userpage")) ?>">Můj účet</a>
                                <a class="dropdown-item waves-effect waves-light" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Logout:")) ?>">odhlásit</a>
                            </div>
                        </li>
<?php
		}
		else {
			?>                        <li<?php if ($_tmp = array_filter([$presenter->isLinkCurrent('Login:login') ? 'active' : NULL,'nav-item'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>
                            <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Login:login")) ?>">Přihlásit</a>
                        </li>
<?php
		}
?>
                </ul>
            </div>
        </nav>
        <!--/navbar-->
        <!-- Start your project here-->
        <main>
<?php
		$this->renderBlock('content', $this->params, 'html');
?>
        </main>
        <!-- /Start your project here-->
        <!-- filler-->
        <div class='filler' ></div>
        <!--filler-->
        <!-- Footer -->
        <footer style="<?php
		if ($presenter->isLinkCurrent('Homepage:project')) {
			?>position: unset !important<?php
		}
?>" class="page-footer font-small blue-gradient fixed-bottom">

            <!-- Copyright -->
            <div class="footer-copyright text-center py-3">© 2018 Copyright:
                <a href="#footer" style="white-space: nowrap;"> Dominik Šlechta</a>
            </div>
            <!-- Copyright -->

        </footer>
        <!-- Footer -->
        <!-- SCRIPTS -->
        <!-- JQuery -->

        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 96 */ ?>/mdb/js/jquery-3.3.1.min.js"></script>
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 98 */ ?>/mdb/js/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 100 */ ?>/mdb/js/bootstrap.min.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 102 */ ?>/mdb/js/mdb.min.js"></script>
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 103 */ ?>/jquery-3.3.1.js"></script>
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 104 */ ?>/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 105 */ ?>/dropzone-amd-module.js"></script>
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 106 */ ?>/dropzone.js"></script>
        <script type="text/javascript">
            //text area counter
            function countChar(val) {
                var len = val.value.length;
                if (len >= 255) {
                    val.value = val.value.substring(0, 256);
                } else {
                    $('#charNum').text(len + "/255");
                }
            }
            console.log(screen.height);
            console.log('<');
            console.log($('body').height() + 100);
            if (screen.height < $('body').height() + 100) {
                $('footer.page-footer').css("position", "unset");
            } else {
                $('footer.page-footer').css("position", "fixed");
            }
        </script>

    </body>

</html>
<?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}

}
