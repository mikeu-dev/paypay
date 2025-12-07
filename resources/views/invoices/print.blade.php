<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->number }}</title>
    <style>
        body { font-family: sans-serif; padding: 40px; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 40px; }
        .logo { font-size: 24px; font-weight: bold; color: {{ $primaryColor }}; }
        .invoice-details { text-align: right; }
        .client-info { margin-bottom: 40px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border-bottom: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f9f9f9; }
        .totals { text-align: right; }
        .totals p { margin: 5px 0; }
        .total-amount { font-size: 18px; font-weight: bold; color: {{ $primaryColor }}; }
        .notes { margin-top: 40px; font-size: 14px; color: #666; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">{{ $settings->site_name ?? 'PayPay ERP' }}</div>
        <div class="invoice-details">
            <h1>INVOICE</h1>
            <p>#{{ $invoice->number }}</p>
            <p>Date: {{ $invoice->date->format('d M Y') }}</p>
            <p>Due: {{ $invoice->due_date->format('d M Y') }}</p>
        </div>
    </div>

    <div class="client-info">
        <strong>Bill To:</strong><br>
        {{ $invoice->client->name }}<br>
        {{ $invoice->client->email }}<br>
        {{ $invoice->client->phone }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: center">Qty</th>
                <th style="text-align: right">Price</th>
                <th style="text-align: right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->description }}</td>
                <td style="text-align: center">{{ $item->qty }}</td>
                <td style="text-align: right">{{ number_format($item->unit_price, 0) }}</td>
                <td style="text-align: right">{{ number_format($item->amount, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p>Subtotal: {{ number_format($invoice->subtotal, 0) }}</p>
        <p>Tax ({{ $invoice->tax_rate }}%): {{ number_format($invoice->tax_amount, 0) }}</p>
        <p class="total-amount">Total: {{ $invoice->currency }} {{ number_format($invoice->total, 0) }}</p>
    </div>

    @if($invoice->notes)
    <div class="notes">
        <strong>Notes:</strong><br>
        {{ $invoice->notes }}
    </div>
    @endif

    <script>
        window.print();
    </script>
</body>
</html>
