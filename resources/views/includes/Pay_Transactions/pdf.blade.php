<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pay Transactions PDF</title>
    <!-- Add any necessary styles here -->
</head>
<body>
    <h1>Pay Transactions Report</h1>
    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr style="height: 60px;">
                <th style="border: 1px solid black;">Payer ID</th>
                <th style="border: 1px solid black;">Payer Name</th>
                <th style="border: 1px solid black;">Payee ID</th>
                <th style="border: 1px solid black;">Payment Mode</th>
                <th style="border: 1px solid black;">Payment Date</th>
                <th style="border: 1px solid black;">Amount (DH)</th>
                <th style="border: 1px solid black;">Transaction ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paytransactions as $paytransaction)
                <tr style="height: 60px;">
                    <td style="border: 1px solid black;">{{ $paytransaction->payer_id }}</td>
                    <td style="border: 1px solid black;">{{ $paytransaction->payer_name }}</td>
                    <td style="border: 1px solid black;">{{ $paytransaction->payee_id }}</td>
                    <td style="border: 1px solid black;">{{ $paytransaction->payment_mode }}</td>
                    <td style="border: 1px solid black;">{{ \Carbon\Carbon::parse($paytransaction->pay_date)->format('d-m-Y') }}</td>
                    <td style="border: 1px solid black;">{{ $paytransaction->amount }}</td>
                    <td style="border: 1px solid black;">{{ $paytransaction->transaction_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
