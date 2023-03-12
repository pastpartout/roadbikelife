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
class RoadbikelifeViewCreatecontent extends JViewLegacy
{

    public function display($tpl = null)
    {
        $this->model = $this->getModel();
        $this->item = $this->get('Item');
        $this->form = $this->model->getForm( $this->item );
        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $isNew      = ($this->item->id === 0);

        JToolbarHelper::title(JText::_('roadbikelife Beitrag vorbereiten'), 'list menu-add');
        JToolbarHelper::preferences('com_roadbikelife');

        // For new records, check the create permission.
        if ($isNew)
        {
            JToolbarHelper::cancel('createcontent.cancel');
        } else {
            JToolbarHelper::apply('createcontent.apply');
            JToolbarHelper::cancel('createcontent.cancel');

        }
    }
}
