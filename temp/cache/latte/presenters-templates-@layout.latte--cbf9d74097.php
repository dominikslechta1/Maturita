<?php
// source: C:\wamp\www\maturita\app\presenters/templates/@layout.latte

use Latte\Runtime as LR;

class Templatecbf9d74097 extends Latte\Runtime\Template
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
?>Nette Web</title>
</head>

<body>
    <div class="menu"><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Main:main")) ?>"><button>home</button></a><a href="<?php
		echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Login:login")) ?>"><button>login</button></a></div>

<?php
		$this->renderBlock('content', $this->params, 'html');
?>

<?php
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('scripts', get_defined_vars());
?>
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


	function blockScripts($_args)
	{
?>	<script src="https://nette.github.io/resources/js/netteForms.min.js"></script>
<?php
	}

}
