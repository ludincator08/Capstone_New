<?php 

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();


if(isset($_POST['add_inclusion'])){
   $frm_data = filteration($_POST);

   $img_r = uploadSVGImage($_FILES['icon'], INCLUSIONS_FOLDER);

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
      $q = "INSERT INTO `inclusions`(`icon`, `name`, `description`) VALUES (?, ?, ?)";
      $values = [$img_r, $frm_data['name'], $frm_data['desc']];
      $res = insert($q, $values, 'sss');
      echo $res;
   }
}

if(isset($_POST['get_inclusions'])){
   $res = selectAll('inclusions');
   $i = 1;
   $path = INCLUSIONS_IMG_PATH;

   while($row = mysqli_fetch_assoc($res)){
      echo <<< data
         <tr class="align-middle">
            <td>$i</td>
            <td><img src="$path$row[icon]" width="100px"</td>
            <td>$row[name]</td>
            <td>$row[description]</td>
            <td>
               <button type='button' onclick='upd_inclusion($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-inclusion-s'>
                  <i class='bi bi-pencil-square'></i>
               </button>
               <button type="button" onclick="rem_inclusion($row[id])" class="btn btn-danger btn-sm shadow-none">
                  <i class='bi bi-trash'></i>
               </button>
            </td>
         </tr> 
      data;
      $i++;
   }
}

if (isset($_POST['get_inclusion_id'])) {
   $frm_data = filteration($_POST);
   $res = select('SELECT * FROM `inclusions` WHERE `id`=?', [$frm_data['get_inclusion_id']], 'i');

   if (mysqli_num_rows($res) > 0) {
      $row = mysqli_fetch_assoc($res);
       // Return inclusion data as JSON for the frontend
      echo json_encode($row);
   }
}


if (isset($_POST['edit_inclusion'])) {
   $frm_data = filteration($_POST);

   // Handle icon upload if new icon is provided
   if ($_FILES['icon']['name']) {
      $img_r = uploadSVGImage($_FILES['icon'], INCLUSIONS_FOLDER);

      if ($img_r == 'inv_img' || $img_r == 'inv_size' || $img_r == 'upd_failed') {
         echo $img_r;
         exit;
      }
      $icon = $img_r;
   } else {
       // If no new icon is uploaded, use the existing icon
      $icon = $_POST['current_icon'];
   }

   $q = "UPDATE `inclusions` SET `icon`=?, `name`=?, `description`=? WHERE `id`=?";
   $values = [$icon, $frm_data['name'], $frm_data['desc'], $frm_data['inclusion_id']];
   
   $res = update($q, $values, 'sssi');
   echo $res;
}


if(isset($_POST['rem_inclusion'])){
   $frm_data = filteration($_POST);
   $values = [$frm_data['rem_inclusion']];

   $check_q = select('SELECT * FROM `facilities_inclusions` WHERE `inclusions_id`=?', [$frm_data['rem_inclusion']], 'i');

   if(mysqli_num_rows($check_q) == 0){
      $pre_q = "SELECT * FROM `inclusions` WHERE `id`=?";
      $res = select($pre_q, $values, 'i');
      $img = mysqli_fetch_assoc($res);
   
      // ma dedelete din sa folder ng inclusions
      if(deleteImage($img['icon'], INCLUSIONS_FOLDER)){
         $q = "DELETE FROM `inclusions` WHERE `id`=?";
         $res = delete($q, $values, 'i');
         echo $res;
      }
      else{
         echo 0;
      }
   }
   else{
      echo 'facility_added';
   }

}



?>