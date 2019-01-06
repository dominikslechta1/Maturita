<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Userpage/userpage.latte

use Latte\Runtime as LR;

class Templatedd8002afcf extends Latte\Runtime\Template
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
<div>
<?php
		if ($user->isLoggedIn()) {
?>

    <ul>
        <li>
            <?php echo LR\Filters::escapeHtmlText($user->getIdentity()->username) /* line 9 */ ?>

        </li>
        <li>
            <?php echo LR\Filters::escapeHtmlText($user->getIdentity()->email) /* line 12 */ ?>

        </li>
        <li><?php echo LR\Filters::escapeHtmlText($user->getRoles()[0]) /* line 14 */ ?></li>
    </ul>

    <a  class="btn btn-info  blue-gradient" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Userpage:changepass")) ?>">změnit heslo</a>


<?php
			if ($user->isInRole('administrator')) {
?>
        <p>administrator settings</p>
<?php
			}
		}
		else {
?>
        <p>nepřihlášen</p>
<?php
		}
		?></div><?php
	}

}
