<?php
// No direct access to this file
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
/**
 * Script file of kunena Cleaner module
 */
class mod_KunenaCleanerInstallerScript
{
	/**
	 * Method to install the extension
	 * $parent is the class calling this method
	 *
	 * @return void
	 */
	function install($parent) 
	{
		echo "Kunena Cleaner has successfully cleaned your Kunena installation.";
	}
 
	/**
	 * Method to uninstall the extension
	 * $parent is the class calling this method
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		echo '<p>The module has been uninstalled</p>';
	}
 
	/**
	 * Method to update the extension
	 * $parent is the class calling this method
	 *
	 * @return void
	 */
	function update($parent) 
	{
		echo "<p>The module has been updated to version";
	}
 
	/**
	 * Method to run before an install/update/uninstall method
	 * $parent is the class calling this method
	 * $type is the type of change (install, update or discover_install)
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{         
		$application = JFactory::getApplication();
		if (file_exists(JPATH_SITE .'/administrator/components/com_kunena/kunena.xml')) 
		{
			$xml = JFactory::getXML(JPATH_SITE .'/administrator/components/com_kunena/kunena.xml');
			$version = (string)$xml->version;
			if($version=='4.0.11'|| $version=='4.0.12')
			{
				echo "";
			} else {
				$application->enqueueMessage(JText::_('Kunena Cleaner requires Kunena 4.0.11 or 4.0.12 to be installed first. Install one of these versions and try to install again. Your files were left untouched.'), 'error');
				return false;
			}
        } else {
		$application->enqueueMessage(JText::_('Kunena Cleaner requires Kunena 4.0.11 or 4.0.12 to be installed first. Install one of these versions and try to install again. Your files were left untouched.'), 'error');
        return false;
		}
	}
 
	/**
	 * Method to run after an install/update/uninstall method
	 * $parent is the class calling this method
	 * $type is the type of change (install, update or discover_install)
	 *
	 * @return void
	 */ 
	function postflight($type, $parent) 
	{
		if (file_exists(JPATH_SITE .'/media/mod_kunenacleaner/upload/administrator/components/com_kunena/template/joomla25/cpanel/default.php')) {
		JFile::copy(JPATH_SITE .'/media/mod_kunenacleaner/upload/administrator/components/com_kunena/template/joomla25/cpanel/default.php', JPATH_SITE .'/administrator/components/com_kunena/template/joomla25/cpanel/default.php');
	}
		if (file_exists(JPATH_SITE .'/media/mod_kunenacleaner/upload/administrator/components/com_kunena/template/joomla30/cpanel/default.php')) {
		JFile::copy(JPATH_SITE .'/media/mod_kunenacleaner/upload/administrator/components/com_kunena/template/joomla30/cpanel/default.php', JPATH_SITE .'/administrator/components/com_kunena/template/joomla30/cpanel/default.php');
	}
		if (file_exists(JPATH_SITE .'/media/mod_kunenacleaner/upload/components/com_kunena/template/blue_eagle/html/credits/default.php')) {
		JFile::copy(JPATH_SITE .'/media/mod_kunenacleaner/upload/components/com_kunena/template/blue_eagle/html/credits/default.php', JPATH_SITE .'/components/com_kunena/template/blue_eagle/html/credits/default.php');
	}
		if (file_exists(JPATH_SITE .'/media/mod_kunenacleaner/upload/components/com_kunena/template/crypsis/layouts/credits/default.php')) {
		JFile::copy(JPATH_SITE .'/media/mod_kunenacleaner/upload/components/com_kunena/template/crypsis/layouts/credits/default.php', JPATH_SITE .'/components/com_kunena/template/crypsis/layouts/credits/default.php');
	}
		if (file_exists(JPATH_SITE .'/media/mod_kunenacleaner/upload/libraries/kunena/view.php')) {
		JFile::copy(JPATH_SITE .'/media/mod_kunenacleaner/upload/libraries/kunena/view.php',JPATH_SITE .'/libraries/kunena/view.php');
	}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$conditions = array(
		$db->quoteName('element') . ' = "mod_kunenacleaner"', 
		$db->quoteName('type') . ' = ' . $db->quote('module'));
		$query->delete($db->quoteName('#__extensions'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->execute();
                $query1 = $db->getQuery(true);
		$conditions = array(
		$db->quoteName('module') . ' = "mod_kunenacleaner"', 
		);
		$query1->delete($db->quoteName('#__modules'));
		$query1->where($conditions);
		$db->setQuery($query1);
		$db->execute();
                
                $query2 = $db->getQuery(true);
                $query2='UPDATE #__update_sites SET enabled="0" WHERE (name="Kunena 4.0 Update Site" or name="Kunena 5.0 Update Site") and enabled=1';
                $db->update($query2);

                $db->setQuery($query2);

                $result = $db->execute();
		JFolder::delete(JPATH_ROOT . '/media/mod_kunenacleaner');
		JFolder::delete(JPATH_ROOT . '/modules/mod_kunenacleaner');
	}
}