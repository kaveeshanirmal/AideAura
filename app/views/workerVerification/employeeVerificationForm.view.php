<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Navigation</title>
    <!-- Placeholder for dynamic CSS -->
    <link rel="stylesheet" href="<?=ROOT?>/public/assets/css/employeeVerificationForm/page1.css" id="dynamic-styles">
    <script>
        const ROOT = "<?php echo ROOT; ?>";
    </script>
</body>
</head>
<body>
    <?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
    <!-- Placeholder for dynamic content -->
    <div id="content"></div>
    <?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
    <!-- JavaScript for handling navigation -->
    <script src="<?=ROOT?>/public/assets/js/employeeVerificationForm/navigation.js"></script>
</body>
</html>
