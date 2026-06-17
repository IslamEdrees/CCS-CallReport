#!/bin/bash

echo "Installing CCS Call Report"

mysql -u root -p aheevaccs < ../sql/create_database.sql

mysql -u root -p aheevaccs < ../sql/views.sql

mkdir -p /var/www/html/callreport

cp ../web/* /var/www/html/callreport/

chown -R apache:apache /var/www/html/callreport

chmod -R 755 /var/www/html/callreport

echo "Done"
