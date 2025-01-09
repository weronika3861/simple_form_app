# Simple form app

### This application consists of two main pages:

#### 1. Form Page with the following fields:
    - First Name (cannot be empty)
    - Last Name (cannot be empty)
    - Attachment (accepts only image files with a maximum size of 2MB)

After submission, a success or error message is displayed to the user.
Attachments are saved into public/uploads directory.
Form data is saved asynchronously to the database using Symfony Messenger.

#### 2. Customer List Page, displays a list of data submitted through the form.
The Customer List page is protected and can only be accessed by authenticated users.

---
# Project Setup 

---

## 🐳 Option 1: Run with Docker

### Requirements
- Docker 
- Docker Compose 

### 1. **Clone the repository**

```
git clone https://github.com/weronika3861/simple_form_app.git`
cd simple_form_app
```

### 2. **Build and start the Docker containers**
   ```bash
   docker-compose up --build -d
   ```

### 3. **Access the application**
   - The app will be available at `http://localhost:8000`

### 4. **Stop the containers**
   ```bash
   docker-compose down
   ```

---

## ⚙️ Option 2: Run without Docker

### Requirements
- PHP (>= 8.2)
- Composer
- PostgreSQL (12 or higher)
- Symfony CLI

### 1. Clone the repository

First, clone the repository to your local machine:

````
git clone https://github.com/weronika3861/simple_form_app.git
cd simple_form_app
````
### 2. Install dependencies

Install all dependencies using the following command:
`composer install`
### 3. Set up PostgreSQL database

Ensure that PostgreSQL is running on your system.

#### Create PostgreSQL Database and User

1. Access PostgreSQL:

   `sudo -u postgres psql`

2. Create a new PostgreSQL user and database:
   ```
   CREATE DATABASE symfony_app;
   CREATE USER symfony_user WITH ENCRYPTED PASSWORD 'password';
   GRANT ALL PRIVILEGES ON DATABASE symfony_app TO symfony_user;
   ```

### 4. Configure the .env file

The .env file already contains the database connection string. However, ensure that it reflects the correct credentials for your PostgreSQL setup.

In your .env file:

`DATABASE_URL="postgresql://symfony_user:password@127.0.0.1:5432/symfony_app?serverVersion=14"`

Make sure to replace symfony_user, password, and symfony_app with the correct values you used when setting up PostgreSQL.


### 5. Run migrations
To run the tests in the project, make the migrations:
```
php bin/console doctrine:migrations:migrate
```

### 6. Start the Symfony server

Now, you can start the Symfony application:

`symfony server:start`
### 7. Access the project

Once the server is running, open your browser and go to http://127.0.0.1:8000 to view the application.

### 8. Start worker
`php bin/console messenger:consume`

