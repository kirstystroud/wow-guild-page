#! /bin/bash

echo 'Loading data'
php artisan get:data

echo 'Loading characters'
php artisan get:characters

echo 'Loading ilvls'
php artisan get:ilvls

echo 'Loading titles'
php artisan get:titles

echo 'Loading reputation'
php artisan get:reputation

echo 'Loding raids'
php artisan get:raids

echo 'Loading professions'
php artisan get:professions --recipes=true

echo 'Loading statistics'
php artisan get:statistics

echo 'Loading achievements'
php artisan get:achievements

echo 'Loading quests'
php artisan get:quests

echo 'Loading pets'
php artisan get:pets

echo 'Loading auctions'
php artisan get:auctions

