![nextsuite-logo.png](public/img/nextsuite-logo-full.png)

# Nextsuite

Nextsuite is a Laravel-based web application designed to manage customers and companies, 
along with their associated skills, attributes, and other customizable characteristics. 
It provides a flexible structure for organizing and enriching entity data, making it suitable for CRM-like use cases, 
talent tracking, or business intelligence workflows.

---

## 🚀 Features 
- Manage **Customers** and **Companies** 
- Each **Skills**, **Attributes**, and custom characteristics to entities
- Modular and extensible data relationships
- Authentication powered by Laravel Fortify
- Role and permission management
- Activity logging and auditing
- Data visualization with charts
- PDF generation support
- API-ready with token authentication

---

## 🧱 Tech Stack

- Laravel 12
- PHP 8.4
- Livewire 4 + Volt + Flux
- Laravel Jetstream
- Laravel Fortify (authentication backend)
- Laravel Sanctum (API authentication)

---

## 📦 Key Dependencies

### Core Packages

- `laravel/framework`
- `laravel/fortify`
- `laravel/jetstream`
- `laravel/sanctum`
- `livewire/livewire`
- `livewire/volt`
- `livewire/flux`

### UI & Utilities

- `blade-ui-kit/blade-heroicons`
- `outhebox/blade-flags`
- `rinvex/countries`
- `propaganistas/laravel-phone`

### Data & Visualization

- `icehouse-ventures/laravel-chartjs`
- `barryvdh/laravel-dompdf`

### Permissions & Logging

- `spatie/laravel-permission`
- `spatie/laravel-activitylog`

### AI Integration

- `openai-php/client`
- `openai-php/laravel`

### Additional Tools

- `guzzlehttp/guzzle`
- `fakerphp/faker`
- `salahhusa9/laravel-updater`

---

## 🛠️ Development Dependencies

- `laravel/sail`
- `laravel/telescope`
- `barryvdh/laravel-debugbar`
- `pestphp/pest`
- `pestphp/pest-plugin-laravel`
- `laravel/pint`
- `mockery/mockery`

---

## ⚙️ Installation

```bash
git clone https://github.com/danilo-quattrini/nextsuite.git
cd nextsuite
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```

## 🧪 Development
Run the full development environment:
```bash
composer run dev
```

This starts:
- Laravel development server
- Queue worker
- Log viewer
- Vite dev server

## 📄 License

This project is licensed under the MIT License.
