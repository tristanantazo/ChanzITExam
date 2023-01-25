build:
	@docker-compose up -d --build
	
down:
	@docker-compose down

migrateseed:
	@docker-compose exec -it php php artisan migrate:fresh
	@docker-compose exec -it php php artisan db:seed

composerdump:
	@docker-compose exec -it php composer dump-autoload

artisanclear:
	@docker-compose exec -it php php artisan cache:clear
	@docker-compose exec -it php php artisan config:clear

artisanmake:
	@docker-compose exec -it php php artisan make:$(args)

artisanmigrate:
	@docker-compose exec -it php php artisan migrate:fresh

npm-dev:
	@docker-compose run --rm npm run dev

npm-prod: 
	@docker-compose run --rm npm run prod

npm-watch:
	@docker-compose run --rm npm run watch