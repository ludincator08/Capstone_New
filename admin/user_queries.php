<?php

require('inc/essentials.php');
require('inc/db_config.php');
adminLogin();


if(isset($_GET['seen'])){
    $frm_data = filteration($_GET);
    if($frm_data['seen'] == 'all'){
        $q = "UPDATE `user_queries` SET `seen`=?";
        $sql = "SELECT * FROM `user_queries` WHERE sr_no = '?'";
        $result = $con->query($sql);    
        $values = [1];

        if(update($q, $values, 'i')){
            $_SESSION['alert'] = ['success', 'Mark all as read'];
        }

        else if ($result->num_rows==0) {
            $_SESSION['alert'] = ['error', 'There are no unread queries!']; 

        }
        
        else{
            $_SESSION['alert'] = ['error', 'All queries are already read!'];

        }

    }
    else{
        $q = "UPDATE `user_queries` SET `seen`=? WHERE `sr_no`=?";
        $values = [1,$frm_data['seen']];
        if(update($q, $values, 'ii')){
            $_SESSION['alert'] = ['success', 'Mark as read'];
        }
        else{
            $_SESSION['alert'] = ['error', 'Operation failed!'];
        }
    }
}

// if(isset($_GET['del']))
// {
//     $frm_data = filteration($_GET);
    
//         if($frm_data['del'] == 'all'){
//             $q = "DELETE FROM `user_queries`";
//             if(mysqli_query($con, $q)){
//                 alert('success', 'All data deleted !');
//             }
//             else{
//                 alert('error', 'Operation Failed!');
//             }
    
//         }
//         else{
//             $q = "DELETE FROM `user_queries` WHERE `sr_no`=?";
//             $values = [$frm_data['del']];
//             if(delete($q, $values, 'i')){
//                 alert('success', 'Data deleted !');
//             }
//             else{
//                 alert('error', 'Operation Failed!');
//             }
//         }

// }

if (isset($_GET['del'])) {
    $frm_data = filteration($_GET);
    
    // Handle delete all scenario
    if ($frm_data['del'] == 'all') {
        $delete_q = "DELETE FROM `user_queries`";  // Query to delete all records
        
        // Execute the query
        if (mysqli_query($con, $delete_q)) {
            $_SESSION['alert'] = ['success', 'All queries deleted successfully.'];
        } else {
            $_SESSION['alert'] = ['error', 'Failed to delete all queries.'];
        }
    } else {
        // Handle delete single query scenario
        $sr_no = $frm_data['del'];
        
        // First, check if the query has been marked as read
        $check_q = "SELECT `seen` FROM `user_queries` WHERE `sr_no` = ?";
        
        // Prepare the SQL statement
        if ($stmt = mysqli_prepare($con, $check_q)) {
            // Bind the parameter to the prepared statement
            mysqli_stmt_bind_param($stmt, 'i', $sr_no);
            
            // Execute the statement
            mysqli_stmt_execute($stmt);
            
            // Store the result
            mysqli_stmt_store_result($stmt);
            
            // Check if any rows were returned
            if (mysqli_stmt_num_rows($stmt) > 0) {  
                // Bind the result variable
                mysqli_stmt_bind_result($stmt, $seen);
                
                // Fetch the result
                mysqli_stmt_fetch($stmt);
                
                // Check if the query is marked as read
                if ($seen != 1) {
                    $_SESSION['alert'] = ['error', 'You must mark the query as read before deleting it'];
                } else {
                    // If marked as read, proceed with deletion
                    $delete_q = "DELETE FROM `user_queries` WHERE `sr_no` = ?";

                    // Prepare the delete query
                    if ($delete_stmt = mysqli_prepare($con, $delete_q)) {
                        // Bind the parameter to the delete query
                        mysqli_stmt_bind_param($delete_stmt, 'i', $sr_no);
                        
                        // Execute the delete statement
                        if (mysqli_stmt_execute($delete_stmt)) {
                            $_SESSION['alert'] = ['success', 'Query deleted successfully.'];
                        } else {
                            $_SESSION['alert'] = ['error', 'Failed to delete query.'];
                        }

                        // Close the delete statement
                        mysqli_stmt_close($delete_stmt);
                    }
                }
            } else {
                $_SESSION['alert'] = ['error', 'Query not found.'];
            }
            
            // Close the select statement
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['alert'] = ['error', 'Failed to prepare the query.'];
        }
    }

    // Redirect to refresh the page and show alert
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Queries</title>
    <?php require('inc/links.php') ?>
</head>

<body class="bg-light">

    <?php include('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">USER QUERIES</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <a href="?seen=all" class="btn btn-dark rounded-pill shadow-none btn-sm">
                                <i class="bi bi-check-all"></i> Mark all read
                            </a>
                            <a href="?del=all" class="btn btn-danger rounded-pill shadow-none btn-sm">
                                <i class="bi bi-trash"></i> Delete all
                            </a>
                        </div>

                        <div class="table-responsive-md" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border ">
                                <thead class="sticky-top ">
                                    <tr class="bg-dark text-light ">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" width="20%">Subject</th>
                                        <th scope="col" width="30%">Message</th>
                                        <th scope="col">Date</th>
                                        <th scope="col" width="15%">Action</th>
                                    </tr>
                                </thead> 
                                <tbody class="align-middle">
                                    <?php
                                    $q = "SELECT * FROM `user_queries` ORDER BY `sr_no` DESC";
                                    $data = mysqli_query($con, $q);
                                    $i = 1;

                                    while($row = mysqli_fetch_assoc($data))
                                    {
                                        $seen = '';
                                        if($row['seen'] !=1){
                                            $seen = "<a href='?seen=$row[sr_no]' class='btn btn-sm rounded-pill btn-primary'> 
                                            <i class='bi bi-check-all' style='color: white'></i> Mark as read</a> <br>";
                                        }
                                        
                                        if($row['seen'] == 1){
                                            $seen = "<button 
                                                        class='btn btn-sm rounded-pill btn-info reply-btn' 
                                                        data-bs-toggle='modal' 
                                                        data-bs-target='#reply' 
                                                        data-name='$row[name]' 
                                                        data-subject='Re: " . htmlspecialchars($row['subject'], ENT_QUOTES, 'UTF-8') . "'>
                                                        <i class='bi bi-reply-all-fill'></i> Reply</button><br>";
                                        }
                                        
                                        $seen .= "<a href='?del=$row[sr_no]' class='btn btn-sm rounded-pill btn-danger mt-2 'bi bi-trash'>
                                        <i class='bi bi-trash'></i> Delete </a> <br>";
                                        echo <<< query
                                            <tr>
                                                <td>$i</td>
                                                <td>$row[name]</td>
                                                <td>$row[email]</td>
                                                <td>$row[subject]</td>
                                                <td>$row[message]</td>
                                                <td>$row[date]</td>
                                                <td>$seen</td>
                                            </tr>
                                        query;
                                        $i++;
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

    <!-- add Reply Modal -->
    <div class="modal fade" id="reply" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="inclusion_s_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Message</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">To</label>
                            <input type="text" name="reply_name" class="form-control shadow-none" required disabled />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="reply_subject" class="form-control shadow-none" required disabled />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reply</label>
                            <textarea name="reply_desc" class="form-control shadow-none" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custom-bg text-black shadow-none"><i class="bi bi-send-check-fill" style="color: black;"></i> SUBMIT</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.onload = function() 
        {
            <?php if (isset($_SESSION['alert'])): ?>
                alert('<?= $_SESSION['alert'][0] ?>', '<?= $_SESSION['alert'][1] ?>');
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>
        };
    
        document.addEventListener('click', function (e) 
        {
            if (e.target.classList.contains('reply-btn')) 
            {
                const button = e.target;
                document.querySelector('input[name="reply_name"]').value = button.getAttribute('data-name');
                document.querySelector('input[name="reply_subject"]').value = button.getAttribute('data-subject');
            }
        });
    </script>


    <?php require('inc/scripts.php'); ?>

</body>

</html>