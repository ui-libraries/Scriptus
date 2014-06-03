<?php 

/**
 * @package Scriptus\View\Helper
 */
	
class Scriptus_View_Helper_Scriptus extends Zend_View_Helper_Abstract
{
	public function scriptus($items)
	{
	    if (!is_array($items)) {
	        return $this->_getscriptus($items);
	    }

	    $scriptus = '';
	    foreach ($items as $item) {
	        $scriptus .= $this->_getScriptus($item);
	        release_object($item);
	    }
	    return $scriptus;
	}

	protected function _getScriptus(Item $item)
	{
		$title = $this->_getElementText($item, 'Title');
		$returntext = "Modified title: " . $title;
		return $returntext;
	}

	protected function _getElementText(Item $item, $elementName)
    {
        $elementText = metadata(
            $item,
            array('Dublin Core', $elementName),
            array('no_filter' => true, 'no_escape' => true, 'snippet' => 500)
        );
        return $elementText;
    }
}

?>