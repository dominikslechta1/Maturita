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
        <meta name="viewport" content="width=device-width">
        <link href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 6 */ ?>/style.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="maturita_gif.gif" type="image/gif">
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
        <div class="menu"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>"><button>home</button></a>
<?php
		if ($user->isInRole('student')) {
			?>            <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Addproject:Add")) ?>"><button>add project</button></a>
<?php
		}
		if ($user->isLoggedIn()) {
			?>                <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Logout:")) ?>"><button>logout</button></a>
<?php
		}
		else {
			?>                <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Login:login")) ?>"><button>login</button></a>
<?php
		}
		if ($user->isInRole('administrator')) {
			?>                <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Login:register")) ?>"><button>register new user</button></a>
<?php
		}
?>
        </div>

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
?>        <script src="https://nette.github.io/resources/js/netteForms.min.js"></script>
<?php
	}

}
