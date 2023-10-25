# Microservice CONSUMER API

This project was made entirely focused on Microservices + RabbitMQ study.
+ We have the usage of Consumes on RabbitMQ QUEUES
+ We have the usage of elastic search for indexing & searching users

## Start up

Best usage of this repo is with [ms-study](https://github.com/Haaragard/microservices-communication-and-search)

### Requirements

- Docker
- Docker Compose

### Step by step

1. Prepare envs
    ```bash
    cp .env.example .env
    ```
2. Build
    ```bash
    docker compose build
    ```
3. Start
    ```bash
    docker compose up -d
    ```

**Necessário criar chave, migrate no banco & composer_install**

## Packages

- [Laravel](https://laravel.com)
- [RabbitMQ](https://www.rabbitmq.com/)
- [RabbitMQ AMQP PHP](https://www.rabbitmq.com/tutorials/tutorial-one-php.html)
