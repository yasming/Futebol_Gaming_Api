# Futebol_Gaming_Api

This project is an api to do the managment of a futebol championship. We can add players, teams, matches and get the ranking of teams(by the gols done) and players(by the cards taken, that one that get less card is the winner). An important thing about this championship is: once a player is added to a team, he can't change to other team, but is possible to update all the others informations from players and make searches!

## Prerequisites

```
PHP >= 7.3
```

```
PHP Unit >=9.3.3
```

```
Laravel >= 8.12
```


### API Collection

https://www.getpostman.com/collections/56a35a2197d13458e169

### API Swagger Documentation

https://app.swaggerhub.com/apis-docs/yasminguimaraes/FUTEBOL_GAMING_API/1.0.0

### Getting Started

- After you clone the project: 

```
composer install
```

```
cp .env.example .env
```

```
php artisan key:generate
```

```
php artisan jwt:generate
```

```
php artisan migrate --seed
```

### How to run project's tests

```
php artisan test
```
