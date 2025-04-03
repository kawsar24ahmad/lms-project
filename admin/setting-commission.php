<?php include "layouts/top.php";

try {
    $statement = $pdo->prepare("select * from settings where id =?");
    $statement->execute([
        1,
    ]);
    $total = $statement->rowCount();
    if (!$total) {
        throw new Exception("Settings is not found!");
    }
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $data = $result[0];
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header("location:" . ADMIN_URL . "category-view.php");
    exit;
}
if (isset($_POST['form_submit'])) {
    try {
        if ($_POST['sales_commission'] == "") {
            throw new Exception("sales commission field can not be empty");
        }
        if ( !is_numeric($_POST['sales_commission'])) {
            throw new Exception("sales commission must be number");
        }
        if ( $_POST['sales_commission'] <= 0 || $_POST['sales_commission'] >= 100) {
            throw new Exception("sales commission must be less than 0 and greater than 100");
        }
        
        $statement = $pdo->prepare("update settings set sales_commission = ? where id=?");
        $statement->execute([
            $_POST['sales_commission'],
            1
        ]);
        $_SESSION['success'] = "Sales commission is updated successfully";
        header("location:" . ADMIN_URL . 'setting-commission.php');
        exit;
           
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
        $_SESSION['error'] = $error_message;
        header("location:" . ADMIN_URL . "setting-commission.php");
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
                                            <label>Sales Commission</label>
                                            <input type="text" class="form-control" name="sales_commission" value="<?php echo $data['sales_commission']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>Admin Commission</th>
                                                            <th><?php echo  $data['sales_commission'] . '%' ?></th>
                                                        </tr>
                                                        <tr>
                                                            <th>Instructor Commission</th>
                                                            <th><?php echo 100 - $data['sales_commission'] . '%' ?></th>
                                                        </tr>
                                                            
                                                    </table>
                                                </div>
                                            </div>
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