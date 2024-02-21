# Cadastro de Pessoas com Laravel & VueJS 🚀

Este projeto é uma API desenvolvida com Laravel, utilizando Laravel Sail para facilitar a configuração e execução em ambientes Docker 🐳. O foco está em fornecer uma solução eficiente para o cadastro e gerenciamento de pessoas 🧑‍🤝‍🧑, acompanhado de testes de integração para garantir a qualidade e confiabilidade do software 🎯.

## Pré-requisitos ✅

Antes de começar, certifique-se de ter o Docker 🐳 instalado em sua máquina. Para usuários Windows ou Mac, o Docker Desktop é recomendado. Para usuários Linux, instale o Docker Engine e Docker Compose seguindo a documentação oficial.

**Importante:** Laravel Sail utiliza a porta `3306` para o MySQL por padrão. Certifique-se de que esta porta esteja disponível 🚦 ou ajuste a configuração conforme necessário.

## DRE - Diagrama de Relacionamento de Entidades 📊
Abaixo está o diagrama de relacionamento de entidades do projeto:
<p align="center">
  <img src="public/dre.svg" alt="DRE - Diagrama de Relacionamento de Entidades" />
</p>

## Como Clonar o Projeto 📋

Para clonar o projeto, abra um terminal e execute o seguinte comando:

```bash
git clone https://github.com/billyfranklim1/cadastro-pessoas-laravel-vuejs.git 
```

🎉 Após clonar o repositório, entre no diretório do projeto:

```bash
cd cadastro-pessoas-laravel-vuejs
```

## Configuração Inicial 🔧

Copie o arquivo `.env.example` para `.env` para configurar o ambiente:

```bash
cp .env.example .env
```

Inicie os contêineres Docker com Laravel Sail 🐳:

```bash
./vendor/bin/sail up -d
```

Instale as dependências do projeto:

```bash
./vendor/bin/sail composer install
```

Gere a chave da aplicação Laravel 🔑:

```bash
./vendor/bin/sail artisan key:generate
```

Execute as migrações para criar as tabelas no banco de dados 🗃️:

```bash
./vendor/bin/sail artisan migrate
```

Ou, se preferir, você rodar o dump SQL que está na raiz do projeto:
    
```bash
dump.sql
```

## Como Rodar os Testes 🧪

Execute os testes de integração com:

```bash
./vendor/bin/sail artisan test
```

<!-- configuração do front-end -->
## Configuração do Front-end 🖥️

Para configurar o front-end, abra um novo terminal e execute o seguinte comando:
    
```bash
    ./vendor/bin/sail npm install
```

e depois:

```bash
    ./vendor/bin/sail npm run dev
```

## Acessando a Aplicação 🌐

A API estará acessível através do `http://localhost:80`.

Se tudo estiver configurado corretamente, você verá a página inicial como a imagem abaixo:
<p align="center">
  <img src="public/capture.png" alt="Página Inicial" />
</p>

## Possíveis Erros e Soluções 🛠️

- **Erro**: Porta `3306` já está em uso 🚫.
  - **Solução**: Verifique se nenhum outro serviço está usando a porta `3306`. Se necessário, ajuste a porta no seu arquivo `.env` e `docker-compose.yml`.

- **Erro**: Permissões ao executar o Sail ⚠️.
  - **Solução**: Execute os comandos do Sail com `sudo` ou adicione seu usuário ao grupo Docker.

## Contribuindo 🤝

Sinta-se à vontade para contribuir com o projeto. Abra uma issue ou envie um pull request com suas sugestões e melhorias.

## Licença 📝

Este projeto está licenciado sob a [Licença MIT](LICENSE).
