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
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 5');
		if (isset($this->params['error'])) trigger_error('Variable $error overwritten in foreach on line 15');
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
?>


<?php
		if ($user->isInRole('administrator')) {
?>
    <div class='add-project'>
<?php
			if (isset($form)) {
?>
            <div>
<?php
				if ($form->hasErrors()) {
?>                <ul class="unsuccess">
<?php
					$iterations = 0;
					foreach ($form->errors as $error) {
						?>                    <li><?php echo LR\Filters::escapeHtmlText($error) /* line 15 */ ?></li>
<?php
						$iterations++;
					}
?>
                </ul>
<?php
				}
?>
            </div>
<?php
			}
			$form = $_form = $this->global->formsStack[] = $this->global->uiControl["addprojectForm"];
			?>        <form class="add-project form"<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
			'class' => NULL,
			), false) ?>>

            <p class="h4 mb-4 text-center"><?php
			if (isset($name)) {
				echo LR\Filters::escapeHtmlText($name) /* line 21 */;
			}
			else {
				?>Přidat projekt<?php
			}
?></p>

            <label for="textInput">Název projektu*</label>
            <input type="text" id="textInput" class="form-control mb-4" placeholder="Název"<?php
			$_input = end($this->global->formsStack)["Name"];
			echo $_input->getControlPart()->addAttributes(array (
			'type' => NULL,
			'id' => NULL,
			'class' => NULL,
			'placeholder' => NULL,
			))->attributes() ?>>

            <div class="texarea-parent mb-1">
                <label for="field">Popis</label>
                <textarea class="form-control txtarea" maxlength='255' placeholder="Popis" id="field" onkeydown="countChar(this)"<?php
			$_input = end($this->global->formsStack)["desc"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'maxlength' => NULL,
			'placeholder' => NULL,
			'id' => NULL,
			'onkeydown' => NULL,
			))->attributes() ?>><?php echo $_input->getControl()->getHtml() ?></textarea>
                <div id="charNum" class='grey-text' style='width: 100%; text-align: right;'>0/255</div>
            </div>
            <label for="select-user">Student*</label>
            <select class="browser-default custom-select mb-4" id="select-user"<?php
			$_input = end($this->global->formsStack)["user"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'id' => NULL,
			))->attributes() ?>>
<?php echo $_input->getControl()->getHtml() ?>            </select>

            <label for="select-cons">Konzultant</label>
            <select class="browser-default custom-select mb-4" id="select-cons"<?php
			$_input = end($this->global->formsStack)["consultant"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'id' => NULL,
			))->attributes() ?>>
<?php echo $_input->getControl()->getHtml() ?>            </select>

            <label for="select-oponent">Oponent</label>
            <select class="browser-default custom-select mb-4" id="select-oponent"<?php
			$_input = end($this->global->formsStack)["oponent"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'id' => NULL,
			))->attributes() ?>>
<?php echo $_input->getControl()->getHtml() ?>            </select>



            <div class="custom-control custom-checkbox mb-4">
                <input type="checkbox" class="custom-control-input" id="checkbox"<?php
			$_input = end($this->global->formsStack)["agree"];
			echo $_input->getControlPart()->addAttributes(array (
			'type' => NULL,
			'class' => NULL,
			'id' => NULL,
			))->attributes() ?>>
                <label class="custom-control-label" for="checkbox">Veřejné</label>
            </div>

            <button class="btn btn-info btn-block blue-gradient" type="submit"<?php
			$_input = end($this->global->formsStack)["login"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'type' => NULL,
			))->attributes() ?>><?php
			if (isset($name)) {
				?>Upravit<?php
			}
			else {
				?>Přidat<?php
			}
?></button>
            <p class="my-4">*povinné</p>
<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>        </form>

    </div>
<?php
			if (isset($error)) {
				?>        <?php echo LR\Filters::escapeHtmlText($error) /* line 59 */ ?>

<?php
			}
		}
		else {
?>
    <strong>Nejsi administrátor, nemůžeš přidávat ani upravovat projekt</strong>
<?php
		}
?>

<?php
	}

}
