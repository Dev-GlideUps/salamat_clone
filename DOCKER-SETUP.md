# Salamat BH - Docker Setup Guide

Complete guide to run Salamat BH Clinic application on Docker.

---

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Clone from GitHub](#clone-from-github)
3. [Install Docker](#install-docker)
4. [Configure the Application](#configure-the-application)
5. [Run Docker Containers](#run-docker-containers)
6. [Import Database](#import-database)
7. [Access the Application](#access-the-application)
8. [Useful Commands](#useful-commands)
9. [Troubleshooting](#troubleshooting)

---

## Prerequisites

- Git installed on your machine
- Docker Desktop
- At least 4GB RAM available
- 10GB free disk space

---

## Clone from GitHub

### Windows (PowerShell or Command Prompt)

```powershell
cd C:\xampp\htdocs
git clone https://github.com/Dev-GlideUps/salamat_clone.git
cd salamatbh.com
```

### Mac/Linux (Terminal)

```bash
cd ~/Projects
git clone https://github.com/Dev-GlideUps/salamat_clone.git
cd salamatbh.com
```

---

## Install Docker

### Windows

1. **Download Docker Desktop**
   - Go to: https://www.docker.com/products/docker-desktop/
   - Click "Download for Windows"

2. **Install Docker Desktop**
   - Run the installer (Docker Desktop Installer.exe)
   - Follow the installation wizard
   - Check "Use WSL 2 instead of Hyper-V" (recommended)

3. **Restart your computer**

4. **Start Docker Desktop**
   - Open Docker Desktop from Start Menu
   - Wait until it shows "Docker is running" (green icon in system tray)

5. **Verify installation**
   ```powershell
   docker --version
   docker-compose --version
   ```

### Mac

1. **Download Docker Desktop**
   - Go to: https://www.docker.com/products/docker-desktop/
   - Click "Download for Mac"
   - Choose Intel Chip or Apple Chip based on your Mac

2. **Install Docker Desktop**
   - Open the downloaded `.dmg` file
   - Drag Docker to Applications folder

3. **Start Docker Desktop**
   - Open Docker from Applications
   - Click "Open" when prompted
   - Wait for Docker to start (whale icon in menu bar)

4. **Verify installation**
   ```bash
   docker --version
   docker-compose --version
   ```

---

## Configure the Application

### Database Configuration

Edit `common/config/main-local.php`:

```php
<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=db;dbname=salamatbh',
            'username' => 'salamatbh',
            'password' => 'salamatbh123',
            'charset' => 'utf8',
            'tablePrefix' => 's6x32z_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
```

> **Note:** Use `db` as the host (Docker service name), not `localhost`.

---

## Run Docker Containers

### Step 1: Build and Start Containers

#### Windows (PowerShell)

```powershell
cd C:\xampp\htdocs\salamatbh\salamatbh.com

# Build and start all containers
docker-compose up -d --build
```

#### Mac/Linux (Terminal)

```bash
cd ~/Projects/salamatbh.com

# Build and start all containers
docker-compose up -d --build
```

### Step 2: Install Composer Dependencies

```bash
docker exec -it salamatbh_clinic composer install --no-dev
```

### Step 3: Initialize Yii2 Application

```bash
docker exec -it salamatbh_clinic php init --env=Development --overwrite=All
```

### Step 4: Run Database Migrations (if needed)

```bash
docker exec -it salamatbh_clinic php yii migrate --interactive=0
```

---

## Import Database

### Option 1: Using Command Line (Recommended)

#### Windows (PowerShell)

```powershell
# Copy SQL file to container
docker cp "C:\xampp\htdocs\salamatbh\salamatbh.com" salamatbh_db:/tmp/database.sql

# Import the database
docker exec -it salamatbh_db mysql -u root -proot salamatbh -e "source /tmp/database.sql"
```

#### Mac/Linux

```bash
# Copy SQL file to container
docker cp ~/Downloads/salamat_db.sql salamatbh_db:/tmp/database.sql

# Import the database
docker exec -it salamatbh_db mysql -u root -proot salamatbh -e "source /tmp/database.sql"
```

### Option 2: Using phpMyAdmin

1. Open http://localhost:8090 in your browser
2. Login with:
   - Username: `root`
   - Password: `root`
3. Select database `salamatbh`
4. Click "Import" tab
5. Choose your SQL file
6. Click "Go"

---

## Access the Application

| Service | URL | Description |
|---------|-----|-------------|
| Clinic App | http://localhost:8080 | Main application |
| phpMyAdmin | http://localhost:8090 | Database management |

### Database Credentials

| Setting | Value |
|---------|-------|
| Host (inside Docker) | `db` |
| Host (outside Docker) | `localhost:3307` |
| Database | `salamatbh` |
| Username | `salamatbh` |
| Password | `salamatbh123` |
| Root Password | `root` |

---

## Useful Commands

### Container Management

```bash
# Start containers
docker-compose up -d

# Stop containers
docker-compose down

# Restart containers
docker-compose restart

# View running containers
docker ps

# View all containers
docker ps -a
```

### Logs

```bash
# View all logs
docker-compose logs

# View clinic app logs
docker-compose logs -f clinic

# View MySQL logs
docker-compose logs -f db
```

### Shell Access

```bash
# Enter clinic container shell
docker exec -it salamatbh_clinic bash

# Enter MySQL container shell
docker exec -it salamatbh_db bash

# Access MySQL directly
docker exec -it salamatbh_db mysql -u root -proot salamatbh
```

### Composer Commands

```bash
# Install dependencies
docker exec -it salamatbh_clinic composer install --no-dev

# Update dependencies
docker exec -it salamatbh_clinic composer update --no-dev

# Clear composer cache
docker exec -it salamatbh_clinic composer clear-cache
```

### Yii2 Commands

```bash
# Run migrations
docker exec -it salamatbh_clinic php yii migrate

# Create migration
docker exec -it salamatbh_clinic php yii migrate/create migration_name

# Clear cache
docker exec -it salamatbh_clinic php yii cache/flush-all
```

### Rebuild

```bash
# Rebuild without cache
docker-compose build --no-cache

# Rebuild and restart
docker-compose up -d --build
```

### Clean Up

```bash
# Remove all containers and volumes
docker-compose down -v

# Remove all unused Docker resources
docker system prune -a
```

---

## Troubleshooting

### Port Already in Use

```
Error: Bind for 0.0.0.0:8080 failed: port is already allocated
```

**Solution:** Change the port in `docker-compose.yml` or stop the conflicting service.

```yaml
ports:
  - "8081:80"  # Change 8080 to 8081
```

### Permission Denied

```
Error: Permission denied
```

**Solution:** Fix permissions inside container:

```bash
docker exec -it salamatbh_clinic chown -R www-data:www-data /var/www/html
docker exec -it salamatbh_clinic chmod -R 755 /var/www/html
```

### Database Connection Failed

```
Error: SQLSTATE[HY000] [2002] Connection refused
```

**Solution:**
1. Ensure MySQL container is running: `docker ps`
2. Wait 30 seconds for MySQL to fully start
3. Check database host is `db` (not `localhost`)

### Composer Memory Error

```
Error: Allowed memory size exhausted
```

**Solution:**

```bash
docker exec -it salamatbh_clinic php -d memory_limit=-1 /usr/bin/composer install --no-dev
```


## File Structure

```
salamatbh.com/
├── admin/              # Admin application
├── api/                # API application
├── clinic/             # Clinic application (main)
├── common/             # Shared code
├── console/            # Console commands
├── docker/
│   └── php/
│       └── php.ini     # PHP configuration
├── insurance/          # Insurance application
├── patient/            # Patient application
├── pharmacy/           # Pharmacy application
├── vendor/             # Composer dependencies
├── .dockerignore       # Docker ignore file
├── docker-compose.yml  # Docker services configuration
├── Dockerfile          # PHP/Apache image configuration
└── composer.json       # PHP dependencies
```

---

## Support

For issues and questions, please contact the development team or create an issue on GitHub.
