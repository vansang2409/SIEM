<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Insight_rule_service
{
    public function build($customer, $metrics)
    {
        $score = 50;
        $tags = [];
        $alerts = [];
        $segment = 'Normal';

        $totalSpent = (float) ($metrics['total_spent'] ?? 0);
        $invoiceCount = (int) ($metrics['invoice_count'] ?? 0);
        $totalDebt = (float) ($metrics['total_debt'] ?? 0);
        $daysSinceLastPurchase = (int) ($metrics['days_since_last_purchase'] ?? 9999);

        if ($totalSpent > 1000000000) {
            $score += 20;
            $tags[] = 'high_value';
            $segment = 'VIP';
        }

        if ($invoiceCount > 30) {
            $score += 10;
            $tags[] = 'repeat_customer';
        }

        if ($totalDebt > 300000000) {
            $score -= 10;
            $tags[] = 'debt_watch';
            $alerts[] = [
                'level' => 'warning',
                'code' => 'HIGH_DEBT',
                'message' => 'Công nợ hiện tại đang cao.'
            ];
        }

        if ($daysSinceLastPurchase > 30) {
            $score -= 8;
            $tags[] = 'inactive';
            $alerts[] = [
                'level' => 'warning',
                'code' => 'INACTIVE',
                'message' => 'Khách đã lâu chưa phát sinh mua mới.'
            ];
        }

        $score = max(0, min(100, $score));

        return [
            'score' => $score,
            'segment' => $segment,
            'tags' => $tags,
            'alerts' => $alerts,
        ];
    }
}
