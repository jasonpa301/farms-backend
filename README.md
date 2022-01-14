# Solita farms backend
This is a laravel app created to serve as a backend for the Solita Dev Academy pre-assignment. The backend is run on docker by using laravels sail commands. The backend uses mainly PHP and will serve the frontend with REST endpoints. The data will be saved into a mySQL database.

I chose laravel because I wanted to use a language I was familiar with for the backend (PHP) and also to learn a new framework that has a solid foundation for creating a REST API, that would also be fairly easy to run with docker.

[The frontend of the project is located here](https://github.com/jasonpa301/farms-frontend)

## Requirements

- Docker
- Docker-compose
(This project was developed using docker desktop v4.3.1 on Windows 10)

If you are running the project on __Windows__ then the project must be ran inside a WSL2 distro e.g Ubuntu. Detailed instructions for this can be found from the [official documentation](https://laravel.com/docs/8.x/installation#getting-started-on-windows). 

## Set up

Clone the repo.

The .env file should be added to the root directory. I'll provide the .env file.

Dependencies can be installed with docker using the following command:
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

Next we use the following command to run the backend:
```
./vendor/bin/sail up
```

The container will be available at http://localhost:80 by default.
The container can be stopped by pressing Control + C, if for some reason the container is running in the background and you wish to stop it you can use the following command:
```
./vendor/bin/sail stop
```
## DB Set up
Next set up the database, using the following command:
```
./vendor/bin/sail artisan migrate
```
then fill the database with the data from the initial csv files with this command (this may take a minute or two):
```
./vendor/bin/sail artisan db:seed
```
**Now the backend is ready.** If you want to reset the database to this default state, you can run the following commands:
```
./vendor/bin/sail artisan migrate:fresh
./vendor/bin/sail artisan db:seed
```

## Tests
__Warning:__ running this command will also test the database migration and seeding, so will reset the database to it's initial state with data from csv files.

Run the following command to run the tests (it may take a minute or two):
 ```
./vendor/bin/sail artisan test
```

