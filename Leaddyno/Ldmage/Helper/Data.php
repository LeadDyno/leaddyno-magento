<?php
class Leaddyno_Ldmage_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function enabled()
    {
        return (bool)Mage::getStoreConfigFlag('ldmage/general/active');
    }


    public function config($field)
    {
        return Mage::getStoreConfig('ldmage/general/' . $field);
    }

}
