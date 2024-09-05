<!-- navbar -->
<?php include(ROOT_PATH . '/app/views/components/navbar.view.php'); ?>
<style>
    .main-content {
    flex-grow: 1;
    padding: 40px;
    background-color: #fff;
}

h1 {
    font-size: 24px;
    margin-bottom: 30px;
}

.personal-info-form {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

.edit-btn {
    background-color: #e0a84c;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px;
}

.edit-btn:hover {
    background-color: #d3953b;
}
</style>
<div class="main-content">
            <h1>Personal Information</h1>
            <form class="personal-info-form">
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" value="Moda">
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" value="Tharindu">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="example@gmail.com">
                </div>
                <div class="form-group">
                    <label for="phone">Mobile Number</label>
                    <input type="text" id="phone" value="+94 77 8475154">
                </div>
                <div class="form-group">
                    <label for="address">Home Address</label>
                    <input type="text" id="address" value="123/A, example road, Colombo 10">
                </div>
                <button type="submit" class="edit-btn">Edit Details</button>
            </form>
        </div>
    </div>

<!-- footer -->
<?php include(ROOT_PATH . '/app/views/components/footer.view.php'); ?>
