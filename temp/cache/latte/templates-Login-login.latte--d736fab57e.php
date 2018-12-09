<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Login/login.latte

use Latte\Runtime as LR;

class Templated736fab57e extends Latte\Runtime\Template
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
?>
<div class='login-form'>
    <p class="h4 mb-4 text-center">Přihlášení</p>
<?php
		if ((!$user->isLoggedIn())) {
			$form = $_form = $this->global->formsStack[] = $this->global->uiControl["loginForm"];
			?>        <form class=form<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
			'class' => NULL,
			), false) ?>>

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

            <button class="btn btn-info btn-block my-4" type="submit"<?php
			$_input = end($this->global->formsStack)["login"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'type' => NULL,
			))->attributes() ?>>Přihlásit</button>
<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>        </form>
<?php
		}
		elseif ((!$user->isInRole('guest'))) {
?>

        <p>
<?php
			if (isset($userse)) {
				?>                <?php echo LR\Filters::escapeHtmlText($userse) /* line 19 */ ?>

<?php
			}
?>
        </p>
        <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Logout:")) ?>">Chcete se odhlásit?</a>
<?php
		}
		?></div><?php
	}

}
