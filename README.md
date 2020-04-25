# Test Order Application

## Architecture
Since the project required raw PHP, I decided to build a micro framework from scratch (googling code snippets don't count right?) to handle requests and make testing easier

It is based on the Model-View-Controller design pattern.

There are a few helper functions which make the core of the framework

1. Router - This receives the request and routes it to a specified controller. It can also accept a callback.
2. Request - This is a simple request bag. It automatically processes requests from POST/GET, or parses JSON requests
3. Dependency Injection Container - This is a simple dependency container that provides automatic injection. Very useful for tests.
4. Validator - This is a simple validator to validate our requests before storing them. The Request class implements this.
5. CSVDatabase - This is our database ORM class for the csv database
6. Helper - Various methods used throughout the application
    
### Lifecycle of Application

When a user hits the application, Apache .htaccess rewrites the URL to the index file (for clean urls).

The routes which are declared in the index.php file searches for a match, and passes the request to a specified Controller method/callback function (I didn't have time to write a Middleware, bear with me)

When the Controller method is fired, it is instantiated with the Request class, Helper class, and CSVDatabase class

Controller can process data from the request class, and return data (currently just raw data)

NB: There might probably be a few bugs/quirks with the application so please bear with me, I have not had lots of time this week.

The tests are only feature tests, I planned to write lots of Unit tests, but I didn't get the chance. I still went on to write a few Feature tests to demonstrate my understanding of testing

## Requirements
1. Docker
2. composer
3. PHP 7+
4. Node.js and NPM

## Installation

### Backend
Navigate to the backend folder and run the `./startup.sh` in your terminal. This should install composer packages (PHPUnit) and Provision the docker container
The container name is `test-app-container`

#### Testing
To run the test (PHPUnit Feature Tests), run the command below:

`docker exec -i test-app-container bash -c "cd /var/www/html && ./vendor/bin/phpunit tests"`


## Frontend
Navigate to the `frontend` directory and run `npm install -g @angular/cli` in your terminal. This installs the Angular CLI globally

After, run `npm install`

Navigate to `src/app/environments/env.ts` and modify the `apiUrl` variable to point to the URI of the application. The default URL from docker ss: `http://localhost:8080/api`

Run `ng serve` to start the development server.

The frontend is an Angular 9 based application.
	
