1. Extract the Zip File
First, extract the zip file to your desired location. This will be the root directory of your Laravel project.

2. Install Composer Dependencies Open a terminal or command prompt and navigate to the root directory of your extracted project. 
Use the cd command to change your directory to the project folder:
cd /path/to/your/project

Once you're in the project directory, 
run the following command to install the PHP dependencies specified in the composer.json file:
composer install

3. Create an Environment File
Copy the .env.example file to a new file named .env. This file contains environment-specific variables. You can do this with the following command:
copy .env.example .env  

4. Generate an Application Key
Run the following Artisan command to generate a new application key.
This key is used to secure your session and encrypted data:
php artisan key:generate

5. Run Migrations
If your project uses a database, run the migrations to create the database schema:
php artisan migrate


6. Install Node.js Dependencies (if necessary)
If your project uses Node.js dependencies (for frontend assets like Vue.js, React, or just to compile assets with Laravel Mix), 
make sure you have Node.js and npm installed. Then run:
npm install
And compile your assets (if needed) with:
npm run dev  # For development


7. For Laravel Queues 
In .env File  set this mailtrap details that i am using for sending email


MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=f9b9a3653b8ec9
MAIL_PASSWORD=9a8a37c5f8226a


8. Setting QUEUE_CONNECTION to database
To use the database queue driver, I set the QUEUE_CONNECTION environment variable in your .env file to database:

QUEUE_CONNECTION=database

9. Preparing the Database for QUEUE
Before the use of database queue driver, you need to prepare your database to store the job queue:

Create the Jobs Table: Run the following Artisan command to create a migration for the jobs table:

php artisan queue:table


10. Migrate: Apply the migration to create the jobs table in your database:

php artisan migrate


11 .Running the Queue Worker
After setting up the database queue connection and migrating your database, you need to start a queue worker to process the queued jobs. Run the following command to start a worker:
php artisan queue:work



12. Serve the Application
Open a new terminal or command prompt and navigate to the root directory of your extracted project. 
Use the cd command to change your directory to the project folder
cd /path/to/your/project

Finally, you can serve your Laravel application using the built-in PHP server:
php artisan serve
