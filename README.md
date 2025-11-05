# Sistema CRUD com Autenticação e Upload

Sistema completo de **CRUD** (Create, Read, Update, Delete) com **sistema de login** e **upload de fotos** desenvolvido em **PHP** e **MariaDB**.

## 🛠️ Tecnologias

- **PHP 8.4+** - Backend com sessões
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

### 5. Fazer login

Acesse: **http://localhost:8000**

**Credenciais padrão:**

- **Login:** `admin`
- **Senha:** `password`

## ⚙️ Funcionalidades

### 🔐 Sistema de Autenticação

- ✅ **Login seguro** - Verificação de credenciais
- ✅ **Sessões PHP** - Controle de acesso
- ✅ **Proteção de páginas** - Apenas usuários logados
- ✅ **Logout** - Encerramento de sessão

### 📝 CRUD Completo

- ✅ **CREATE** - Cadastrar novos usuários
- ✅ **READ** - Listar todos os usuários
- ✅ **UPDATE** - Editar informações existentes
- ✅ **DELETE** - Excluir usuários

### 📸 Upload de Fotos

- ✅ **Upload de imagens** - JPG, PNG, GIF (máx. 2MB)
- ✅ **Exibição de fotos** - Na listagem de usuários
- ✅ **Gestão de arquivos** - Exclusão automática

### 🛡️ Segurança

- ✅ **Senhas criptografadas** - `password_hash()`
- ✅ **Proteção SQL Injection** - Prepared statements
- ✅ **Validação de dados** - Frontend e backend
- ✅ **Controle de sessões** - Acesso protegido

## 📁 Estrutura do projeto

```
php_web3/
├── index.php              # Página de login
├── home.php               # Dashboard principal (CRUD)
├── editar.php             # Formulário de edição
├── excluir.php            # Processa exclusão
├── logout.php             # Encerra sessão
├── verificar_sessao.php   # Middleware de autenticação
├── conexao.php            # Conexão com banco
├── uploads/               # Pasta para fotos dos usuários
├── usuarios.sql           # Estrutura do banco
└── README.md              # Este arquivo
```

## 🗄️ Banco de dados

**Tabela:** `usuarios`

| Campo     | Tipo         | Descrição                  |
| --------- | ------------ | -------------------------- |
| id        | INT          | Chave primária             |
| nome      | VARCHAR(100) | Nome completo              |
| email     | VARCHAR(100) | E-mail único               |
| data_nasc | DATE         | Data nascimento            |
| estado    | VARCHAR(2)   | UF do estado               |
| endereco  | VARCHAR(255) | Endereço completo          |
| sexo      | VARCHAR(10)  | Masculino/Feminino         |
| login     | VARCHAR(50)  | Nome de usuário (único)    |
| senha     | VARCHAR(255) | Senha criptografada        |
| foto      | VARCHAR(255) | Caminho da foto (opcional) |

## � Fluxo da Aplicação

1. **Acesso inicial** → Tela de login (`index.php`)
2. **Autenticação** → Verificação no banco de dados
3. **Dashboard** → Página principal (`home.php`) com CRUD
4. **Navegação protegida** → Todas as páginas verificam sessão
5. **Logout** → Encerra sessão e volta ao login

## 📝 Validações

- **Login:** Usuário deve existir no banco
- **Senha:** Verificação com `password_verify()`
- **Nome:** Deve conter pelo menos 2 palavras
- **Fotos:** JPG, PNG, GIF - máximo 2MB
- **Sessão:** Verificada em todas as páginas protegidas

## 🔧 Comandos úteis

```bash
# Parar serviços
sudo systemctl stop mariadb

# Ver usuários cadastrados
sudo mariadb -u root -e "USE agenda03; SELECT login, nome FROM usuarios;"

# Resetar senha de usuário
sudo mariadb -u root -e "USE agenda03; UPDATE usuarios SET senha = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE login = 'admin';"
```

---

**Desenvolvido para:** Programação Web 3 - Católica SC  
**Funcionalidades:** CRUD + Login + Upload de Fotos
