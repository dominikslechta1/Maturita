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

<?php
			ob_start(function () {});
			?>            <span class="error"><?php
			ob_start();
			echo LR\Filters::escapeHtmlText(end($this->global->formsStack)["email"]->getError());
			$this->global->ifcontent = ob_get_flush();
?></span>
<?php
			if (rtrim($this->global->ifcontent) === "") ob_end_clean();
			else echo ob_get_clean();
			?>            <input type="email" id="defaultLoginFormEmail" class="form-control mb-4" placeholder="E-mail"<?php
			$_input = end($this->global->formsStack)["email"];
			echo $_input->getControlPart()->addAttributes(array (
			'type' => NULL,
			'id' => NULL,
			'class' => NULL,
			'placeholder' => NULL,
			))->attributes() ?>>

<?php
			ob_start(function () {});
			?>            <span class="error"><?php
			ob_start();
			echo LR\Filters::escapeHtmlText(end($this->global->formsStack)["password"]->getError());
			$this->global->ifcontent = ob_get_flush();
?></span>
<?php
			if (rtrim($this->global->ifcontent) === "") ob_end_clean();
			else echo ob_get_clean();
			?>            <input type="password" id="defaultLoginFormPassword" class="form-control mb-4" placeholder="Heslo"<?php
			$_input = end($this->global->formsStack)["password"];
			echo $_input->getControlPart()->addAttributes(array (
			'type' => NULL,
			'id' => NULL,
			'class' => NULL,
			'placeholder' => NULL,
			))->attributes() ?>>
            
<div>
                <a href="#" data-toggle="modal" data-target="#orangeModalSubscription">Zapomenuté heslo?</a>
            </div>
            <button class="btn btn-info btn-block my-4 blue-gradient" type="submit"<?php
			$_input = end($this->global->formsStack)["login"];
			echo $_input->getControlPart()->addAttributes(array (
			'class' => NULL,
			'type' => NULL,
			))->attributes() ?>>Přihlásit</button>
<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>        </form>


        <div class="modal fade" id="orangeModalSubscription" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true" data-backdrop="false">
            <div class="modal-dialog modal-notify modal-info" role="document">
                <!--Content-->
                <div class="modal-content">
                    <!--Header-->
                    <div class="modal-header text-center blue-gradient">
                        <h4 class="modal-title white-text w-100 font-weight-bold py-2">Žádost</h4>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" class="white-text">&times;</span>
                        </button>
                    </div>
<?php
			$form = $_form = $this->global->formsStack[] = $this->global->uiControl["forgetPassword"];
			?>                    <form class=form<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
			'class' => NULL,
			), false) ?>>
                    <!--Body-->
                    <div class="modal-body">
                        <div class="modal-top">
                        <p>zadej svůj email, který používáš na těchto stránkách a přijde ti možnost změny hesla </p>
                    </div>
                        
                        <div class="md-form mb-4">
                            <input type="email" id="form2" class="form-control validate"<?php
			$_input = end($this->global->formsStack)["email"];
			echo $_input->getControlPart()->addAttributes(array (
			'type' => NULL,
			'id' => NULL,
			'class' => NULL,
			))->attributes() ?>>
                            <label data-error="" data-success="" for="form2">Tvůj email</label>
                        </div>
                    </div>

                    <!--Footer-->
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-outline-light-blue waves-effect"<?php
			$_input = end($this->global->formsStack)["send"];
			echo $_input->getControlPart()->addAttributes(array (
			'type' => NULL,
			'class' => NULL,
			))->attributes() ?>>Odeslat</button>
                    </div>
<?php
			echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>                    </form>
                </div>
                <!--/.Content-->
            </div>
        </div>
        <?php
			if (isset($success)) {
				echo LR\Filters::escapeHtmlText($success) /* line 57 */;
			}
?>

<?php
		}
		elseif ((!$user->isInRole('guest'))) {
?>

        <p>
<?php
			if (isset($userse)) {
				?>                <?php echo LR\Filters::escapeHtmlText($userse) /* line 62 */ ?>

<?php
			}
?>
        </p>
        <a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Logout:")) ?>">Chcete se odhlásit?</a>
<?php
		}
		if (isset($alert)) {
			?>    <script type='text/javascript'>alert(<?php echo LR\Filters::escapeJs($alert) /* line 68 */ ?>);</script>
<?php
		}
		?></div><?php
	}

}
