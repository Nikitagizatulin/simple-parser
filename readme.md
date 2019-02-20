# Parser for the Video Content

> A Laravel 5.6 project with

![](https://github.com/Nikitagizatulin/simple-parser/blob/master/readme_image.png)

## Build Setup

``` bash
# first what you need - install package dependencies
composer install

# rename .env.example to .env and pass in this file settings
cp .env.example .env

# generate key for our project
php artisan key:generate

# create all table fro project
php artisan migrate

#if you want get fake data run this
php artisan db:seed

# now run the project 
php artisan serve

```
## Functionality:
* Service accepts a URl address from youtube, rutube or vimeo site, parsed and stored this video in database.
* Display all list of parsed video and it is possible to look at it is direct on the website
