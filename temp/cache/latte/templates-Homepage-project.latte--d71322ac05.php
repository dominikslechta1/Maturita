<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Homepage/project.latte

use Latte\Runtime as LR;

class Templated71322ac05 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'_Locked' => 'blockLocked',
		'_Public' => 'blockPublic',
	];

	public $blockTypes = [
		'content' => 'html',
		'_Locked' => 'html',
		'_Public' => 'html',
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
        <!--Locked-->
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('Locked')) ?>"><?php $this->renderBlock('_Locked', $this->params) ?></div>        <!--Public-->
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('Public')) ?>"><?php $this->renderBlock('_Public', $this->params) ?></div>        <div class="jumbotron" style="display: contents;">

            <h2 class="display-4"><?php echo LR\Filters::escapeHtmlText($project->Name) /* line 54 */ ?></h2>
            <p class="lead"><?php echo LR\Filters::escapeHtmlText($project->Desc) /* line 55 */ ?></p>


            <div class="project-users">
                <span class="span-user">Vypracovává: <?php echo LR\Filters::escapeHtmlText($project->ref('users','User')->UserName) /* line 59 */ ?></span>
                <span class="span-oponent">Oponent: <?php echo LR\Filters::escapeHtmlText($project->ref('users','Oponent')->UserName) /* line 60 */ ?></span>
                <span class="span-consultant">Konzultant: <?php echo LR\Filters::escapeHtmlText($project->ref('users','Consultant')->UserName) /* line 61 */ ?></span>
            </div>
            <hr class="my-4">
            <div class="project-buttons">
                <a class="btn btn-primary btn-lg blue" onclick="return confirm('opravdu to chces smazat?')" href="<?php
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("delete!", [$project->idProjects])) ?>">smazat</a><a class="btn btn-primary btn-lg blue" href="<?php
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Addproject:add", [$project->idProjects])) ?>">upravit</a>
            </div>

        </div>

    </div>
<?php
		}
		
	}


	function blockLocked($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("Locked", "static");
?>
            <part class="project-locked">
<?php
		if ($user->isInRole('administrator')) {
			?>                        <a class="ajax"  href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("locked!", [$project->idProjects,$project->Locked])) ?>">
                            <img 
<?php
			if ($project->Locked == 1) {
				?>                                    src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 11 */ ?>/lock.png" alt="locked"
<?php
			}
			else {
				?>                                    src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 13 */ ?>/unlock.png" alt="unlocked"
<?php
			}
?>
                                width="50px">
                        </a>
<?php
		}
		else {
?>
                        <img 
<?php
			if ($project->Locked == 1) {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 20 */ ?>/lock.png" alt="locked"
<?php
			}
			else {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 22 */ ?>/unlock.png" alt="unlocked"
<?php
			}
?>
                            width="50px">
<?php
		}
?>
            </part>
<?php
		$this->global->snippetDriver->leave();
		
	}


	function blockPublic($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("Public", "static");
?>
            <part class="project-locked">
<?php
		if ($user->isInRole('administrator')) {
			?>                    <a class="ajax" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("publiced!", ['id'=>$project->idProjects,'public'=>$project->Public])) ?>"> 
                        <img 
<?php
			if ($project->Public == 0) {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 35 */ ?>/closed-eye.png" alt="closed eye"
<?php
			}
			else {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 37 */ ?>/opened-eye.png" alt="opened eye"
<?php
			}
?>
                            width="50px">
                    </a>
<?php
		}
		else {
?>
                    <img 
<?php
			if ($project->Public == 0) {
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 44 */ ?>/closed-eye.png" alt="closed eye"
<?php
			}
			else {
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 46 */ ?>/opened-eye.png" alt="opened eye"
<?php
			}
?>
                        width="50px">
<?php
		}
?>
            </part>
<?php
		$this->global->snippetDriver->leave();
		
	}

}
