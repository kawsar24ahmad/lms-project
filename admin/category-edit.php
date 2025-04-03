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
        }
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $row = $result[0];
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

if (isset($_POST['form_submit'])) {
    try {
        if ($_POST['name'] == "") {
            throw new Exception("Name field can not be empty");
        }
        $statement = $pdo->prepare("select * from categories where name=? and id !=?");
        $statement->execute([
            $_POST['name'],
            $_REQUEST['id']
        ]);
        $total = $statement->rowCount();
        if ($total) {
            throw new Exception("This name already exits", 1);
        } else {
            $statement = $pdo->prepare("update categories set name = ? where id=?");
            $statement->execute([
                $_POST['name'],
                $_REQUEST['id']
            ]);
            $_SESSION['success'] = "Category updated successfully";
            header("location:" . ADMIN_URL . 'category-view.php');
            exit;
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error'] = $error_message;
        header("location:" . ADMIN_URL . "category-edit.php?id=" . $_REQUEST['id']);
        exit;
    }
}

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Edit Category</h1>
            <div class="ml-auto">
                <a href="<?= ADMIN_URL ?>category-view.php" class="btn btn-primary"> Back</a>
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
                                            <input type="text" class="form-control" name="name" value="<?php echo $row['name']; ?>">
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