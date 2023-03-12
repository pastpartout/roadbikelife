<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Roadbikelife
 * @author     Stephan Riedel <job@pastpartout.com>
 * @copyright  2019 Stephan Riedel
 * @license    GNU General Public License Version 2 oder später; siehe LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

jimport('joomla.application.component.modellist');
jimport('joomla.component.models.memberarea');
JLoader::register('RoadbikelifeModel', __DIR__ . '/roadbikelife.php');
JLoader::register('fwGalleryModelFile', JPATH_BASE . '/components/com_fwgallery/models/file.php');


class RoadbikelifeModelAnalytics extends JModelLegacy
{

    private $fromDate = '';
    private $toDate = '';
    public $chartTypes = [
        'VisitChart' => [],
        'BrowserChart' => [],
        'DeviceChart' => [],
        'CountryChart' => [],
        'CityChart' => [],
    ];

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
        // List state information.
        parent::populateState("a.id", "ASC");

        $context = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $context);

        // Split context into component and optional section
        $parts = FieldsHelper::extract($context);

        if ($parts)
        {
            $this->setState('filter.component', $parts[0]);
            $this->setState('filter.section', $parts[1]);
        }
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

                
                    return parent::getStoreId($id);
                
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getVisitorItems()
	{

        $fromDate = $this->getFromDate();
        $toDate = $this->getToDate();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = "
            SELECT *,count(*) as visitors
            FROM #__cwtraffic 
            WHERE tm > $fromDate AND tm < $toDate AND browser != 'Robot' AND ip != '127.0.0.1'
            GROUP BY tm DIV 3600 
            ORDER BY tm
        ";
        $db->setQuery($query);
        if ($items = $db->loadObjectList()) {

            $this->visitorItems = $items;
            return $items;
        }
	}

	public function getVisits() {
        if($this->visitorItems) {
            $visitors = 0;

            foreach ($this->visitorItems as $item) {
                $visitors += $item->visitors;
            }

            return $visitors;
        }
    }

	public function getBrowserItems()
	{
        $fromDate = $this->getFromDate();
        $toDate = $this->getToDate();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = "
            SELECT *, count(*) as visitors
            FROM #__cwtraffic 
            WHERE tm > $fromDate AND tm < $toDate AND browser != 'Robot'
            group by browser  
            order by browser
        ";
        $db->setQuery($query);
        if ($items = $db->loadObjectList()) {
            return $items;
        }
	}
	public function getDeviceItems()
	{
        $fromDate = $this->getFromDate();
        $toDate = $this->getToDate();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = "
            SELECT *, count(*) as visitors
            FROM #__cwtraffic 
            WHERE tm > $fromDate AND tm < $toDate AND browser != 'Robot'
            group by platform  
            order by platform
        ";
        $db->setQuery($query);
        if ($items = $db->loadObjectList()) {
            return $items;
        }
	}
	public function getCountryItems()
	{
        $fromDate = $this->getFromDate();
        $toDate = $this->getToDate();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = "
            SELECT *, count(*) as visitors
            FROM #__cwtraffic 
            WHERE tm > $fromDate AND tm < $toDate AND browser != 'Robot' AND country_name != ''
            group by country_name  
            order by country_name
        ";
        $db->setQuery($query);
        if ($items = $db->loadObjectList()) {
            return $items;
        }
	}
	public function getCityItems()
	{
        $fromDate = $this->getFromDate();
        $toDate = $this->getToDate();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query = "
            SELECT *, count(*) as visitors
            FROM #__cwtraffic 
            WHERE tm > $fromDate AND tm < $toDate AND browser != 'Robot' AND city != ''
            group by city
            order by city
        ";
        $db->setQuery($query);
        if ($items = $db->loadObjectList()) {
            return $items;
        }
	}

	private function getFromDate() {
        $jinput = JFactory::getApplication()->input;
        $fieldsData = $jinput->getArray(array(
            'general' => 'RAW',
        ));
        $this->fromDate = strtotime($fieldsData['general']['fromDate']);

        if(!$this->fromDate) {
            $this->fromDate = strtotime('now - 24 hours');
        }
        return $this->fromDate;
    }

    private function getToDate() {
        $jinput = JFactory::getApplication()->input;
        $fieldsData = $jinput->getArray(array(
            'general' => 'RAW',
        ));
        $this->toDate = strtotime($fieldsData['general']['toDate']);
        if(!$this->toDate) {
            $this->toDate = strtotime('now');
        }
        return $this->toDate;
    }

    public function getCharts() {
	    foreach ($this->chartTypes as $chartTypeName => $chartType) {
	        $functionName = 'get'.ucfirst($chartTypeName);
            $json[$chartTypeName]['data'] = $this->$functionName();
        }

	    $js = 'const chartsJson = '.json_encode($json);
	    return $js;
    }

    public function getVisitChart() {
        $items = $this->getVisitorItems();
        $fromDate = $this->getFromDate();
        $counter = (strtotime('now') - $fromDate) / 3600;
        $counter = (int)$counter;

        foreach ($items as $key => $item) {
            $jsDataArray[] = [$item->tm,$item->visitors];
        }

        return $jsDataArray;
    }

    public function getBrowserChart() {
        $items = $this->getBrowserItems();
        foreach ($items as $key => $item) {
            $jsDataArray[] = [
                'label' => $item->browser,
                'data' => $item->visitors,
            ];
        }

        return $jsDataArray;
    }

    public function getDeviceChart() {
        $items = $this->getDeviceItems();
        foreach ($items as $key => $item) {
            $jsDataArray[] = [
                'label' => $item->platform,
                'data' => $item->visitors,
            ];
        }

        return $jsDataArray;
    }

    public function getCountryChart() {
        $items = $this->getCountryItems();
        foreach ($items as $key => $item) {
            $jsDataArray[] = [
                'label' => $item->country_name,
                'data' => $item->visitors,
            ];
        }

        return $jsDataArray;
    }

    public function getCityChart() {
        $items = $this->getCityItems();
        foreach ($items as $key => $item) {
            $jsDataArray[] = [
                'label' => $item->city,
                'data' => $item->visitors,
            ];
        }

        return $jsDataArray;
    }

    public function getForm()
    {
        $jinput = JFactory::getApplication()->input;
        $fieldsData = $jinput->getArray(array(
            'general' => 'RAW',
        ));


        $form = JForm::getInstance('com_rbl.chart', JPATH_COMPONENT_ADMINISTRATOR . '/models/forms/analytics.xml');
        if (empty($form)) {
            return false;
        }

        foreach ($form->getFieldsets() as $fieldset) {
            $fields = $form->getFieldset($fieldset->name);
            if (count($fields)) {
                foreach ($fields as &$field) {
                    $value = $fieldsData[$field->group][$field->fieldname];

                    if ($field->type == 'calendar') {
//                        $value = JHtml::date($value, 'd.m.YYYY');
                    }

                    $form->setValue($field->fieldname, $field->group, $value);
                }
            }
        }


        return $form;
    }

}
