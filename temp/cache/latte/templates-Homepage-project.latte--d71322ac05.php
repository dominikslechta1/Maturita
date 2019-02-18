<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Homepage/project.latte

use Latte\Runtime as LR;

class Templated71322ac05 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'_flash' => 'blockFlash',
		'_Locked' => 'blockLocked',
		'_Public' => 'blockPublic',
		'_filesUp' => 'blockFilesUp',
		'_reqfile' => 'blockReqfile',
		'_file' => 'blockFile',
		'_itemsContainer' => 'blockItemsContainer',
	];

	public $blockTypes = [
		'content' => 'html',
		'_flash' => 'html',
		'_Locked' => 'html',
		'_Public' => 'html',
		'_filesUp' => 'html',
		'_reqfile' => 'html',
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
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 6');
		if (isset($this->params['id'])) trigger_error('Variable $id overwritten in foreach on line 145');
		if (isset($this->params['item'])) trigger_error('Variable $item overwritten in foreach on line 145');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
		if (isset($project)) {
?>

<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('flash')) ?>"><?php $this->renderBlock('_flash', $this->params) ?></div>    <div>
<?php
			if (isset($test)) {
				?>            <?php echo LR\Filters::escapeHtmlText($test) /* line 12 */ ?>

<?php
			}
?>
    </div>

    <!--projekt-->
    <div class="project-one">

        <!--Locked-->
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('Locked')) ?>"><?php $this->renderBlock('_Locked', $this->params) ?></div>        <!--Public-->
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('Public')) ?>"><?php $this->renderBlock('_Public', $this->params) ?></div>        <div class="jumbotron" style="display: contents;">

            <h2 class="display-4 project-title"><?php echo LR\Filters::escapeHtmlText($project->Name) /* line 69 */ ?></h2>
            <p class="lead"><?php echo LR\Filters::escapeHtmlText($project->Desc) /* line 70 */ ?></p>
            <div class="projects-bottom-section">
                <div class="projects-users">
                    <p class="span-user">Vypracovává: <?php
			if ($project->User != null) {
				echo LR\Filters::escapeHtmlText($project->ref('users','User')->UserName) /* line 73 */;
			}
			else {
				?>neurčeno<?php
			}
?></p>
                    <p class="span-oponent">Oponent: <?php
			if ($project->Oponent != null) {
				echo LR\Filters::escapeHtmlText($project->ref('users','Oponent')->UserName) /* line 74 */;
			}
			else {
				?>neurčeno<?php
			}
?></p>
                    <p class="span-consultant">Konzultant: <?php
			if ($project->Consultant != null) {
				echo LR\Filters::escapeHtmlText($project->ref('users','Consultant')->UserName) /* line 75 */;
			}
			else {
				?>neurčeno<?php
			}
?></p>                                    
                    <p>Rok: <?php echo LR\Filters::escapeHtmlText($project->Year) /* line 76 */ ?></p>
                </div>
<div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('filesUp')) ?>"><?php $this->renderBlock('_filesUp', $this->params) ?></div>            </div>

            <hr class="my-4">
            <div class="project-buttons">
                <a onclick="return confirm('opravdu to chces smazat?')" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("delete!", [$project->idProjects])) ?>"<?php
			if ($_tmp = array_filter(['btn', 'btn-primary', 'btn-lg', 'blue', ($user->isInRole('administrator'))?'':'disabled'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>smazat</a>
                <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Addproject:add", [$project->idProjects])) ?>"<?php
			if ($_tmp = array_filter(['btn', 'btn-primary', 'btn-lg', 'blue', (($project->Locked == 0 && !$user->isInRole('guest'))||$user->isInRole('administrator'))?'':'disabled'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>upravit</a>

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
			$this->renderBlock('_itemsContainer', $this->params) ?></div>        <div style="display:<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::escapeCss($useing)) /* line 137 */ ?>;" class="ajax">

<?php
			/* line 139 */
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
                        <h5 class="card-title"><?php echo LR\Filters::escapeHtmlText($item->Name) /* line 148 */ ?></h5>
                        <p class="card-link"><?php echo LR\Filters::escapeHtmlText($item->Desc) /* line 149 */ ?></p>
                        <small class="card-text"><?php echo LR\Filters::escapeHtmlText($item->ref('filetypes','FileType')->FileType) /* line 150 */ ?></small><br>
                        <a class="card-link" href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 151 */ ?>/files/<?php
					echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($item->FileName)) /* line 151 */;
					echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($item->ref('filetypes','FileType')->FileType)) /* line 151 */ ?>" download="<?php
					echo LR\Filters::escapeHtmlAttr($item->Name) /* line 151 */;
					echo LR\Filters::escapeHtmlAttr($item->ref('filetypes','FileType')->FileType) /* line 151 */ ?>">Stáhnout</a>
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


	function blockFlash($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("flash", "static");
?>
        <!--flashmessage-->
<?php
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>            <div class="flash <?php echo LR\Filters::escapeHtmlAttr($flash->type) /* line 7 */ ?>"><li><?php
			echo LR\Filters::escapeHtmlText($flash->message) /* line 7 */ ?></li></div>
<?php
			$iterations++;
		}
		$this->global->snippetDriver->leave();
		
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
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 26 */ ?>/lock.png" alt="locked" title="Projekt je zamknutý"
<?php
			}
			else {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 28 */ ?>/unlock.png" alt="unlocked" title="Projekt je odemknutý"
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
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 35 */ ?>/lock.png" alt="locked" title="Projekt se nemůže nadále editovat"
<?php
			}
			else {
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 37 */ ?>/unlock.png" alt="unlocked" title="Projekt se může editovat"
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
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 50 */ ?>/closed-eye.png" alt="closed eye" title="Projekt je skrytý"
<?php
			}
			else {
				?>                                src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 52 */ ?>/opened-eye.png" alt="opened eye" title="Projekt je viditelný"
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
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 59 */ ?>/closed-eye.png" alt="closed eye" title="Projekt je skrytý"
<?php
			}
			else {
				?>                            src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 61 */ ?>/opened-eye.png" alt="opened eye" title="Projekt je viditelný"
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


	function blockFilesUp($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("filesUp", "static");
?>
                    <div class="projects-files">
                        <?php
		if (isset($project->Url)) {
			?><p>url projektu: <a href="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($project->Url)) /* line 80 */ ?>"><?php
			echo LR\Filters::escapeHtmlText($project->Url) /* line 80 */ ?></a> <?php
			if ($btndis) {
				?><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("deleteUrl!", [$project->idProjects])) ?>">smazat</a><?php
			}
?>
</p>
<?php
		}
		else {
			if ($btndis) {
				$form = $_form = $this->global->formsStack[] = $this->global->uiControl["urlForm"];
				?>                                <form action='#' id="url-form" class='form url-form'<?php
				echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
				'action' => NULL,
				'id' => NULL,
				'class' => NULL,
				), false) ?>>
                                    <input class="url-up form-control text" type="url" placeholder="Url projektu"<?php
				$_input = end($this->global->formsStack)["url"];
				echo $_input->getControlPart()->addAttributes(array (
				'class' => NULL,
				'type' => NULL,
				'placeholder' => NULL,
				))->attributes() ?>>
                                    <em>příklad: 'https://www.mujweb.cz'</em>
                                    <input class="btn blue url-btn" type="submit" value="odeslat"<?php
				$_input = end($this->global->formsStack)["upload"];
				echo $_input->getControlPart()->addAttributes(array (
				'class' => NULL,
				'type' => NULL,
				'value' => NULL,
				))->attributes() ?>>
<?php
				echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>                                </form>
<?php
			}
		}
		if ($btndis) {
			?><div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('reqfile')) ?>"><?php $this->renderBlock('_reqfile', $this->params) ?></div>
<?php
		}
?>
                    </div>
<?php
		$this->global->snippetDriver->leave();
		
	}


	function blockReqfile($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("reqfile", "static");
?>

<?php
		if ($reqshow) {
?>                                <cite style="text-align: right;
                                      margin: 0 6px;">Ke stažení</cite>
<?php
		}
		if (isset($reqfile)) {
			?>                                    <p>text práce: <a href="#" download=""><?php echo LR\Filters::escapeHtmlText($reqfile->RqFile) /* line 96 */ ?></a> <a href="#">smazat</a></p>
<?php
		}
		else {
?>

                                    <legend style="text-align: right;
                                            margin: 0 6px;">Požadované soubory</legend>
<?php
			$form = $_form = $this->global->formsStack[] = $this->global->uiControl["reqFiles"];
			?>                                    <form action='#' id="req-form" class='form d-grid'<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
			'action' => NULL,
			'id' => NULL,
			'class' => NULL,
			), false) ?>>
                                        <input class="upload" type="file" id="worktxt"<?php
			$_input = end($this->global->formsStack)["file"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'type' => NULL,
			'id' => NULL,
			))->attributes() ?>><label class="blue btn" for="worktxt"><img src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 102 */ ?>/upload.png" width="13px"> text prace: </label>
                                        <button class="btn blue url-btn"  type="submit"<?php
			$_input = end($this->global->formsStack)["save"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'type' => NULL,
			))->attributes() ?>>save</button>
<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>                                    </form>
<?php
		}
		if (isset($reqfilepdf)) {
			?>                                    <p>text práce v pdf: <a href="#" download=""><?php echo LR\Filters::escapeHtmlText($reqfilepdf->RqFilePdf) /* line 107 */ ?></a>  <a href="#">smazat</a></p>
<?php
		}
		else {
			$form = $_form = $this->global->formsStack[] = $this->global->uiControl["reqFilesPdf"];
			?>                                    <form action='#' id="req-form" class='form d-grid'<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
			'action' => NULL,
			'id' => NULL,
			'class' => NULL,
			), false) ?>>
                                        <input class="upload" type="file" id="workpdf"<?php
			$_input = end($this->global->formsStack)["filepdf"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'type' => NULL,
			'id' => NULL,
			))->attributes() ?>><label class="blue btn" for="workpdf"><img src="<?php echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 110 */ ?>/upload.png" width="13px"> text prace v pdf: </label>
                                        <button class="btn blue url-btn"  type="submit"<?php
			$_input = end($this->global->formsStack)["save"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'type' => NULL,
			))->attributes() ?>>save</button>
<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>                                    </form>
<?php
		}
		$this->global->snippetDriver->leave();
		
	}


	function blockFile($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("file", "static");
		echo LR\Filters::escapeHtmlText($upBtn) /* line 126 */;
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
