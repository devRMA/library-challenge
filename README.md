# Library Challenge

<div align="center">
    <a href="https://library.devrma.com" target="_blank">
        <img src="./public/logo.png" alt="Logo" height="200" />
    </a>
</div>

<br>

Uma simulação de biblioteca, onde pode ser feito o cadastro de novos usuários e livros, e quando que os usuários pegaram quais livros alugados.

O projeto pode ser acessado aqui: <https://library.devrma.com>
Os endpoints da API podem ser acessados aqui: <https://library.devrma.com/docs>

## Requisitos para executar o projeto em localhost

-   [PHP](https://www.php.net/) na versão 8.3.2 ou superior.
-   [Composer](https://getcomposer.org/) na versão 2.6.5 ou superior.
-   [Node.js](https://nodejs.org/en) na versão 20.X ou superior
-   [Docker](https://www.docker.com/) na versão 24.0.7 ou superior.
-   Observação: Para usuários de Windows, é necessário instalar o [WSL 2](https://docs.microsoft.com/pt-br/windows/wsl/install).

## Como baixar o projeto

```shell
git clone https://github.com/devRMA/library-challenge.git
cd library-challenge
```

## Configuração inicial

Só é necessário executar esses comandos uma vez, assim que baixar o projeto.

1. Criar e configurar o `.env`

    ```shell
    cp .env.example .env
    ```

2. Instalar as dependências

    ```shell
    composer install
    npm install
    ```

3. Gerar a chave de [encriptação](https://laravel.com/docs/10.x/encryption)

    ```shell
    php artisan key:generate
    # ou
    ./vendor/bin/sail artisan key:generate
    ```

4. Executar as [migrations](https://laravel.com/docs/10.x/migrations) (só execute esse passo, após ter [iniciado o projeto](#iniciar-o-projeto-em-localhost))

    ```shell
    php artisan migrate
    # ou
    ./vendor/bin/sail artisan migrate
    ```

5. Compilar o frontend

    ```shell
    npm run dev
    ```

## Iniciar o projeto em localhost

Observação: Para usuários de Windows, é necessário executar esse comando dentro do terminal do Linux, usando o
[WSL](https://docs.microsoft.com/pt-br/windows/wsl/install).

```shell
./vendor/bin/sail up -d
```

Esse comando, irá iniciar os [containers do projeto](./docker-compose.yml).

## Executar os testes

Para executar os testes, basta usar este comando

```shell
./vendor/bin/sail test
```

## Tecnologias utilizadas

-   [Laravel](https://laravel.com/docs/10.x) v10
-   [PHP](https://www.php.net/) v8
-   [FakerPHP](https://fakerphp.github.io) v1
-   [Laravel Sail](https://laravel.com/docs/10.x/sail) v1
-   [Laravel Pint](https://laravel.com/docs/10.x/pint) v1
-   [Pest PHP](https://pestphp.com) v2
-   [Vue.js](https://vuejs.org) v3
-   [Tailwind CSS](https://tailwindcss.com) v3

---

<p align="center">Feito com 🤍 por Rafael Alves</p>
