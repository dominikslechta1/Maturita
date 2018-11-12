<?php
// source: C:\wamp\www\maturita\app\presenters/templates/Login/login.latte

use Latte\Runtime as LR;

class Template5fb8d7b5f3 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'content' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
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
?>
<div>
<?php
		/* line 5 */ $_tmp = $this->global->uiControl->getComponent("loginForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
		$_tmp->render();
?>
</div>
    
<div>
    <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Login:register")) ?>">are you new?</a>
    
</div><?php
	}

}
