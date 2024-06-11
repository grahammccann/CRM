<?php

function fullUrl() {
	try {
	    return sprintf("%s://%s/", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME']);
	} catch(Exception $e) {
        echo $e->getMessage();		
	}
}


function handleAvatarUpload($user_id) {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileType = $_FILES['avatar']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = ['jpg', 'gif', 'png'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = 'avatar-' . md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = $_SERVER['DOCUMENT_ROOT'] . '/app/avatar/';
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $db = DB::getInstance();
                $existing_avatar = $db->selectOne("SELECT * FROM user_avatars WHERE user_id = :user_id", ['user_id' => $user_id]);
                if ($existing_avatar) {
                    unlink($uploadFileDir . $existing_avatar['avatar_path']); // delete old avatar
                    $db->update('user_avatars', 'user_id', $user_id, ['avatar_path' => $newFileName]);
                } else {
                    $db->insert('user_avatars', ['user_id' => $user_id, 'avatar_path' => $newFileName]);
                }
                return $newFileName;
            } else {
                $_SESSION['warning'] = "Failed to move the uploaded file. Check permissions.";
                return false;
            }
        } else {
            $_SESSION['warning'] = "Invalid file extension.";
            return false;
        }
    }
    return false;
}

function stdmsg($text) {
    ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check"></i> <?= $text; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
}

function stderr($text) {
    ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-times"></i> <?= $text; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
}

function getPageMetadata($page) {
    $baseTitle = "SyncFàs CRM | ";
    $metadata = [
        "/about.php" => [
            "title" => "About Us",
            "description" => "Learn more about SyncFàs CRM, our mission, and the team behind the product."
        ],
        "/contact.php" => [
            "title" => "Contact",
            "description" => "Get in touch with us for more information or support regarding SyncFàs CRM."
        ],
        "/features.php" => [
            "title" => "Features",
            "description" => "Explore the powerful features of SyncFàs CRM designed to streamline your business processes."
        ],
        "/index.php" => [
            "title" => "Powerful CRM Solutions for Business Growth",
            "description" => "Welcome to SyncFàs CRM – the ultimate solution for managing customer relationships, streamlining business processes, and driving business growth with our powerful CRM features."
        ],
        "/login.php" => [
            "title" => "Login",
            "description" => "Login to access your SyncFàs CRM account and manage your business more efficiently."
        ],
        "/pricing.php" => [
            "title" => "Pricing",
            "description" => "Discover our competitive pricing plans for SyncFàs CRM and choose the best fit for your business."
        ],
        "/support.php" => [
            "title" => "Support",
            "description" => "Access our support resources to get help with SyncFàs CRM."
        ]
    ];

    $pageTitle = $baseTitle . ($metadata[$page]['title'] ?? "Home - Powerful CRM Solutions for Business Growth");
    $pageDescription = $metadata[$page]['description'] ?? "Welcome to SyncFàs CRM – the ultimate solution for managing customer relationships, streamlining business processes, and driving business growth with our powerful CRM features.";

    return [
        "title" => $pageTitle,
        "description" => $pageDescription
    ];
}

?>