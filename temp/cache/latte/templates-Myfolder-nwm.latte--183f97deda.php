<?php
// source: C:\wamp\www\maturita\app\presenters/templates/Myfolder/nwm.latte

use Latte\Runtime as LR;

class Template183f97deda extends Latte\Runtime\Template
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
    <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">homepage</a></li>
    <li><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Main:main")) ?>">main</a></li>
    hello
</div><?php
	}

}
