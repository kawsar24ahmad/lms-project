<?php include "header.php";
if (!isset($_SESSION['instructor'])) {
    $_SESSION['error'] = "Login first";
    header("location:" . BASE_URL . 'login');
    exit;
}

?>

<div class="page-top" style="background-image: url('<?= BASE_URL; ?>uploads/banner.jpg')">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>All Courses</h2>
                <div class="breadcrumb-container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= BASE_URL; ?>">Home</a></li>
                        <li class="breadcrumb-item active">All Courses</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <div class="card">
                    <?php include "instructor-sidebar.php" ?>

                </div>
            </div>
            <div class="col-lg-9 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="example1">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Title</th>
                                        <th>Photo</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $statement = $pdo->prepare("select * from courses where instructor_id = ? order by id desc");
                                    $statement->execute([
                                        $_SESSION['instructor']['id']
                                    ]);

                                    if ($statement->rowCount() > 0) {

                                        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($data as $index => $row) { ?>


                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= $row['title'] ?></td>
                                                <td><img src="<?= BASE_URL . 'uploads/' . $row['featured_photo'] ?>" alt="" class="w-150"></td>
                                                <td>$<?= $row['price'] ?></td>

                                                <td class="badge <?php echo ($row['status'] == 'pending' || $row['status'] == 'in_review') ? 'bg-danger' : 'bg-success' ?>"><?= $row['status'] ?></td>
                                                <td class="pt_10 pb_10">
                                                    <a href="<?= BASE_URL ?>instructor-course-edit-basic/<?= $row['id'] ?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                    <a href="<?=BASE_URL?>/instructor-course-delete/<?=$row['id']?>" class="btn btn-danger" onClick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                                </td>

                                            </tr>
                                        <?php   }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6">No items</td>
                                        </tr>
                                    <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include "footer.php" ?>