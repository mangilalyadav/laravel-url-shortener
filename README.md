URL Shortener

A Laravel-based URL Shortener application that allows users to generate short URLs, track URL hits, and manage generated URLs based on user roles and permissions.

Features

Generate short URLs from long URLs

Redirect short URLs to the original URL

Track the number of URL hits

Role-based access control

Superadmin, Admin, and Member dashboards

Client/company management

Team member management

Generated URL management

Edit and delete generated URLs

Filter generated URLs by date

Download generated URL listings as PDF

Pagination for URL listings

Permission-based actions

---------------------------------------------------------------------------------------

Requirements

PHP: 8.2 or higher
Composer version: 2.10.3
database: MySQL
Node.js / NPM vresion: 9.5.1
Laravel version: ^11.31

-------------------------------------------------------------------------------------

Installation
1. Clone the repository
git clone https://github.com/mangilalyadav/laravel-url-shortener.git

2. Go to the project directory
cd laravel-url-shortener

3. Install PHP dependencies
composer install

4. Install frontend dependencies
npm install

5. Create the environment file

Copy .env.example to .env.

On Windows:

copy .env.example .env


On Linux/macOS:

cp .env.example .env

6. Configure the database

Open the .env file and configure your MySQL database:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=root
DB_PASSWORD=

Use your own local database credentials.


7. Generate the application key
php artisan key:generate

8. Run migrations
php artisan migrate

9. Run database seeders

Run seeders for roles, permissions, or initial users, run:

php artisan db:seed

10. Start the Laravel application
php artisan serve


The application will be available at:

http://127.0.0.1:8000/admin/auth/login

URL Shortening

After logging in, authorized users can generate a short URL by providing a valid long URL.


The application redirects the visitor to the original long URL and increments the URL hit count.
------------------------------------------------------
User Roles

The application supports different user roles with different access levels:
-------------------------------------------------------------------------------
Superadmin

Manage clients

View generated URLs across clients

View team/user information

Manage generated URLs

Download generated URL reports

---------------------------------------------------------------------

Admin

Generate short URLs

View URLs belonging to their client/company

Manage team members

View URL statistics

Download filtered URL reports

Member

Generate short URLs

View their available generated URLs

View URL hit counts

Manage URLs according to assigned permissions

PDF Reports

Authorized users can download generated URL listings as PDF reports.

Available date filters include:

Today

Last Week

This Month

Last Month

The downloaded report contains information such as:

Short URL

Long URL

Created By

Company

Hits

Created At

---------------------------------------------------------------------------------
Member user role

Generate short URLs

View URLs belonging to their client/company

Manage team members

View URL statistics

Download filtered URL reports

Member

Generate short URLs

View their available generated URLs

View URL hit counts

Manage URLs according to assigned permissions

PDF Reports

--------------------------------------------------------------------------------------------------------

Project Structure

The main Laravel directories used by this project include:

app/
├── Http/
├── Models/
└── ...

database/
├── migrations/
└── seeders/

resources/
├── views/
└── ...

routes/
└── web.php

public/


------------------------------------------------------------------------------------------
YOU CAN EASILY ACCESS THE ASSIGNMENT AN SHARE THE DATABASE EXPORT FILE ALSO.

CREDENTIALS:
SUPERADMIN USER : 
U: demo.super@gmail.com
P: 12345678

ADMIN USER:
U: demo.admin@gmail.com
P: password

MEMBER USER:
U: demo.member@gmail.com
P: password


Database backup: attached with mail




-------------------------------------------------------------------------------------------
AI Tool Used: chatgpt(for create the logo of the project for the admin panel).


