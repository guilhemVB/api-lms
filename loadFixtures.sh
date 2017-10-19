#!/bin/bash

echo "Mise à jour de la base de données :"
php bin/console doctrine:database:create
php bin/console doctrine:schema:drop --force --full-database
php bin/console doctrine:schema:create

echo "Import des devises :"
php bin/console app:import:currencies web/files/devises.csv

echo "Mise à jour des taux des devises :"
php bin/console app:update:rates

echo "Import des pays :"
php bin/console app:import:countries web/files/pays.csv

echo "Import des destinations :"
php bin/console app:import:destinations web/files/destinations.csv

echo "Calcul des destinations par défaut pour chaque pays :"
php bin/console app:update:defaultDestination

echo "Création du fichier pays geoJson :"
php bin/console app:update:geojson-country

echo "Chargement des fixtures :"
bin/behat -c behat_fixtures.yml -f progress