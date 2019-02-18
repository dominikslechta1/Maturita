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
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 5');
		if (isset($this->params['row'])) trigger_error('Variable $row overwritten in foreach on line 14');
		if (isset($this->params['id'])) trigger_error('Variable $id overwritten in foreach on line 24');
		if (isset($this->params['item'])) trigger_error('Variable $item overwritten in foreach on line 24');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<!--flashmessage-->
<?php
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>        <div class="flash <?php echo LR\Filters::escapeHtmlAttr($flash->type) /* line 6 */ ?>"><li><?php
			echo LR\Filters::escapeHtmlText($flash->message) /* line 6 */ ?></li></div>
<?php
			$iterations++;
		}
		if (isset($Years)) {
?>
        <div class="dropdown show">
            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Zobrazit Rok</a>
<?php
			if (isset($curyear)) {
				?>            <span> nyní zobrazen rok: <?php echo LR\Filters::escapeHtmlText($curyear) /* line 11 */ ?></span>
<?php
			}
?>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item ajax" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("sortNews!", ['id' => 'all'])) ?>">všechny</a>
<?php
			$iterations = 0;
			foreach ($Years as $row) {
				?>                    <a class="dropdown-item ajax" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("sortNews!", ['id' => $row->Year])) ?>">
                        <?php echo LR\Filters::escapeHtmlText($row->Year) /* line 16 */ ?>

                    </a>
<?php
				$iterations++;
			}
?>
            </div>
        </div>
<?php
		}
?>
<div class="projects-div">
<?php
		if ($projects !== null) {
			$iterations = 0;
			foreach ($projects as $id => $item) {
				?>            <div class="project <?php echo LR\Filters::escapeHtmlAttr($id) /* line 25 */ ?> hoverable">
                <!-- Card -->
                <a class="project <?php echo LR\Filters::escapeHtmlAttr($id) /* line 27 */ ?> project-link" href="<?php
				echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:project", [$item->idProjects])) ?>">
                    <div class="card card-cascade wider reverse ">


                        <!-- Card content -->
                        <div class="card-body card-body-cascade text-center">

                            <!-- Title -->
                            <h4 class="card-title"><strong class="card-title-strong"><?php echo LR\Filters::escapeHtmlText($item->Name) /* line 35 */ ?></strong></h4>
                            <!-- Subtitle -->
                            <h6 class="font-weight-bold indigo-text py-2"><?php echo LR\Filters::escapeHtmlText($item->ref('users','User')->UserName) /* line 37 */ ?></h6>
                            <!-- Text -->
                            <p class="card-subtitle"><?php echo LR\Filters::escapeHtmlText($item->Desc) /* line 39 */ ?></p>
                            <p class="card-text"><?php echo LR\Filters::escapeHtmlText($item->Year) /* line 40 */ ?></p>
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
