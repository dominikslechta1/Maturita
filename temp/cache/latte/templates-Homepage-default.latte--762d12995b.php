<?php
// source: C:\wamp\www\maturita\app\presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Template762d12995b extends Latte\Runtime\Template
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
		?><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Login:login")) ?>">hello</a>
<?php echo LR\Filters::escapeHtmlText($user->isLoggedIn() ? 'ano' : 'ne') /* line 5 */ ?>

<?php echo LR\Filters::escapeHtmlText($user->isInRole('student') ? 'ano':'ne') /* line 6 */ ?>

<br>
<?php
	}

}
