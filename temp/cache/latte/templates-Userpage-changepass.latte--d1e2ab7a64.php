<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Userpage/changepass.latte

use Latte\Runtime as LR;

class Templated1e2ab7a64 extends Latte\Runtime\Template
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
    <p class="h4 mb-4 text-center">Změna hesla</p>
<?php
		if (isset($test)) {
			?>        <?php echo LR\Filters::escapeHtmlText($test) /* line 8 */ ?>

<?php
		}
		$form = $_form = $this->global->formsStack[] = $this->global->uiControl["changePasswordForm"];
		?>    <form class=form<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
		'class' => NULL,
		), false) ?>>
        <?php
		ob_start(function () {});
		echo LR\Filters::escapeHtmlText(end($this->global->formsStack)["old"]->getError());
		$_fi = new LR\FilterInfo('html');
		$n = ob_get_length() ? new LR\Html(ob_get_clean()) : ob_get_clean();
?>

        <input type="password" class="form-control mb-4 <?php
		if ($n !== '') {
			?>is-invalid<?php
		}
		else {
			?>is-valid<?php
		}
		?>" placeholder="<?php
		if ($n !== '') {
			echo LR\Filters::escapeHtmlAttr($n) /* line 12 */;
		}
		else {
			?>Staré heslo<?php
		}
		?>"<?php
		$_input = end($this->global->formsStack)["old"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		))->attributes() ?>>

        <?php
		ob_start(function () {});
		echo LR\Filters::escapeHtmlText(end($this->global->formsStack)["new"]->getError());
		$_fi = new LR\FilterInfo('html');
		$n = ob_get_length() ? new LR\Html(ob_get_clean()) : ob_get_clean();
?>

        <input type="password" class="form-control mb-4 <?php
		if ($n !== '') {
			?>is-invalid<?php
		}
		else {
			?>is-valid<?php
		}
		?>" placeholder="<?php
		if ($n !== '') {
			echo LR\Filters::escapeHtmlAttr($n) /* line 15 */;
		}
		else {
			?>Nové heslo<?php
		}
		?>"<?php
		$_input = end($this->global->formsStack)["new"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		))->attributes() ?>>

        <?php
		ob_start(function () {});
		echo LR\Filters::escapeHtmlText(end($this->global->formsStack)["repeat"]->getError());
		$_fi = new LR\FilterInfo('html');
		$n = ob_get_length() ? new LR\Html(ob_get_clean()) : ob_get_clean();
?>

        <input type="password" class="form-control mb-4 <?php
		if ($n !== '') {
			?>is-invalid<?php
		}
		else {
			?>is-valid<?php
		}
		?>" placeholder="<?php
		if ($n !== '') {
			echo LR\Filters::escapeHtmlAttr($n) /* line 18 */;
		}
		else {
			?>Potvrdit heslo<?php
		}
		?>"<?php
		$_input = end($this->global->formsStack)["repeat"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		'class' => NULL,
		'placeholder' => NULL,
		))->attributes() ?>>

        <button class="btn btn-info btn-block my-4 blue-gradient" type="submit"<?php
		$_input = end($this->global->formsStack)["send"];
		echo $_input->getControlPart()->addAttributes(array (
		'class' => NULL,
		'type' => NULL,
		))->attributes() ?>>Změnit</button>
<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>    </form>
</div><?php
	}

}
