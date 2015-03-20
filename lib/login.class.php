<?php

	// The class handling logging in/out and registering
	class Login {
		
		// Current database connection
		private $connection = null;

		// Login details of the current user
		private $loggedIn = false;

		// Constructor called with a new instance of login
		// Passed an instance of database class to make a connection if needed
		public function __construct($db) {
			// Start the PHP session
			session_start();

			// Logout from any page with ?logout GET parameter
			if (isset($_GET['logout'])) {
				$this->logout();
			// Otherwise, if there is a logged in user session active
			} elseif (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
				// Validate the login if the database connection is valid
				if ($this->db_connect($db)) {
					$this->validateLogin();
				}
			}

			// Look for login post data
			if (isset($_POST['login'])) {
				if (!empty($_POST['username']) && !empty($_POST['password'])) {
					if ($this->db_connect($db)) {
						$this->login($_POST['username'], $_POST['password']);
					}
				} else {
					$this->logout();
					// If any field is blank redirect with an error
					$this->errorRedirect('Username or password cannot be blank', 'login');
				}
			}
			// Look for register post data
			elseif (isset($_POST['register'])) {
				// Ensure fields are not empty
				if (!empty($_POST['username']) && !empty($_POST['email']) &&
						!empty($_POST['password']) && !empty($_POST['password2'])) {
					// Double entry verification on the password
					if ($_POST['password'] == $_POST['password2']) {
						if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
							if ($this->db_connect($db)) {
								$this->register($_POST['username'], $_POST['password'], $_POST['email']);
							}
						} else {
							// If not a valid email redirect with error
							$this->errorRedirect('Email is not in a valid format', 'register');
						}
					} else {
						$this->logout();
						// If password fields did not match redirect with error
						$this->errorRedirect('Password fields must match', 'register');
					}
				} else {
					$this->logout();
					// If any field is blank redirect with an error
					$this->errorRedirect('All fields must be completed', 'register');
				}
			}
			// Confirm an account
			elseif (isset($_GET['token'])) {
				if ($this->db_connect($db)) {
					$this->activateAccount($_GET['token']);
				}
			}
		}

		// Return whether a user is logged in
		public function isLoggedIn() {
				return $this->loggedIn;
		}

		// Validate a user login - ensure it is still valid (check for hijacking etc)
		private function validateLogin() {
			// Ensure user's IP address and user agent has not changed and a check
			// to session hijacking
			if ($_SESSION['userIP'] == $_SERVER['REMOTE_ADDR'] 
					&& $_SESSION['userAgent'] == $_SERVER['HTTP_USER_AGENT']) {
				// Regenerate the session before login for session hijacking
				session_regenerate_id(true);
				$this->loggedIn = true;
			} else {
				// Log out now if the login isn't valid
				$this->logout();
			}
		}

		// Logs in a user and sets session variables
		private function login($username, $password) {
			// Prepare login query, selects the password of the user being logged in
			if (!($stmt = $this->connection->prepare("SELECT ID, Password, Active, Email FROM Users WHERE Username = ? LIMIT 1"))) {
				$this->errorRedirect('Failed to prepare query: ('.$this->connection->errno.') '.$this->connection->error, 'login');
			} else {
				// Bind the username as a parameter to the prepared query
				$stmt->bind_param('s', $username);

				// Execute the query and store the result set
				$stmt->execute();
				$stmt->store_result();

				// If the username exists
				if ($stmt->num_rows > 0) {
					// Fetch the value of the password for this user
					$stmt->bind_result($userID, $mySQLHash, $active, $email);
					$stmt->fetch();

					// Check that the user is confirmed before proceeding
					if ($active == '1') {
						// Verify that the given hash matches the password got from mySQL
						if (password_verify($password, $mySQLHash)) {
							// Verified, log in the user
							// IP and user agent checking to attempt to prevent session hijacking

							// DO OTHER LOGIN STUFF HERE
							$_SESSION['userIP'] = $_SERVER['REMOTE_ADDR'];
							$_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
							$_SESSION['loggedIn'] = true;
							// Store username in session variable
							$_SESSION['username'] = $username;
							$_SESSION['userID'] = $userID;
							$_SESSION['email'] = $email;
							// Set login flag within class to true
							$this->loggedIn = true;
						} else {
							$this->errorRedirect('Incorrect Username or Password', 'login');
						}
					} else {
						// Not confirmed
						header('Location: confirm.php');
					}
				} else {
					// Incorrect username error
					$this->errorRedirect('Incorrect Username or Password', 'login');
				}
			}
		}

	  // Register a new user
		private function register($username, $password, $email) {
			// Hashes a password with bcrypt, 60 character long string
			// String contains algorithm, cost and salt
			$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

			// MySQL Insert
			// Prepare registration query, prevents MySQL injection and is best practice
			if (!($stmt = $this->connection->prepare("INSERT INTO Users(Username, Password, Email, Email_Confirm_Token) VALUES (?, ?, ?, ?)"))) {
				$this->errorRedirect('Failed to prepare query: ('.$this->connection->errno.') '.$this->connection->error, 'register');
			} else {
				// Get a unique token for email validation
				$token = sha1(uniqid($username, true));
				// Bind parameters to prepared query (three strings)
				$stmt->bind_param('ssss', $username, $hashedPassword, $email, $token);
				// Execute the query to add the row
				if ($stmt->execute()) {
					// Registration was successful but send a confirmation email
					$headers = "From:FileDigger<mail@filedigger.me>";
					$emailContent = "Thank you for registering with FileDigger. To activate your account, click on the following link: http://filedigger.me/confirm.php?token=".$token."\n\nIf you did not sign up with this email address, please ignore this email";
					mail($email, "FileDigger.me Registration - Activate your account", $emailContent, $headers);
					// Use the login function. This will not work if the account is not yet confirmed, but acts as a redirect to /confirm.php
					$this->login($username, $password);
				} else {
					$this->errorRedirect('Failed to register: ('.$this->connection->errno.') '.$this->connection->error, 'register');
				}
			}
		}

		// Validate the email of an account and activate it
		private function activateAccount($token) {
			if (!($stmt = $this->connection->prepare("UPDATE Users SET Active=1 WHERE Email_Confirm_Token = ?"))) {
				$this->errorRedirect('Failed to prepare query: ('.$this->connection->errno.') '.$this->connection->error, 'confirm');
			} else {
				// Bind parameters to prepared query (three strings)
				$stmt->bind_param('s', $token);
				// Execute the query to update the row
				if ($stmt->execute()) {
					header('Location: confirm.php?success='.urlencode("Your account has been successfully activated! You can now login using the header above or from the main page"));
				} else {
					$this->errorRedirect('Failed to confirm account: ('.$this->connection->errno.') '.$this->connection->error, 'confirm');
				}
			}
		}

		// Logout by destroying the session and associated cookies
		private function logout() {
			session_unset();
			session_regenerate_id(true);
			$this->loggedIn = false;
		}

		// Make a database connection and return it
		private function db_connect($db) {
			// If there is not already a connection
			if ($this->connection == null) {
				// Request one from the database class and store it in instance vars
				$this->connection = $db->getConnection();
			}
			return $this->connection;
		}

		// Error with login/register ends in a redirect
		private function errorRedirect($message, $action) {
			if ($action == 'confirm') {
				header('Location: confirm.php?err='.urlencode($message));
			} else {
				header('Location: login.php?err='.urlencode($message).'&a='.$action);
			}
		}
	}

?>
