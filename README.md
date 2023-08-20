## Prerequisites

---

Make sure you have insalled the following prerequisites in your production machine.

-   `Docker` - [Download & install Docker](https://docs.docker.com/get-docker/) make sure that the latest docker version has been installed on your machine.

### Technologies

---

List of technologies which are used in this project.

-   PHP : Version 8.2
-   Laravel: Version 10.x
-   MySQL: Version 8
-   Node: Version 14.15.0

### Setup Environment for Development:

- Clone this repository `git clone git@github.com:chege-maina/tradler.git`
- After clone, cd into the project folder `cd tradler`.
- Make sure you have docker installed on your local machine, you do not need to have php / mysql / redis / node installed on your machine
- Copy `.env` file: `cp .env.example .env`in Linux and Mac or `copy .env.example .env` Windows.
- Set the environment variables in `.env` file
- Run command: `docker-compose up --build -d`
-  Run the container in bash mode: `docker exec -it Tradler_php /bin/sh`
- Inside this container now you can run all the commands as if if you are on local environment:
- Run: `chown -R www-data:www-data *`
- Install composer dependencies: `composer install`
- Generate key: `php artisan key:generate`
- Run migration: `php artisan migrate`
- Run seeder: `php artisan db:seed`
- Install javascript dependencies: `npm install`
- Compile the assets: `npm run build`   
- You can access the project at: `http://localhost:8000`

### Deploying on Laravel Vapor:

-   You need to have an AWS account and a Laravel Vapor account.
-   Link the Vapor account to AWS account.
-   In this project folder open terminal and type `composer require laravel/vapor-cli` to add vapor CLI
-   Type `php vendor/bin/vapor login` on the terminal to login to your vapor account and provide the password.
-   Create a project in the vapor account and a database.
-   For deploying, edit the `vapor.yml` file in this project folder to have the details in your Vapor project.
-   To deploy, type `php vendor/bin/vapor deploy production` in the project folder to deploy to the production environment.

## About

This is a simple app to create users, list users, edit a specific user, delete a specific user and get a specific user. The app is built on Laravel framework as backend with a Vue Js user interface
frontend to test the API calls. The app creates users with two roles Admin and User role. The users details are validated and a first time password created and hashed. There's no authentication and authorization for now but for future the password is included. 
On creation the backend logic automatically assigns the user customers based on the role selected on user creation. The customer data is got from two CSV files based on the user role condition (Admin a separate file from User role). This is to simulate uploading a CSV file with user's customer data per user on user creation. The data from the files is bulk so the need for batch processing, the data is broken to chunks and each chunk creates a background job stored in redis and processed in a queue. 
I have reduced the files to a total of 30 customers for admin and 80 customers for user in the CSV files, breaking each set of data into chunks of 10 to ease demonstration and quick test. Bulk data can be processed in chunks of 2000 and so on. I have further delayed job execution by 10 seconds to allow for visually seeing the list of customers being processed in the background sequentially.

## Usage 

Click on Add User button to create a new user, fill in the form in the pop-up modal selecting the role and providing an email and name for the user. Click Save button and the data is sent to the backend for processing. Click on view customer eye icon on the table for the new user and a list of customers will be shown. Users menu on the Nav takes you to the users list.
If the jobs are still processing in the background, the total number of users are increasing, just refresh the page to test. Click on edit from the table to edit user details and delete also on the table to delete a user. Each user on the table list can be edited, deleted and also view customers for the customer. The data is persisted in a MySQL database.


