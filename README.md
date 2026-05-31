<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/MySQL-MariaDB-003545?style=for-the-badge&logo=mariadb&logoColor=white" alt="MariaDB">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License">
</p>

<h1 align="center">🔥 Clube do Pack</h1>

<p align="center">
  <strong>Plataforma SaaS de Creator Economy — Marketplace híbrido inspirado no Gumroad + OnlyFans</strong><br>
  Focado na venda de packs de imagens e vídeos com monetização por compra avulsa e assinatura mensal.
</p>

---

## 📋 Sobre o Projeto

O **Clube do Pack** é uma plataforma completa de creator economy onde criadores de conteúdo podem publicar e monetizar packs de fotos e vídeos. Os clientes podem comprar packs individuais ou assinar o perfil de um criador para ter acesso a todo o seu conteúdo.

### Destaques

- 🎨 **Design premium** com tema Dark/Light (tons sensuais de rosa/magenta + preto profundo)
- 💰 **Monetização dupla**: venda avulsa (one-off) + assinatura mensal (recurring)
- 🏦 **Split de pagamento automático**: taxa da plataforma configurável
- ☁️ **Cloudflare R2 ready**: armazenamento local para dev, R2 para produção
- 📱 **100% responsivo**: mobile-first com sidebar mobile e componentes adaptáveis
- 🔌 **Gateway modular**: arquitetura preparada para Mercado Pago, Asaas ou PagSeguro

---

## 🛠️ Stack Tecnológica

| Tecnologia | Uso |
|------------|-----|
| **Laravel 12** | Backend & Frontend (Monolito) |
| **Blade Templates** | Views com componentes reutilizáveis |
| **JavaScript Puro** | Interatividade (sem frameworks) |
| **CSS Moderno** | Design system com Custom Properties + Glassmorphism |
| **MySQL / MariaDB** | Banco de dados relacional |
| **Cloudflare R2** | Armazenamento de mídia (S3-compatible) |

---

## 🎯 Funcionalidades

### 👥 Tipos de Usuário

| Role | Permissões |
|------|-----------|
| **Admin** | Painel completo: gerenciar usuários, transações, categorias e configurações |
| **Criador** | Dashboard: publicar packs, gerenciar mídia, ver ganhos, editar perfil |
| **Cliente** | Comprar packs, assinar criadores, acessar biblioteca pessoal |
| **Visitante** | Navegar pelo marketplace, ver perfis e capas (conteúdo bloqueado) |

### 🏠 Marketplace

- Vitrine com grid dinâmico de packs
- Hero section animada com gradientes
- Slider de criadores em destaque
- Packs em destaque com badge especial
- Filtros por **categoria** (pills interativas)
- **Busca** por título, descrição ou nome do criador
- **Ordenação**: mais recentes, populares, mais vendidos, menor/maior preço
- Paginação customizada

### 👤 Perfil do Criador (`/{username}`)

- Banner customizável + avatar
- Bio, estatísticas (packs publicados, assinantes ativos)
- Botão de assinatura com preço mensal
- Grid de packs com badge de acesso desbloqueado
- URL amigável por username

### 📦 Sistema de Packs

- Upload de **capa** e **múltiplas mídias** (drag & drop com preview)
- Suporte a imagens (JPG, PNG, WebP) e vídeos (MP4, MOV)
- Auto-seleção de capa a partir do conteúdo do pack
- Conteúdo **bloqueado** (blur + ícone de cadeado) para não-compradores
- Contadores de views e downloads
- Categorias com ícones

### 💰 Monetização

- **Compra avulsa**: pagamento único → acesso vitalício ao pack
- **Assinatura mensal**: acesso a **todos** os packs do criador enquanto ativo
- **Split automático**: plataforma fica com X% (configurável via `.env`)
- **Registro de transações**: histórico completo com taxas discriminadas

### 🛡️ Painel Admin

- Dashboard com métricas globais (usuários, packs, receita, taxas)
- Gerenciamento de usuários (ativar/desativar)
- Listagem de transações com filtros
- CRUD de categorias
- Informações do sistema e configurações

### 🎨 UI/UX

- **Tema Dark** (padrão): preto profundo + rosa/magenta sensual
- **Tema Light**: alternativa clara com mesma paleta
- **Toggle** ☀️/🌙 no header com persistência via `localStorage`
- Glassmorphism com `backdrop-filter`
- Micro-animações e transições suaves
- Notificações toast
- Menu mobile com sidebar deslizante
- Tipografia premium (Google Fonts: Inter + Outfit)

---

## 🚀 Instalação

### Pré-requisitos

- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Node.js (opcional)

### Passo a passo

```bash
# 1. Clonar o repositório
git clone https://github.com/emanuelxenos/clubedopack.git
cd clubedopack

# 2. Instalar dependências
composer install

# 3. Copiar e configurar o .env
cp .env.example .env
php artisan key:generate

# 4. Configurar o banco de dados no .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=clubedopack
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Criar o banco de dados
mysql -u root -e "CREATE DATABASE clubedopack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Rodar migrations e seeders
php artisan migrate:fresh --seed

# 7. Criar link simbólico do storage
php artisan storage:link

# 8. Iniciar o servidor
php artisan serve
```

Acesse: **http://localhost:8000**

---

## 🔑 Credenciais Padrão

| Usuário | E-mail | Senha | Role |
|---------|--------|-------|------|
| Administrador | `admin@admin.com` | `admin123` | Admin |
| Isabella Santos | `isabella@demo.com` | `demo123` | Creator |
| Carolina Mendes | `carol@demo.com` | `demo123` | Creator |
| Valentina Costa | `valentina@demo.com` | `demo123` | Creator |
| Fernanda Lima | `fernanda@demo.com` | `demo123` | Creator |
| João Cliente | `cliente@demo.com` | `demo123` | Customer |

---

## 📁 Estrutura do Projeto

```
clubedopack/
├── app/
│   ├── Contracts/
│   │   └── PaymentGatewayInterface.php    # Interface para gateways de pagamento
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminController.php        # Painel administrativo
│   │   │   ├── AuthController.php         # Login, registro, logout
│   │   │   ├── DashboardController.php    # Dashboard do criador
│   │   │   ├── HomeController.php         # Marketplace (home)
│   │   │   ├── PackController.php         # Detalhe do pack
│   │   │   ├── ProfileController.php      # Perfil público do criador
│   │   │   └── PurchaseController.php     # Compras, assinaturas, biblioteca
│   │   └── Middleware/
│   │       └── CheckRole.php              # Middleware de verificação de roles
│   ├── Models/
│   │   ├── Category.php
│   │   ├── Media.php
│   │   ├── Pack.php
│   │   ├── Purchase.php
│   │   ├── Subscription.php
│   │   ├── Transaction.php
│   │   └── User.php
│   └── Services/
│       └── Payments/
│           └── MockGateway.php            # Gateway mock para desenvolvimento
├── database/
│   ├── migrations/                        # 8 migrations (users → transactions)
│   └── seeders/
│       └── DatabaseSeeder.php             # Admin + categorias + dados demo
├── public/
│   ├── css/
│   │   └── app.css                        # Design system completo (~1200 linhas)
│   └── js/
│       └── app.js                         # Toggle tema, toasts, upload, dropdowns
├── resources/views/
│   ├── admin/                             # Painel admin (5 views)
│   ├── auth/                              # Login e registro
│   ├── dashboard/                         # Dashboard do criador (5 views)
│   ├── layouts/                           # Layouts: app, dashboard, admin
│   ├── packs/                             # Detalhe do pack
│   ├── pagination/                        # Paginação customizada
│   ├── profile/                           # Perfil do criador
│   ├── home.blade.php                     # Marketplace
│   └── library.blade.php                  # Biblioteca do cliente
└── routes/
    └── web.php                            # Todas as rotas organizadas por contexto
```

---

## 🗄️ Modelagem do Banco de Dados

```
users ──────────┬──── packs ──────── media
                │       │
                │       └──── purchases
                │
                ├──── subscriptions (subscriber ↔ creator)
                │
                └──── transactions (polymorphic → purchases/subscriptions)

categories ──── packs
```

### Tabelas

| Tabela | Descrição |
|--------|-----------|
| `users` | Usuários com roles (admin/creator/customer), avatar, bio, preço de assinatura |
| `categories` | Categorias dos packs (com ícone e ordenação) |
| `packs` | Packs de conteúdo com capa, preço, contadores |
| `media` | Arquivos de mídia (imagens/vídeos) vinculados aos packs |
| `subscriptions` | Assinaturas mensais (subscriber → creator) |
| `purchases` | Compras avulsas de packs |
| `transactions` | Registro financeiro com split de pagamento |

---

## ⚙️ Variáveis de Ambiente

| Variável | Descrição | Padrão |
|----------|-----------|--------|
| `STORAGE_MODE` | Modo de armazenamento (`local` ou `r2`) | `local` |
| `PLATFORM_FEE_PERCENT` | Taxa da plataforma em % | `15` |
| `R2_ACCESS_KEY_ID` | Chave de acesso Cloudflare R2 | — |
| `R2_SECRET_ACCESS_KEY` | Chave secreta R2 | — |
| `R2_BUCKET` | Nome do bucket R2 | `clubedopack` |
| `R2_ENDPOINT` | Endpoint do R2 | — |

---

## 🔌 Integração de Pagamentos

O sistema utiliza o padrão **Strategy** com uma interface `PaymentGatewayInterface` que define os métodos:

- `createOneTimePayment()` — Compra avulsa
- `createSubscription()` — Assinatura mensal
- `cancelSubscription()` — Cancelamento
- `handleWebhook()` — Processar callbacks do gateway
- `getPaymentStatus()` — Consultar status

Atualmente usa o `MockGateway` (auto-confirma tudo). Para produção, implemente:

```php
// app/Services/Payments/MercadoPagoGateway.php
class MercadoPagoGateway implements PaymentGatewayInterface
{
    // Implementar métodos com SDK do Mercado Pago
}
```

---

## 🗂️ Categorias Pré-cadastradas

| Ícone | Nome |
|-------|------|
| 🔥 | Ensaio Sensual |
| 💪 | Fitness |
| 🎭 | Cosplay |
| ✨ | Lifestyle |
| 📸 | Arte & Fotografia |
| 👗 | Moda |
| 💄 | Beleza |
| 💎 | Exclusivo |

---

## 📄 Licença

Este projeto é distribuído sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

<p align="center">
  Feito com 💖 no Brasil
</p>
