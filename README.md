# Default DB settings
Defaults (development):
- DB user: `root`
- DB password: `root`
- DB name: `evisa`

> IMPORTANT: `root:root` is for local development convenience only.
> For production you must create a dedicated DB user with a strong password.

# How to run
1. `docker-compose up -d --build`
2. Install PHP deps:
   `docker exec -it evisa-php composer install --no-interaction --prefer-dist`
3. Create DB & run migrations:
   `docker exec -it evisa-php php bin/console doctrine:database:create --if-not-exists`
   `docker exec -it evisa-php php bin/console doctrine:migrations:migrate --no-interaction`
4. Open: `http://localhost:8080`

# evisa-assignment
