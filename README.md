# CRUD - Cadastro de Usuários

Sistema completo de **CRUD** (Create, Read, Update, Delete) desenvolvido em **PHP** e **MariaDB** para gerenciar cadastro de usuários.

## 🛠️ Tecnologias

- **PHP 8.4+** - Backend
- **MariaDB/MySQL** - Banco de dados
- **Bootstrap 5** - Interface responsiva
- **HTML5/CSS3** - Frontend

## 🚀 Como executar

### 1. Pré-requisitos

```bash
# Verificar se PHP está instalado
php --version

# Verificar se MariaDB está rodando
systemctl status mariadb
```

### 2. Configurar banco de dados

```bash
# Iniciar MariaDB (se necessário)
sudo systemctl start mariadb

# Importar estrutura do banco
sudo mariadb -u root agenda03 < usuarios.sql
```

### 3. Configurar credenciais

Edite o arquivo `conexao.php` com suas credenciais:

```php
$usuario = "seu_usuario";  // ex: root
$senha = "sua_senha";      // sua senha do MariaDB
```

### 4. Executar aplicação

```bash
php -S localhost:8000
```

Acesse: **http://localhost:8000**

## ⚙️ Funcionalidades

- ✅ **CREATE** - Cadastrar novos usuários
- ✅ **READ** - Listar todos os usuários
- ✅ **UPDATE** - Editar informações existentes
- ✅ **DELETE** - Excluir usuários
- ✅ Interface responsiva com Bootstrap
- ✅ Validação de dados e persistência de formulário
- ✅ Senhas criptografadas com `password_hash()`
- ✅ Proteção contra SQL Injection

## 📁 Estrutura do projeto

```
php_web3/
├── index.php       # Página principal (cadastro + listagem)
├── editar.php      # Formulário de edição
├── excluir.php     # Processa exclusão
├── conexao.php     # Conexão com banco
├── usuarios.sql    # Estrutura do banco
└── README.md       # Este arquivo
```

## 🗄️ Banco de dados

**Tabela:** `usuarios`

| Campo     | Tipo         | Descrição           |
| --------- | ------------ | ------------------- |
| id        | INT          | Chave primária      |
| nome      | VARCHAR(100) | Nome completo       |
| email     | VARCHAR(100) | E-mail único        |
| data_nasc | DATE         | Data nascimento     |
| estado    | VARCHAR(2)   | UF do estado        |
| endereco  | VARCHAR(255) | Endereço completo   |
| sexo      | VARCHAR(10)  | Masculino/Feminino  |
| login     | VARCHAR(50)  | Nome de usuário     |
| senha     | VARCHAR(255) | Senha criptografada |

## 📝 Validações

- Nome deve conter pelo menos 2 palavras
- Todos os campos são obrigatórios
- Email deve ter formato válido
- Senhas são automaticamente criptografadas

---

https://github.com/user-attachments/assets/fed040be-d2b6-4092-8c7e-c944ef7932a5



**Desenvolvido para:** Matéria de Programação Web 3 - Católica SC
