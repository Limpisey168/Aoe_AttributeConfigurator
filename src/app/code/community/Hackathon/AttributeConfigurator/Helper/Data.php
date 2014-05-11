<?php

/**
 * Class Hackathon_AttributeConfigurator_Helper_Data
 */
class Hackathon_AttributeConfigurator_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_PATH_FILENAME = 'catalog/attribute_configurator/product_xml_location';
    const XML_PATH_CURRENT_HASH = 'attributeconfigurator/hashes/current';

    public function getImportFilename()
    {
        return Mage::getBaseDir() . DS . trim(Mage::getStoreConfig(self::XML_PATH_FILENAME), '/\ ');
    }

    /**
     * Method creates md5 hash of a given file based on its content
     * Intent: We need to figure out when to re-import a file so we have to know when its content changes
     *
     * @param $file path and filename of Attribute Configuration XML
     *
     * @return bool|string
     */

    public function createFileHash($file)
    {
        if (file_exists($file)) {
            return md5_file($file);
        }

        return false;
    }

    /**
     * Check if the XML file is newer than the last imported one.
     *
     * return bool
     */
    public function isAttributeXmlNewer()
    {
        $filename        = $this->getImportFilename();
        $currentFileHash = Mage::getStoreConfigFlag(self::XML_PATH_CURRENT_HASH);
        $latestFileHash  = $this->createFileHash($filename);

        if ($latestFileHash !== $currentFileHash) {
            return true;
        }

        return false;
    }


}
