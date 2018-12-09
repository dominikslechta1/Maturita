<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Addproject/add.latte

use Latte\Runtime as LR;

class Templatee4cf15a6db extends Latte\Runtime\Template
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
		if (isset($projectId)) {
			?>        <?php echo LR\Filters::escapeHtmlText($projectId) /* line 5 */ ?>

<?php
		}
?>
    <div class='add-project'>
<?php
		$form = $_form = $this->global->formsStack[] = $this->global->uiControl["addprojectForm"];
		?><form class=form<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
		'class' => NULL,
		), false) ?>>

    <p class="h4 mb-4 text-center">Sign in</p>

    <label for="textInput">Text input Label</label>
    <input type="text" id="textInput" class="form-control mb-4" placeholder="Text input"<?php
		$_input = end($this->global->formsStack)["Name"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		'id' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		))->attributes() ?>>

    <label for="field">Textarea Label</label>
    <textarea class="form-control" maxlength='255' placeholder="Textarea" id="field" onkeyup="countChar(this)"<?php
		$_input = end($this->global->formsStack)["desc"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		'maxlength' => NULL,
		'placeholder' => NULL,
		'id' => NULL,
		'onkeyup' => NULL,
		))->attributes() ?>><?php echo $_input->getControl()->getHtml() ?></textarea>
    <div id="charNum" class='grey-text mb-4'>0/255</div>

    <label for="select">Default select</label>
    <select class="browser-default custom-select mb-4" id="select"<?php
		$_input = end($this->global->formsStack)["user"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		'id' => NULL,
		))->attributes() ?>>
<?php echo $_input->getControl()->getHtml() ?>    </select>

    <label for="select">Default select</label>
    <select class="browser-default custom-select mb-4" id="select"<?php
		$_input = end($this->global->formsStack)["consultant"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		'id' => NULL,
		))->attributes() ?>>
<?php echo $_input->getControl()->getHtml() ?>    </select>

    <label for="select">Default select</label>
    <select class="browser-default custom-select mb-4" id="select"<?php
		$_input = end($this->global->formsStack)["oponent"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		'id' => NULL,
		))->attributes() ?>>
<?php echo $_input->getControl()->getHtml() ?>    </select>



    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" class="custom-control-input" id="checkbox"<?php
		$_input = end($this->global->formsStack)["agree"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		'class' => NULL,
		'id' => NULL,
		))->attributes() ?>>
        <label class="custom-control-label" for="checkbox">Default checkbox</label>
    </div>

    <button class="btn btn-info btn-block my-4" type="submit"<?php
		$_input = end($this->global->formsStack)["login"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		'type' => NULL,
		))->attributes() ?>>Sign in</button>

<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?></form>
    </div>
<?php
	}

}
