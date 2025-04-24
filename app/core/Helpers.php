<?php

// Redirect to another page
function redirect($path)
{
    header('Location: ' . ROOT . '/' . $path);
    exit;
}

// Check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Get current user ID (optional helper)
function getUserID()
{
    return $_SESSION['user_id'] ?? null;
}

// Sanitize input
function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Flash messages (simple version)
function setFlash($key, $message)
{
    $_SESSION['flash'][$key] = $message;
}

function flash($name = '', $message = '')
{
    if ($name) {
        // If message is passed, set it in the session
        if ($message && !isset($_SESSION[$name])) {
            $_SESSION[$name] = $message;
        } 
        // If no message, retrieve it and remove from session
        elseif (!empty($message) && isset($_SESSION[$name])) {
            $message = $_SESSION[$name];
            unset($_SESSION[$name]);
            return $message;
        }
    }
} 

function getFlash($name)
{
    if (isset($_SESSION[$name])) {
        $message = $_SESSION[$name];
        unset($_SESSION[$name]); // Remove after fetching
        return $message;
    }
    return null;
}

// Debugging function
function dd($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
}



