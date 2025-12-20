<?php
require_once "../models/Book.php";

class BookController {

    public function store() {
        $book = new Book();

        $title = $_POST['title'];
        $author = $_POST['author'];
        $category = $_POST['category'];
        $year = $_POST['publish_year'];

        $quantity = $_POST['quantity'] ?? 0;

        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $imageName = time() . "_" . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $imageName);
        }

        $book->create($title, $category, $author, $year, $imageName, $quantity);

        header("Location: ../views/books/list.php");
        exit();
    }


    //capnhat
    public function update($id) {
    $book = new Book();
    $title = $_POST['title'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $year = $_POST['publish_year'];

    $quantity = $_POST['quantity'] ?? 0;

    $imageName = null;
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/uploads/" . $imageName);
    }

    $book->update($id, $title, $category, $author, $year, $imageName, $quantity);

    header("Location: ../views/books/list.php");
    exit();
    }

    //xoa
    public function delete($id) {
    $book = new Book();
    $book->delete($id);

    header("Location: ../views/books/list.php?msg=deleted");
    exit();
    }


}

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? null;

$controller = new BookController();

if ($action == "store") {
    $controller->store();
} elseif ($action == "update" && $id) {
    $controller->update($id);
}

if ($action == "store") {
    $controller->store();
} elseif ($action == "update" && $id) {
    $controller->update($id);
} elseif ($action == "delete" && $id) {
    $controller->delete($id);
}

