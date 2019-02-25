## WoW Guild Page
This project is a Laravel 5.3 web application which shows information and statistics related to a Guild on World of Warcraft. 

### Installing the codebase
This section assumes you have `composer`, `npm` and `gulp` already installed.
```sh
# Pull in PHP dependencies
composer install
# Pull in JavaScript dependencies
npm install
# Build CSS
gulp
```

### Configurating the application
In order to pull in data from Blizzard's API, the `.env` file should first be set up as a copy of `.env.example`.

| Key | Comment |
|-----|---------|
| DB_DATABASE | local database name |
| DB_USERNAME | local database user |
| DB_PASSWORD | database password for above user |
| WOW_CLIENT_ACCESS | Client access token for Blizzard API, see the [Blizzard documentation](https://develop.battle.net/access/clients) |
| WOW_CLIENT_SECRET | Client secret key for Blizzard API, see the [Blizzard documentation](https://develop.battle.net/access/clients) |
| WOW_REGION | In-game region on which realm is located |
| WOW_REALM | In-game realm in which guild is located |
| WOW_GUILD | In-game guild name |

Once `.env` has been set up run the following to build the database and set the application encryption key.
```sh
php artisan key:generate
php artisan migrate
```

### Loading in data
For the application to work there needs to be data in the database. This is retrieved from Blizzard's API using a variety of the available endpoints. A quick-start shell script can be used to populate the initial load of data. Note the first execution of this may be slow, depending on the number of guild members.
```sh
.getAll.sh
```
Once this data has been imported, then the application is ready to go. To run locally on port 8000 use
```sh
php artisan serve
```

### Scheduler
To regularly update data stored locally, a cron job can be set up to run Laravel's command scheduler every minute. 
```sh
php artisan schedule:run
```