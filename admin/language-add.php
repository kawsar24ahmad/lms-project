<?php include "layouts/top.php";

if (isset($_POST['form_submit'])) {
    try {
        if ($_POST['name'] == "") {
            throw new Exception("Name field can not be empty");
        }
        $statement = $pdo->prepare("select * from languages where name=?");
        $statement->execute([
            $_POST['name'],
        ]);
        $total = $statement->rowCount();
        if ($total) {
            throw new Exception("This name already exits", 1);
        } else {
            $statement = $pdo->prepare("insert into languages (name) value(?)");
            $statement->execute([
                $_POST['name'],
            ]);
            $_SESSION['success'] = "language created successfully";
            header("location:" . ADMIN_URL . 'language-view.php');
            exit;
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error'] = $error_message;
    }
}

?>

<div class="main-content">
    <section class="section">
        <div class="section-header justify-content-between">
            <h1>Add Language</h1>
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
                                            <input type="text" class="form-control" name="name" value="">
                                        </div>
                                    </div>

                                </div>


                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" name="form_submit">Add</button>
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