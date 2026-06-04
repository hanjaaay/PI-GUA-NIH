@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card border-0 shadow rounded-4">

                <div class="card-body p-4">

                    <h1 class="fw-bold text-center mb-4">
                        QR Ticket Scanner
                    </h1>

                    <div id="reader"></div>

                    <div class="alert alert-info mt-4">

                        Point the camera to the ticket QR code.

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>

function onScanSuccess(decodedText, decodedResult) {

    if (window.festigoScanningLocked) {
        return;
    }

    window.festigoScanningLocked = true;

    try {

        // QR format:
        // ORDER_ID|TICKET_CODE

        const qrParts = decodedText.split('|');

        if (qrParts.length < 2) {

            alert('Invalid QR ticket format.');

            window.festigoScanningLocked = false;

            return;
        }

        const ticketCode = qrParts[1];

        const form = document.createElement('form');

        form.method = 'POST';

        form.action =
            '/checkin/' +
            encodeURIComponent(ticketCode);

        const token =
            document.createElement('input');

        token.type = 'hidden';

        token.name = '_token';

        token.value = '{{ csrf_token() }}';

        form.appendChild(token);

        document.body.appendChild(form);

        form.submit();

    } catch (e) {

        console.error(e);

        alert('QR processing failed.');

        window.festigoScanningLocked = false;
    }
}

const html5QrcodeScanner =
    new Html5QrcodeScanner(

        "reader",

        {
            fps: 10,
            qrbox: 250
        }
    );

html5QrcodeScanner.render(
    onScanSuccess
);

</script>

@endsection
