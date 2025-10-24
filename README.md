# Projeto: API de Gerenciamento de Assinaturas (Desafio)

Esta é uma API RESTful desenvolvida em Laravel como solução para um desafio técnico de back-end. O objetivo principal é gerenciar um sistema de assinaturas de planos, com a lógica de negócio central focada na troca de planos com cálculo de crédito pro-rata (proporcional).

O sistema permite que um usuário (fixo, conforme os requisitos) gerencie seus planos de assinatura, podendo contratar, consultar e trocar de plano a qualquer momento.

## Recursos Principais

* **Listagem de Planos:** Exposição de todos os planos disponíveis no sistema.
* **Gestão de Usuário:** Retorno dos dados de um usuário fixo (padrão).
* **Contratação de Plano:** Permite ao usuário contratar um plano inicial, simulando um pagamento via PIX.
* **Consulta de Contrato:** Permite ao usuário verificar qual é o seu plano/contrato atualmente ativo.
* **Troca de Plano (Upgrade/Downgrade):** O recurso principal do sistema. Permite ao usuário trocar seu plano atual por qualquer outro.
    * O sistema calcula automaticamente o crédito proporcional referente aos dias não utilizados do plano anterior.
    * Esse crédito é aplicado como desconto no primeiro pagamento do novo plano.
    * A lógica funciona tanto para *upgrade* (plano mais caro) quanto para *downgrade* (plano mais barato).
* **Histórico:** Todas as contratações e pagamentos são registrados no banco de dados para manter um histórico completo.

## Tecnologias Utilizadas

* **PHP 8.3**
* **Laravel 12**
* **MySQL** (Gerenciado via Laragon)
* **Composer** (Gerenciador de dependências)

---

## Instalação e Execução Local

Siga os passos abaixo para configurar e executar o projeto em seu ambiente local.

### Pré-requisitos

* PHP (v8.3+)
* Composer
* Um servidor MySQL (recomenda-se o uso do **Laragon**, que já inclui o PHP e o Composer)
* Git

### Passos

1.  **Clone o repositório:**
    ```bash
    git clone https://[URL_DO_SEU_REPOSITORIO_AQUI]
    cd [NOME_DA_PASTA_DO_PROJETO]
    ```

2.  **Instale as dependências do PHP:**
    ```bash
    composer install
    ```

3.  **Configure o Ambiente:**
    * Copie o arquivo de ambiente de exemplo:
        ```bash
        copy .env.example .env
        ```
    * **Crie um banco de dados MySQL** (ex: `desafio_api`) em seu gerenciador (como o HeidiSQL, que vem com o Laragon).
    * Edite o arquivo `.env` com as credenciais do seu banco de dados:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=desafio_api
        DB_USERNAME=root
        DB_PASSWORD=
        ```

4.  **Gere a Chave da Aplicação:**
    ```bash
    php artisan key:generate
    ```

5.  **Execute as Migrations e Seeders:**
    * Este comando é crucial. Ele irá criar toda a estrutura do banco de dados e irá popular as tabelas `users` e `plans` com os dados fixos necessários para testar a API.
    ```bash
    php artisan migrate:fresh --seed
    ```

6.  **Ative as extensões do PHP (se necessário):**
    * Se você encontrar um erro de `"could not find driver"` ao rodar a aplicação, certifique-se de que as extensões `pdo_mysql` e/ou `mysqlnd` estão ativadas no seu `php.ini`. (No Laragon, isso pode ser feito clicando com o botão direito > PHP > Extensions).

7.  **Inicie o servidor local:**
    ```bash
    php artisan serve
    ```

*A API estará disponível em `http://127.0.0.1:8000`.*

---

## Documentação da API (Endpoints)

A URL base para todos os endpoints é: `http://127.0.0.1:8000/api`

### 1. Informações do Usuário

Retorna as informações do usuário fixo (ID 1) cadastrado no sistema.

* **Endpoint:** `GET /usuario`
* **Método:** `GET`
* **Resposta (Sucesso 200 OK):**
    ```json
    {
      "id": 1,
      "name": "Usuário Fixo Teste",
      "email": "usuario@teste.com",
      "email_verified_at": null,
      "created_at": "2025-10-23T21:52:23.000000Z",
      "updated_at": "2025-10-23T21:52:23.000000Z"
    }
    ```

### 2. Listar Planos Disponíveis

Retorna uma lista de todos os planos de assinatura cadastrados no sistema.

* **Endpoint:** `GET /planos`
* **Método:** `GET`
* **Resposta (Sucesso 200 OK):**
    ```json
    [
      {
        "id": 1,
        "name": "Plano Básico",
        "price": "100.00",
        "quotas": 10,
        "storage_space_gb": 50,
        "created_at": "2025-10-23T21:52:23.000000Z",
        "updated_at": "2025-10-23T21:52:23.000000Z"
      },
      {
        "id": 2,
        "name": "Plano Pro",
        "price": "200.00",
        "quotas": 50,
        "storage_space_gb": 200,
        "created_at": "2025-10-23T21:52:23.000000Z",
        "updated_at": "2025-10-23T21:52:23.000000Z"
      }
    ]
    ```

### 3. Contratar um Plano

Permite ao usuário (ID 1) contratar um plano. Isso cria o primeiro registro de `Subscription` (contrato) e um `Payment` (pagamento) simulado.

* **Endpoint:** `POST /contratar`
* **Método:** `POST`
* **Corpo da Requisição (Body) (JSON):**
    ```json
    {
      "plan_id": 1
    }
    ```
* **Resposta (Sucesso 201 Created):**
    ```json
    {
      "message": "Plano contratado com sucesso!",
      "subscription": {
        "user_id": 1,
        "plan_id": 1,
        "start_date": "2025-10-23T23:32:50.261058Z",
        "status": "active",
        ...
        "id": 1
      },
      "payment": {
        "subscription_id": 1,
        "amount": "100.00",
        "payment_date": "2025-10-23T23:32:50.273826Z",
        ...
        "id": 1
      }
    }
    ```

### 4. Consultar Contrato Ativo

Retorna o contrato atualmente ativo (`status: 'active'`) do usuário (ID 1), incluindo os dados do plano vinculado.

* **Endpoint:** `GET /contrato-ativo`
* **Método:** `GET`
* **Resposta (Sucesso 200 OK):**
    ```json
    {
      "id": 1,
      "user_id": 1,
      "plan_id": 1,
      "start_date": "2025-10-23",
      "status": "active",
      ...
      "plan": {
        "id": 1,
        "name": "Plano Básico",
        "price": "100.00",
        ...
      }
    }
    ```
* **Resposta (Erro 404 Not Found):**
    * (Caso o usuário ainda não tenha contratado um plano)
    ```json
    {
      "message": "Nenhum contrato ativo encontrado."
    }
    ```

### 5. Trocar de Plano (Upgrade/Downgrade)

Permite ao usuário trocar seu plano ativo por um novo. O sistema cancela o contrato antigo e calcula o crédito proporcional para abater no primeiro pagamento do novo plano.

* **Endpoint:** `PUT /trocar-plano`
* **Método:** `PUT`
* **Corpo da Requisição (Body) (JSON):**
    ```json
    {
      "new_plan_id": 2
    }
    ```
* **Resposta (Sucesso 200 OK):**
    * (Exemplo de troca do plano de R$ 100 para o de R$ 200, feito no mesmo dia)
    ```json
    {
      "message": "Troca de plano realizada com sucesso!",
      "credit_applied": 96.7,
      "new_payment_amount": 103.3,
      "new_subscription": {
        "user_id": 1,
        "plan_id": 2,
        "start_date": "2025-10-23T23:44:21.292446Z",
        "status": "active",
        ...
        "id": 2
      }
    }
    ```
* **Respostas de Erro:**
    * `404 Not Found`: `{"message": "Nenhum contrato ativo para trocar."}`
    * `400 Bad Request`: `{"message": "Você já está neste plano."}`

---

## Estrutura do Banco de Dados

O banco de dados é composto pelas seguintes tabelas principais:

1.  **`users`**: Armazena os usuários (padrão do Laravel).
2.  **`plans`**: Armazena os planos de assinatura disponíveis.
    * `id` (Primária)
    * `name` (string): Nome do plano (ex: "Plano Básico").
    * `price` (decimal): Preço mensal do plano (ex: 100.00).
    * `quotas` (integer): Quantidade de cotas.
    * `storage_space_gb` (integer): Espaço de armazenamento.
3.  **`subscriptions`**: Armazena os contratos (histórico de assinaturas).
    * `id` (Primária)
    * `user_id` (foreign key): ID do usuário.
    * `plan_id` (foreign key): ID do plano contratado.
    * `start_date` (date): Data de início da contratação.
    * `status` (string): 'active' ou 'cancelled' (garante que só há um ativo).
4.  **`payments`**: Armazena o histórico de pagamentos simulados.
    * `id` (Primária)
    * `subscription_id` (foreign key): ID do contrato ao qual este pagamento pertence.
    * `amount` (decimal): Valor pago.
    * `payment_date` (date): Data do pagamento.
    * `payment_method` (string): (ex: 'pix_simulado', 'pix_simulado (troca)').
