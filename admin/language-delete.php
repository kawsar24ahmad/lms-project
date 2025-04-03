<?php include "layouts/top.php";

if (isset($_REQUEST['id'])) {
    try {
        $statement = $pdo->prepare("select * from languages where id =?");
        $statement->execute([
            $_REQUEST['id'],
        ]);
        $total = $statement->rowCount();
        if (!$total) {
            throw new Exception("language is not found!");
        } else {
            $statement = $pdo->prepare("delete from languages where id =?");
            $statement->execute([
                $_REQUEST['id']
            ]);
            $_SESSION['success'] = "language is deleted successfully";
            header("location:" . ADMIN_URL . "language-view.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("location:" . ADMIN_URL . "language-view.php");
        exit;
    }
} else {
    $_SESSION['error'] = "language is not found";
    header("location:" . ADMIN_URL . "language-view.php");
    exit;
}
