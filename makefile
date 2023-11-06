migrate-fresh:
	@docker exec -it ms-consumer-api php artisan migrate:fresh

consume-user-created:
	@docker exec -it ms-consumer-api php artisan rabbitmq:consume:user-created

consume-user-updated:
	@docker exec -it ms-consumer-api php artisan rabbitmq:consume:user-updated

consume-user-deleted:
	@docker exec -it ms-consumer-api php artisan rabbitmq:consume:user-deleted