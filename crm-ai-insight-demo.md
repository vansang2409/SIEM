# CRM AI Insight Demo

## 1. HTML mockup

```html
<div class="ai-insight-box card">
  <div class="ai-insight-box__header">
    <h3>AI Customer Insight</h3>
    <span class="badge badge-warning">Debt Watch</span>
  </div>

  <div class="ai-insight-box__meta">
    <div><strong>Segment:</strong> VIP</div>
    <div><strong>Score:</strong> 87/100</div>
    <div><strong>Updated:</strong> 2026-03-27 07:35</div>
  </div>

  <div class="ai-insight-box__section">
    <h4>Tóm tắt nhanh</h4>
    <p>
      Khách hàng giá trị cao, có lịch sử mua hàng mạnh và tổng chi lớn.
      Hiện cần ưu tiên theo dõi công nợ và kích hoạt lại chăm sóc nếu chưa có tương tác gần đây.
    </p>
  </div>

  <div class="ai-insight-box__section">
    <h4>Cảnh báo</h4>
    <ul>
      <li>Công nợ hiện tại đang cao</li>
      <li>32 ngày chưa phát sinh mua mới</li>
    </ul>
  </div>

  <div class="ai-insight-box__section">
    <h4>Hành động đề xuất</h4>
    <ol>
      <li>Kiểm tra lần chăm sóc gần nhất</li>
      <li>Gọi hoặc nhắn chăm sóc trong 1-3 ngày</li>
      <li>Đề xuất gói/dịch vụ phù hợp theo lịch sử mua gần nhất</li>
    </ol>
  </div>

  <div class="ai-insight-box__actions">
    <button type="button">Phân tích sâu</button>
    <button type="button">Soạn tin nhắn</button>
    <button type="button">Tóm tắt lịch sử</button>
  </div>
</div>
```

## 2. JSON response mẫu

```json
{
  "customer_id": 920954,
  "generated_at": "2026-03-27T07:35:00Z",
  "score": 87,
  "segment": "VIP",
  "tags": ["high_value", "debt_watch", "repeat_customer"],
  "summary": "Khách hàng giá trị cao, có lịch sử mua hàng mạnh và tổng chi lớn. Hiện cần ưu tiên theo dõi công nợ và kích hoạt lại chăm sóc nếu chưa có tương tác gần đây.",
  "metrics": {
    "invoice_count": 148,
    "total_spent": 2700000000,
    "total_debt": 389000000,
    "total_loan": 125000000,
    "days_since_last_purchase": 32
  },
  "alerts": [
    {
      "level": "warning",
      "code": "HIGH_DEBT",
      "message": "Công nợ hiện tại đang cao."
    },
    {
      "level": "warning",
      "code": "INACTIVE_32_DAYS",
      "message": "32 ngày chưa phát sinh mua mới."
    }
  ],
  "next_actions": [
    {
      "type": "follow_up",
      "label": "Kiểm tra lần chăm sóc gần nhất"
    },
    {
      "type": "message",
      "label": "Gọi hoặc nhắn chăm sóc trong 1-3 ngày"
    },
    {
      "type": "upsell",
      "label": "Đề xuất gói/dịch vụ phù hợp theo lịch sử mua gần nhất"
    }
  ],
  "quick_actions": [
    {
      "action": "deep_analysis",
      "label": "Phân tích sâu"
    },
    {
      "action": "generate_message",
      "label": "Soạn tin nhắn"
    },
    {
      "action": "summarize_history",
      "label": "Tóm tắt lịch sử"
    }
  ]
}
```

## 3. Pseudo API endpoint

### Request

```http
GET /api/customer-insight/920954
Authorization: Bearer <token>
```

### Response
- Trả về JSON như mẫu trên.
- Nếu chưa có dữ liệu phân tích sẵn, có thể trả trạng thái `pending` hoặc fallback summary ngắn.

### Pseudo controller (CI3 style)

```php
public function customer_insight($customerId)
{
    $customer = $this->Customer_model->find($customerId);
    if (!$customer) {
        return $this->output
            ->set_status_header(404)
            ->set_content_type('application/json')
            ->set_output(json_encode(['message' => 'Customer not found']));
    }

    $metrics = $this->Customer_model->getInsightMetrics($customerId);
    $history = $this->Customer_model->getRecentHistory($customerId, 20);

    $payload = [
        'customer' => $customer,
        'metrics' => $metrics,
        'history' => $history,
    ];

    // Option A: gọi n8n webhook để xử lý insight
    $insight = $this->Insight_service->generate($payload);

    return $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($insight));
}
```

## 4. Logic n8n/backend để sinh insight

### Luồng khuyến nghị
1. CRM gửi request hoặc n8n poll theo customer_id.
2. Lấy dữ liệu tối thiểu:
   - profile cơ bản
   - tổng chi / tổng nợ / tổng vay / số hóa đơn
   - lần mua gần nhất
   - lịch sử mua gần nhất (giới hạn, ví dụ 10-20 bản ghi)
   - lịch sử chăm sóc gần nhất (nếu có)
3. Rule-based layer tính nhanh:
   - segment
   - tags
   - debt flag
   - inactivity days
4. Chỉ khi cần mới gọi OpenClaw/LLM để tạo:
   - summary
   - next actions
   - wording tự nhiên
5. Trả JSON về UI.

### Rule-first, AI-second
- Segment, score, alert cơ bản nên tính bằng code/rule.
- AI chỉ nên làm phần:
  - tóm tắt ngắn
  - suy luận hành động đề xuất
  - soạn câu chữ

### Ví dụ rule đơn giản
```text
if total_spent > 1000000000 => segment = VIP
if total_debt > 300000000 => add tag debt_watch
if days_since_last_purchase > 30 => add alert inactive
```

## 5. Mức triển khai ít đụng CRM nhất
- Giữ CRM gần như nguyên.
- Chỉ thêm 1 box UI gọi API ngoài.
- API ngoài có thể do CI3 gọi sang n8n hoặc service riêng.
- Nếu không muốn sửa nhiều UI, có thể inject box bằng userscript/browser extension nội bộ.
```