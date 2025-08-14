

## About Multi Tenancy Order Management System

This multi-tenant order management system with a separate database per tenant, each tenant is distinctly identified by a unique tenant_id. This architecture ensures complete data isolation and security, as each tenant's products and orders reside in their own dedicated database. The system is designed to handle orders from creation to delivery, with a robust order tracking feature. When an order is placed, it is initially in a 'processing' state. As the order is fulfilled and leaves the warehouse, its status is updated to 'shipped'. The system provides real-time updates as the order moves through the logistics chain, and finally, the status changes to 'delivered' once the customer receives their package. This setup provides each tenant with a private and secure order management solution, while offering a comprehensive and transparent tracking experience for their customers. We have following Module:

- [Central Application (responsible for creating tenants)](https://github.com/kathmandu1/multi-tenancy-order-management/tree/main/Modules/CentralApplication).
- [Order Management (responseble for product, shipping and order management for tenants)](https://github.com/kathmandu1/multi-tenancy-order-management/tree/main/Modules/OrderManagement).

This Application Backend is Build on Laravel lastest version using Swagger-OpenAPI for APi docmentation

## Architecture and Design Pattern

The system's architecture is built on a clear separation of concerns, ensuring maintainability and scalability. Data transfer objects [(DTOs )](https://en.wikipedia.org/wiki/Data_transfer_object) and the DataBuilder pattern are employed for efficient data mapping, isolating the application's internal data structures from the data exposed to clients. This approach simplifies data transformation and enhances security by controlling which data fields are shared. Business logic is encapsulated within dedicated service classes, providing a single, consistent entry point for all operations and preventing code duplication. Data persistence is handled by the repository pattern, which abstracts the underlying data storage mechanism. This separation allows for flexible database changes without affecting the core application logic. Furthermore, the system adheres to the single responsibility principle, with each class designed to handle a specific, isolated functionality. This modular design minimizes dependencies, making the codebase easier to understand, test, and modify.



### DESIGN Pattern Used For Appication

- **[Pipeline Pattern ( For complex Search, Filter)](https://abc.com)**
- **[Repository Pattern (For chanagable data source and expandable Data abstration)](https://abc.com)**
- **[Service Pattern (Resuable and central business logic)](https://abc.com)**
- **[DTO Pattern (Add Data Security layer and Consistency which data exachanfe between layer)](https://abc.com)**
- **[TDD Pattern (Test each and individual componets of system and ensure that they work as expected)](https://abc.com)**


## Installaction Guideline


Run Permission command if file is not writable or appendable, if cache and boostrap file are not creating in folder when bootstrapping application
```
sudo chmod -R 777 ./
or permission to specific folder
```

## Installation and Database Table For Main Application

These commands will create a tables and migration for main application , where you create a tenants and other stuff, 

```
$ cp .env.example .env
$ docker compose build (only first time or if any settings have changed)
$ docker compose up -d
$ docker compose exec app composer install
$ docker compose exec app php artisan key:generate
$ docker compose exec app php artisan migrate --seed
docker compose exec app php artisan db:seed 
$ docker compose exec app php artisan storage:link

```


The application should now be reachable at http://localhost:8000.
you are free to change APP_PORT in .env .ie APP_PORT=9000 and  run docker compose up -d and then you can access app through   http://localhost:8000

## Running artisan command through docker

    $ docker compose exec app php artisan # you can alias `docker compose exec app php artisan` in you .bashrc or .zshrc


## Access database

- PhpmyAdmin

  For adminer go to http://localhost:8080
  For command line access:
  $ docker compose exec db mysql -u root -p # default password is `root` or `@dmin@123`


## Access Mail and Development time mail sandbox

During development and UAT testing you may need a random email for testing for sending email of order notification, order delivery email notification as per application need, please use folling port for mailbox sandbox

- Maildev
Please go to this link for mail check  http://localhost:8002



## Running the tests

- Run unit and feature test

```
docker compose exec app ./vendor/bin/phpunit 
Or
docker compose exec app php artisan test

```

## Test code Quality and Coding Standard

- For more details and  usecases read docs https://github.com/phpro/grumphp

```
docker compose exec app vendor/bin/grumphp run

```




## Tenancy Migration and Seeder Setup
We have make a seeder, factory for tenant for testing and TDD development, use can use following command

```
    php artisan tenants:list  // get list of tenant
    php artisan tenants:migrate // migration if  any new migration file is added in tenant folder of migration
    php artisan tenants:migrate-fresh // drop all table and re-run migration
    php artisan tenants:seed
```

## Tenancy and Tenancy management
We are using the Laravel Tenancy package for tenant management [laravel Tenancy (https://tenancyforlaravel.com/)], with separate databases for each tenant.

```
    POST /api/tenants   \\create tenant
    GET /api/tenant  \\ check list of tenant
```

For more details, see the Swagger documentation of the main application.

After creating a tenant:

- A new database for that tenant will be automatically created.
- All required migrations will be executed in the tenant’s database by the application.


## Swagger and OpenAPI swagger Anotation
We have use a laravel swagger package  [laravel swagger package (https://github.com/DarkaOnLine/L5-Swagger?tab=readme-ov-file)], see documentation for more detail, you can create swagger by following comand

```
php artisan l5-swagger:generate  

// you can change swagger endpoint from .env
L5_SWAGGER_CONST_HOST=http://localhost:8000

```

Please check swagger documentation   http://localhost:8000/api/documentation

If You want to get seperate OpenAPI json file , please use api-docs.json file from root of this project

for APi structure please study schema for each api endpoint


## Modules and Module Management
We have use  Laravel laravel nwidart-module package for Module management [laravel module (https://nwidart.com/laravel-modules/v6/introduction)], for  more usage please  visit  this link

```
    php artisan module:make-module OrdermanagementTest
```

