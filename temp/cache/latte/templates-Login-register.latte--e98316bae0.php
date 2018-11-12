<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Login/register.latte

use Latte\Runtime as LR;

class Templatee98316bae0 extends Latte\Runtime\Template
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
		if ($user->isInRole('administrator')) {
?>
<div>
<?php
			/* line 6 */ $_tmp = $this->global->uiControl->getComponent("registrationForm");
			if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
			$_tmp->render();
?>
</div>
<?php
		}
		
	}

}
