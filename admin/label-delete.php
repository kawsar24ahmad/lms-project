<?php include "layouts/top.php";

if (isset($_REQUEST['id'])) {
    try {
        $statement = $pdo->prepare("select * from labels where id =?");
        $statement->execute([
            $_REQUEST['id'],
        ]);
        $total = $statement->rowCount();
        if (!$total) {
            throw new Exception("label is not found!");
        } else {
            $statement = $pdo->prepare("delete from labels where id =?");
            $statement->execute([
                $_REQUEST['id']
            ]);
            $_SESSION['success'] = "label is deleted successfully";
            header("location:" . ADMIN_URL . "label-view.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("location:" . ADMIN_URL . "label-view.php");
        exit;
    }
} else {
    $_SESSION['error'] = "label is not found";
    header("location:" . ADMIN_URL . "label-view.php");
    exit;
}
