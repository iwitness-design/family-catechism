# Family Catechism
A custom plugin for the Family Catechism web app

## Setup
Alright! You are creating a new plugin and that is fantastic! There are a few things that you need to do right off the bat to get things started correctly.

## Setup Composer
Use [CamelCase](https://en.wikipedia.org/wiki/Camel_case) for all classes and files inside the `includes` directory.

Now, navigate to the plugin directory in your terminal and run `composer install`.

Composer is setup to use autoloading. Any file placed in the `includes` folder that uses the [psr 4](http://www.php-fig.org/psr/psr-4/) format will be loaded automatically when called.


## Gulp
Run `npm install` to install Gulp and the dependencies for this project.

Gulp is setup to run and each task has it's own file in the `gulp/tasks` directory.

Run `gulp watch` to watch and process styles and scripts.