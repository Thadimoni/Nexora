<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? 'Nexora'); ?></title>

    <link rel="stylesheet" href="/nexora/public/assets/css/style.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

<div class="app-container">

    <?php require 'C:\xampp\htdocs\nexora\app/Cache/partials/sidebar.php'; ?>

    <div class="main-content">

        <?php require 'C:\xampp\htdocs\nexora\app/Cache/partials/navbar.php'; ?>

        <div class="page-content">

            <?php echo $this->yieldSection('content'); ?>

        </div>

        <?php require 'C:\xampp\htdocs\nexora\app/Cache/partials/footer.php'; ?>

    </div>

</div>

</body>
</html>