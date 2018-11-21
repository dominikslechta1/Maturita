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
			foreach ($iterator = $this->global->its[] = new LR\CachingIterator($projects) as $id => $item) {
				if ($iterator->last) {
					?>                <div class="project <?php echo LR\Filters::escapeHtmlAttr($id) /* line 8 */ ?>" style="border: none;">
<?php
				}
				else {
					?>                    <div class="project <?php echo LR\Filters::escapeHtmlAttr($id) /* line 10 */ ?>" >
<?php
				}
?>
                <div class="name">
                    <h1 style="margin: 0px;font-size: 30pt;">název: <?php echo LR\Filters::escapeHtmlText($item->Name) /* line 13 */ ?></h1>
                </div>
                <br>
                <div class="developer">
                    vývojář: <?php echo LR\Filters::escapeHtmlText($item->ref('users','User')->UserName) /* line 17 */ ?>

                </div>
                <div class="consultant">
                    consultant: <?php echo LR\Filters::escapeHtmlText($item->ref('users','Consultant')->UserName) /* line 20 */ ?>

                </div>
                <div class="oponent">
                    oponent: <?php echo LR\Filters::escapeHtmlText($item->ref('users','Oponent')->UserName) /* line 23 */ ?>

                </div>
                <div class="year">
                    v roce: <?php echo LR\Filters::escapeHtmlText($item->Year) /* line 26 */ ?>

                </div>
            </div>
<?php
				$iterations++;
			}
			array_pop($this->global->its);
			$iterator = end($this->global->its);
		}
		?></div><?php
	}

}
