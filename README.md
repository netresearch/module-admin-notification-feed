Netresearch Admin Notification Feed Extension
=============================================

The Netresearch Admin Notification Feed extension for Magento® 2 allows
subscribing to news feeds that show up in the admin notifications.

Requirements
------------
* PHP >= 7.2

Compatibility
-------------
* Magento >= 2.3.0+

Installation Instructions
-------------------------

Install sources:

    composer require netresearch/module-admin-notification-feed

Enable module:

    ./bin/magento module:enable Netresearch_AdminNotificationFeed
    ./bin/magento setup:upgrade

Flush cache and compile:

    ./bin/magento cache:flush
    ./bin/magento setup:di:compile

Uninstallation
--------------

To unregister the carrier module from the application, run the following command:

    ./bin/magento module:uninstall --remove-data Netresearch_AdminNotificationFeed
    composer update

This will automatically remove source files, clean up the database, update package dependencies.

License
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2022 Netresearch DTT GmbH
