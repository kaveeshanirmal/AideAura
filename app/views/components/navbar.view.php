<?php
// I am add this is because I want touse ROOT in this file but Root is not loaded Need to delete this and fix the problem
define('ROOT', 'http://localhost/AideAura/public');
?>

<link rel="stylesheet" href="<?=ROOT?>/public/assets/css/navbar.css">
<div class="header">
    <div class="logo-container">
        <img src="<?=ROOT?>/assets/images/logo.png" alt="logo" id="logo">
    </div>
    <nav class="navlinks">
        <a href="<?=ROOT?>/public/home">Home</a>
        <a href="<?=ROOT?>/public/about">About</a>
        <a href="<?=ROOT?>/public/contact">Contact</a>
        <a href="<?=ROOT?>/public/login">Login</a>
    </nav>
    <div class="right-section">
        <button class="reg-btn">Register</button>
        <img class="icon" src="<?=ROOT?>/public/assets/images/profile_icon.svg" alt="profile logo">
        <img class="icon" src="<?=ROOT?>/public/assets/images/bell_icon.svg" alt="notifications logo">
    </div>
</div>