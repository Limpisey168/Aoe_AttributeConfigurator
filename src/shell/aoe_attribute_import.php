<?php

// @codingStandardsIgnoreStart
require_once 'abstract.php';
// @codingStandardsIgnoreEnd

/**
 * Class Aoe_AttributeConfigurator_Shell_Import
 *
 * @category Shell
 * @package  Aoe_AttributeConfigurator
 * @author   FireGento Team <team@firegento.com>
 * @author   AOE Magento Team <team-magento@aoe.com>
 * @license  Open Software License v. 3.0 (OSL-3.0)
 * @link     https://github.com/AOEpeople/AttributeConfigurator
 * @see      https://github.com/magento-hackathon/AttributeConfigurator
 */
class Aoe_AttributeConfigurator_Shell_Import extends Mage_Shell_Abstract
{
    const PARAM_RUN_ALL = 'runAll';

    const RUN_MODE_ALL = 'all';

    /**
     * Script run mode
     *
     * @var string
     */
    protected $_runMode;

    /**
     * Process identifier to run with
     *
     * @var string
     */
    protected $_identifier;

    /**
     * Validate the input parameters
     *
     * @return void
     */
    protected function _validate()
    {
        parent::_validate();

        $config = $this->checkConfig();
        if (!$config) {
            // @codingStandardsIgnoreStart
            die($this->configError());
            // @codingStandardsIgnoreEnd
        }

        $runAll = $this->getArg(self::PARAM_RUN_ALL);

        if (!$runAll) {
            // @codingStandardsIgnoreStart
            die($this->usageHelp());
            // @codingStandardsIgnoreEnd
        }

        if ($runAll) {
            $this->_runMode = self::RUN_MODE_ALL;
            return;
        }
    }

    /**
     * Run Script
     * @return void
     */
    public function run()
    {
        /** can't use G or -1 here because of GD2 */
        ini_set('memory_limit', '20000M');

        switch ($this->_runMode) {
            case self::RUN_MODE_ALL:
                $this->_runAll();
                break;

            default:
                // @codingStandardsIgnoreStart
                die($this->_usageHelp());
                // @codingStandardsIgnoreEnd
        }
    }

    /**
     * Run all import processors
     *
     * @return void
     */
    protected function _runAll()
    {
        $observer = $this->_getObserver();
        $observer->runAll();
    }

    /**
     * Get an Observer Model Instance
     *
     * @return Aoe_AttributeConfigurator_Model_Observer
     */
    protected function _getObserver()
    {
        return Mage::getModel('aoe_attributeconfigurator/observer');
    }

    /**
     * Retrieve usage help message
     *
     * @return string
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f aoe_attribute_import.php -- <options>

  Options:
  --runAll                                      Run complete Import
  help                                          This help

USAGE;
    }

    /**
     * Check if System Setting is correct
     *
     * @return string
     */
    protected function checkConfig()
    {
        /** @var Aoe_AttributeConfigurator_Helper_Config $helper */
        $helper = Mage::helper('aoe_attributeconfigurator/config');
        $configFilePath = $helper->getImportFilePath();
        return $helper->checkFile($configFilePath);
    }

    /**
     * Return Error Message
     *
     * @return string
     */
    protected function configError()
    {
        return <<<USAGE
Error: System Config Settings missing or File could not be read.

USAGE;
    }
}

/**
 * @var $shell Fraport_Import_Shell_Frapimport
 */
$shell = new Aoe_AttributeConfigurator_Shell_Import();
$shell->run();
