to run application via docker-compose:
  1. copy .env.dist file to .env and change variables as you wants
  2. run `mkdir docker/database` to create an empty folder for database files
  3. run `$ docker-compose --env-file .env build`
  4. run `$ docker-compose --env-file .env up -d`
  5. run `$ docker-compose exec app composer install --no-dev`
  6. run `$ docker-compose exec app doctrine:migrations:migrate`