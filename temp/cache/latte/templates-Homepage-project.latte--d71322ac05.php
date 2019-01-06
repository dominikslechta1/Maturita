<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Homepage/project.latte

use Latte\Runtime as LR;

class Templated71322ac05 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'_Locked' => 'blockLocked',
		'_Public' => 'blockPublic',
		'_file' => 'blockFile',
		'_itemsContainer' => 'blockItemsContainer',
	];

	public $blockTypes = [
		'content' => 'html',
		'_Locked' => 'html',
		'_Public' => 'html',
		'_file' => 'html',
		'_itemsContainer' => 'html',
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
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
		if (isset($project)) {
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
?>

    <!--projekt-->
    <div class="project-one">

        <!--Locked-->
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('Locked')) ?>"><?php $this->renderBlock('_Locked', $this->params) ?></div>        <!--Public-->
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('Public')) ?>"><?php $this->renderBlock('_Public', $this->params) ?></div>        <div class="jumbotron" style="display: contents;">

            <h2 class="display-4"><?php echo LR\Filters::escapeHtmlText($project->Name) /* line 62 */ ?></h2>
            <p class="lead"><?php echo LR\Filters::escapeHtmlText($project->Desc) /* line 63 */ ?></p>


            <div class="project-users">
                <span class="span-user">Vypracovává: <?php
			if ($project->User != null) {
				echo LR\Filters::escapeHtmlText($project->ref('users','User')->UserName) /* line 67 */;
			}
			else {
				?>neurčeno<?php
			}
?></span>
                <span class="span-oponent">Oponent: <?php
			if ($project->Oponent != null) {
				echo LR\Filters::escapeHtmlText($project->ref('users','Oponent')->UserName) /* line 68 */;
			}
			else {
				?>neurčeno<?php
			}
?></span>
                <span class="span-consultant">Konzultant: <?php
			if ($project->Consultant != null) {
				echo LR\Filters::escapeHtmlText($project->ref('users','Consultant')->UserName) /* line 69 */;
			}
			else {
				?>neurčeno<?php
			}
?></span>
            </div>
            <hr class="my-4">
            <div class="project-buttons">
                <a onclick="return confirm('opravdu to chces smazat?')" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("delete!", [$project->idProjects])) ?>"<?php
			if ($_tmp = array_filter(['btn', 'btn-primary', 'btn-lg', 'blue', ($user->isInRole('administrator'))?'':'disabled'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>smazat</a>
                <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Addproject:add", [$project->idProjects])) ?>"<?php
			if ($_tmp = array_filter(['btn', 'btn-primary', 'btn-lg', 'blue', ($user->isInRole('administrator'))?'':'disabled'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>upravit</a>

                <a onsubmit="alert('hello')" type="submit" form="upload-form" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("update!", ['state'=>$upBtnState])) ?>"<?php
			if ($_tmp = array_filter(['btn', 'btn-primary', 'btn-lg', 'blue', 'ajax', 'upload-button', ($btndis)? '':'disabled'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"';
			echo ' id="' . htmlSpecialChars($this->global->snippetDriver->getHtmlId('file')) . '"' ?>><?php $this->renderBlock('_file', $this->params) ?>
</a>
            </div>

        </div>

    </div>
<?php
			$useing = $permissed;
			?><div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('itemsContainer')) ?>"><?php
			$this->renderBlock('_itemsContainer', $this->params) ?></div>    <div style="display:<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss($useing)) /* line 86 */ ?>;" class="ajax">

<?php
			/* line 88 */
			$this->createTemplate('../Upload/upload.latte', $this->params, "include")->renderToContentType('html');
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
			?>                    <a class="ajax"  href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("locked!", [$project->idProjects,$project->Locked])) ?>">
                        <img 
<?php
			if ($project->Locked == 1) {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 19 */ ?>/lock.png" alt="locked" title="Projekt je zamknutý"
<?php
			}
			else {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 21 */ ?>/unlock.png" alt="unlocked" title="Projekt je odemknutý"
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
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 28 */ ?>/lock.png" alt="locked" title="Projekt se nemůže nadále editovat"
<?php
			}
			else {
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 30 */ ?>/unlock.png" alt="unlocked" title="Projekt se může editovat"
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
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 43 */ ?>/closed-eye.png" alt="closed eye" title="Projekt je skrytý"
<?php
			}
			else {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 45 */ ?>/opened-eye.png" alt="opened eye" title="Projekt je viditelný"
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
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 52 */ ?>/closed-eye.png" alt="closed eye" title="Projekt je skrytý"
<?php
			}
			else {
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 54 */ ?>/opened-eye.png" alt="opened eye" title="Projekt je viditelný"
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


	function blockFile($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("file", "static");
		echo LR\Filters::escapeHtmlText($upBtn) /* line 76 */;
		$this->global->snippetDriver->leave();
		
	}


	function blockItemsContainer($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("itemsContainer", "static");
		$useing = $permissed;
		$this->global->snippetDriver->leave();
		
	}

}
