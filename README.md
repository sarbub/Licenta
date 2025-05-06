<<<<<<< HEAD
# Centrul Vieții Platform

This is a Laravel web application developed for "Centrul Vieții", likely a student dormitory or similar organization based in Timișoara. The platform manages public information, contact requests, applications for residency, and user accounts with different roles (Admin, Moderator, User).

## Key Features (Implemented So Far)

*   **Public Pages:**
    *   Homepage (`/`) displaying information, carousel, cards, gallery.
    *   About Page (`/about`) with details about the organization, canteen, rooms, etc.
    *   Contact Page (`/contact`) with a contact form that sends emails.
    *   Privacy Policy (`/policy`).
*   **User Authentication (Laravel Jetstream with Livewire):**
    *   User Registration (`/register`) - *Note: Current implementation uses standard Jetstream registration. Logic might need adjustment based on the requirement that only users with accepted requests can register.*
    *   User Login (`/login`).
    *   Password Reset.
    *   Email Verification (Fortify feature).
    *   Profile Management (`/user/profile`):
        *   Update Profile Information (Name, Email, Profile Photo).
        *   Display Account Type (Read-only).
        *   Update Password.
        *   Two-Factor Authentication (Enable/Disable, QR Code, Recovery Codes).
        *   Browser Session Management.
        *   Account Deletion.
*   **User Roles:**
    *   `admin`, `moderator`, and standard user roles implemented via an `account_type` field on the `User` model.
*   **Request Submission Process:**
    *   Email verification step before submitting a request (`/autentificate` form -> `/insert_email_validation_code`).
    *   Resend verification code functionality.
    *   Detailed request form (`/send-request`) capturing personal, academic, and financial details.
    *   Data storage in `pending_requests` table (income encrypted).
    *   Email notification sent to the applicant and admin upon successful submission.
*   **Moderator/Admin Account Creation:**
    *   Separate flow initiated via `/moderator`.
    *   Email verification (`/moderator-validation-code`).
    *   User information form (`/moderator-create`).
    *   Creates a `moderator` if none exists, otherwise creates an `admin`.
*   **Authenticated Area:**
    *   Dashboard (`/dashboard`).
    *   Requests View (`/requestsView`): Displays pending requests based on user role (All for Admin, specific logic for Moderator/User - currently based on email match).

## Technology Stack

*   **Backend:** PHP / Laravel (likely v10/v11)
*   **Frontend:**
    *   **Hybrid Styling:**
        *   **Bootstrap 5:** Used via CDN for public-facing pages (`index`, `about`, `contact`, `autentificate`, `send_request`, etc.) and custom Blade components (`<x-carousel>`, `<x-card>`, etc.). Supplemented by custom CSS (`style.css`, `classes.css`, `index.css`).
        *   **Tailwind CSS:** Used via Vite for the authenticated user area managed by Jetstream (`<x-app-layout>`, `dashboard`, `profile.show`, `requestsView`, etc.) and Jetstream's built-in components.
    *   **Alpine.js:** Used by Jetstream for frontend interactivity.
    *   **Livewire:** Used by Jetstream for dynamic backend interactions (e.g., profile forms).
*   **Database:** Likely MySQL/MariaDB (based on Laragon usage).
*   **Development Environment:** Laragon
*   **Build Tool:** Vite

## Installation & Setup

1.  **Clone the repository:**
    ```bash
    git clone <your-repository-url>
    cd coursera_laravel
    ```
2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```
3.  **Install Node.js Dependencies:**
    ```bash
    npm install
    ```
4.  **Environment Configuration:**
    *   Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    *   Generate an application key:
        ```bash
        php artisan key:generate
        ```
    *   Configure your database connection details (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) in the `.env` file.
    *   Configure your mail driver details (`MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`) in the `.env` file for email sending to work.

5.  **Database Migration:**
    ```bash
    php artisan migrate
    ```
6.  **Build Frontend Assets:**
    *   For development (with hot reloading):
        ```bash
        npm run dev
        ```
    *   For production:
        ```bash
        npm run build
        ```
7.  **Run the Development Server:**
    ```bash
    php artisan serve
    ```
8.  Access the application in your browser (usually `http://127.0.0.1:8000` or your Laragon configured URL).

## Key Routes

*   `/`: Homepage
*   `/about`: About Page
*   `/contact`: Contact Page
*   `/autentificate`: Authentication entry / Start request submission
*   `/login`: User Login
*   `/register`: User Registration
*   `/dashboard`: User Dashboard (Authenticated)
*   `/user/profile`: User Profile Management (Authenticated)
*   `/requestsView`: View Requests (Authenticated, content varies by role)
*   `/moderator`: Start Moderator/Admin creation flow

## Database Structure Highlights

*   `users`: Stores user information, including `first_name`, `last_name`, `email`, `password`, and the custom `account_type` ('admin', 'moderator', or null/default). Managed by Laravel Fortify/Jetstream.
*   `pending_requests`: Stores submitted residency requests, including personal details and encrypted `income`.
*   `sessions`: Managed by Laravel for user sessions.
*   Other tables related to Jetstream features (e.g., `two_factor_...`, `personal_access_tokens`).

## Known Issues / TODOs

*   **Registration Logic:** The standard Jetstream registration allows anyone to register. This needs to be reconciled with the requirement that only users with *accepted* requests can create accounts. How is request acceptance tracked and linked to registration eligibility?
*   **`pending_requests` Table:**
    *   Missing `user_id` column to link requests submitted by logged-in users to their `users` table record.
    *   Missing `status` column (e.g., 'pending', 'approved', 'rejected') to track the state of a request, which is needed for moderator/admin views and potentially for registration logic.
*   **Styling Consistency:** The application uses two different CSS frameworks (Bootstrap for public, Tailwind for authenticated). Consider unifying the styling for a more consistent user experience, or ensure the transition between the two is seamless.
*   **Error Handling:** Review user-facing error messages and validation feedback for clarity and consistency across different forms.

## Contributing

Please follow standard contributing guidelines (Fork, Branch, Pull Request).

## License

(Specify your license here, e.g., MIT License)

=======
# ProiectLicenta
>>>>>>> 2696f3584e51f95155536976e0ae96b2a5335f0e
