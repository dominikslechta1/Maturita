<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Homepage/project.latte

use Latte\Runtime as LR;

class Templated71322ac05 extends Latte\Runtime\Template
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
		if (isset($project)) {
?>
    <div class="project-one">
        <div class="jumbotron">
            <h2 class="display-4"><?php echo LR\Filters::escapeHtmlText($project->Name) /* line 5 */ ?></h2>
            <p class="lead"><?php echo LR\Filters::escapeHtmlText($project->Desc) /* line 6 */ ?></p>
            <hr class="my-4">
            <div class="project-buttons">
            <button class="btn btn-primary btn-lg blue-gradient">smazat</button><button class="btn btn-primary btn-lg blue-gradient">upravit</button>
        </div>
        </div>
            <div>
                <ul>
                    <li>
                        vydal
                    </li>
                    <li>
                        vydal
                    </li>
                    <li>
                        vydal
                    </li>
                </ul>
            </div>
    </div>
<?php
		}
		
	}

}
