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
        }
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $data = $result[0];
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

if (isset($_POST['form_submit'])) {
    try {
        if ($_POST['name'] == "") {
            throw new Exception("Name field can not be empty");
        }
        $statement = $pdo->prepare("select * from languages where name=? and id !=?");
        $statement->execute([
            $_POST['name'],
            $_REQUEST['id']
        ]);
        $total = $statement->rowCount();
        if ($total) {
            throw new Exception("This name already exits", 1);
        } else {
            $statement = $pdo->prepare("update languages set name = ? where id=?");
            $statement->execute([
                $_POST['name'],
                $_REQUEST['id']
            ]);
            $_SESSION['success'] = "language updated successfully";
            header("location:" . ADMIN_URL . 'language-view.php');
            exit;
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error'] = $error_message;
        header("location:" . ADMIN_URL . "language-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Language</h1>
            <div class="ml-auto">
                <a href="<?= ADMIN_URL ?>language-view.php" class="btn btn-primary"> Back</a>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo $data['name']; ?>">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="form_submit">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include "layouts/footer.php"; ?>