<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Roadbikelife.
 *
 * @since  1.6
 */
class RoadbikelifeViewAnalytics extends JViewLegacy
{

    public function display($tpl = null)
    {
        $this->model = $this->getModel();
        $this->items = $this->get('Items');
        $this->chartsJs = $this->get('charts');
        $this->visits = $this->get('visits');
        $this->form = $this->model->getForm();
        $this->addToolbar();

        $doc = JFactory::getDocument();
        $doc->addScript('https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js');
        $doc->addScript('/node_modules/flot/dist/es5/jquery.flot.js');
        $doc->addScript('/node_modules/flot/source/jquery.flot.time.js');
        $doc->addScript('/node_modules/flot/source/jquery.flot.resize.js');
        $doc->addScript('/node_modules/flot/source/jquery.flot.hover.js');
        $doc->addScript('/node_modules/flot/source/jquery.flot.pie.js');
        $doc->addScript('/administrator/components/com_roadbikelife/assets/js/init_flot.js');
        $doc->addStyleSheet('/administrator/components/com_roadbikelife/assets/css/roadbikelife.css');
        $doc->addScriptDeclaration($this->chartsJs);

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        $isNew      = ($this->item->id === 0);

        JToolbarHelper::title(JText::_('roadbikelife Statistik'), 'list menu-add');
        JToolbarHelper::preferences('com_roadbikelife');

        // For new records, check the create permission.
        if ($isNew)
        {
            JToolbarHelper::cancel('createcontent.cancel');
        } else {
//            JToolbarHelper::apply('createcontent.apply');
            JToolbarHelper::cancel('createcontent.cancel');

        }
    }
}
