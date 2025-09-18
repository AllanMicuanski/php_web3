# Formulário de Cadastro (Atividade Programação Web 3)

Formulário simples em **HTML, Bootstrap e PHP** para capturar e armazenar dados do usuário em um banco de dados **MariaDB**.

## Estrutura do projeto

- `index.php` → formulário de cadastro
- `processa.php` → processa os dados do formulário e insere no banco
- `conexao.php` → arquivo responsável pela conexão com o banco
- `agenda03.sql` → exportação do banco de dados (tabela `usuarios`)

## Como rodar

1. Abra o terminal na pasta do projeto.
2. Rode o servidor PHP embutido:

   ```bash
   php -S localhost:8000
   ```

3. Acesse no navegador: http://localhost:8000/index.php
4. Preencha o formulário e clique em Salvar.
5. Para conferir os dados no banco, acesse o phpMyAdmin: http://localhost/phpmyadmin

## Banco de dados

Tabela usuarios:
| Campo | Tipo | Observações |
| ---------- | ------------ | ---------------------------- |
| id | INT | PRIMARY KEY, AUTO_INCREMENT |
| nome | VARCHAR(255) | Nome completo do usuário |
| email | VARCHAR(255) | E-mail |
| data_nasc | DATE | Data de nascimento |
| estado | VARCHAR(2) | Estado |
| endereco | VARCHAR(255) | Endereço |
| sexo | VARCHAR(10) | Masculino/Feminino |
| login | VARCHAR(50) | Nome de login |
| senha | VARCHAR(255) | Senha (hash) |

## Observações

- O formulário faz validação básica: o nome precisa ter pelo menos duas palavras.
- A idade do usuário é calculada automaticamente pelo PHP.
- As senhas são armazenadas com hash seguro (`password_hash`).
- O arquivo `agenda03.sql` contém a estrutura da tabela `usuarios` importada do meu teste no phpMyAdmin.

- <img width="1892" height="644" alt="image" src="https://github.com/user-attachments/assets/737a0ce9-72a1-4f70-9d06-e9889c3abc3b" />

