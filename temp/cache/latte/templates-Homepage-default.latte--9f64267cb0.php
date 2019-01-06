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
		if (isset($this->params['id'])) trigger_error('Variable $id overwritten in foreach on line 6');
		if (isset($this->params['item'])) trigger_error('Variable $item overwritten in foreach on line 6');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<div class="projects-div">
<?php
		if ($projects !== null) {
			$iterations = 0;
			foreach ($projects as $id => $item) {
				?>            <div class="project <?php echo LR\Filters::escapeHtmlAttr($id) /* line 7 */ ?> hoverable">
                <!-- Card -->
                <a class="project <?php echo LR\Filters::escapeHtmlAttr($id) /* line 9 */ ?> project-link" href="<?php
				echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:project", [$item->idProjects])) ?>">
                    <div class="card card-cascade wider reverse ">


                        <!-- Card content -->
                        <div class="card-body card-body-cascade text-center">

                            <!-- Title -->
                            <h4 class="card-title"><strong class="card-title-strong"><?php echo LR\Filters::escapeHtmlText($item->Name) /* line 17 */ ?></strong></h4>
                            <!-- Subtitle -->
                            <h6 class="font-weight-bold indigo-text py-2"><?php echo LR\Filters::escapeHtmlText($item->ref('users','User')->UserName) /* line 19 */ ?></h6>
                            <!-- Text -->
                            <p class="card-subtitle"><?php echo LR\Filters::escapeHtmlText($item->Desc) /* line 21 */ ?></p>
                            <p class="card-text"><?php echo LR\Filters::escapeHtmlText($item->Year) /* line 22 */ ?></p>
                        </div>

                    </div>
                </a>
                <!-- Card -->
            </div>
<?php
				$iterations++;
			}
		}
		else {
?>
        <p>nejsou projekty k vidění</p>
<?php
		}
?>
</div>
<?php
	}

}
