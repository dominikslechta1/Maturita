<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Login/register.latte

use Latte\Runtime as LR;

class Templatee98316bae0 extends Latte\Runtime\Template
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



    <div<?php if ($_tmp = array_filter(['register-form'])) echo ' class="', LR\Filters::escapeHtmlAttr(implode(" ", array_unique($_tmp))), '"' ?>>
<?php
			$form = $_form = $this->global->formsStack[] = $this->global->uiControl["signUpForm"];
			?>        <form class=form autocomplete="off"<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
			'class' => NULL,
			'autocomplete' => NULL,
			), false) ?>>

            <p class="h4 mb-4 text-center">Registrace</p>

            <input class="form-control mb-4" id="textInput" type="text" placeholder="Jméno"<?php
			$_input = end($this->global->formsStack)["name"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'id' => NULL,
			'type' => NULL,
			'placeholder' => NULL,
			))->attributes() ?>>

            <input type="email" id="defaultLoginFormEmail" class="form-control mb-4" placeholder="E-mail"<?php
			$_input = end($this->global->formsStack)["email"];
			echo $_input->getControlPart()->addAttributes(array (
			'type' => NULL,
			'id' => NULL,
			'class' => NULL,
			'placeholder' => NULL,
			))->attributes() ?>>

            <input type="password" id="defaultLoginFormPassword" class="form-control mb-4" placeholder="Heslo"<?php
			$_input = end($this->global->formsStack)["password"];
			echo $_input->getControlPart()->addAttributes(array (
			'type' => NULL,
			'id' => NULL,
			'class' => NULL,
			'placeholder' => NULL,
			))->attributes() ?>>
            <input type="password" id="defaultLoginFormPasswordVerify" class="form-control mb-4" placeholder="Heslo pro kontrolu"<?php
			$_input = end($this->global->formsStack)["passwordVerify"];
			echo $_input->getControlPart()->addAttributes(array (
			'type' => NULL,
			'id' => NULL,
			'class' => NULL,
			'placeholder' => NULL,
			))->attributes() ?>>

            <select class="browser-default custom-select mb-4" id="select"<?php
			$_input = end($this->global->formsStack)["privilege"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'id' => NULL,
			))->attributes() ?>>   
<?php echo $_input->getControl()->getHtml() ?>            </select>
            <button class="btn btn-info btn-block my-4 blue-gradient" type="submit"<?php
			$_input = end($this->global->formsStack)["login"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'type' => NULL,
			))->attributes() ?>>Registrovat</button>
<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>        </form>
    </div>
<?php
		}
		
	}

}
