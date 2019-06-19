<?php
/**
 * @package     PlanArchiv
 * @subpackage  Component.Site
 * @author      Thomas Hunziker <thomi.hunziker@sbb.ch>
 * @copyright   © 2017 - Thomas Hunziker
 * @license     http://www.gnu.org/licenses/gpl.html
 **/

defined('_JEXEC') or die;

use JeroenDesloovere\VCard\VCardParser;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;

require_once JPATH_COMPONENT_SITE . '/controllers/contact.php';

/**
 * Controller class for VCard upload
 *
 * @since  1.0.0
 */
class PlanarchivControllerVcard extends PlanarchivControllerContact
{
	/**
	 * Upload a file
	 *
	 * @return  void  Echoes an AJAX response
	 *
	 * @throws Exception
	 *
	 * @since 1.0.0
	 */
	public function upload()
	{
		// Check for request forgeries
		if (!JSession::checkToken('request'))
		{
			$response = array(
				'status' => '0',
				'error'  => Text::_('JINVALID_TOKEN'),
			);
			echo json_encode($response);

			return;
		}

		// Authorize User
		$user = JFactory::getUser();

		if (!$user->authorise('core.create', 'com_contact'))
		{
			$response = array(
				'status' => '0',
				'error'  => Text::_('JGLOBAL_AUTH_ACCESS_DENIED'),
			);
			echo json_encode($response);

			return;
		}

		// Initialise variables.
		/** @var JApplicationSite $app */
		$app    = JFactory::getApplication();
		$params = $app->getParams('com_planarchiv');
		$jinput = $app->input;

		// Get some data from the request
		$file = $jinput->files->get('file');

		if (!$file['name'])
		{
			$response = array(
				'status' => '0',
				'error'  => Text::_('COM_CONTACT_UPLOAD_FAILED'),
			);
			echo json_encode($response);

			return;
		}

		// Check file extension
		$ext = File::getExt($file['name']);

		if ($ext !== 'vcf')
		{
			$response = array(
				'status' => '0',
				'error'  => Text::sprintf('COM_CONTACT_FILETYPE_NOT_ALLOWED', $ext),
			);
			echo json_encode($response);

			return;
		}

		$vcard = VCardParser::parseFromFile($file['tmp_name']);
		$item = $vcard->getCardAtIndex(0);

		$contactModel = $this->getModel();
		$contact = $contactModel->getItem();
		$contact->published   = $user->authorise('core.edit.state', 'com_contact') ? 1 : 0;
		$contact->catid   = $params->get('vcard_category');
		$contact->name   = $item->fullname;

		if (!empty($item->address['WORK'][0]))
		{
			$contact->address   = $item->address['WORK'][0]->street;
			$contact->suburb   = $item->address['WORK'][0]->city;
			$contact->postcode   = $item->address['WORK'][0]->zip;
			$contact->state   = $item->address['WORK'][0]->region;
			$contact->country   = $item->address['WORK'][0]->country;
		}

		if (!empty($item->email[0][0]))
		{
			$contact->email_to   = $item->email[0][0];
		}

		if (!empty($item->url[0][0]))
		{
			$contact->website   = $item->url[0][0];
		}

		if (!empty($item->phone[0][0]))
		{
			$contact->telephone   = $item->phone[0][0];
		}

		$return = $contactModel->save($contact->getProperties());
		$errors = $contactModel->getErrors();
		return;
	}
}
