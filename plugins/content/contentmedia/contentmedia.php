<?php
/**
 * @copyright    Copyright (c) 2019 Roadbikelife. All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access


use Joomla\CMS\Layout\LayoutHelper;

require_once __DIR__ . '/phpQuery/phpQuery.php';


defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');

/**
 * content - Content Images Plugin
 *
 * @package        Joomla.Plugin
 * @subpakage    Roadbikelife.ContentImages
 */
class plgcontentContentMedia extends JPlugin
{

    /**
     * Constructor.
     *
     * @param    $subject
     * @param array $config
     */


    function __construct(&$subject, $config = array())
    {
        // call parent constructor
        parent::__construct($subject, $config);
    }

    public function onContentPrepare($context, $article, $isNew)
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        JLoader::register('RoadbikelifeHelper', JPATH_ADMINISTRATOR . '/components/com_roadbikelife/helpers/roadbikelife.php');
        $images = json_decode($article->images) ??  new stdClass();
        $componentParams = \Joomla\CMS\Component\ComponentHelper::getParams('com_roadbikelife');


        // set default Fulltext Image fr blog
        if (!isset($images->image_fulltext) && $article->catid === 8) {
            $defaultImg = $componentParams->get('default_article_header_img');
            $images->image_introtext = $defaultImg;
            $images->image_fulltext = $defaultImg;
            $article->images = json_encode($images);
        }

        if ($app->isClient('site') && $context === 'com_content.article') {
            $this->processContentImageLinks($article);
            $this->processContentImages($article);
            $this->processContentShortCodes($article);
        }
    }

    private function processContentImageLinks(&$article)
    {
        $matches = [];
        $regex = '/{contentimagelink(.*?)\}/';
        preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

        if (count($matches)) {
            foreach ($matches as $match) {
                $matches = explode('|', $match[1]);
                $number = trim($matches[0]);
                $text = trim($matches[1]);
                $html = "<a href='#post-gallery-$number'>$text</a>";
                $article->text = preg_replace($regex, $html, $article->text, 1);
            }

        }

    }

    private function processContentShortCodes(&$article)
    {
        $matches = [];
        $regex = '/{contentimage(.*?)\}/';
        preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

        if (count($matches)) {
            foreach ($matches as $match) {
                $matches = explode('|', $match[1]);
                $subTitle = '';
                $size = '';

                if (isset($matches)) {
                    if (isset($matches)) {
                        $url = trim($matches[0]);

                        if (substr($url, 0, 1) == '/') {
                            $url = substr($url, 1);
                        }

                        if (preg_match('/href=\"(.*?)\"/', $url, $urlMatch)) {
                            $url = $urlMatch[1];
                        }

                    }

                    if (isset($matches[1])) {
                        $subTitle = trim($matches[1]);
                    }

                    if (isset($matches[2])) {
                        $size = trim($matches[2]);
                    }
                }

                $html = JLayoutHelper::render('tmpl.contentimage', [
                    'size' => $size,
                    'url' => $url,
                    'subTitle' => $subTitle,
                ], __DIR__);
                $article->text = preg_replace($regex, $html, $article->text, 1);
            }
        }

    }

    private function processContentImages(&$article)
    {

        $doc = PhpQuery::newDocument($article->text);

        foreach ($doc['.rbl-img'] as $image) {
            $image = pq($image);
            if ($image->parent()->elements[0]->tagName !== 'source') {
                $image->replaceWith($this->replaceImageWithPicture($image));
            }
        }

        $article->text = $doc->htmlOuter();

    }

    private function replaceImageWithPicture($pqImageObject)
    {
        $displayData = new stdClass();
        $displayData->src = $pqImageObject->attr('src');
        $displayData->alt = $pqImageObject->attr('alt');
        return pq(LayoutHelper::render($this->content_image_tmpl_path, $displayData, __DIR__));
    }

}
