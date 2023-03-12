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
class RoadbikelifeViewCrawl extends JViewLegacy
{

	public function display($tpl = null)
    {
//	    $this->model = $this->getModel();
//	    $this->form = $this->model->getForm();
	    $this->addToolbar();




		parent::display($tpl);



        /* AJAX check  */
//            JFactory::getApplication()->input->set('tmpl', 'ajax');
//            header('Content-Type: application/json');
//            echo  $this->get('Crawl');
//            exit;


    }

	protected function addToolbar()
	{
		JToolbarHelper::title(JText::_('roadbikelife Crawler'), 'list menu-add');
		JToolbarHelper::preferences('com_roadbikelife');

		$bar = \Joomla\CMS\Toolbar\Toolbar::getInstance('toolbar');
//		$bar->appendButton('Standard', $icon, $alt, $task, $listSelect, $formId);

		$bar->appendButton('Link','icon-play', 'Crawler starten',false,'adminForm');
		$bar->appendButton('Link','icon-stop', 'Crawler stoppen',false,'adminForm');
	}
}
