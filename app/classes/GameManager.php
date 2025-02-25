
<?php
class GameManager {
    private $conn;

    public function __construct(Database $db){
        $this->conn = $db->getConnection();
    }

    // Insert form data into the database
    public function insert($data, $fileName) {
        //filteren om Script Injectie te voorkomen (XSS)
        $title = htmlspecialchars($data['title']);
        $genre = htmlspecialchars($data['genre']);
        $platform = htmlspecialchars($data['platform']);
        $release_year = htmlspecialchars($data['release_year']);
        $rating = htmlspecialchars($data['rating']);

        $sql = "INSERT INTO games (title, genre, platform, release_year, rating, photo) 
                VALUES (:title, :genre, :platform, :release_year, :rating, :photo)";

        try {
            $stmt = $this->conn->prepare($sql);
            //bind parameter is een manier om SQL injectie tegen te gaan
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':genre', $genre);
            $stmt->bindParam(':platform', $platform);
            $stmt->bindParam(':release_year', $release_year);
            $stmt->bindParam(':rating', $rating); 
            $stmt->bindParam(':photo', $fileName);  // Store file path
            $stmt->execute();
            echo "New record created successfully";
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // File upload handler
    public function fileuload($file) {
        // Check if file is uploaded
        if (isset($file) && $file['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($file["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a valid image
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

            // Check file size (limit to 5MB)
            if ($file["size"] > 5000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // If file upload checks pass, move the uploaded file to the target directory
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                return null;  // Return null if upload failed
            } else {
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    echo "The file " . htmlspecialchars(basename($file["name"])) . " has been uploaded.";
                    return $target_file;  // Return the file path to store in the database
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    return null;  // Return null if there was an error uploading
                }
            }
        } else {
            return null;  // If no file is uploaded or there is an error
        }
    }

    public function getAllGames() {
      // Initialize an empty array to store the game objects
      $games = [];
  
      $sql = "SELECT * FROM games";
      
      $stmt = $this->conn->query($sql);
      

      if ($stmt) {
          // Fetch all results as associative arrays
          //de resultset wordt in een assocatieve array gezet
          //dit doet we, zodat we makkelijker door de data heen kunnen loopen
          $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
          // Iterate through each result and create a new Game object
          foreach ($result as $row) {
              $game = new Game($row);
  
              $games[] = $game;
          }
      }
  
      // Return the array of game objects
      return $games;
  }

}
  
?>


