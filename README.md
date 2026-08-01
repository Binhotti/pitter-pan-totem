# Totem Pitter Pan — PHP + MySQL

Esta versão mantém a interface original do totem e acrescenta:

- cadastro de pedidos no MySQL;
- geração de senha sequencial (`A0001`, `A0002`...);
- API CRUD em PHP;
- painel administrativo;
- busca e filtro por status;
- edição de texto, quantidade, preço e status;
- exclusão de pedidos;
- indicadores básicos de pedidos e faturamento.

## 1. Instalação

Copie a pasta `pitterpan_totem_php` para:

```text
C:\xampp\htdocs\pitterpan_totem_php
```

## 2. Criar o banco

1. Abra o phpMyAdmin.
2. Vá em **Importar**.
3. Selecione o arquivo `database.sql`.
4. Execute a importação.

O banco criado será:

```text
pitterpan_totem
```

## 3. Configuração da conexão

O arquivo está em:

```text
config/database.php
```

Ele foi configurado para o ambiente informado:

```text
Host: 127.0.0.1
Porta: 4406
Banco: pitterpan_totem
Usuário: root
Senha: vazia
```

Altere esses dados quando necessário.

## 4. Abrir o totem

```text
http://localhost/pitterpan_totem_php/
```

## 5. Abrir o painel administrativo

```text
http://localhost/pitterpan_totem_php/admin/
```

## CRUD disponível

### Criar pedido

```http
POST /api/orders.php
```

### Listar pedidos

```http
GET /api/orders.php
```

Filtros opcionais:

```http
GET /api/orders.php?status=producao
GET /api/orders.php?search=A0001
```

### Consultar pedido

```http
GET /api/orders.php?id=1
```

### Atualizar pedido

```http
PUT /api/orders.php?id=1
```

### Excluir pedido

```http
DELETE /api/orders.php?id=1
```

## Observação de segurança

O painel administrativo ainda não possui login. Antes de colocar o sistema na internet, implemente autenticação, permissões, proteção CSRF e variáveis de ambiente para as credenciais do banco.
