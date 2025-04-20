<?php

class ComplaintController extends Controller
{
    public function index()
    {
        $complaintModel = new CustomerComplaintModel();
        $data['complaints'] = $complaintModel->getAllComplaints();
        $this->view('complaints/index', $data);
    }

    public function view($id)
    {
        $model = new CustomerComplaintModel();
        $data['complaint'] = $model->getComplaintById($id);
        $data['updates'] = $model->getSolutionByComplaintId($id);
        $this->view('complaints/detail', $data);
    }

    public function addReply()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new CustomerComplaintModel();
            $data = [
                'complaintID' => $_POST['complaintID'],
                'status' => $_POST['status'],
                'comments' => $_POST['comment'],
            ];
            $model->submitComplaintUpdates($data);
            redirect('complaints/view/' . $_POST['complaintID']);
        }
    }
}
