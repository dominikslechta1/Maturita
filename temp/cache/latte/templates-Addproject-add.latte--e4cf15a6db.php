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
?>
<div class="card-body px-lg-5 pt-0">
<?php
		if (isset($projectId)) {
			?>        <?php echo LR\Filters::escapeHtmlText($projectId) /* line 6 */ ?>

<?php
		}
		/* line 8 */ $_tmp = $this->global->uiControl->getComponent("addprojectForm");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
		$_tmp->render();
?>
</div>

<form class="border border-light p-5">

    <p class="h4 mb-4 text-center">Sign in</p>

    <label for="textInput">Text input Label</label>
    <input type="text" id="textInput" class="form-control mb-4" placeholder="Text input">

    <label for="textarea">Textarea Label</label>
    <textarea id="textarea" class="form-control mb-4" placeholder="Textarea"></textarea>

    <label for="select">Default select</label>
    <select class="browser-default custom-select mb-4" id="select">
        <option value="" disabled="" selected="">Choose your option</option>
        <option value="1">Option 1</option>
        <option value="2">Option 2</option>
        <option value="3">Option 3</option>
    </select>

    <label for="select">Default select</label>
    <select class="browser-default custom-select mb-4" id="select">
        <option value="" disabled="" selected="">Choose your option</option>
        <option value="1">Option 1</option>
        <option value="2">Option 2</option>
        <option value="3">Option 3</option>
    </select>

    <label for="select">Default select</label>
    <select class="browser-default custom-select mb-4" id="select">
        <option value="" disabled="" selected="">Choose your option</option>
        <option value="1">Option 1</option>
        <option value="2">Option 2</option>
        <option value="3">Option 3</option>
    </select>

<div class="file-upload-wrapper">
  <input type="file" id="input-file-now" class="file-upload">
</div>

    <div class="custom-control custom-checkbox mb-4">
        <input type="checkbox" class="custom-control-input" id="checkbox">
        <label class="custom-control-label" for="checkbox">Default checkbox</label>
    </div>

    <button class="btn btn-info btn-block my-4" type="submit">Sign in</button>

    <div class="text-center">
        <p>Not a member?
            <a href="">Register</a>
        </p>

        <p>or sign in with:</p>
        <a type="button" class="light-blue-text mx-2">
            <i class="fa fa-facebook"></i>
        </a>
        <a type="button" class="light-blue-text mx-2">
            <i class="fa fa-twitter"></i>
        </a>
        <a type="button" class="light-blue-text mx-2">
            <i class="fa fa-linkedin"></i>
        </a>
        <a type="button" class="light-blue-text mx-2">
            <i class="fa fa-github"></i>
        </a>
    </div>
</form>
<?php
	}

}
