## CNJ_DIGITAL test project

#### This is a laravel react project

### Project Installation 

Download the project from the repository provided to your local machine. 

## Set Up 

Copy env file

```cp .env.example .env```

Generate Application Key

``` php artisan key:generate ```

Run commands: 

```composer install```

```npm install & npm run dev```

### Setup database as the same name in the .env file, or you may change it as per your local requirements


Run migrations

```php artisan migrate```

### You can open the project by setting up a vhost or just run

```php artisan serve```


# Congratulations, the project is now set!!!

# Usage

You may upload a csv file as provided in the example 
if you tick the save to database checkbox, all data will be written in database and then display the necessary data from the last file you uploaded. 
if you don't tick only display the data from the file you are uploading. 
