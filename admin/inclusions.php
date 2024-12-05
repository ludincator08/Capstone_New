<?php

require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - INCLUSIONS</title>
    <?php require('inc/links.php') ?>
</head>

<body class="bg-light">

    <?php include('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <!-- Inclusions -->
                <h3>INCLUSIONS</h3>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="card-title  m-0"></h3>
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-dark shadow-none btn-sm " data-bs-toggle="modal" data-bs-target="#inclusion-s">
                                <i class="bi bi-plus-square"></i> Add
                            </button>
                        </div>

                        <div class="table-responsive-md" style="height: 350px; overflow-y: scroll;">
                            <table class="table table-hover border ">
                                <thead>
                                    <tr class="bg-dark text-light ">
                                        <th scope="col">#</th>
                                        <th scope="col">Icon</th>
                                        <th scope="col">Name</th>
                                        <th scope="col" width="40%">Description</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead> 
                                <tbody id="inclusions-data" class="align-middle">
                                </tbody>
                            </table>
                        </div>
                    
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- add Inclusion Modal -->
    <div class="modal fade" id="inclusion-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="inclusion_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Inclusion</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="inclusion_name" class="form-control shadow-none" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Icon</label>
                            <input type="file" name="inclusion_icon" accept=".png, .jpg, .jpeg" class="form-control shadow-none" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="inclusion_desc" class="form-control shadow-none" row="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Inclusion Modal -->
    <div class="modal fade" id="edit-inclusion-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="edit_inclusion_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Inclusion</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <input type="text" name="inclusion_name" id="inclusion_name_inp" class="form-control shadow-none" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Icon</label>
                            <input type="file" name="inclusion_icon" id="inclusion_icon_inp" accept=".png, .jpg, .jpeg" class="form-control shadow-none"/>
                            <input type="hidden" name="inclusion_id" id="inclusion_id_inp" />
                            <input type="hidden" name="current_icon" id="current_icon_inp" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="inclusion_desc" id="inclusion_desc_inp" class="form-control shadow-none" row="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <?php require('inc/scripts.php'); ?>

    <script src="scripts/inclusions.js"></script>

</body>

</html>