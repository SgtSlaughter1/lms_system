<?php   

require_once dirname(__DIR__) . '/models/Book.php';

class BookController {
    private $bookModel;

    public function __construct($connection) {
        $this->bookModel = new Book($connection);
    }

    public function getAllBooks() {
        return $this->bookModel->getAllBooks();
    }

    public function getBook($id) {
        return $this->bookModel->getBookById($id);
    }

    public function createBook($bookData) {
        return $this->bookModel->save($bookData);
    }

    public function updateBook($id, $bookData) {
        $book = $this->bookModel->getBookById($id);
        if ($book) {
            return $this->bookModel->save($bookData);
        }
        return false;
    }

    public function deleteBook($id) {
        return $this->bookModel->delete($id);
    }

    public function searchBooks($searchTerm = '') {
        if (empty($searchTerm)) {
            return $this->bookModel->getAllBooks();
        }
        return $this->bookModel->searchBooks($searchTerm);
    }
}
