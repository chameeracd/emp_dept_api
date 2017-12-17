emp_dept_api
============

A Symfony project created on December 16, 2017, 4:42 am.

## Installation

First, open a command console, enter your web root directory and
execute the following command(s)

    git clone git@github.com:chameeracd/emp_dept_api.git
    cd emp_dept_api
    composer update
    php app/console assets:install
    php app/console assets:install --env=prod
    php app/console cache:clear
    php app/console cache:clear --env=prod
    
change `app/config/paramaters.yml` for database config

    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force


## Usage

    please refere http://<web-root>/emp_dept_api/web/api/doc