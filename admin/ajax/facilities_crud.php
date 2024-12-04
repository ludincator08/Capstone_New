<?php 

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if(isset($_POST['add_facility'])) {
   $facilities = filteration(json_decode($_POST['inclusions']));

   $frm_data = filteration($_POST);
   $flag = 0;

   $q1 = "INSERT INTO `facilities`(`name`, `area`, `price`, `quantity`, `description`) VALUES (?,?,?,?,?)";
   $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['quantity'],$frm_data['desc']];

   if(insert($q1, $values, 'siiis')){
      $flag = 1;
   }

   $facility_id = mysqli_insert_id($con);

   $q2 = "INSERT INTO `facilities_inclusions`(`facility_id`, `inclusions_id`) VALUES (?,?)";
   if ($stmt = mysqli_prepare($con, $q2))
   {
      foreach($facilities as $f){
         mysqli_stmt_bind_param($stmt, 'ii', $facility_id, $f);
         mysqli_stmt_execute($stmt);
      }
      mysqli_stmt_close($stmt);
   } 
   else {
      $flag = 0;
      die('Query cannot be executed - Insert');
   }

   if($flag){
      echo 1;
   } else {
      echo 0;
   }
}

if(isset($_POST['get_all_facilities'])) {

   $res = select("SELECT * FROM `facilities` WHERE `removed`=?",[0],'i');
   $i = 1;

   $data = "";

   while($row = mysqli_fetch_assoc($res))
   {
      if($row['status'] == 1){
         $status = "<button onclick='toggle_status($row[id],0)' class='btn btn-dark btn-sm shadow-none'>Active</button>";
      }
      else{
         $status = "<button onclick='toggle_status($row[id],1)' class='btn btn-warning btn-sm shadow-none'>Inactive</button>";
      }

      $data .= "
      <tr class='align-middle'>
         <td>$i</td>
         <td>$row[name]</td>
         <td>$row[area]</td>
         <td>₱$row[price]</td>
         <td>$row[quantity]</td>
         <td>$status</td>
         <td>
            <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-facility'>
               <i class='bi bi-pencil-square'></i>
            </button>
            <button type='button' onclick=\"facility_images($row[id],'$row[name]')\" class='btn btn-info shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#facility-images'>
               <i class='bi bi-images'></i>
            </button>
            <button type='button' onclick='remove_facility($row[id])' class='btn btn-danger shadow-none btn-sm'>
               <i class='bi bi-trash'></i>
            </button>
         </td>
      </tr>
      
      ";
      $i++;
   }
   echo $data;
}

if(isset($_POST['get_facility'])){
   $frm_data = filteration($_POST);

   $res1 = select("SELECT * FROM `facilities` WHERE `id`=?",[$frm_data['get_facility']], 'i');
   $res3 = select("SELECT * FROM `facilities_inclusions` WHERE `facility_id`=?",[$frm_data['get_facility']], 'i');

   $facilitydata = mysqli_fetch_assoc($res1);
   $facilities = [];

   if(mysqli_num_rows($res3) > 0){
      while($row = mysqli_fetch_assoc($res3)){
         array_push($facilities, $row['inclusions_id']);
      }
   }

   $data = ["facilitydata" => $facilitydata, "facilities" => $facilities];

   $data = json_encode($data);

   echo $data;
}

if(isset($_POST['edit_facility'])) {
   $facilities = filteration(json_decode($_POST['inclusions']));

   $frm_data = filteration($_POST);
   $flag = 0;

   $q1 = "UPDATE `facilities` SET `name`=?,`area`=? ,`price`=? ,`quantity`=?,
      `description`=? WHERE `id`=?";
   $values = [$frm_data['name'],$frm_data['area'],$frm_data['price'],$frm_data['quantity'],$frm_data['desc'], $frm_data['facility_id']];

   if(update($q1, $values, 'siiisi')){
      $flag = 1;
   }
   $del_facilities = delete("DELETE FROM `facilities_inclusions` WHERE `facility_id`=?", [$frm_data['facility_id']], 'i');

   if(!($del_facilities)){
      $flag = 0;
   }

   $q2 = "INSERT INTO `facilities_inclusions`(`facility_id`, `inclusions_id`) VALUES (?,?)";
   if ($stmt = mysqli_prepare($con, $q2))
   {
      foreach($facilities as $f){
         mysqli_stmt_bind_param($stmt, 'ii', $frm_data['facility_id'], $f);
         mysqli_stmt_execute($stmt);
      }
      $flag = 1;
      mysqli_stmt_close($stmt);
   } 
   else {
      $flag = 0;
      die('Query cannot be executed - Insert');
   }

   if($flag){
      echo 1;
   } else {
      echo 0;
   }

}

if(isset($_POST['toggle_status'])) {
   $frm_data = filteration($_POST);

   $q = "UPDATE `facilities` SET `status`=? WHERE `id`=?";
   $values = [$frm_data['value'], $frm_data['toggle_status']];

   if(update($q, $values, 'ii')){
      echo 1;
   }
   else{
      echo 0;
   }
}

if(isset($_POST['add_image'])){
   $frm_data = filteration($_POST);

   $img_r = uploadImage($_FILES['image'], FACILITIES_FOLDER);

   if($img_r == 'inv_img'){
      echo $img_r;
   } 
   else if($img_r == 'inv_size'){
      echo $img_r;
   } 
   else if($img_r == 'upd_failed'){
      echo $img_r;
   } 
   else{
      $q = "INSERT INTO `facility_images`(`facility_id`, `image`) VALUES (?,?)";
      $values = [$frm_data['facility_id'], $img_r];
      $res = insert($q, $values, 'is');
      echo $res;
   }
}

if(isset($_POST['get_facility_images'])){
   $frm_data = filteration($_POST);
   $res = select("SELECT * FROM `facility_images` WHERE `facility_id`=?", [$frm_data['get_facility_images']], 'i');

   $path = FACILITIES_IMG_PATH;

   while($row = mysqli_fetch_assoc($res)){

      if($row['thumb'] == 1){
         $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
      } else {
         $thumb_btn = " <button onclick='thumb_image($row[sr_no], $row[facility_id])' class='btn btn-secondary shadow-none'>
                           <i class='bi bi-check-lg'></i>
                        </button>";
      }

      echo <<< data
         <tr class='align-middle'>
            <td><img src='$path$row[image]' class='img-fluid'></td>
            <td>$thumb_btn</td>
            <td>
               <button onclick='rem_image($row[sr_no], $row[facility_id])' class='btn btn-danger shadow-none'>
                  <i class='bi bi-trash'></i>
               </button>
            </td>
         </tr>
      data;
   }

}

if(isset($_POST['rem_image'])){
   $frm_data = filteration($_POST);
   $values = [$frm_data['image_id'], $frm_data['facility_id']];

   $pre_q = "SELECT * FROM `facility_images` WHERE `sr_no`=? AND `facility_id`=?";
   $res = select($pre_q, $values, 'ii');
   $img = mysqli_fetch_assoc($res);

   if(deleteImage($img['image'], FACILITIES_FOLDER)){
      $q = "DELETE FROM `facility_images` WHERE `sr_no`=? AND `facility_id`=?";
      $res = delete($q,$values,'ii');
      echo $res;
   }
   else {
      echo 0;
   }
}

// Activate Image
if(isset($_POST['thumb_image'])){
   $frm_data = filteration($_POST);
   
   $pre_q = "UPDATE `facility_images` SET `thumb`=? WHERE `facility_id`=?";
   $pre_v = [0, $frm_data['facility_id']];
   $pre_res = update($pre_q, $pre_v, 'ii');

   $q = "UPDATE `facility_images` SET `thumb`=? WHERE `sr_no`=? AND `facility_id`=?";
   $values = [1, $frm_data['image_id'], $frm_data['facility_id']];
   $res = update($q, $values, 'iii');
   echo $res;
}
// Delete Image
if(isset($_POST['remove_facility']))
{
   $frm_data = filteration($_POST);

   $res1 = select("SELECT * FROM `facility_images` WHERE `facility_id`=?", [$frm_data['facility_id']], 'i');
   while($row = mysqli_fetch_assoc($res1)){
      deleteImage($row['image'], FACILITIES_FOLDER);
   }

   $res2 = delete("DELETE FROM `facility_images` WHERE `facility_id`=?", [$frm_data['facility_id']], 'i');
   $res4 = delete("DELETE FROM `facilities_inclusions` WHERE `facility_id`=?", [$frm_data['facility_id']], 'i');
   $res5 = update("UPDATE `facilities` SET `removed`=? WHERE `id`=?", [1, $frm_data['facility_id']], 'ii');

   if($res2 || $res4 || $res5){
      echo 1;
   }
   else{
      echo 0;
   }
}



?>