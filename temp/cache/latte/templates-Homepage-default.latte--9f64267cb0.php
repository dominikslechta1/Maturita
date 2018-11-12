<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Template9f64267cb0 extends Latte\Runtime\Template
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
        jsi administrator
    </div>
<?php
		}
		elseif ($user->isInRole('teacher')) {
?>
    <div>
        jsi ucitel
    </div>
<?php
		}
		elseif ($user->isInRole('student')) {
?>
    <div>
        jsi student
    </div>
<?php
		}
		else {
?>
    <div>
        jsi guest
    </div>
<?php
		}
		
	}

}
