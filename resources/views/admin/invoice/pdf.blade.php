<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ optional($photoSession->payment)->order_id ?? $photoSession->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { margin-bottom: 24px; }
        .header h1 { margin: 0 0 4px 0; font-size: 20px; }
        .header .muted { color: #666; font-size: 11px; }
        .invoice-title { text-align: right; }
        .invoice-title h3 { margin: 0 0 4px 0; font-size: 14px; color: #666; text-transform: uppercase; }
        .invoice-title .number { font-weight: bold; }
        hr { border: none; border-top: 1px solid #ddd; margin: 20px 0; }
        .row { margin-bottom: 16px; }
        .col-6 { width: 48%; display: inline-block; vertical-align: top; }
        .col-6.right { text-align: right; }
        .label { font-size: 10px; text-transform: uppercase; color: #666; margin-bottom: 4px; }
        table.items { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table.items th, table.items td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        table.items th { background: #f5f5f5; font-size: 10px; text-transform: uppercase; }
        table.items td.amount, table.items th.amount { text-align: right; }
        table.items tfoot td { font-weight: bold; }
        .footer { margin-top: 32px; padding-top: 16px; border-top: 1px solid #ddd; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    <h1>YASHA SNAP</h1>
                    <p class="muted">Photobooth Service</p>
                </td>
                <td class="invoice-title">
                    <h3>Invoice</h3>
                    <p class="number">#{{ optional($photoSession->payment)->order_id ?? 'INV-' . $photoSession->id }}</p>
                    <p class="muted">{{ $photoSession->created_at->format('d M Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <hr>

    <div class="row">
        <table width="100%">
            <tr>
                <td width="50%">
                    <div class="label">Session</div>
                    <p style="margin: 0;">Session #{{ $photoSession->id }}</p>
                    <p style="margin: 0;">{{ $photoSession->machine->name ?? '-' }}</p>
                    <p style="margin: 0; color: #666;">{{ $photoSession->template->name ?? '-' }}</p>
                    <p style="margin: 0; color: #666;">{{ $photoSession->created_at->format('d M Y H:i') }}</p>
                </td>
                <td width="50%" style="text-align: right;">
                    <div class="label">Payment</div>
                    <p style="margin: 0;">Order ID: {{ optional($photoSession->payment)->order_id ?? '-' }}</p>
                    <p style="margin: 0;">Status: {{ strtoupper(optional($photoSession->payment)->transaction_status ?? 'N/A') }}</p>
                    @if (optional($photoSession->payment)->paid_at)
                        <p style="margin: 0; color: #666;">Paid: {{ $photoSession->payment->paid_at->format('d M Y H:i') }}</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>
                    <strong>Photobooth Session</strong><br>
                    <span style="font-size: 10px; color: #666;">
                        Machine: {{ $photoSession->machine->name ?? '-' }}, Template: {{ $photoSession->template->name ?? '-' }}
                        @if ($photoSession->machine && $photoSession->machine->paperSize)
                            · {{ $photoSession->machine->paperSize->name }}
                        @endif
                    </span>
                </td>
                <td class="amount">Rp {{ number_format(optional($photoSession->payment)->amount ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right;"><strong>Total</strong></td>
                <td class="amount"><strong>Rp {{ number_format(optional($photoSession->payment)->amount ?? 0, 0, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p style="margin: 0;">Thank you for your order.</p>
        <p style="margin: 0;">This is a computer-generated invoice. No signature required.</p>
    </div>
</body>
</html>
