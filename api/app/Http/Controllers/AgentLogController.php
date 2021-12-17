<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\AgentLogRepository;
use App\Repository\AgentLogImageRepository;
use App\Repository\UserRepository;
use App\Repository\SellerRepository;

class AgentLogController extends Controller {

    protected $agentLogRepo;
    protected $userRepo;
    protected $sellerRepo;
    protected $agentLogImageRepo;

    public function __construct(AgentLogRepository $agentLogRepo, UserRepository $userRepo, SellerRepository $sellerRepo,
            AgentLogImageRepository $agentLogImageRepo) {
        $this->agentLogRepo = $agentLogRepo;
        $this->userRepo = $userRepo;
        $this->sellerRepo = $sellerRepo;
        $this->agentLogImageRepo = $agentLogImageRepo;
    }

    public function createAgentLog(Request $request) {
        $data = $request->all();
        $data['agent_id'] = $this->userRepo->UserOfId($data['agent_id']);
        $data['seller_id'] = $this->sellerRepo->SellerOfId($data['seller_id']);
        if (isset($data['photo_shoot_date'])) {
            $data['photo_shoot_date'] = new \DateTime($data['photo_shoot_date']);
        }

        if (isset($data['payment_date'])) {
            $data['payment_date'] = new \DateTime($data['payment_date']);
        }

        if (isset($data['invoice_images'])) {
            $data['agent_log_invoice_images'] = [];
            if (count($data['invoice_images']) > 0) {
                foreach ($data['invoice_images'] as $invoiceImageId) {
                    $data['agent_log_invoice_images'][] = $this->agentLogImageRepo->ImageOfId($invoiceImageId);
                }
            }
        }

        $agentLog = $this->agentLogRepo->prepareData($data);
        $this->agentLogRepo->create($agentLog);

        return response()->json('agent log created successfully');
    }

    public function getAgentLogById($id) {
        return $this->agentLogRepo->getAgentLogById($id);
    }

    public function updateAgentLog(Request $request, $id) {
        $agentLog = $this->agentLogRepo->getAgentLogObjById($id);
        $data = $request->all();

        if (isset($data['agent_id'])) {
            $data['agent_id'] = $this->userRepo->UserOfId($data['agent_id']);
        }

        if (isset($data['seller_id'])) {
            $data['seller_id'] = $this->sellerRepo->SellerOfId($id);
        }

        if (isset($data['photo_shoot_date'])) {
            $data['photo_shoot_date'] = new \DateTime($data['photo_shoot_date']);
        }

        if (isset($data['payment_date'])) {
            $data['payment_date'] = new \DateTime($data['payment_date']);
        }

        if (isset($data['invoice_images'])) {
            $data['agent_log_invoice_images'] = [];
            if (count($data['invoice_images']) > 0) {
                foreach ($data['invoice_images'] as $invoiceImageId) {
                    $data['agent_log_invoice_images'][] = $this->agentLogImageRepo->ImageOfId($invoiceImageId);
                }
            }
        }

        $this->agentLogRepo->update($agentLog, $data);
        return response()->json('agent log updated successfully');
    }

    public function getAgentsLogs(Request $request) {
        $filters = $request->all();
        $response = [];
        $response['draw'] = $filters['draw'];
        $data = $this->agentLogRepo->getAgentLogs($filters);
        $response['data'] = $data['data'];
        $response['recordsTotal'] = $data['total'];
        $response['recordsFiltered'] = $this->agentLogRepo->getAgentLogsTotal($filters);
        return response()->json($response, 200);
    }

    public function deleteAgentLog($id) {
        $agentLog = $this->agentLogRepo->getAgentLogObjById($id);
        $this->agentLogRepo->delete($agentLog);
        return response()->json('agent log deleted successfully');
    }

    public function submitForApproval($id) {
        $agentLog = $this->agentLogRepo->getAgentLogObjById($id);
        $baseURL = str_replace("/api", "", url("/agents_logs"));

        $email = "sell@thelocalvault.com";
        $subject = "Agent Log Approval";

        $agentName = $agentLog->getAgent_id()->getFirstname(). ' ' . $agentLog->getAgent_id()->getLastname();

        $introLines = [
            $agentName . " has submitted a new invoice for approval.",
            "<a href='" . $baseURL . "' target='_blank'>Click here</a> to view."
        ];

        $viewData = ['level' => 'success', 'outroLines' => [], 'introLines' => $introLines];
        $emailView = \View::make('emails.new_product_email', $viewData)->render();

        app('App\Http\Controllers\EmailController')->sendMail($email, $subject, $emailView);

        return response()->json("agent log approval submmition email sent");
    }

    public function getAgentsArchiveLogs(Request $request) {
        $filters = $request->all();
        $response = [];
        $response['draw'] = $filters['draw'];
        $data = $this->agentLogRepo->getAgentArchiveLogs($filters);
        $response['data'] = $data['data'];
        $response['recordsTotal'] = $data['total'];
        $response['recordsFiltered'] = $this->agentLogRepo->getAgentArchiveLogsTotal($filters);
        return response()->json($response, 200);
    }

    public function archiveLog(Request $request) {
        $id = $request->get('id');

        $data = [
            'is_archive' => 1
        ];

        $agentLog = $this->agentLogRepo->getAgentLogObjById($id);
        $this->agentLogRepo->update($agentLog, $data);

        return response()->json('agent log updated successfully');
    }

    public function archiveRestoreLog(Request $request) {
        $id = $request->get('id');

        $data = [
            'is_archive' => 0
        ];

        $agentLog = $this->agentLogRepo->getAgentLogObjById($id);
        $this->agentLogRepo->update($agentLog, $data);

        return response()->json('agent log updated successfully');
    }

    public function uploadInvoiceImages(Request $request) {
        $file = $request->file('photo');

        $size = \File::size($file);

        $extension = $file->getClientOriginalExtension();

        $image_original = \Image::make($file->getRealPath());

        $destinationPath = public_path() . '/../../Uploads/agents_logs/';
        @mkdir(public_path() . '/../../Uploads/agents_logs', 0777);
        $filename = str_random(25) . '.' . $extension;
        $allowed = array('gif', 'png', 'jpg', 'Jpeg', 'jpeg', 'JPG', 'PNG', 'GIF');

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $upload_success = $image_original->save($destinationPath . $filename);

        if ($upload_success && in_array($ext, $allowed)) {

            $imageData = [
                'name' => $filename
            ];

            $preparedData = $this->agentLogImageRepo->prepareData($imageData);
            $imageid = $this->agentLogImageRepo->create($preparedData);

            return response()->json(['filename' => $filename, 'id' => $imageid, 'size' => $size]);
        } else if ($upload_success) {
            return response()->json(['filename' => $filename, 'id' => 0, 'size' => $size]);
        } else {
            return 'YEP: Problem in file upload';
        }
    }

    public function deleteInvoiceImage(Request $request) {
        $details = $request->all();
        $filename = $details['name'];
        $path_final_dir = public_path() . '/../../Uploads/agents_logs/';

        if (\File::delete($path_final_dir . $filename)) {
            $image = $this->agentLogImageRepo->ImageOfId($details['id']);
            $this->agentLogImageRepo->delete($image);

            return 1;
        } else {
            return 0;
        }
    }

}
