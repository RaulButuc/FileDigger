<?php

	// The files class handling uploads and downloads
	class Files {
		
		// Current database connection
		private $connection = null;
		
		// String to store errors generated in this class, usually checked when
		// a function returns false
		private $lastError = '';

		// Constructor called with a new instance of a file
		public function __construct($db) {
		  if (!$this->db_connect($db)) {
		    $lastError = 'Failed DB connection in files.class.php';
		  }
		}

		// Upload a file
		// Gets file and file info from submitted file, rest of the arguments are passed to it
		public function uploadFile($userid, $lat, $long) {

			// Check that there were no errors
			if ($_FILES['file']['error'] > 0) {
				$this->lastError = 'An error ocurred when trying to upload the file';
				return false;
			}

			// Check that the file mime type is allowed and get extension
			$uploadedFileInfo = mime_content_type($_FILES['file']['tmp_name']);
			$allowedTypes = array(
				'txt' => 'text/plain',
				'zip' => 'application/x-compressed',
				'zip' => 'application/x-zip-compressed',
				'gz'  => 'application/x-compressed',
				'mp3' => 'audio/mpeg3',
				'mp3' => 'audio/x-mpeg-3',
				'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp'
			);
			if(!$extension = array_search($uploadedFileInfo, $allowedTypes)) {
				$this->lastError = 'You cannot upload this filetype to the map';
				return false;
			}

			// If file size is too large give error
			if ($_FILES['file']['size'] > 1000000) {
				$this->lastError = 'Exceeded filesize limit of 10MB';
				return false;
			}

			// File name needs to be replaced with a name given from binary data
			// and the extension which is explicitly defined and allowed from above
			$location = '/uploads/'.md5_file($_FILES['file']['tmp_name']).'.'.$extension;
			if (!move_uploaded_file($_FILES['file']['tmp_name'], __DIR__ . $location)) {
				$this->lastError = 'Failed to move uploaded file to storage';
				// DUE TO FILE PERMISSIONS WE CANNOT UPLOAD FILES TO THE WEBSERVER YET
				//return false;
			}

			// Add to the database afterwards
		  if (!($stmt = $this->connection->prepare("INSERT INTO Files(User_ID, Latitude, Longitude, Name, Location) VALUES (?, ?, ?, ?, ?)"))) {
			  $this->lastError = 'Failed to prepare query: ('.$this->connection->errno.') '.$this->connection->error;
			  return false;
		  } else {
			  // Bind parameters to prepared query (three strings)
			  $stmt->bind_param('sddss', $userid, $lat, $long, $_FILES['file']['tmp_name'], $location);
			  // Execute the query to add the row
			  if ($stmt->execute()) {
				  // Successful upload
				  return true;
			  } else {
				  $this->lastError = 'Failed to upload: ('.$this->connection->errno.') '.$this->connection->error;
				  return false;
			  }
		  }
		}

		// Get a file from the database by ID and return the data
		// Returns an associative array with all the fields or false if file wasn't found
		public function getFile($id) {
			if (!($stmt = $this->connection->prepare("SELECT location FROM Files WHERE ID = ? LIMIT 1"))) {
				$this->lastError = 'Failed to prepare query: ('.$this->connection->errno.') '.$this->connection->error;
				return false;
			} else {
				// Bind the username as a parameter to the prepared query
				$stmt->bind_param('i', $id);

				// Execute the query and store the result set
				$stmt->execute();
				$stmt->store_result();

				// If the username exists
				if ($stmt->num_rows > 0) {
					// Fetch the value of the fields for this file
					$stmt->bind_result($results);
					$stmt->fetch();
					return $results;
				} else {
					// ID doesn't exist
					$this->lastError = 'The given file ID does not exist';
					return false;
				}
			}
		}
		
		// Gets all the files from a specific user
		// Returns an associative array or false if error
		public function getFilesByUser($userID) {
			if (!($stmt = $this->connection->prepare("SELECT ID, Latitude, Longitude, Name, Radius FROM Files WHERE User_ID = ?"))) {
				$this->lastError = 'Failed to prepare query: ('.$this->connection->errno.') '.$this->connection->error;
				return false;
			} else {
				// Bind the username as a parameter to the prepared query
				$stmt->bind_param('i', $userID);

				// Execute the query and store the result set
				$stmt->execute();
				$stmt->store_result();

				// If the username exists
				if ($stmt->num_rows > 0) {
				    // Bind the results to variables
				    $stmt->bind_result($id, $latitude, $longitude, $name, $radius);
				    $results = array();
				    // Keep fetching rows
				    while ($stmt->fetch()) {
				        // Add to array
					    $results[] = array(
					        'ID' => $id,
					        'Latitude' => $latitude,
					        'Longitude' => $longitude,
					        'Name' => $name,
					        'Radius' => $radius,
					    );
					}
					// Return the results array
					return $results;
				} else {
					// ID doesn't exist
					$this->lastError = 'The given user ID does not exist';
					return false;
				}
			}
		}
		
		// Removes a file with a given file ID. Checks user ID too
		public function removeFile($fileID, $userID) {
			// Check that the file exists
			if (!($stmt = $this->connection->prepare("SELECT Location FROM Files WHERE ID = ? AND User_ID = ? LIMIT 1"))) {
				$this->lastError = 'Failed to prepare query: ('.$this->connection->errno.') '.$this->connection->error;
				return false;
			} else {
				// Bind the IDs as a parameter to the prepared query
				$stmt->bind_param('ii', $fileID, $userID);

				// Execute the query and store the result set
				$stmt->execute();
				$stmt->store_result();

				// If the username exists
				if ($stmt->num_rows > 0) {

				  // Bind the results to variables
				  $stmt->bind_result($location);
				  $stmt->fetch();

					// Delete file from the database
				  if (!($stmt = $this->connection->prepare("DELETE FROM Files WHERE ID = ? AND User_ID = ?"))) {
					  $this->lastError = 'Failed to prepare query: ('.$this->connection->errno.') '.$this->connection->error;
					  return false;
				  } else {
					  // Bind parameters to prepared query (three strings)
					  $stmt->bind_param('ii', $userID, $fileID);
					  // Execute the query to add the row
					  if ($stmt->execute()) {
						  // Successful delete
						  return true;
					  } else {
						  $this->lastError = 'Failed to delete the file from the database: ('.$this->connection->errno.') '.$this->connection->error;
						  return false;
					  }
				  }

				  // Attempt to delete data from the disk
				  if (unlink($location)) {
				  	// If everything was succeeded, no problems with the deletion
				  	return true;
				  } else {
				  	$this->lastError = 'Could not delete data from disk';
				  	return false;
				  }

				} else {
					// File doesn't exist
					$this->lastError = 'The given file does not exist';
					return false;
				}
			}
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
		
		// Return the current error message
		public function getError() {
		  return $this->lastError;
		}
	}

?>