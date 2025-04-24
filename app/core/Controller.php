<?php

class Controller
{
    protected $notifications = [];

    public function __construct()
    {
        // Initialize notifications using notificationModel
        if (isset($_SESSION['userID'])) {
            $this->notifications = $this->loadModel('NotificationModel')->getUnread($_SESSION['userID']);
        } else {
            $this->notifications = [];
        }
    }
    public function view($name, $data = [])
    {
        // Inject notifications into the data array
        $data['notifications'] = $this->notifications;

        $filename = "../app/views/" . $name . ".view.php";
        //echo "Looking for view at: " . $filename; // Debug line
        
        if (file_exists($filename)) {
            // Extract the data array into variables
            extract($data);
            
            // Include the view file
            require $filename;
        } else {
            // If the view file does not exist, load a 404 page
            require "../app/views/404.view.php";
        }
    }
    public function loadModel($model)
    {
        $filename = "../app/models/" . ucfirst($model) . ".php";
        if(file_exists($filename))
        {
            require_once $filename;
            return new $model;
        }
        return false;
    }

}
