<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Requirement Gathering Form</title>
    <link rel="stylesheet" href="<?php echo ROOT; ?>/public/assets/css/serviceForms.css">
</head>
<body>
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php');?>
<div class="container">
    <h2>Select a Service</h2>
    <div class="progress-bar">
        <div class="progress"></div>
    </div>

    <form id="serviceForm">
        <div class="image-group">
            <div class="image-button">
                <a href="<?=ROOT?>/public/SelectService/cook" class="service-link" data-service="Cook">
                    <img src="<?php echo ROOT; ?>/public/assets/images/service_cook.png" alt="Sample Image 1">
                    <span>Cook</span>
                </a>
            </div>
            <div class="image-button">
                <a href="<?=ROOT?>/public/SelectService/maid" class="service-link" data-service="Maid">
                    <img src="<?php echo ROOT; ?>/public/assets/images/service_maid.png" alt="Sample Image 2">
                    <span>Maid</span>
                </a>
            </div>
            <div class="image-button">
                <a href="<?=ROOT?>/public/SelectService/nanny" class="service-link" data-service="Nanny">
                    <img src="<?php echo ROOT; ?>/public/assets/images/service_nanny.png" alt="Sample Image 3">
                    <span>Nanny</span>
                </a>
            </div>
            <div class="image-button">
                <a href="<?=ROOT?>/public/SelectService/cook24" class="service-link" data-service="Cook 24-hour Live in">
                    <img src="<?php echo ROOT; ?>/public/assets/images/service_cook24.png" alt="Sample Image 4">
                    <span>Cook 24H Live-in</span>
                </a>
            </div>
            <div class="image-button">
                <a href="<?=ROOT?>/public/SelectService/allRounder" class="service-link" data-service="All rounder">
                    <img src="<?php echo ROOT; ?>/public/assets/images/service_allrounder.png" alt="Sample Image 5">
                    <span>All-rounder</span>
                </a>
            </div>
        </div>

        <div class="button-group">
            <button type="button" class="btn-previous">Previous</button>
            <button type="button" class="btn-next">Next</button>
        </div>
    </form>
</div>
<?php include(ROOT_PATH . '/app/views/components/footer.view.php');?>

<script>
    const ROOT = "<?php echo ROOT; ?>";
    document.addEventListener('DOMContentLoaded', function() {
        var links = document.querySelectorAll('.service-link');

        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var service = this.dataset.service;
                var href = this.href;

                fetch(`${ROOT}/public/SelectService`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'service=' + encodeURIComponent(service)
                })
                    .then(function(response) {
                        window.location.href = href;
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                    });
            });
        });
    });
</script>
</body>
</html>