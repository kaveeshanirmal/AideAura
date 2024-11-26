<?php

class ServiceController extends Controller {
    private $serviceModel;

    public function __construct() {
        $this->serviceModel = new ServiceModel();
    }

    // This controller can handle other service-related operations
    // like updating prices, managing services, etc.
}