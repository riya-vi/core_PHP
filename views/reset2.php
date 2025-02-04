To implement a **Forgot Password** functionality in PHP, you need to allow users to reset their password by sending a **reset link** to their email address. Here’s a step-by-step guide on how to implement this:

### Steps to Implement Forgot Password:

1. **Create a Forgot Password Form (HTML)**
2. **Handle Form Submission (PHP)**: When the user submits the email address, check if it exists in the database.
3. **Generate a Password Reset Token**: Generate a unique token and save it to the database.
4. **Send Reset Link via Email**: Send an email to the user with a reset link containing the token.
5. **Create a Reset Password Form**: Allow the user to enter a new password.
6. **Verify the Token and Update the Password**: Check if the token is valid, and if so, allow the user to reset the password.

---

### 1. **Create Forgot Password Form (HTML)**

```html
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>

<body>
    <h1>Forgot Password</h1>
    <form method="POST" action="forgot-password.php">
        <div>
            <label for="email">Enter your email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <button type="submit">Send Reset Link</button>
        </div>
    </form>
</body>

</html>
```

---

<!-- ### 2. **Handle Form Submission (PHP)**

In the `forgot-password.php` file, we will handle the form submission. If the email exists in the database, we generate a reset token and send the reset link to the user via email. -->

```php
<?php
include 'config/dataBaseConnect.php';  // Include database connection
use PHPMailer\PHPMailer\PHPMailer;  // Include PHPMailer class for sending emails
use PHPMailer\PHPMailer\Exception;  // Include PHPMailer Exception class

require 'vendor/autoload.php';  // Assuming you're using Composer to autoload PHPMailer classes

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email entered by the user
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT id FROM users WHERE email = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If email exists in the database, generate reset token and send an email
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];

        // $email_token = openssl_random_pseudo_bytes(16);
        // $token = bin2hex($email_token);

        // Generate a unique reset token (could use random bytes or UUID)
        $token = bin2hex(random_bytes(50));
        $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store the reset token and expiry time in the database
        $query = "INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("iss", $userId, $token, $expiry);
        $stmt->execute();

        // Send the reset link via email
        $resetLink = "http://localhost/reset-password.php?token=" . $token;

        // Use PHPMailer to send the email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // SMTP server (use your email provider's SMTP server)
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com';  // Sender's email
            $mail->Password = 'your_email_password';  // Sender's email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('your_email@gmail.com', 'Your Website Name');
            $mail->addAddress($email);  // Add recipient email address
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click the link below to reset your password:\n\n" . $resetLink;

            $mail->send();
            echo 'Reset link sent to your email.';
        } catch (Exception $e) {
            echo "Error sending email: " . $mail->ErrorInfo;
        }
    } else {
        echo "Email not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>

<body>
    <h1>Reset Password</h1>
    <form method="POST" action="reset-password.php">
        <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
        <div>
            <label for="password">New Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <button type="submit">Reset Password</button>
        </div>
    </form>
</body>

</html>



<?php
include 'config/dataBaseConnect.php';  // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];

    $query = "SELECT user_id, expiry FROM password_resets WHERE token = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $resetRequest = $result->fetch_assoc();

        // Check if the token has expired
        if (strtotime($resetRequest['expiry']) > time()) {
            // The token is valid, proceed with password reset
            $userId = $resetRequest['user_id'];

            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $query = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("si", $hashedPassword, $userId);
            $stmt->execute();

            // Delete the token from the password_resets table (token can only be used once)
            $query = "DELETE FROM password_resets WHERE token = ?";
            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $token);
            $stmt->execute();

            echo "Your password has been reset successfully.";
        } else {
            echo "The reset link has expired.";
        }
    } else {
        echo "Invalid token.";
    }
}
?>
```

---

<!-- ### 5. **Database Structure**

Make sure to add a table for storing password reset tokens.

```sql
CREATE TABLE password_resets (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
token VARCHAR(255) NOT NULL,
expiry DATETIME NOT NULL,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Additional Considerations:

- **Email sending**: Ensure you use proper email credentials and a real email SMTP server (e.g., Gmail or any other provider).
- **Security**: Always validate the email format, use secure password hashing (`password_hash`), and ensure tokens have an expiry time.
- **User experience**: You can show more helpful messages to the user (e.g., "If an account with that email exists, you will receive a reset link").

---

This is a basic implementation of the Forgot Password functionality in PHP. You can improve it further by using libraries like **PHPMailer** for more complex email sending, **validation** for token expiration, and **security** measures like rate-limiting requests.

Let me know if you need further help!





Generating tokens for password reset is an important part of implementing secure "Forgot Password" functionality. Here are the main reasons why it's necessary:

### 1. **Security and Privacy**
- **Unique Link**: A token ensures that each password reset request is unique. It generates a one-time use link that only works for the person who requested it.
- **Prevent Unauthorized Access**: If someone else tries to reset the password for an account without the token, they won't be able to do so. The token can be linked to the user's account and only sent to the registered email.
- **Avoid Guessable Links**: Without a token, someone could just guess the URL and change passwords. A randomly generated token makes it hard for anyone to predict or tamper with.

### 2. **Expiry for Limited Time Access**
- A token can be given an **expiration time** (e.g., 1 hour), meaning the link will only be valid for a limited period. After the expiration time, the token will no longer work, protecting the user in case they don't complete the password reset immediately.
- By storing the token in the database along with its expiration time, you can check whether the token is still valid when the user clicks the reset link.

### 3. **Prevents Multiple Requests from Colliding**
- If the password reset link was just a static URL or based on the email alone, multiple users could potentially use the same link if they tried to reset their password at the same time.
- With a token, each request has a unique link, and even if two users request a reset at the same time, they will each receive a different, unique token.

### 4. **Audit and Tracking**
- When a user requests a password reset, generating a token allows you to track the request (e.g., log when it was made, who requested it, etc.). This can be useful for auditing purposes and detecting suspicious activities (like multiple failed attempts or tokens being requested too frequently).

### 5. **Avoid Exposure of Sensitive Information**
- A token is just a random string, so it doesn't expose any sensitive user information (like the user's email or account details) in the URL. This reduces the chances of someone intercepting or exploiting information.

### How Token-Based Reset Works:

1. **User Requests Password Reset**: They submit their email in the form.
2. **Token Generation**: A random token is generated and saved in the database (along with an expiration time). The token is sent to the user's email with a link containing the token.
3. **User Clicks the Link**: The link takes the user to a password reset page, where the token is verified (checking if it's correct and has not expired).
4. **Password Reset**: If the token is valid, the user can reset their password. After the reset, the token is deleted to prevent it from being reused.

### Example Token Flow:

1. **Token Generation**:
```php
$token = bin2hex(random_bytes(16)); // 32-character random token
$expiry = time() + 3600; // Token expires in 1 hour
// Save token and expiry time in the database for the user
```

2. **Link to Send in Email**:
```php
$resetLink = "http://yourwebsite.com/reset_password.php?token=$token";
```

3. **Checking Token When User Clicks Link**:
```php
// Get token from URL
$tokenFromUrl = $_GET['token'];

// Query to check if the token exists and hasn't expired
$query = "SELECT * FROM password_resets WHERE token = '$tokenFromUrl' AND expiry > NOW()";
```

By generating and validating tokens, you're ensuring that password resets are both secure and robust, providing a better user experience while protecting sensitive data.

Would you like more help with any part of this process?










Sure! Here's an explanation of **PHPMailer**, **Composer**, **SMTP**, and how they all work together in the context of sending emails in PHP.

---

### 1. **PHPMailer**
**PHPMailer** is a popular and powerful library for sending emails in PHP. It simplifies sending emails and adds additional functionality compared to PHP's built-in `mail()` function.

#### Key Features of PHPMailer:
- **SMTP Support**: It allows you to send emails via an SMTP server, which is more reliable and professional compared to using the default PHP `mail()` function.
- **HTML Emails**: PHPMailer supports sending HTML emails (which can include images, attachments, etc.).
- **Attachments**: You can easily send attachments such as images, documents, or other files with the email.
- **Email Validation**: PHPMailer provides tools to validate email addresses and make sure you're sending emails to correct addresses.
- **Authentication**: It supports sending emails with proper authentication (e.g., username and password for SMTP servers).

#### Basic Example of Using PHPMailer:
```php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true); // Create a new PHPMailer object

try {
//Server settings
$mail->isSMTP(); // Send email using SMTP
$mail->Host = 'smtp.example.com'; // Set SMTP server address
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = 'your-email@example.com'; // SMTP username
$mail->Password = 'your-email-password'; // SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption method
$mail->Port = 587; // SMTP server port

//Recipients
$mail->setFrom('from@example.com', 'Mailer');
$mail->addAddress('recipient@example.com', 'Joe User'); // Add a recipient

//Content
$mail->isHTML(true); // Set email format to HTML
$mail->Subject = 'Test email';
$mail->Body = 'This is a <b>test email</b>.';

$mail->send(); // Send the email
echo 'Message has been sent';
} catch (Exception $e) {
echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
```

### 2. **Composer**
**Composer** is a dependency management tool for PHP. It allows you to easily manage libraries and packages for your PHP projects.

#### How Composer Works:
- Composer handles the installation and updating of libraries or dependencies (e.g., PHPMailer, Guzzle, etc.) for your project.
- It uses a `composer.json` file, where you list the libraries your project requires.
- Composer then installs these libraries into a `vendor/` directory and automatically handles any dependencies of those libraries.

#### Example of using Composer to install PHPMailer:
1. **Install Composer**: First, you need to install Composer. You can download it from [getcomposer.org](https://getcomposer.org).
2. **Create `composer.json`**: In your project directory, create a `composer.json` file or run `composer init` to generate it.
3. **Install PHPMailer**:
Run the following command to install PHPMailer:
```bash
composer require phpmailer/phpmailer
```
4. **Autoloading**: Composer will generate an `autoload.php` file in the `vendor/` directory. You can include this file in your project to automatically load all your libraries.
```php
require 'vendor/autoload.php';
```

Now you can use PHPMailer in your project without manually downloading or including the library.

### 3. **SMTP (Simple Mail Transfer Protocol)**
**SMTP** is the protocol used for sending emails from one server to another over the internet. When you send an email via an SMTP server, it handles the routing of your email to the recipient’s email server.

#### How SMTP Works:
1. **Sender's SMTP Server**: The email client (e.g., PHPMailer) connects to the SMTP server using the configured host, port, and credentials.
2. **Authentication**: SMTP servers typically require authentication (username and password) to verify that you have permission to send emails.
3. **Message Submission**: Once authenticated, the email client submits the email message to the SMTP server, including:
- **Sender's and recipient's email address**
- **Message content** (subject, body)
- **Attachments** (if any)
4. **SMTP Routing**: The SMTP server routes the message to the recipient's email server.
5. **Recipient's Mail Server**: The recipient’s email server receives the message and stores it in their mailbox.
6. **Client Retrieval**: The recipient's email client can then retrieve the message using IMAP or POP3 protocols.

#### Why use SMTP for sending emails?
- **Reliability**: SMTP ensures emails are delivered correctly to the recipient, reducing the risk of emails being marked as spam.
- **Security**: SMTP allows for encryption (e.g., using TLS or SSL) for secure email transmission.

### 4. **SMTP Server**
An **SMTP server** is a server that handles the sending of emails using the SMTP protocol. Many email services (e.g., Gmail, Outlook, Mailgun, SendGrid) provide SMTP servers that you can use to send emails from your PHP application.

#### Common SMTP Providers:
- **Gmail SMTP**:
- Host: `smtp.gmail.com`
- Port: `587` (TLS) or `465` (SSL)
- Authentication: Gmail username and password
- Security: TLS or SSL
- **Mailgun** (for bulk or transactional emails):
- Host: `smtp.mailgun.org`
- Port: `587`
- Authentication: API key or SMTP username/password
- **SendGrid**:
- Host: `smtp.sendgrid.net`
- Port: `587`
- Authentication: API key

#### Example of SMTP Server Settings for PHPMailer:
- **SMTP Host**: This is the address of the SMTP server you'll use to send emails (e.g., `smtp.gmail.com` or `smtp.sendgrid.net`).
- **SMTP Port**: The port used by the SMTP server. Common ports are `587` (for TLS) and `465` (for SSL).
- **SMTP Authentication**: You'll need to authenticate with the server using a valid username and password, or an API key if using a service like SendGrid or Mailgun.

---

### Putting It All Together
- **PHPMailer** is the PHP library that sends emails via **SMTP**.
- **Composer** is used to manage dependencies (like PHPMailer) and handle the installation process.
- You configure the **SMTP server** (e.g., Gmail, SendGrid, etc.) to actually send the email. PHPMailer uses this server to connect, authenticate, and send the email.

For example, if you're using Gmail as your SMTP provider, PHPMailer connects to Gmail’s SMTP server (`smtp.gmail.com`), authenticates with your credentials, and sends the email. Composer helps you manage PHPMailer as a dependency, while the SMTP server handles the email routing.

---

I hope this clears things up! Let me know if you need more detailed explanations or examples. -->