<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Insight_ai_service
{
    /**
     * V1 strategy:
     * - fallback bằng rule-based summary để luôn có output
     * - sau này có thể thay bằng gọi n8n/OpenClaw/OpenAI API thật
     */
    public function summarize($customer, $metrics, $recentPurchases = [], $recentCare = [], $ruleData = [])
    {
        $summary = $this->buildFallbackSummary($customer, $metrics, $ruleData);
        $nextActions = $this->buildFallbackNextActions($metrics, $ruleData, $recentCare);

        return [
            'summary' => $summary,
            'next_actions' => $nextActions,
        ];
    }

    protected function buildFallbackSummary($customer, $metrics, $ruleData)
    {
        $segment = $ruleData['segment'] ?? 'Normal';
        $tags = $ruleData['tags'] ?? [];
        $totalSpent = (float) ($metrics['total_spent'] ?? 0);
        $totalDebt = (float) ($metrics['total_debt'] ?? 0);
        $invoiceCount = (int) ($metrics['invoice_count'] ?? 0);
        $daysSinceLastPurchase = (int) ($metrics['days_since_last_purchase'] ?? 9999);

        $parts = [];

        if ($segment === 'VIP') {
            $parts[] = 'Khách thuộc nhóm giá trị cao với lịch sử chi tiêu lớn.';
        } elseif ($invoiceCount > 10) {
            $parts[] = 'Khách có lịch sử giao dịch tương đối đều.';
        } else {
            $parts[] = 'Khách hiện chưa có nhiều lịch sử giao dịch nổi bật.';
        }

        if ($totalDebt > 300000000) {
            $parts[] = 'Hiện cần ưu tiên theo dõi công nợ vì số dư nợ đang ở mức cao.';
        }

        if ($daysSinceLastPurchase > 30) {
            $parts[] = 'Khách đã lâu chưa phát sinh mua mới, nên cân nhắc chăm sóc lại.';
        } elseif ($daysSinceLastPurchase <= 7) {
            $parts[] = 'Khách vừa có giao dịch gần đây, phù hợp tiếp tục chăm sóc để giữ nhiệt.';
        }

        if ($totalSpent > 0 && empty($parts)) {
            $parts[] = 'Khách đã có phát sinh giao dịch, có thể tiếp tục theo dõi để chăm sóc phù hợp.';
        }

        return implode(' ', $parts);
    }

    protected function buildFallbackNextActions($metrics, $ruleData, $recentCare = [])
    {
        $actions = [];
        $totalDebt = (float) ($metrics['total_debt'] ?? 0);
        $daysSinceLastPurchase = (int) ($metrics['days_since_last_purchase'] ?? 9999);
        $tags = $ruleData['tags'] ?? [];

        if ($totalDebt > 300000000) {
            $actions[] = [
                'type' => 'debt_follow_up',
                'label' => 'Kiểm tra tình trạng công nợ và lịch sử nhắc nợ gần nhất'
            ];
        }

        if ($daysSinceLastPurchase > 30) {
            $actions[] = [
                'type' => 'follow_up',
                'label' => 'Gọi hoặc nhắn chăm sóc lại trong 1-3 ngày tới'
            ];
        }

        if (in_array('high_value', $tags)) {
            $actions[] = [
                'type' => 'upsell',
                'label' => 'Xem xét đề xuất dịch vụ/gói phù hợp theo lịch sử mua gần nhất'
            ];
        }

        if (empty($actions)) {
            $actions[] = [
                'type' => 'review',
                'label' => 'Kiểm tra thêm lịch sử mua và chăm sóc gần nhất để xác định hướng follow-up'
            ];
        }

        return array_slice($actions, 0, 3);
    }

    /**
     * Gợi ý hướng mở rộng:
     * - gọi n8n webhook nội bộ
     * - hoặc gọi OpenAI/OpenClaw gateway
     * - nếu gọi thất bại thì fallback về summarize() hiện tại
     */
    public function summarizeViaWebhook($payload)
    {
        // TODO: implement when endpoint is available
        // Example flow:
        // 1. POST JSON payload to n8n webhook/internal AI endpoint
        // 2. Read summary/next_actions from response
        // 3. Fallback to local summary if request fails
        return null;
    }
}
