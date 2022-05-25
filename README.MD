to run application via docker-compose:
  1. copy .env.dist file to .env and change variables as you wants
  2. run `$ docker-compose build`
  3. run `$ docker-compose --env-file .env up -d`
  4. run `$ docker-compose exec app composer install`
