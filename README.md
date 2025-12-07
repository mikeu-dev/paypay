# PayPay ERP - Software House Edition

**PayPay ERP** is a comprehensive, open-source management system designed specifically for modern software houses. Built with **Laravel 11** and **Filament v3**, it unifies HR, Finance, and Project Management into a single, beautiful interface.

![PayPay ERP Dashboard](/docs/dashboard-preview.png)

## 🚀 Features

### 👥 Human Resources (HR)
-   **Employee Management**: distinct profiles, departments, and roles.
-   **Attendance System**: Daily tracking with status (Present, Late, Absent).
-   **Calendar View**: Visual attendance tracking.

### 💰 Finance & Payroll
-   **Automated Payroll**: Calculates `(Base + Allowances) - Deductions`.
-   **Payslip Generation**: Single-click PDF-ready payslip views.
-   **Invoicing**: Create professional invoices linked to Clients and Projects.
-   **allowances & Deductions**: Fixed or percentage-based rules.

### 💼 CRM & Operations
-   **Client Management**: Track client details and contracts.
-   **Project Management**: Manage projects like "Website Redesign", budgets, and timelines.
-   **Task Tracking**: Kanban/List view for tasks (Pekerjaan).

### 🛠 System & Admin
-   **Role-Based Access Control (RBAC)**: Super Admin, HR, Finance, Operations, Marketing.
-   **Audit Logs**: Tracks every action (Created, Updated, Deleted).
-   **Theme Customization**: Change colors, fonts, and navigation layout on the fly.
-   **Data Safety**: Soft deletes enabled for critical data.

## 🛠 Installation

### Prerequisites
-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   MySQL / MariaDB

### Setup

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/yourusername/paypay-erp.git
    cd paypay-erp
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Configure Environment**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    *Update `.env` with your database credentials.*

4.  **Migrate & Seed**
    ```bash
    php artisan migrate
    php artisan db:seed
    ```

5.  **Create Admin User**
    ```bash
    php artisan make:filament-user
    ```

## 🔑 Default Credentials (Seeded)

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@paypay.com` | `password` |
| **HR Manager** | `hr@paypay.com` | `password` |
| **Finance** | `finance@paypay.com` | `password` |
| **Operations** | `operations@paypay.com` | `password` |
| **Marketing** | `marketing@paypay.com` | `password` |

## 🧪 Testing

Run the automated feature tests to verify the system:

```bash
php artisan test
```

## 📜 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# paypay
