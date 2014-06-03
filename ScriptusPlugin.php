<?php 


class ScriptusPlugin extends Omeka_Plugin_AbstractPlugin 
{
	protected $_hooks = array(
		'public_items_show',
	);

	public function hookPublicItemsShow()
	{
		echo get_view()->scriptus(get_current_record('item'));
		
	}
}

?>