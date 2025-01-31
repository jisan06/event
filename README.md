# Event Management System

A simple event management system built with PHP and MySQL, following the MVC pattern and repository design.

## Features

- User registration and authentication
- Event creation, editing, and deletion
- Event listing with details
- Event filtering by different data
- Event registration system for audience/users
- Show registered users for an event

## Live Demo with Login Credential
- URL: https://event.qubemet.com
- Email: admin@gmail.com
- Password: 12345678

## Installation Instructions

Follow these steps to set up the project on your local server:

### 1. Clone the Repository

```sh
git clone https://github.com/jisan06/event.git
cd event-main  # You can change your project/directory name
```

### 2. Configure Project

```sh
cp .env_example .env  # Copy the environment file
```

Edit the `.env` file and update your database credentials:

```
DB_HOST=your_database_host
DB_NAME=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 3. Browse the Project

Open your browser and navigate to:

```sh
http://localhost/event-main  # You can define your project name or you can run according to your server, example: event-main.test
```

- You will see the homepage with options to **Register** and **Login**.
- After logging in, you can create and manage events.

Enjoy building and managing events! 🚀

