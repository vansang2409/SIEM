<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_insight extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->library('Insight_rule_service');
        $this->load->library('Insight_ai_service');
    }

    public function show($customerId = null)
    {
        $customerId = (int) $customerId;
        if ($customerId <= 0) {
            return $this->respond(['message' => 'Invalid customer id'], 400);
        }

        $customer = $this->Customer_model->find($customerId);
        if (!$customer) {
            return $this->respond(['message' => 'Customer not found'], 404);
        }

        $metrics = $this->Customer_model->getInsightMetrics($customerId);
        $recentPurchases = $this->Customer_model->getRecentPurchases($customerId, 5);
        $recentCare = $this->Customer_model->getRecentCareHistory($customerId, 5);

        $ruleData = $this->insight_rule_service->build($customer, $metrics);
        $aiData = $this->insight_ai_service->summarize($customer, $metrics, $recentPurchases, $recentCare, $ruleData);

        $response = [
            'customer_id' => $customerId,
            'generated_at' => gmdate('c'),
            'score' => isset($ruleData['score']) ? $ruleData['score'] : 0,
            'segment' => isset($ruleData['segment']) ? $ruleData['segment'] : 'Normal',
            'tags' => isset($ruleData['tags']) ? $ruleData['tags'] : [],
            'summary' => isset($aiData['summary']) ? $aiData['summary'] : 'Chưa có dữ liệu phân tích.',
            'metrics' => $metrics,
            'alerts' => isset($ruleData['alerts']) ? $ruleData['alerts'] : [],
            'next_actions' => isset($aiData['next_actions']) ? $aiData['next_actions'] : [],
        ];

        return $this->respond($response);
    }

    protected function respond($data, $status = 200)
    {
        return $this->output
            ->set_status_header($status)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
