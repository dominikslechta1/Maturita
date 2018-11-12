<?php
// source: C:\wamp\www\maturita\app\presenters/templates/Login/register.latte

use Latte\Runtime as LR;

class Templateeb1aeb604c extends Latte\Runtime\Template
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
		/* line 5 */ $_tmp = $this->global->uiControl->getComponent("registrationForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
		$_tmp->render();
		echo LR\Filters::escapeHtmlText($hash) /* line 6 */ ?>

</div><?php
	}

}
