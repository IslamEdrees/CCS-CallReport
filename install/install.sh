#!/bin/bash

echo "Installing CCS Call Report"

DBNAME="your_database"

mysql -u root -p $DBNAME < ../sql/create_database.sql

mysql -u root -p $DBNAME < ../sql/views.sql

mkdir -p /var/www/html/callreport

cp ../web/* /var/www/html/callreport/

chown -R apache:apache /var/www/html/callreport

chmod -R 755 /var/www/html/callreport

echo "Done"