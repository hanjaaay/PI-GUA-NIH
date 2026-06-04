<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket - {{ $booking->order_id }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .ticket-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 3px solid #28a745;
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        /* Hapus background SVG inline karena tidak kompatibel dengan Dompdf */
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            position: relative;
            z-index: 1;
        }
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 30px;
        }
        .ticket-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #28a745;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6c757d;
            font-weight: 500;
            flex: 1;
        }
        .info-value {
            color: #212529;
            font-weight: 600;
            text-align: right;
            flex: 1;
        }
        .attraction-details {
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            position: relative;
        }
        .attraction-details::before {
            content: '🎫';
            position: absolute;
            top: -15px;
            left: 20px;
            background: white;
            padding: 0 10px;
            font-size: 20px;
        }
        .attraction-title {
            font-size: 20px;
            font-weight: bold;
            color: #495057;
            margin-bottom: 10px;
            margin-top: 10px;
        }
        .attraction-location {
            color: #6c757d;
            margin-bottom: 15px;
            font-size: 14px;
        }
        .ticket-details {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        .ticket-detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .ticket-detail-row:last-child {
            margin-bottom: 0;
            font-weight: bold;
            font-size: 16px;
            color: #28a745;
        }
        .qr-section {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .qr-code {
            width: 150px;
            height: 150px;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        .qr-text {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        .ticket-number {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #495057;
            font-weight: bold;
        }
        .important-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .important-notice h4 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .important-notice ul {
            margin: 0;
            padding-left: 20px;
            color: #856404;
            font-size: 12px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            border-top: 1px solid #e9ecef;
        }
        .validity-badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .perforated-line {
            border-top: 2px dashed #dee2e6;
            margin: 20px 0;
            position: relative;
        }
        .perforated-line::before {
            content: '✂️';
            position: absolute;
            left: 50%;
            top: -8px;
            transform: translateX(-50%);
            background: white;
            padding: 0 5px;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Header -->
        <div class="header">
            <h1>E-TICKET</h1>
            <p>Festigo - Your Travel Companion</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Ticket Information -->
            <div class="ticket-info">
                <div class="info-row">
                    <span class="info-label">Ticket Number:</span>
                    <span class="info-value">{{ $booking->order_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Booking Date:</span>
                    <span class="info-value">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Customer:</span>
                    <span class="info-value">{{ $booking->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="validity-badge">{{ ucfirst($booking->status) }}</span>
                    </span>
                </div>
            </div>

            <!-- Attraction Details -->
            <div class="attraction-details">
                <div class="attraction-title">{{ $booking->touristAttraction->name }}</div>
                <div class="attraction-location">📍 {{ $booking->touristAttraction->location }}</div>
                
                <div class="ticket-details">
                    <div class="ticket-detail-row">
                        <span>Ticket Type:</span>
                        <span>{{ $booking->ticket->name ?? 'General Admission' }}</span>
                    </div>
                    <div class="ticket-detail-row">
                        <span>Visit Date:</span>
                        <span>{{ $booking->visit_date->format('d M Y') }}</span>
                    </div>
                    <div class="ticket-detail-row">
                        <span>Quantity:</span>
                        <span>{{ $booking->quantity }} ticket(s)</span>
                    </div>
                    <div class="ticket-detail-row">
                        <span>Total Amount:</span>
                        <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="qr-text">Present this QR code at the entrance</div>
                <div class="qr-code">
                    <div style="text-align: center;">
                        <div style="font-size: 12px; color: #6c757d;">QR Code</div>
                        <div class="ticket-number">{{ $booking->order_id }}</div>
                    </div>
                </div>
                <div style="font-size: 10px; color: #6c757d;">
                    Valid for entry on {{ $booking->visit_date->format('d M Y') }}
                </div>
            </div>

            <!-- Important Notice -->
            <div class="important-notice">
                <h4>⚠️ Important Notice:</h4>
                <ul>
                    <li>This ticket is valid only for the date specified above</li>
                    <li>Please arrive on time for your visit</li>
                    <li>Keep this ticket safe and present it at the entrance</li>
                    <li>No refunds for unused tickets</li>
                    <li>Contact customer service for any issues</li>
                </ul>
            </div>

            <!-- Perforated Line -->
            <div class="perforated-line"></div>

            <!-- Terms and Conditions -->
            <div style="font-size: 10px; color: #6c757d; text-align: center; margin-top: 15px;">
                <p>By using this ticket, you agree to the terms and conditions of Festigo.</p>
                <p>This is an electronic ticket - no physical ticket required.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for choosing Festigo!</strong></p>
            <p>Have a wonderful experience at {{ $booking->touristAttraction->name }}!</p>
            <p>Generated on {{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>
</body>
</html>
