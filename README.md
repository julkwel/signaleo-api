# Symfony With ReactJS without using bundle

# Requirements 
```
- php ^7.2
- NodeJs
- yarn or npm
- symfony cli
```
# Installation

## Clone this reposiroty

`git clone https://github.com/julkwel/my-api.git`

Go inside 

`cd my-api`

Update composer and node package

`composer install && yarn install`

Update database configuration, edit this line in `.env` file with your own configuration

`DATABASE_URL=mysql://databaseUsername:databasePassword@127.0.0.1:3306/db_name?serverVersion=5.7`

Create database:

`bin/console doctrine:database:create`

Then update schema by running:

`bin/console d:s:u -f`

Run symfony server

`symfony server:start`

Run yarn to run reactjs , open localhost:8000

`yarn encore dev watch`

**Happy coding**
