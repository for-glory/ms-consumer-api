migrate-fresh:
	@docker exec -t ms-consumer-api php artisan migrate:fresh

consume-user-created:
	@docker exec -t ms-consumer-api php artisan rabbitmq:consume:user-created

consume-user-updated:
	@docker exec -t ms-consumer-api php artisan rabbitmq:consume:user-updated

consume-user-deleted:
	@docker exec -t ms-consumer-api php artisan rabbitmq:consume:user-deleted