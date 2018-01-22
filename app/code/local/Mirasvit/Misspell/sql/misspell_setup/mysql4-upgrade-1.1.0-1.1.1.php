<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at http://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   Search Spell-Correction
 * @version   1.2.5
 * @build     345
 * @copyright Copyright (C) 2015 Mirasvit (http://mirasvit.com/)
 */




$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE {$this->getTable('misspell/misspell')} CHANGE `freq` `freq` DECIMAL(12,4) NOT NULL DEFAULT '0';
");

$installer->endSetup();
