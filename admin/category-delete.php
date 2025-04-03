<?php include "layouts/top.php";

if (isset($_REQUEST['id'])) {
    try {
        $statement = $pdo->prepare("select * from categories where id =?");
        $statement->execute([
            $_REQUEST['id'],
        ]);
        $total = $statement->rowCount();
        if (!$total) {
            throw new Exception("Category is not found!");
        } else {
            $statement = $pdo->prepare("delete from categories where id =?");
            $statement->execute([
                $_REQUEST['id']
            ]);
            $_SESSION['success'] = "Category is deleted successfully";
            header("location:" . ADMIN_URL . "category-view.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("location:" . ADMIN_URL . "category-view.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Category is not found";
    header("location:" . ADMIN_URL . "category-view.php");
    exit;
}
