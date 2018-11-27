<?php
// source: C:\wamp\www\Maturita\app\presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Template9f64267cb0 extends Latte\Runtime\Template
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
		if (isset($this->params['id'])) trigger_error('Variable $id overwritten in foreach on line 6');
		if (isset($this->params['item'])) trigger_error('Variable $item overwritten in foreach on line 6');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>
<div class="projects-div">
<?php
		if ($projects !== null) {
			$iterations = 0;
			foreach ($projects as $id => $item) {
				?>            <div class="project <?php echo LR\Filters::escapeHtmlAttr($id) /* line 7 */ ?>">
                <div class="name">
                    <h1 style="margin: 0px;font-size: 30pt;"><?php echo LR\Filters::escapeHtmlText($item->Name) /* line 9 */ ?></h1>
                </div>
                <br>
                <div class="developer">
                    Vytvořil: <?php echo LR\Filters::escapeHtmlText($item->ref('users','User')->UserName) /* line 13 */ ?>

                </div>
                <div class="consultant">
                    consultant: <?php echo LR\Filters::escapeHtmlText($item->ref('users','Consultant')->UserName) /* line 16 */ ?>

                </div>
                <div class="oponent">
                    oponent: <?php echo LR\Filters::escapeHtmlText($item->ref('users','Oponent')->UserName) /* line 19 */ ?>

                </div>
                <div class="year">
                    v roce: <?php echo LR\Filters::escapeHtmlText($item->Year) /* line 22 */ ?>

                </div>
            </div>
<?php
				$iterations++;
			}
		}
		?></div><?php
	}

}
