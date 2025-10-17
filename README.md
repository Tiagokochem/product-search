# Product Search - Laravel 11 + Livewire

Sistema de busca de produtos com filtros combinados desenvolvido em Laravel 11 com Livewire, PostgreSQL e Docker Sail.

## 🚀 Tecnologias Utilizadas

- **Laravel 11** - Framework PHP
- **Livewire 3** - Componentes reativos
- **PostgreSQL** - Banco de dados
- **Docker Sail** - Ambiente de desenvolvimento
- **Tailwind CSS** - Framework CSS
- **PHPUnit** - Testes automatizados

## 📋 Funcionalidades

### ✅ Filtros de Pesquisa
- **Nome do produto** - Busca por nome, descrição ou SKU
- **Categoria** - Filtro por uma ou múltiplas categorias
- **Marca** - Filtro por uma ou múltiplas marcas

### ✅ Recursos Implementados
- Filtros combinados (Nome E Categoria E Marca)
- Seleção múltipla de categorias e marcas
- Persistência de filtros na URL (refresh da página mantém filtros)
- Botão para limpar todos os filtros
- Ordenação por nome, preço, data e estoque
- Paginação dos resultados
- Busca em tempo real com debounce
- Interface responsiva e moderna
- Contadores de produtos por filtro

## 🏗️ Arquitetura

### MVC + Service Pattern
```
app/
├── Http/Controllers/
│   └── ProductController.php      # Controller principal
├── Services/
│   └── ProductService.php         # Lógica de negócio
├── Models/
│   ├── Product.php               # Model de produtos
│   ├── Category.php              # Model de categorias
│   └── Brand.php                 # Model de marcas
├── Livewire/
│   └── ProductSearch.php         # Componente Livewire
└── ...
```

### Database Schema
```sql
-- Categorias
categories: id, name, slug, description, active, timestamps

-- Marcas  
brands: id, name, slug, description, active, timestamps

-- Produtos
products: id, name, slug, description, price, sku, stock_quantity, 
         images (json), category_id, brand_id, active, timestamps
```

## 🐳 Instalação e Execução

### Pré-requisitos
- Docker
- Docker Compose

### 1. Clone o repositório
```bash
git clone https://github.com/Tiagokochem/product-search
cd mood
```

### 2. Instale as dependências (primeira execução)
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### 3. Copie o arquivo de ambiente
```bash
cp .env.example .env
```

### 4. Inicie o ambiente Docker
```bash
./vendor/bin/sail up -d
```

### 5. Configure o ambiente
```bash
./vendor/bin/sail artisan key:generate
```

### 6. Execute as migrations e seeders
```bash
./vendor/bin/sail artisan migrate --seed
```

### 7. Acesse a aplicação
- **Frontend**: http://localhost
- **Banco de dados**: localhost:5432
  - Database: `laravel`
  - Username: `sail`
  - Password: `password`

## 🧪 Testes

### Executar todos os testes
```bash
./vendor/bin/sail artisan test
```

### Executar testes específicos
```bash
./vendor/bin/sail artisan test --filter=ProductSearchTest
```

### Cobertura de Testes
- ✅ Carregamento da página
- ✅ Busca por nome do produto
- ✅ Filtro por categoria única
- ✅ Filtro por marca única
- ✅ Filtro por múltiplas categorias
- ✅ Filtro por múltiplas marcas
- ✅ Filtros combinados (busca + categoria + marca)
- ✅ Ordenação por preço (crescente/decrescente)
- ✅ Ordenação por nome
- ✅ Limpeza de filtros
- ✅ Persistência de filtros na URL
- ✅ Paginação
- ✅ Mensagem de "nenhum resultado"
- ✅ Toggle de filtros (adicionar/remover)
- ✅ Busca case-insensitive
- ✅ Funcionalidade de debounce

## 📊 Dados de Exemplo

O seeder cria automaticamente produtos brasileiros:
- **10 categorias** (Eletrônicos, Eletrodomésticos, Moda, etc.)
- **40+ marcas brasileiras** (Natura, O Boticário, Havaianas, etc.)
- **25+ produtos** com dados realistas do mercado brasileiro

## 🎯 Requisitos Atendidos

### ✅ Funcionalidades Obrigatórias
- [x] Filtro por nome do produto
- [x] Filtro por categoria
- [x] Filtro por marca
- [x] Filtros combinados (E lógico)
- [x] Seleção múltipla de categorias e marcas
- [x] Persistência de filtros no refresh
- [x] Botão para limpar filtros

### ✅ Tecnologias Obrigatórias
- [x] Laravel 11
- [x] Livewire
- [x] PostgreSQL
- [x] Docker Sail
- [x] Migrations, Factories e Seeders
- [x] Testes automatizados

### ✅ Qualidade de Código
- [x] Código limpo e organizado
- [x] Arquitetura MVC + Services
- [x] Relacionamentos Eloquent
- [x] Scopes personalizados
- [x] Validações e tratamento de erros
- [x] Documentação completa

## 🔧 Comandos Úteis

### Desenvolvimento
```bash
# Acessar container
./vendor/bin/sail shell

# Executar Artisan commands
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail artisan make:model Product

# Logs
./vendor/bin/sail logs

# Parar containers
./vendor/bin/sail down
```

### Banco de Dados
```bash
# Acessar PostgreSQL
./vendor/bin/sail psql

# Reset database
./vendor/bin/sail artisan migrate:fresh --seed
```

## 📱 Interface

### Funcionalidades da Interface
- **Busca em tempo real** com debounce de 300ms
- **Filtros laterais** com checkboxes para categorias e marcas
- **Contadores** mostrando quantidade de produtos por filtro
- **Ordenação** por nome, preço, data e estoque
- **Paginação** responsiva
- **Loading states** durante as operações
- **Mensagens** de feedback para o usuário
- **Design responsivo** para mobile e desktop

### Componentes
- Header com título e informações do projeto
- Barra de busca com ícone
- Controles de ordenação
- Sidebar com filtros de categoria e marca
- Grid de produtos com informações detalhadas
- Paginação customizada
- Estados de loading e empty state

## 🏆 Diferenciais Implementados

- **Arquitetura robusta** com Services e Repository pattern
- **Testes abrangentes** cobrindo todos os cenários
- **Interface moderna** com Tailwind CSS
- **Performance otimizada** com índices no banco
- **Código limpo** seguindo PSR-12
- **Documentação completa** com exemplos
- **Docker otimizado** para desenvolvimento
- **Tratamento de erros** adequado
- **Validações** em todos os níveis

## 🔧 Troubleshooting

### Problemas Comuns

**Erro: "vendor/bin/sail: No such file or directory"**
```bash
# Execute primeiro a instalação das dependências
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

**Erro: "Port 5432 already in use"**
```bash
# Pare outros containers PostgreSQL
docker ps | grep postgres
docker stop <container-id>
```

**Erro: "Port 80 already in use"**
```bash
# Use uma porta diferente
echo "APP_PORT=8080" >> .env
./vendor/bin/sail up -d
# Acesse: http://localhost:8080
```

**Reset completo do ambiente**
```bash
./vendor/bin/sail down -v
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed
```

## 📄 Licença

Este projeto foi desenvolvido como teste técnico e demonstra as melhores práticas de desenvolvimento Laravel.

---
<img width="1400" height="1006" alt="image" src="https://github.com/user-attachments/assets/c20a132e-33c0-451a-97ee-ea09b5765969" />


**Desenvolvido com ❤️ usando Laravel 11 + Livewire + PostgreSQL + Docker Sail**
