<?php
/**
* HTML_TreeMenu_Listbox class
*
* This class presents the menu as a listbox
*/
class HTML_TreeMenu_Listbox extends HTML_TreeMenu_Presentation
{
    /**
    * The text that is displayed in the first option
    * @var string
    */
    var $promoText;

    /**
    * The character used for indentation
    * @var string
    */
    var $indentChar;

    /**
    * How many of the indent chars to use
    * per indentation level
    * @var integer
    */
    var $indentNum;

    /**
    * Target for the links generated
    * @var string
    */
    var $linkTarget;

    /**
    * Constructor
    *
    * @param object $structure The menu structure
    * @param array  $options   Options whic affect the display of the listbox.
    *                          These can consist of:
    *                           o promoText  The text that appears at the the top of the listbox
    *                                        Defaults to "Select..."
    *                           o indentChar The character to use for indenting the nodes
    *                                        Defaults to "&nbsp;"
    *                           o indentNum  How many of the indentChars to use per indentation level
    *                                        Defaults to 2
    *                           o linkTarget Target for the links. Defaults to "_self"
    *                           o submitText Text for the submit button. Defaults to "Go"
    */
    function __construct($structure, $options = array())
    {
        parent::__construct($structure);

        $this->promoText  = null;
        $this->indentChar = '&nbsp;';
        $this->indentNum  = 2;
        $this->linkTarget = '_self';
        $this->submitText = 'Go';

        foreach ($options as $option => $value) {
            $this->$option = $value;
        }
    }

    /**
    * Returns the HTML generated
    */
    function toHTML()
    {
        static $count = 0;
        $nodeHTML = '';

        /**
        * Loop through subnodes
        */
        if (isset($this->menu->items)) {
            for ($i=0; $i<count($this->menu->items); $i++) {
                $nodeHTML .= $this->_nodeToHTML($this->menu->items[$i]);
            }
        }

        if ($this->promoText) {
            return sprintf('<option value="">%s</option>%s', $this->promoText, $nodeHTML);
        }
        else {
            return $nodeHTML;
        }
    }

    /**
    * Returns HTML for a single node
    *
    * @access private
    */
    function _nodeToHTML($node, $prefix = '')
    {
        $html = sprintf('<option value="%s">%s%s</option>', $node->id, $prefix, $node->text);

        /**
        * Loop through subnodes
        */
        if (isset($node->items)) {
            for ($i=0; $i<count($node->items); $i++) {
                $html .= $this->_nodeToHTML($node->items[$i], $prefix . str_repeat($this->indentChar, $this->indentNum));
            }
        }

        return $html;
    }
} // End class HTML_TreeMenu_Listbox
