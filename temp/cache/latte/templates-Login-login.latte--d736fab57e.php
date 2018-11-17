<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Login/login.latte

use Latte\Runtime as LR;

class Templated736fab57e extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'title' => 'blockTitle',
	];

	public $blockTypes = [
		'content' => 'html',
		'title' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars()) ?>    <?php
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
		$this->renderBlock('title', get_defined_vars());
		if ((!$user->isLoggedIn())) {
?>
<div>
<?php
			/* line 7 */ $_tmp = $this->global->uiControl->getComponent("loginForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
?>
</div>
<?php
		}
		elseif ((!$user->isInRole('guest'))) {
?>
    
        <p>
<?php
			if (isset($userse)) {
				?>                <?php echo LR\Filters::escapeHtmlText($userse) /* line 13 */ ?>

<?php
			}
?>
            prihlasen
        </p>
        <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Logout:")) ?>">chces se odhlásit?</a>
<?php
		}
		
	}


	function blockTitle($_args)
	{
		extract($_args);
?><h1>Přihlášení</h1>
<?php
	}

}
