<?php
/**
 * @copyright      Copyright (c) 2022 CommentRelaodCaptcha. All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_BASE . '/components/com_comment/helpers/captcha.php';

/**
 * CommentRelaodCaptcha - CommentRelaodCaptcha Helper Class.
 *
 * @package        Joomla.Site
 * @subpakage      CommentRelaodCaptcha.CommentRelaodCaptcha
 */
class modCommentRelaodCaptchaHelper
{
	public static function relaodCpatchaAjax()
	{
		$captchaClass = new ccommentHelperCaptcha();
		return $captchaClass->insertCaptcha('jform[security_refid]', 'default', '');
	}
}