<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Login/recoverpassword.latte

use Latte\Runtime as LR;

class Templateebf3d1a023 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'_flash' => 'blockFlash',
	];

	public $blockTypes = [
		'content' => 'html',
		'_flash' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		if (isset($username)) {
			echo LR\Filters::escapeHtmlText($username) /* line 60 */;
		}
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		if (isset($this->params['flash'])) trigger_error('Variable $flash overwritten in foreach on line 6');
		if (isset($this->params['error'])) trigger_error('Variable $error overwritten in foreach on line 15');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
		if ($flashes) {
?><div class="form-2">
        <div id="<?php echo htmlSpecialChars($this->global->snippetDriver->getHtmlId('flash')) ?>"><?php
			$this->renderBlock('_flash', $this->params) ?></div>    </div>
<?php
		}
?>
        <section>
<?php
		$form = $_form = $this->global->formsStack[] = $this->global->uiControl["newPassForm"];
		?>        <form class="form-2"<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormBegin(end($this->global->formsStack), array (
		'class' => NULL,
		), false) ?>>
            <h1><span class="log-in">Nové heslo</span></h1>
<?php
		if ($form->hasErrors()) {
?>            <ul class="errors">
<?php
			$iterations = 0;
			foreach ($form->errors as $error) {
				?>                <li><?php echo LR\Filters::escapeHtmlText($error) /* line 15 */ ?></li>
<?php
				$iterations++;
			}
?>
            </ul>
<?php
		}
		?>            <input type="hidden"<?php
		$_input = end($this->global->formsStack)["username"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		))->attributes() ?>>
            <input type="hidden"<?php
		$_input = end($this->global->formsStack)["hash"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		))->attributes() ?>>
            <p>
                <label<?php
		$_input = end($this->global->formsStack)["password"];
		echo $_input->getLabelPart()->attributes() ?>><i class="fa fa-lock"></i>Nové heslo</label>
                <input type="password" tabindex="2" placeholder="Heslo" class="showpassword"<?php
		$_input = end($this->global->formsStack)["password"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		'tabindex' => NULL,
		'placeholder' => NULL,
		'class' => NULL,
		))->attributes() ?>>
                <label for="showPassword"><input id="showPassword" tabindex="4" class="showpasswordcheckbox"
                                                 type="checkbox">Zobrazit heslo</label>
            </p>
            <p class="clearfix">
                <input type="submit" value="Změnit heslo"<?php
		$_input = end($this->global->formsStack)["send"];
		echo $_input->getControlPart()->addAttributes(array (
		'type' => NULL,
		'value' => NULL,
		))->attributes() ?>>
            </p>
<?php
		echo Nette\Bridges\FormsLatte\Runtime::renderFormEnd(array_pop($this->global->formsStack), false);
?>        </form>
        ​​
    </section>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $(".showpassword").each(function (index, input) {
                var $input = $(input);
                $("#showPassword").click(function () {
                    var change = $(this).is(":checked") ? "text" : "password";
                    var rep = $("<input placeholder='Heslo' type='" + change + "' />")
                            .attr("id", $input.attr("id"))
                            .attr("name", $input.attr("name"))
                            .attr('class', $input.attr('class'))
                            .val($input.val())
                            .insertBefore($input);
                    $input.remove();
                    $input = rep;
                })
            });
            $('#showPassword').click(function () {
                if ($("#showPassword").is(":checked")) {
                    $('.icon-lock').addClass('icon-unlock');
                    $('.icon-unlock').removeClass('icon-lock');
                } else {
                    $('.icon-unlock').addClass('icon-lock');
                    $('.icon-lock').removeClass('icon-unlock');
                }
            });
        });
    </script>
<?php
	}


	function blockFlash($_args)
	{
		extract($_args);
		$this->global->snippetDriver->enter("flash", "static");
		$iterations = 0;
		foreach ($flashes as $flash) {
			?>            <div class="alert alert-<?php echo LR\Filters::escapeHtmlAttr($flash->type) /* line 6 */ ?> fade in show">
                <p><?php echo LR\Filters::escapeHtmlText($flash->message) /* line 7 */ ?></p>
            </div>
<?php
			$iterations++;
		}
		$this->global->snippetDriver->leave();
		
	}

}
