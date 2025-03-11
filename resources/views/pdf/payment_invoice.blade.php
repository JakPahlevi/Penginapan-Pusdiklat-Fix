    <style>
        @page {
            size: Legal;
            /* Change to 'letter' for US Letter size */
            margin: 20mm;
            /* Adjust margins as needed */
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            /* Adjust width for A4 */
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #333;
        }

        h2 {
            font-size: 1.5em;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        p {
            margin: 5px 0;
            color: #000000;
        }

        .logo {
            width: 100px;
            height: auto;
            float: right;
        }

        .header {
            overflow: auto;
            margin-bottom: 20px;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info div {
            width: 48%;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            color: #000000;

        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 1.2em;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
    </style>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <img src="https://via.placeholder.com/100" alt="Logo Perusahaan" class="logo">
            <p>No. Reservation: {{ $payment->reservation->code }}</p>
            <p>Tanggal: {{ $payment->payment_date->format('d M Y') }}</p>
        </div>

        <hr>

        <div class="info">
            <div class="info-item">
                <h2>From:</h2>
                <p>{{ $payment->reservation->room->boardingHouse->name }}</p>
                <p>Jl. Ir H. Juanda, Cemp. Putih, Kec. Ciputat, Kota Tangerang Selatan, Banten 15412</p>
                <p>081234567890 | pusdiklatstay@gmail.com</p>
            </div>
            <div class="info-item">
                <h2>To:</h2>
                <p>{{ $payment->reservation->customer->name }}</p>
                <p>Telepon: {{ $payment->reservation->customer->phone_number }}</p>
                <p>Email: {{ $payment->reservation->customer->email }}</p>
            </div>
        </div>

        <h2>Reservation Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Duration</th>
                    <th>Room Type</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->reservation->check_in_date->format('d M Y') }}</td>
                    <td>{{ $payment->reservation->check_out_date->format('d M Y') }}</td>
                    <td>{{ $payment->reservation->duration }} Malam</td>
                    <td>{{ $payment->reservation->room->room_type }}</td>
                    <td>Rp {{ number_format($payment->reservation->room->price_per_day, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <h2>Payment Details</h2>
        <table>
            <thead>
                <tr>
                    <th>Payment Status</th>
                    <th>Payment Date</th>
                    <th>Payment Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->payment_status }}</td>
                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            <p>Total: Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
        </div>

        <div class="footer">
            <p>Terima kasih telah memilih kami.</p>
            <p>Silakan hubungi kami jika ada pertanyaan.</p>
        </div>
    </div>
