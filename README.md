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

-   Clone and cd into the project folder.
-   Run `composer install`.
-   Copy `.env.example` to `.env` Run `cp .env.example .env`in Linux or Mac or `copy .env.example .env` Windows.
-   Generate key `php artisan key:generate`
-   Run `npm install`.
-   Set `QUEUE_CONNECTION=redis` in the `.env` file.
-   Set up the App, database and redis settings in the `.env` file.
-   Run `docker-compose up -d` for development purpose.

-   Install npm package then run `docker exec tradler-app npm install`.
-   Install compose package then run `docker exec tradler-app compose install`.
-   Install compose package then run `docker exec tradler-app php artisan migrate`.
-   Install compose package then run `docker exec tradler-app php artisan db:seed`.
-   Install compose package then run `docker exec tradler-app php artisan queue:work`.

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

Click on Add User button to create a new user, fill in the form in the pop-up modal selecting the role and providing an email and name for the user. Click Save button and the data is sent to the backend for processing. Click on view customer eye icon on the table for the new user and a list of customers will be shown. 
If the jobs are still processing in the background, the total number of users are increasing, just refresh the page to test. Click on edit from the table to edit user details and delete also on the table to delete a user. Each user on the table list can be edited, deleted and also view customers for the customer. The data is persisted in a MySQL database.


