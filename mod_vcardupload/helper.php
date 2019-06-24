<?php
/**
 * @package     PlanArchiv
 * @subpackage  Module.VCardUpload
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die();

/**
 * Helper class for VCardUpload module
 *
 * @since  1.0.0
 */
abstract class ModVcarduploadHelper
{
	/**
	 * Function to determine max upload value
	 *
	 * @return  string  Lower PHP Setting Value
	 *
	 * @since 1.0.0
	 */
	static public function getMaxUploadValue()
	{
		// Check some PHP settings for upload limit so I can show it as an info
		$post_max_size       = ini_get('post_max_size');
		$upload_max_filesize = ini_get('upload_max_filesize');

		return (self::return_bytes($post_max_size) < self::return_bytes($upload_max_filesize)) ? $post_max_size : $upload_max_filesize;
	}

	/**
	 * Function to return bytes from the PHP settings. Taken from the ini_get() manual
	 *
	 * @param   string  $val  Value from the PHP setting
	 *
	 * @return  int  $val  Value in bytes
	 *
	 * @since 1.0.0
	 */
	static private function return_bytes($val)
	{
		$val  = trim($val);
		$last = strtolower($val[strlen($val) - 1]);
		$val  = (int) $val;

		switch ($last)
		{
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}
}
