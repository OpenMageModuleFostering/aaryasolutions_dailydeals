<?php 


$installer = $this;
$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS `dailydeals` (
  `dailydeals_id` int(11) NOT NULL AUTO_INCREMENT,
  `dailydeals_date` date NOT NULL,
  `dailydeals_product` int(11) NOT NULL,
  PRIMARY KEY (`dailydeals_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23;
");

$installer->endSetup();

