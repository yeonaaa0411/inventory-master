<?php
  $page_title = 'Edit Group';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(1);
?>

<!--     *************************     -->

<?php
  $e_group = find_by_id('user_groups',(int)$_GET['id']);
  if(!$e_group){
    $session->msg("d","Missing Group id.");
    redirect('group.php');
  }
?>

<!--     *************************     -->

<?php
  if(isset($_POST['update'])){

   $req_fields = array('group-name','group-level');
   validate_fields($req_fields);
   if(empty($errors)){
           $name = remove_junk($db->escape($_POST['group-name']));
          $level = remove_junk($db->escape($_POST['group-level']));
         $status = remove_junk($db->escape($_POST['status']));

        $query  = "UPDATE user_groups SET ";
        $query .= "group_name='{$name}',group_level='{$level}',group_status='{$status}'";
        $query .= "WHERE ID='{$db->escape($e_group['id'])}'";
        $result = $db->query($query);
         if($result && $db->affected_rows() === 1){
          //sucess
          $session->msg('s',"Group has been updated! ");
          redirect('edit_group.php?id='.(int)$e_group['id'], false);
        } else {
          //failed
          $session->msg('d',' Sorry failed to updated Group!');
          redirect('edit_group.php?id='.(int)$e_group['id'], false);
        }
   } else {
     $session->msg("d", $errors);
    redirect('edit_group.php?id='.(int)$e_group['id'], false);
   }
 }
?>

<<<<<<< Updated upstream
<!--     *************************     -->
=======
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($page_title) ? remove_junk($page_title) : "Admin"; ?></title>
  <!-- Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    .custom-class { color: #eaf5e9; }
  </style>
</head>
<body class="bg-gray-100">
>>>>>>> Stashed changes

<?php include_once('layouts/header.php'); ?>

<<<<<<< Updated upstream
=======
<div class="mt-6 ml-6"> <!-- Removed justify-center, added ml-8 to move it left -->
    <div class="w-2/6"> <!-- Reduced width to 2/3 -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-3xl font-bold">
                    <span class="glyphicon glyphicon-pencil" style="font-size: 20px;"></span>
                    Edit Group
                </h2>
            </div>
            <div class="p-4">
                <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id']; ?>">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Group Name</label>
                        <input type="text" class="form-control border rounded w-full py-2 px-3" name="group-name" value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>">
                    </div>
>>>>>>> Stashed changes

<div class="login-page">
    <div class="text-center">
<!--     *************************     -->
       <h3>Edit Group</h3>
<!--     *************************     -->
     </div>
     <?php echo display_msg($msg); ?>
      <form method="post" action="edit_group.php?id=<?php echo (int)$e_group['id'];?>" class="clearfix">
<!--     *************************     -->
        <div class="form-group">
              <label for="name" class="control-label">Group Name</label>
              <input type="name" class="form-control" name="group-name" value="<?php echo remove_junk(ucwords($e_group['group_name'])); ?>">
        </div>
<!--     *************************     -->
        <div class="form-group">
              <label for="level" class="control-label">Group Level</label>
              <input type="number" class="form-control" name="group-level" value="<?php echo (int)$e_group['group_level']; ?>">
        </div>
<!--     *************************     -->
        <div class="form-group">
          <label for="status">Status</label>
              <select class="form-control" name="status">
                <option <?php if($e_group['group_status'] === '1') echo 'selected="selected"';?> value="1"> Active </option>
                <option <?php if($e_group['group_status'] === '0') echo 'selected="selected"';?> value="0">Deactive</option>
              </select>
        </div>
<!--     *************************     -->
        <div class="form-group clearfix">
                <button type="submit" name="update" class="btn btn-info">Update</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
