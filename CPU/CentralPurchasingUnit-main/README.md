# Xavier University's Purchase Request Web Application

Xavier University's Purchase Request Web Application is a web-based platform designed to streamline the process of submitting, managing, and tracking purchase requests within the university. Users can submit requests digitally instead of using physical forms, while admin users can efficiently review, approve, or reject requests through the admin panel. Additionally, the application provides insightful charts for report generation based on request data.

## Features

- **Digital Purchase Request Form:** Users can submit purchase requests through a user-friendly digital form accessible on the front page of the application.
- **Admin Panel:** Admin users have access to an admin panel where they can view, manage, and take action on submitted requests. Actions include approval, rejection, and adding digital signatures.
- **Google Sign-In:** Users can sign in to the application using their Google accounts for added convenience and security.
- **Email Notifications:** The application integrates PHPMailer library to send email notifications to users upon request submission, approval, or rejection.
- **Responsive Design:** Utilizes Bootstrap and Tailwind CSS frameworks for a responsive and visually appealing user interface across different devices.

## Technologies Used

- **Frontend:** HTML, CSS, JavaScript, jQuery, Bootstrap, Tailwind CSS
- **Backend:** PHP
- **Database:** MySQL
- **Authentication:** Google Sign-In API

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/VinniAgawinUba/CPU.git

2. Import the database schema from database-schema.sql file into your MySQL database.
3. Configure database connection in config/dbcon.php file.
4. Ensure that your web server is configured to serve PHP files.
5. Set up Google Sign-In credentials and configure them in the application.
6. Open the application in your web browser.

## Usage
Users can access the front page to fill out and submit purchase request forms digitally.
Admin users can log in to the admin panel to view, manage, and take action on submitted requests.
Use the provided charts for report generation and analysis of request data.
Contributing
Contributions are welcome! If you have any suggestions, bug reports, or feature requests, please open an issue or submit a pull request.

License
This project is licensed under the MIT License.