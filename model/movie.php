<?php
class Movie
{
    // Connection
    private $conn;

    // Table
    private $db_table = "movie";

    // Columns
    public $id;
    public $name;
    public $releasedYear;
    public $description;
    public $poster;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getMovies()
    {
        $sqlQuery = "SELECT id, name, releasedYear, description, poster FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // GET MOVIES NAMES
    public function getMoviesNames()
    {
        $sqlQuery = "SELECT id, name FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createMovie()
    {
        $sqlQuery = "INSERT INTO
                     " . $this->db_table . "
                 SET
                     name = :name, 
                     releasedYear = :releasedYear, 
                     description = :description, 
                     poster = :poster";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->releasedYear = htmlspecialchars(strip_tags($this->releasedYear));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->poster = htmlspecialchars(strip_tags($this->poster));

        // bind data
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":releasedYear", $this->releasedYear);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":poster", $this->poster);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // READ single
    public function getSingleMovie()
    {
        $sqlQuery = 'SELECT
                     *
                     FROM ' . $this->db_table . '
                 WHERE id = ?
                 ';

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $dataRow['name'];
        $this->releasedYear = $dataRow['releasedYear'];
        $this->description = $dataRow['description'];
        $this->poster = $dataRow['poster'];
    }

    public function getPosterById()
    {
        $sqlQuery = 'SELECT
                        poster
                        FROM ' . $this->db_table . '
                        WHERE id = ?';

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->poster = $dataRow['poster'];
    }

    // READ Movies by Released Year
    public function getMoviesByReleasedYear()
    {
        $sqlQuery = "SELECT
                    id, 
                    name, 
                    releasedYear, 
                    description, 
                    poster 
                    FROM " . $this->db_table . "
                    WHERE releasedYear = ?";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->releasedYear);
        $stmt->execute();
        return $stmt;
    }

    public function getMoviesByName()
    {
        $sqlQuery = "SELECT
                        id, 
                        name, 
                        releasedYear, 
                        description, 
                        poster 
                        FROM " . $this->db_table . "
                        WHERE name = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->name);
        $stmt->execute();
        return $stmt;
    }

    public function getMoviesWithFilter()
    {
        $sqlQuery = "SELECT
                        id,
                        name,
                        releasedYear,
                        description,
                        poster FROM " . $this->db_table . " WHERE name = ? AND releasedYear = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, $this->releasedYear);
        $stmt->execute();
        return $stmt;
    }

    // UPDATE
    public function updateMovie()
    {
        $sqlQuery = "UPDATE " . $this->db_table . " SET name = :name, 
                     releasedYear = :releasedYear, 
                     description = :description, 
                     poster = :poster WHERE id = :id
                 ";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->releasedYear = htmlspecialchars(strip_tags($this->releasedYear));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->poster = htmlspecialchars(strip_tags($this->poster));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // bind data
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":releasedYear", $this->releasedYear);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":poster", $this->poster);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    function deleteMovie()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
