<?php include "layouts/top.php"; ?>
<div class="main-content">
    <section class="section">
        <div class="section-header  d-flex justify-content-between">
            <h1>All Label</h1>
            <a class="btn btn-primary" href="<?= ADMIN_URL ?>label-add.php">Add Label</a>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $statement = $pdo->prepare("select * from labels");
                                        $statement->execute();
                                        $total = $statement->rowCount();
                                        if ($total) {
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $index => $row) { ?>
                                                <tr>
                                                    <td><?php echo $index + 1; ?></td>
                                                    <td><?php echo $row['name']; ?></td>
                                                    <td class="pt_10 pb_10">
                                                        <a href="" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modal_1"><i class="fas fa-eye"></i></a>
                                                        <a href="<?= ADMIN_URL?>label-edit.php?id=<?=$row['id'];?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                                        <a href="<?= ADMIN_URL?>label-delete.php?id=<?=$row['id'];?>" class="btn btn-danger" onClick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                    <div class="modal fade" id="modal_1" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Detail</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-4"><label class="form-label">Item Name</label></div>
                                                                        <div class="col-md-8">Laptop</div>
                                                                    </div>
                                                                    <div class="form-group row bdb1 pt_10 mb_0">
                                                                        <div class="col-md-4"><label class="form-label">Description</label></div>
                                                                        <div class="col-md-8">This is a very good product. This is a very good product. This is a very good product. This is a very good product. This is a very good product. This is a very good product. </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                        <?php  }
                                        }


                                        ?>


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php include "layouts/footer.php"; ?>