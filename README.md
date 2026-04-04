# 💇‍♀️ Salão de Beleza - Cabeleleila Leila

Projeto desenvolvido para processo seletivo DSIN para vaga de Estágio como DEV. O projeto foca em oferecer uma experiência fluida para o cliente e uma gestão eficiente para o administrador, permitindo o controle de serviços, horários e status de atendimento.

---

## 🚀 Tecnologias Utilizadas

O projeto demonstra o uso de boas práticas de engenharia de software e padrões de mercado:

* **Linguagem:** PHP 8.x (PHP Puro/Vanilla)
* **Arquitetura:** MVC (Model-View-Controller) para separação de lógica e visualização.
* **Banco de Dados:** MySQL com persistência via PDO (PHP Data Objects).
* **Gestão de Dependências:** [Composer](https://getcomposer.org/).
* **Ambiente e Segurança:** `vlucas/phpdotenv` para gerenciamento de variáveis de ambiente (`.env`).
* **Frontend:** Bootstrap 5 (Layout Responsivo) e Bootstrap Icons.
* **Padronização:** Autoloading via PSR-4.

---

## 🛠️ Instruções de Instalação e Execução

Siga os passos abaixo para rodar o projeto em sua máquina local:

### 1. Clonar e Instalar Dependências
Baixe o projeto e, na pasta raiz, execute o comando para instalar as bibliotecas necessárias:
```bash
composer install
```
### 2. Configurar o Banco de Dados
1. Certifique-se de que seu servidor MySQL (como o XAMPP) esteja ativo.
2. Crie um banco de dados chamado `bd-cabeleleila-leila`.
3. Importe o arquivo SQL enviado no projeto (`database.sql`) para criar as tabelas e dados iniciais.

### 3. Configurar Variáveis de Ambiente (.env)
1. Na raiz do projeto, localize o arquivo `.env.example`.
2. Renomeie ou copie este arquivo para um novo chamado `.env`.
3. Abra o arquivo `.env` e preencha com suas credenciais locais:
```
# EXEMPLO:
DB_HOST=localhost
DB_NAME=bd-cabeleleila-leila
DB_USER=root
DB_PASS=
DB_PORT=3306
```

### 4. Iniciar o Servidor
O projeto utiliza a pasta public como diretório raiz do servidor web, e o router direciona o usuário entre as páginas. Para iniciar, execute:
```bash
php -S localhost:8000 public/router.php
```
Acesse o sistema em seu navegador através do endereço: http://localhost:8080.

---

**Desenvolvido por:** Rafael Panciera Queroli
  
