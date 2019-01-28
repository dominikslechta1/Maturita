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
		if (isset($this->params['id'])) trigger_error('Variable $id overwritten in foreach on line 113');
		if (isset($this->params['item'])) trigger_error('Variable $item overwritten in foreach on line 113');
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
    <div>
<?php
			if (isset($test)) {
				?>            <?php echo LR\Filters::escapeHtmlText($test) /* line 10 */ ?>

<?php
			}
?>
    </div>

    <!--projekt-->
    <div class="project-one">

        <!--Locked-->
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('Locked')) ?>"><?php $this->renderBlock('_Locked', $this->params) ?></div>        <!--Public-->
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('Public')) ?>"><?php $this->renderBlock('_Public', $this->params) ?></div>        <div class="jumbotron" style="display: contents;">

            <h2 class="display-4 project-title"><?php echo LR\Filters::escapeHtmlText($project->Name) /* line 67 */ ?></h2>
            <p class="lead"><?php echo LR\Filters::escapeHtmlText($project->Desc) /* line 68 */ ?></p>
            <div class="projects-bottom-section">
                <div class="projects-users">
                    <p class="span-user">Vypracovává: <?php
			if ($project->User != null) {
				echo LR\Filters::escapeHtmlText($project->ref('users','User')->UserName) /* line 71 */;
			}
			else {
				?>neurčeno<?php
			}
?></p>
                    <p class="span-oponent">Oponent: <?php
			if ($project->Oponent != null) {
				echo LR\Filters::escapeHtmlText($project->ref('users','Oponent')->UserName) /* line 72 */;
			}
			else {
				?>neurčeno<?php
			}
?></p>
                    <p class="span-consultant">Konzultant: <?php
			if ($project->Consultant != null) {
				echo LR\Filters::escapeHtmlText($project->ref('users','Consultant')->UserName) /* line 73 */;
			}
			else {
				?>neurčeno<?php
			}
?></p>                                    
                    <p>Rok: <?php echo LR\Filters::escapeHtmlText($project->Year) /* line 74 */ ?></p>
                </div>
<?php
			if (isset($reqfiles)) {
?>
                    <div class="projects-files"> 
                        <p>text práce: <a href="#download">stáhnout</a></p>
                        <p>text práce v pdf: <a href="#downloadpdf">stáhnout</a></p>
                    </div>
<?php
			}
			else {
?>
                    <div class="projects-files">
                        <label>text prace: <input class="upload" type="file"></label>
                        <label>text prace v pdf: <input class="upload" type="file"></label>
                    </div>
<?php
			}
?>
            </div>

            <hr class="my-4">
            <div class="project-buttons">
                <a onclick="return confirm('opravdu to chces smazat?')" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("delete!", [$project->idProjects])) ?>"<?php
			if ($_tmp = array_filter(['btn', 'btn-primary', 'btn-lg', 'blue', ($user->isInRole('administrator'))?'':'disabled'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>smazat</a>
                <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Addproject:add", [$project->idProjects])) ?>"<?php
			if ($_tmp = array_filter(['btn', 'btn-primary', 'btn-lg', 'blue', ($project->Locked == 0||$user->isInRole('administrator'))?'':'disabled'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>upravit</a>

                <a type="submit" form="upload-form" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("update!", ['state'=>$upBtnState, 'project' => $project->Locked])) ?>"<?php
			if ($_tmp = array_filter(['btn', 'btn-primary', 'btn-lg', 'blue', 'ajax', 'upload-button', ($btndis)? '':'disabled'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"';
			echo ' id="' . htmlSpecialChars($this->global->snippetDriver->getHtmlId('file')) . '"' ?>><?php $this->renderBlock('_file', $this->params) ?>
</a>
            </div>

        </div>

    </div>
<?php
			$useing = $permissed;
?>
    <div class="mediaup">
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('itemsContainer')) ?>"><?php
			$this->renderBlock('_itemsContainer', $this->params) ?></div>        <div style="display:<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss($useing)) /* line 105 */ ?>;" class="ajax">

<?php
			/* line 107 */
			$this->createTemplate('../Upload/upload.latte', $this->params, "include")->renderToContentType('html');
?>
        </div>
    </div>

    <div class="cards">
<?php
			if (isset($files)) {
				$iterations = 0;
				foreach ($files as $id => $item) {
?>
                <div class="card mb-3">
                    <div class="card-body hoverable ">
                        <h5 class="card-title"><?php echo LR\Filters::escapeHtmlText($item->Name) /* line 116 */ ?></h5>
                        <p class="card-link"><?php echo LR\Filters::escapeHtmlText($item->Desc) /* line 117 */ ?></p>
                        <small class="card-text"><?php echo LR\Filters::escapeHtmlText($item->ref('filetypes','FileType')->FileType) /* line 118 */ ?></small><br>
                        <a class="card-link" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 119 */ ?>/files/<?php
					echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($item->FileName)) /* line 119 */;
					echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($item->ref('filetypes','FileType')->FileType)) /* line 119 */ ?>" download="<?php
					echo LR\Filters::escapeHtmlAttr($item->Name) /* line 119 */;
					echo LR\Filters::escapeHtmlAttr($item->ref('filetypes','FileType')->FileType) /* line 119 */ ?>">Stáhnout</a>
                        <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("deleteFile!", ['fileId'=>$id])) ?>"<?php
					if ($_tmp = array_filter(['card-link', ($user->isInRole('administrator'))? '':'disabled'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>smazat</a>
                    </div>
                </div>
<?php
					$iterations++;
				}
			}
?>
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
			?>                    <a class="ajax"  href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("locked!", [$project->idProjects,$project->Locked])) ?>">
                        <img 
<?php
			if ($project->Locked == 1) {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 24 */ ?>/lock.png" alt="locked" title="Projekt je zamknutý"
<?php
			}
			else {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 26 */ ?>/unlock.png" alt="unlocked" title="Projekt je odemknutý"
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
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 33 */ ?>/lock.png" alt="locked" title="Projekt se nemůže nadále editovat"
<?php
			}
			else {
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 35 */ ?>/unlock.png" alt="unlocked" title="Projekt se může editovat"
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
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 48 */ ?>/closed-eye.png" alt="closed eye" title="Projekt je skrytý"
<?php
			}
			else {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 50 */ ?>/opened-eye.png" alt="opened eye" title="Projekt je viditelný"
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
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 57 */ ?>/closed-eye.png" alt="closed eye" title="Projekt je skrytý"
<?php
			}
			else {
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 59 */ ?>/opened-eye.png" alt="opened eye" title="Projekt je viditelný"
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
		echo LR\Filters::escapeHtmlText($upBtn) /* line 94 */;
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
