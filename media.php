<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? remove_junk($page_title) : "Media"; ?></title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        /* Custom styles */
        th, td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
        th { background-color: #eaf5e9; /* Light green color */ }
    </style>
</head>
<body class="bg-gray-100">

<?php
$page_title = 'All Images';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
?>
<?php $media_files = find_all('media'); ?>
<?php
if (isset($_POST['submit'])) {
    $photo = new Media();
    $photo->upload($_FILES['file_upload']);
    if ($photo->process_media()) {
        $session->msg('s', 'Photo has been uploaded.');
        redirect('media.php');
    } else {
        $session->msg('d', join($photo->errors));
        redirect('media.php');
    }
}
?>
<?php include_once('layouts/header.php'); ?>

<!-- Display messages -->
<div class="flex justify-center">
    <div class="w-12/12 md:w-3/6">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<!-- Media Panel -->
<div class="bg-white p-4 rounded-lg shadow-md mt-4 mx-auto w-12/12 md:w-4/4">
    <div class="flex justify-between items-center border-b pb-2 mb-4">
        <div class="flex items-center">
        <span class="mr-2 text-xl"><i class="glyphicon glyphicon-camera" style="font-size: 24px;"></i></span>
        <span class="text-2xl font-bold">All Photos</span>
            
        </div>
        <form class="flex" action="media.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file_upload" multiple="multiple" class="hidden" id="fileUpload" />
            <label for="fileUpload" class="bg-blue-500 text-white px-4 py-3 rounded cursor-pointer hover:bg-blue-600">
                Choose Files
            </label>
            <button type="submit" name="submit" class="bg-green-500 text-white px-4 py-2 rounded ml-2 hover:bg-green-600">Upload</button>
        </form>
    </div>

    <div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">#</th>
                    <th class="text-center">Photo</th>
                    <th class="text-center">Photo Name</th>
                    <th class="text-center" style="width: 20%;">Photo Type</th>
                    <th class="text-center" style="width: 50px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($media_files as $media_file): ?>
                <tr class="list-inline">
                    <td class="text-center"><?php echo count_id(); ?></td>
                    <td class="text-center">
                        <img src="uploads/products/<?php echo $media_file['file_name']; ?>" class="img-thumbnail w-16 h-16 object-cover" />
                    </td>
                    <td class="text-center"><?php echo $media_file['file_name']; ?></td>
                    <td class="text-center"><?php echo $media_file['file_type']; ?></td>
                    <td class="text-center">
                        <a href="delete_media.php?id=<?php echo (int) $media_file['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-700" title="Delete">
                            <span class="glyphicon glyphicon-trash"></span>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
</body>
</html>
