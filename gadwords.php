<?php
/**
 * 2007-2019 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2019 PrestaShop SA
 *  @version	Release: $Revision: 17142 $
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
	exit;
}

require_once dirname(__FILE__).'/classes/GAdwordsModuleManagement.php';

class GAdwords extends Module
{
	const PRESTASHOP_ADS_MODULE_NAME = 'emarketing';
	const PRESTASHOP_ADS_MODULE_ID = 18716;

	public $name;
	public $tab;
	public $version;
	public $author;
	public $bootstrap;
	public $displayName;
	public $description;
	public $ps_versions_compliancy;
	public $isPrestashop16;

	public function __construct()
	{
		$this->name = 'gadwords';
		$this->tab = 'advertising_marketing';
		$this->version = '2.0.0';
		$this->author = 'PrestaShop';
		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Google AdWords');
		$this->description = $this->l('You want to be more visible on Google and attract new clients ? Use our 75€ promo code on Google Adwords !');
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
		$this->isPrestashop16 = version_compare(_PS_VERSION_, '1.7.0.0', '<');
	}

	/**
	 * Install the module gadwords and manage the module Prestashop Ads
	 *
	 * @return bool
	 */
	public function install()
	{
		$moduleManagement = new GAdwordsModuleManagement;

		return parent::install() 
			&& $moduleManagement->moduleManagement(self::PRESTASHOP_ADS_MODULE_NAME, self::PRESTASHOP_ADS_MODULE_ID);
	}

	/**
	 * Load Assets 
	 *
	 * @return void
	 */
	private function loadAssets()
	{
		if ($this->isPrestashop16) {
			$this->context->controller->addCSS('//fonts.googleapis.com/icon?family=Material+Icons');
		}

		$this->context->controller->addCSS($this->_path.'views/css/gadwords.css');
	}

	/**
	 * Get the module's configuration link for Prestashop 1.6 or Prestashop 1.7
	 *
	 * @return string
	 */
	private function getModulePrestashopAdsLink()
	{
		if (!Module::isInstalled(self::PRESTASHOP_ADS_MODULE_NAME)) {
			return '';
		}

		if ($this->isPrestashop16) {
			return $this->context->link->getAdminLink('AdminModules').'&configure='.self::PRESTASHOP_ADS_MODULE_NAME;
		}

		return $this->context->link->getAdminLink(
			'AdminModules', 
			true, 
			false, 
			array('configure' => self::PRESTASHOP_ADS_MODULE_NAME)
		);
	}

	/**
	 * getContent
	 *
	 * @return string
	 */
	public function getContent()
	{
		$this->loadAssets();

		$this->context->smarty->assign(array(
			'module_dir' => $this->_path,
			'moduleLink' => $this->getModulePrestashopAdsLink(),
			'modulePrestashopAdsInstalled' => Module::isInstalled(self::PRESTASHOP_ADS_MODULE_NAME),
		));

		return $this->display(__FILE__, 'views/templates/admin/router.tpl');
	}
}
