<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Roadbikelife.
 *
 * @since  1.6
 */
class RoadbikelifeViewStravalogin extends JViewLegacy
{

    public function display($tpl = null)
    {
        $this->addToolbar();

        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JUri::base().'components/com_roadbikelife/assets/css/roadbikelife.css');
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('roadbikelife Konfiguration'), 'list menu-add');
//        JToolbarHelper::apply('item.apply');
//        JToolbarHelper::save('item.save');
        JToolbarHelper::preferences('com_roadbikelife');
    }
}
