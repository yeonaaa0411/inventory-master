<?php
$page_title = 'All Images';
require_once('includes/load.php');
// Checkin What level user has permission to view this page
page_require_level(2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? remove_junk($page_title) : "Media"; ?></title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    <style>
        /* Custom styles */
        th, td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
th {
    background-color: rgba(236, 253, 245, var(--tw-bg-opacity)); /* bg-green-50 */
    font-weight: 600;
    color: #4A5568;
}

        td { font-size: 14px; color: #2D3748; }
        
        /* Custom Modal Styles */
        #cropModal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Control image size in the cropping modal */
        #imageToCrop {
            max-width: 100%;
            max-height: 500px;
            margin: 0 auto;
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">

<?php
$media_files = find_all('media');

// Original file upload handling
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
<div class="flex justify-center mb-4">
    <div class="w-full md:w-8/12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<!-- Media Panel -->
<div class="bg-white p-6 rounded-lg shadow-lg mt-6 mx-auto w-full md:w-full">


    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <div class="flex items-center space-x-3">
            <span class="text-3xl text-blue-500"><i class="glyphicon glyphicon-camera" style="font-size: 32px;"></i></span>
            <span class="text-2xl font-semibold text-gray-700">All Photos</span>
        </div>
        <form class="flex" action="media.php" method="POST" enctype="multipart/form-data" id="uploadForm">
            <input type="file" name="file_upload" multiple="multiple" class="hidden" id="fileUpload" />
            <label for="fileUpload" class="bg-blue-600 text-white px-6 py-2 rounded-lg cursor-pointer hover:bg-blue-700 transition duration-300">
                Choose Files
            </label>
        </form>
    </div>

    <!-- Cropping Modal -->
    <div id="cropModal" class="modal">
        <div class="modal-content">
            <h3 class="text-center text-xl font-semibold mb-4">Crop Image</h3>
            <div class="flex justify-center mb-4">
                <img id="imageToCrop" src="" alt="Image to Crop">
            </div>
            <div class="flex justify-center mt-4">
                <button id="cropImageButton" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">Upload</button>
            </div>
        </div>
    </div>

    <!-- Table of Media Files -->
    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse">
            <thead class="text-left">
                <tr>
                    <th class="text-center p-3">#</th>
                    <th class="text-center p-3">Photo</th>
                    <th class="text-center p-3">Photo Name</th>
                    <th class="text-center p-3">Photo Type</th>
                    <th class="text-center p-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($media_files as $media_file): ?>
                <tr class="bg-gray-50 hover:bg-gray-100">
                    <td class="text-center p-3"><?php echo count_id(); ?></td>
                    <td class="text-center p-3">
                        <img src="uploads/products/<?php echo $media_file['file_name']; ?>" class="w-16 h-16 object-cover rounded-full mx-auto" />
                    </td>
                    <td class="text-center p-3"><?php echo $media_file['file_name']; ?></td>
                    <td class="text-center p-3"><?php echo $media_file['file_type']; ?></td>
                    <td class="text-center p-3">
    <a href="delete_media.php?id=<?php echo (int) $media_file['id']; ?>" onClick="return confirm('Are you sure you want to delete?')" class="text-red-600 hover:text-red-800 transition duration-300">
        <i class="glyphicon glyphicon-trash"></i>
    </a>
</td>

                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>

<!-- Cropper.js and jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let cropper;
let formData = new FormData();
let originalFileName = ''; // Variable to store the original file name

// Open the cropping modal
$('#fileUpload').change(function (e) {
    const file = e.target.files[0];
    originalFileName = file.name; // Store the original file name
    const reader = new FileReader();

    reader.onload = function (event) {
        const imgElement = document.getElementById('imageToCrop');
        imgElement.src = event.target.result;
        $('#cropModal').show();

        // Initialize Cropper.js
        cropper = new Cropper(imgElement, {
            aspectRatio: 1, // Maintain a square crop
            viewMode: 2,
            autoCropArea: 0.5,
            responsive: true,
            zoomable: true,
        });
    };

    reader.readAsDataURL(file);
});

// Upload cropped image when button is clicked
$('#cropImageButton').click(function () {
    const canvas = cropper.getCroppedCanvas();
    canvas.toBlob(function (blob) {
        formData.append('file_upload', blob, originalFileName); // Use the original file name here
        formData.append('submit', 'Upload'); // Add submit key to trigger PHP upload handler

        // Call the original PHP upload function via AJAX
        $.ajax({
            url: 'media.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Image uploaded successfully!');
                location.reload(); // Refresh the page to display the new image
            },
            error: function() {
                alert('Error uploading image.');
            }
        });
    });
});

// Close the modal if user clicks outside of it
window.onclick = function(event) {
    if (event.target == document.getElementById("cropModal")) {
        document.getElementById("cropModal").style.display = "none";
    }
};
</script>

</body>
</html>
