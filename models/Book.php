<?php

class Book
{
    // Properties
    private $id;
    private $title;
    private $author;
    private $isbn;
    private $available_copies;
    private $connect;

    // Constructor
    public function __construct($connection)
    {
        $this->connect = $connection;
    }

    // Getters and Setters
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function setIsbn($isbn)
    {
        $this->isbn = $isbn;
    }

    public function getAvailableCopies()
    {
        return $this->available_copies;
    }

    public function setAvailableCopies($copies)
    {
        $this->available_copies = $copies;
    }

    // Database Operations
    public function getAllBooks()
    {
        $sql = "SELECT * FROM books";
        $result = $this->connect->query($sql);

        $books = array();
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }

        return $books;
    }

    public function getBookById($id)
    {
        try {
            $id = (int)$id;
            $query = "SELECT * FROM books WHERE id = $id";
            $result = mysqli_query($this->connect, $query);

            if (!$result) {
                throw new Exception("Error fetching book: " . mysqli_error($this->connect));
            }

            return mysqli_fetch_assoc($result);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function save($data)
    {
        try {
            $title = mysqli_real_escape_string($this->connect, $data['title']);
            $author = mysqli_real_escape_string($this->connect, $data['author']);
            $isbn = mysqli_real_escape_string($this->connect, $data['isbn']);
            $copies = (int)$data['available_copies'];

            if ($this->id) {
                // Update existing book
                $query = "UPDATE books SET 
                         title = '$title',
                         author = '$author',
                         isbn = '$isbn',
                         available_copies = $copies
                         WHERE id = $this->id";
            } else {
                // Insert new book
                $query = "INSERT INTO books (title, author, isbn, available_copies) 
                         VALUES ('$title', '$author', '$isbn', $copies)";
            }

            return mysqli_query($this->connect, $query);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $id = (int)$id;
            $query = "DELETE FROM books WHERE id = $id";
            return mysqli_query($this->connect, $query);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function searchBooks($searchTerm)
    {
        $searchPattern = "%{$searchTerm}%";
        $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR isbn LIKE ?";
        $stmt = $this->connect->prepare($sql);
        $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
        $stmt->execute();
        $result = $stmt->get_result();

        $books = array();
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }

        return $books;
    }
}
