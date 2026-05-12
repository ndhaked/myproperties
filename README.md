Here is the updated `README.md` with the **Default Login Credentials** section added at the bottom.

```markdown
# ERM Project (Laravel 12)

This guide provides the steps to install and configure the ERM project locally.

## Prerequisites
Ensure you have the following installed:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL (or your preferred database)

## Installation Steps

### 1. Clone the Repository
```bash
git clone <your-repo-url>
cd erm

```

### 2. Install Backend Dependencies

Install the required PHP packages using Composer:

```bash
composer install

```

### 3. Environment Configuration

Copy the example environment file to create your local configuration:

**For Mac/Linux:**

```bash
cp .env.example .env

```

**For Windows:**

```bash
copy .env.example .env

```

### 4. Generate Application Key

Generate the encryption key required by Laravel:

```bash
php artisan key:generate

```

### 5. Configure Database

Open the `.env` file in your code editor and update the database credentials. Look for this section:

```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=erm_db       # Change to your database name
DB_USERNAME=root         # Change to your database username
DB_PASSWORD=             # Change to your database password

```

*Make sure to create an empty database in your MySQL setup matching the name above.*

### 6. Run Migrations & Seeders

Create the database tables and populate them with dummy data:

```bash
php artisan migrate
php artisan db:seed

```

### 7. Install Frontend Dependencies

Install the Node packages and build the assets:

```bash
npm install
npm run build

```

### 8. Run the Application

Start the local development server:

```bash
php artisan serve

```

The application should now be accessible at: `http://127.0.0.1:8000`

---

## Default Login Credentials

Use the following credentials to log in after seeding the database:

| Role | Email | Password |
| --- | --- | --- |
| **Admin** | `admin@yopmail.com` | `12345678` |
| **Agent** | `agent@yopmail.com` | `12345678` |
| **Leader** | `leader@yopmail.com` | `12345678` |

```

```