# LinkShortener

A clean and simple URL shortener built with plain PHP, MySQL, and Tailwind CSS. It supports instant link shortening without registration, optional user accounts to manage links, and detailed visit analytics.

---

## Quick Start

### 1. Requirements
- PHP 8.0+
- MySQL / MariaDB
- Apache with `mod_rewrite` enabled (e.g. MAMP, XAMPP, or LAMP)

### 2. Database Setup
Create a new MySQL database named `linkshortner` and import [`database.sql`](database.sql):

```bash
mysql -u root -p linkshortner < database.sql
```

### 3. Configuration
Copy the sample configuration file:

```bash
cp config/config.example.php config/config.php
```

Open [`config/config.php`](config/config.php) and adjust your database connection details and application URL if needed:

```php
'app' => [
    'url' => 'http://localhost:8888/Linkshortner',
],
'database' => [
    'host' => '127.0.0.1',
    'port' => 3306, // or 8889 for MAMP
    'name' => 'linkshortner',
    'user' => 'root',
    'pass' => 'root',
],
```

### 4. Run the Project
Place the project folder in your web server document root (e.g., `htdocs` for MAMP/XAMPP) and access it via your browser:

```
http://localhost:8888/Linkshortner
```

---

## What's Included

- **No-fuss shortening:** Shorten links right from the homepage without needing an account.
- **User accounts:** Optional signup/login with password hashing and "Remember Me" session support.
- **Link dashboard:** Manage, update, or remove your created links.
- **Detailed analytics:** Track clicks, referrers, devices, locations, and export data as CSV.
- **Performance & protection:** Built-in rate limiting and fast URL lookup caching.
