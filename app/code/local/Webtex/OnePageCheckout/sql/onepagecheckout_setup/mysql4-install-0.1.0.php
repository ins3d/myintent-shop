<?php

$this->startSetup();

$this->run("

CREATE TABLE IF NOT EXISTS {$this->getTable('onepage_customfields')} (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `step` int(1) NOT NULL,
    `field_name` varchar(15) NOT NULL,
    `field_type` varchar(12) NOT NULL,
    `field_label` varchar(30) NOT NULL,
    `field_value` text,
    `requred` int(1) NOT NULL default 0,
    `readonly` int(1) NOT NULL DEFAULT 0,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS {$this->getTable('onepage_customfields_order')} (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `field_id` int(10) unsigned NOT NULL,
    `order` varchar(20) NOT NULL,
    `field_value` text,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$this->endSetup();
