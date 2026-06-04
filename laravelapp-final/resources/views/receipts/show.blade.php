<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $booking->order_id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .receipt-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 16px;
        }
        .info-label {
            color: #6c757d;
            font-weight: 500;
        }
        .info-value {
            color: #212529;
            text-align: right;
        }
        .attraction-details {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .attraction-title {
            font-size: 18px;
            font-weight: bold;
            color: #495057;
            margin-bottom: 10px;
        }
        .attraction-location {
            color: #6c757d;
            margin-bottom: 15px;
        }
        .ticket-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-top: 1px solid #e9ecef;
        }
        .ticket-info {
            flex: 1;
        }
        .ticket-price {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        .total-section {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .total-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background: #d4edda;
            color: #155724;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .qr-code img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <h1>PAYMENT RECEIPT</h1>
            <p>Festigo - Your Travel Companion</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Receipt Information -->
            <div class="receipt-info">
                <div class="info-row">
                    <span class="info-label">Receipt Number:</span>
                    <span class="info-value">{{ $booking->order_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Date:</span>
                    <span class="info-value">{{ $booking->updated_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Customer:</span>
                    <span class="info-value">{{ $booking->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $booking->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-paid">{{ ucfirst($booking->status) }}</span>
                    </span>
                </div>
            </div>

            <!-- Attraction Details -->
            <div class="attraction-details">
                <div class="attraction-title">{{ $booking->touristAttraction->name }}</div>
                <div class="attraction-location">📍 {{ $booking->touristAttraction->location }}</div>
                
                <div class="ticket-details">
                    <div class="ticket-info">
                        <div><strong>Ticket Type:</strong> {{ $booking->ticket->name ?? 'General Admission' }}</div>
                        <div><strong>Visit Date:</strong> {{ $booking->visit_date->format('d M Y') }}</div>
                        <div><strong>Quantity:</strong> {{ $booking->quantity }} ticket(s)</div>
                        @if($booking->notes)
                            <div><strong>Notes:</strong> {{ $booking->notes }}</div>
                        @endif
                    </div>
                    <div class="ticket-price">
                        Rp {{ number_format($booking->ticket->price ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Total Amount -->
            <div class="total-section">
                <div class="total-label">Total Amount Paid</div>
                <div class="total-amount">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
            </div>

            <!-- QR Code for verification -->
            <div class="qr-code">
                <div style="font-size: 12px; color: #6c757d; margin-bottom: 10px;">
                    Scan this QR code for verification
                </div>
                <!-- QR Code will be generated here -->
                <div style="width: 150px; height: 150px; background: #f8f9fa; border: 2px dashed #dee2e6; display: flex; align-items: center; justify-content: center; margin: 0 auto; border-radius: 8px;">
                    <div style="text-align: center; color: #6c757d;">
                        <div style="font-size: 12px;">QR Code</div>
                        <div style="font-size: 10px;">{{ $booking->order_id }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for choosing Festigo!</strong></p>
            <p>This receipt serves as proof of payment for your booking.</p>
            <p>For any inquiries, please contact our customer service.</p>
            <p>Generated on {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>
