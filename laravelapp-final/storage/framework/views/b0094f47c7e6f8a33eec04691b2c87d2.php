<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Festigo Ticket</title>

    <style>

        @page {
            margin: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            padding: 0;
            margin: 0;
            color: #111827;
            background: #f3f4f6;
        }

        .ticket {
            border: 2px solid #111827;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 25px;
            background: #ffffff;
        }

        .banner {
            width: 100%;
            height: 220px;
        }

        .content {
            padding: 25px;
        }

        .event-name {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #111827;
        }

        .event-location {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 25px;
        }

        .section {
            margin-bottom: 20px;
        }

        .label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .value {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 14px;
            word-break: break-word;
        }

        .row {
            width: 100%;
        }

        .col {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }

        .qr-section {
            margin-top: 30px;
            text-align: center;
        }

        .qr-section img {
            width: 140px;
            height: 140px;
        }

        .ticket-code {
            margin-top: 10px;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .ticket-number {
            margin-top: 6px;
            font-size: 12px;
            color: #6b7280;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
        }

        .page-break {
            page-break-after: always;
        }

    </style>

</head>

<body>

<?php $__currentLoopData = $booking->bookingTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

<div class="ticket">

    <?php
        $banner = $booking->touristAttraction->featured_image ?? null;
    ?>

    <?php if($banner): ?>

        <img
            class="banner"
            src="<?php echo e(asset('storage/' . $banner)); ?>">

    <?php endif; ?>

    <div class="content">

        <div class="event-name">
            <?php echo e($booking->touristAttraction->name); ?>

        </div>

        <div class="event-location">
            <?php echo e($booking->touristAttraction->location); ?>

        </div>

        <div class="row">

            <div class="col">

                <div class="section">

                    <div class="label">
                        Attendee
                    </div>

                    <div class="value">
                        <?php echo e($booking->user?->name); ?>

                    </div>

                    <div class="label">
                        Ticket Type
                    </div>

                    <div class="value">
                        <?php echo e($booking->ticket?->name); ?>

                    </div>

                </div>

            </div>

            <div class="col">

                <div class="section">

                    <div class="label">
                        Order ID
                    </div>

                    <div class="value">
                        <?php echo e($booking->order_id); ?>

                    </div>

                    <div class="label">
                        Event Date
                    </div>

                    <div class="value">

                        <?php if($booking->ticket?->valid_date): ?>

                            <?php echo e(\Carbon\Carbon::parse($booking->ticket->valid_date)->format('d F Y')); ?>


                        <?php else: ?>

                            <?php echo e(\Carbon\Carbon::parse($booking->touristAttraction->start_date)->format('d F Y')); ?>

                            -
                            <?php echo e(\Carbon\Carbon::parse($booking->touristAttraction->end_date)->format('d F Y')); ?>


                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

        <div class="qr-section">

            <img
                src="data:image/svg+xml;base64,<?php echo e($ticket->qr_code); ?>">

            <div class="ticket-code">
                <?php echo e($ticket->ticket_code); ?>

            </div>

            <div class="ticket-number">
                Ticket <?php echo e($index + 1); ?>

                /
                <?php echo e($booking->bookingTickets->count()); ?>

            </div>

        </div>

        <div class="footer">
            FESTIGO DIGITAL EVENT PASS
        </div>

    </div>

</div>

<?php if(!$loop->last): ?>
    <div class="page-break"></div>
<?php endif; ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html><?php /**PATH /home/skezdyug/laravelapp/resources/views/tickets/ticket-pdf.blade.php ENDPATH**/ ?>