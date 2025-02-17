# Contact Management Application

This application is a simple contact management tool built using the Laravel framework, providing functionalities to manage contacts, import contacts from XML files, and search contacts.

## Table of Contents

-   [Introduction](#introduction)
-   [Architecture](#architecture)
-   [Features](#features)
-   [Installation](#installation)
-   [Development](#development)
-   [Usage](#usage)
-   [Technologies Used](#technologies-used)
-   [File Structure](#file-structure)
-   [Deployment](#deployment) (Optional - Add if applicable)
-   [Contributing](#contributing) (Optional)
-   [License](#license) (Optional)

## Introduction

This application allows users to store, retrieve, update, and delete contact information. It also provides a feature to import contacts from an XML file and a search functionality to quickly find contacts.

## Architecture

The application follows the Model-View-Controller (MVC) architectural pattern:

-   **Model:** The `Contact` model interacts with the database to manage contact data.
-   **View:** Blade templates are used to create the user interface.
-   **Controller:** The `ContactController` handles user requests, interacts with the model, and selects the appropriate view.

The application uses a relational database (PostgreSQL) for data persistence. Nginx serves as a reverse proxy and web server, and PHP-FPM processes PHP requests. Redis is used for caching (optional, but recommended).

## Features

-   **Contact Management:** Create, read, update, and delete contacts.
-   **Import from XML:** Import contacts from an XML file.
-   **Search:** Search contacts by name, email, or phone.
-   **Responsive UI:** User interface is responsive and adapts to different screen sizes (using Tailwind CSS).

## Installation

1.  **Clone the Repository:**

    ```bash
    git clone [https://github.com/hariharandr/contacts-app.git](https://github.com/hariharandr/contacts-app.git)
    cd contact-management-app
    ```

2.  **Install Dependencies:**

    ```bash
    composer install
    ```

3.  **Environment Configuration:**

    -   Copy the `.env.example` file to `.env`:

        ```bash
        cp .env.example .env
        ```

    -   Generate the application key:

        ```bash
        php artisan key:generate
        ```

    -   Configure the database connection in the `.env` file:

        ```
        DB_CONNECTION=pgsql
        DB_HOST=postgres  # Service name in docker-compose
        DB_PORT=5432
        DB_DATABASE=laravel
        DB_USERNAME=root
        DB_PASSWORD= # Your Postgres password or leave blank if using trust authentication
        ```

4.  **Docker Setup (Recommended for Development):**

    -   Install Docker and Docker Compose.
    -   Build and run the Docker containers:

        ```bash
        docker-compose up -d --build
        ```
    - or you can just start the application in .devcontainer using vs-code reopen in contianer
    -   Access the application at `http://localhost`.

## Development

1.  **Start the Development Server (if not using Docker):**

    ```bash
    php artisan serve
    ```

2.  **Access the application:** Open your browser and go to `http://localhost:8000`.

3.  **Run Tests (if applicable):**

    ```bash
    php artisan test
    ```

## Usage

1.  **Contact List:** Navigate to `/contacts` to view the list of contacts.

2.  **Add Contact:** Click the "Add Contact" button to add a new contact.

3.  **Edit Contact:** Click the "Edit" link next to a contact to edit its information.

4.  **Delete Contact:** Click the "Delete" button next to a contact to delete it.

5.  **Import Contacts:** Navigate to `/import-contacts` to import contacts from an XML file.

6.  **Search Contacts:** Use the search bar in the sidebar to search for contacts.

## Technologies Used

-   **Laravel:** PHP framework.
-   **PostgreSQL:** Relational database.
-   **Nginx:** Web server and reverse proxy.
-   **PHP-FPM:** FastCGI Process Manager.
-   **Redis:** Caching (Optional).
-   **Tailwind CSS:** CSS framework.

## File Structure

app/
├── Http/
│   ├── Controllers/
│   │   └── ContactController.php
│   └── Models/
│       └── Contact.php
resources/
├── views/
│   ├── contacts/
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   ├── import.blade.php
│   │   ├── index.blade.php
│   │   └── _form.blade.php
│   └── layouts/
│       └── app.blade.php
routes/
└── web.php
docker-compose.yml
nginx/
└── default.conf
.env

## Future Improvements

Due to time constraints, I was unable to implement all the desired features and best practices in this version. However, I would like to outline the key improvements I would make in future development:

**1. Database Migrations:**

I would create explicit database migrations to manage the database schema. This is crucial for version control and collaborating on the project.

```php
// Example migration (database/migrations/create_contacts_table.php)
Schema::create('contacts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->string('phone');
    $table->timestamps();
});
2. Queues for XML Imports:

For large XML files, I would implement queues to handle imports in the background, preventing timeouts and improving performance.

PHP

// Example job (app/Jobs/ImportContacts.php)
ImportContacts::dispatch($xmlData);
3. Enhanced Caching:

While Redis is used for session caching, I would implement more aggressive caching for frequently accessed data (e.g., the contact list) to further optimize performance.

PHP

// Example caching
$contacts = Cache::remember('all_contacts', now()->addMinutes(60), function () {
    return Contact::all();
});
4. API Development:

I would create API resources and controllers to provide a clean API for accessing contact data.

PHP

// Example API resource (app/Http/Resources/ContactResource.php)
return [
    'id' => $this->id,
    'name' => $this->name,
    // ...
];
5. Testing (Unit and Feature Tests):

Thorough testing is essential. I would write unit tests for the ContactService and feature tests for the ContactController to ensure the application's reliability and prevent regressions.

6. Authentication and Authorization:

For production applications, adding authentication and authorization to secure the application is crucial.

7. Package Integration:

I would explore and integrate useful Laravel packages for form handling, UI components, and other functionalities to enhance the development process and the user interface.

8. Improved Error Handling:

While basic error handling is in place, I would add more specific error messages and logging to provide better feedback to users and aid in debugging.

9. Code Formatting and Style:

Consistent code formatting is important.  I would use a code formatter (e.g., php-cs-fixer) to enforce coding standards.

I believe these improvements would significantly enhance the application's functionality, performance, maintainability, and security.  I am eager to implement these in future development.
