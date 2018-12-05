<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Template43cc225190 extends Latte\Runtime\Template
{
	public $blocks = [
		'scripts' => 'blockScripts',
	];

	public $blockTypes = [
		'scripts' => 'html',
	];


	function main()
	{
		extract($this->params);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Bootstrap core CSS -->
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 11 */ ?>/mdb/css/bootstrap.min.css" rel="stylesheet">
        <!-- Material Design Bootstrap -->
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 13 */ ?>/mdb/css/mdb.min.css" rel="stylesheet">
        <!-- Your custom styles (optional) -->
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 15 */ ?>/mdb/css/style.css" rel="stylesheet">

        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 17 */ ?>/style.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="maturita_gif.gif" type="image/gif">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <title><?php
		if (isset($this->blockQueue["title"])) {
			$this->renderBlock('title', $this->params, function ($s, $type) {
				$_fi = new LR\FilterInfo($type);
				return LR\Filters::convertTo($_fi, 'html', $this->filters->filterContent('striphtml', $_fi, $s));
			});
			?> | <?php
		}
?>Maturita</title>
    </head>

    <body>
           <nav class="navbar navbar-expand-lg navbar-dark indigo">
  <a class="navbar-brand" href="Homepage:default">Maturitni projekty</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText"
    aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
          <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">Domů
                      <span class="sr-only">(current)</span>
        </a>
          
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Addproject:add")) ?>">Pridat projekt</a>

      </li>
<?php
		if ($user->isInRole('administrator')) {
?>      <li class="nav-item">
        <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Login:login")) ?>">Zaregistrovat uživatele</a>
      </li>
<?php
		}
?>
    </ul>
    <span class="navbar-text white-text">
        <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
<?php
		if ($user->isLoggedIn()) {
			?>                <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Logout:")) ?>">logout</a>
<?php
		}
		else {
			?>                <a class="nav-link" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Login:login")) ?>">Přihlásit</a>
<?php
		}
?>
        </li>
        </ul>
        </div>
    </span>
  </div>
</nav>

<?php
		$this->renderBlock('content', $this->params, 'html');
?>

<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('scripts', get_defined_vars());
?>
    </body>
</html><?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockScripts($_args)
	{
		extract($_args);
?>
        <script src="https://nette.github.io/resources/js/netteForms.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <!-- SCRIPTS -->
        <!-- JQuery -->
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 71 */ ?>/mdb/js/jquery-3.3.1.min.js"></script>
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 73 */ ?>/mdb/js/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 75 */ ?>/mdb/js/bootstrap.min.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 77 */ ?>/mdb/js/mdb.min.js"></script>
<?php
	}

}
