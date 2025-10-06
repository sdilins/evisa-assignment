# evisa-assignment

# Requirements
- Docker
- Docker Compose

# How to run
1. Clone repo:
   `git clone https://github.com/sdilins/evisa-assignment.git`
2. `cd evisa-assignment`
3. `docker-compose up -d --build`
4. Install PHP deps:
   `docker exec -it evisa-php composer install --no-interaction --prefer-dist`
5. Run migrations:
   `docker exec -it evisa-php php bin/console doctrine:migrations:migrate --no-interaction`
6. Open: `http://localhost:8080`
