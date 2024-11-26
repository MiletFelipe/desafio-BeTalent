# Documentação da API

Esta API permite gerenciar usuários, clientes, produtos e vendas, com autenticação protegida via JWT.

O arquivo de configuração do **Insomnia** já está disponível dentro do repositório, facilitando o teste das rotas da API.

## Como Começar

Siga os passos abaixo para configurar e rodar a API localmente.

### Pré-requisitos

Certifique-se de ter os seguintes requisitos instalados em sua máquina:

- **PHP** (versão 8.0 ou superior)
- **Composer** (para gerenciar dependências do Laravel)
- **MySQL** ou **MariaDB** (para o banco de dados)
- **Node.js** (para compilar os assets front-end, caso necessário)
- **Insomnia** (opcional, para testar a API)

### Passos para Configuração

1. **Clone o repositório**:

   Clone o repositório para sua máquina local com o comando:

   ```bash
   git clone https://github.com/MiletFelipe/desafio-BeTalent.git

2. **Instale as dependências:** :
    cd seu diretório
    composer install

3. **Lembre de configurar seu Banco de Dados no .env**
    Exemplo:
    ```
        DB_CONNECTION=mysql 
        DB_HOST=127.0.0.1 
        DB_PORT=3306 
        DB_DATABASE=nome_do_banco 
        DB_USERNAME=usuario_do_banco 
        DB_PASSWORD=senha_do_banco 
    ```

4. **Gere seu token JWT e confira no .env se gerou**
    ```bash
    php artisan jwt:secret

5. **Rode as migrations**
    ```bash
    php artisan migrate

6. **Rode a seed**
    ```bash
    php artisan db:seed
    
## Arquivo Insomnia

Você pode importar o arquivo de configuração do **Insomnia** para testar as rotas da API de forma fácil e rápida. O arquivo de configuração já está incluso neste repositório.

### Passos para importar o arquivo Insomnia:

1. Baixe o arquivo `Insomnia_API_Collection.json` que está localizado na raiz deste repositório.
2. Abra o **Insomnia** e vá até **File** > **Import**.
3. Selecione o arquivo `Insomnia_API_Collection.json`.
4. As rotas já estarão configuradas, incluindo as de login (com JWT).

## Endpoints

### **Autenticação**

- **POST** `/register`
Registrar um novo usuário.
- **POST** `/login` -
 Login para gerar um token JWT.

### **Gestão de Usuários**

- **GET** `/users` - Listar todos os usuários.
- **DELETE** `/user/{user}` - Deletar um usuário.

### **Gestão de Clientes**

- **GET** `/clients` - Listar todos os clientes.
- **GET** `/client/{client}` - Visualizar um cliente (aceita parâmetros de mês e ano).
- **POST** `/client` - Criar um novo cliente.
- **PUT** `/client/{client}` - Atualizar um cliente.
- **DELETE** `/client/{client}` - Deletar um cliente.

### **Gestão de Produtos**

- **GET** `/products` - Listar todos os produtos.
- **GET** `/product/{product}` - Visualizar um produto.
- **POST** `/product` - Criar um novo produto.
- **PUT** `/product/{product}` - Atualizar um produto.
- **DELETE** `/product/{product}` - Deletar um produto.

### **Vendas**

- **POST** `/client/{client}/sales` - Registrar uma venda para um cliente.
