@extends('homepage')
@section('title', 'Pay Transactions View')
@section('content')
<div class="detail-container">
    <div class="elementary">
        <h1>Pay Transactions</h1>
        <a class="add-button" href="{{ route('paytransactionform') }}">
            <button class="add-btn">Add</button>
        </a>
        <div class="sep"></div>
    </div>    
    <div class="table-container">
        <table class="display-table">
            <thead>
                <tr style="height: 50px;">
                    <th>Payer ID</th>
                    <th>Payer Name</th>
                    <th>Payee ID</th>
                    <th>Payment Mode</th>
                    <th>Payment Date</th>
                    <th>Amount (DH)</th>
                    <th>Transaction ID</th>
                    <th style="width: 60px;">Delete</th>
                </tr>
            </thead>
    
            <tbody>
                @if(isset($paytransactions) && count($paytransactions) > 0)
                    @foreach($paytransactions as $paytransaction)
                        <tr style="height: 60px;">
                            <td>{{ $paytransaction->payer_id }}</td>
                            <td>{{ $paytransaction->payer_name }}</td>
                            <td>{{ $paytransaction->payee_id}}</td>
                            <td>{{ $paytransaction->payment_mode}}</td>
                            <td>{{ \Carbon\Carbon::parse($paytransaction->pay_date)->format('d-m-Y') }}</td>
                            <td>{{ $paytransaction->amount}}</td>
                            <td>{{ $paytransaction->transaction_id}}</td>
                            <td>
                                <div class="delete-button">
                                    <form action="{{ route('deletepaytransaction', $paytransaction->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" onclick="return confirm('Are you sure you want to delete this transaction?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach 
                @else
                    <tr>
                        <td colspan="8">No entries available.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="download-container" style="margin-top: 10px; " >
        <a href="{{ route('paytransactionspdf') }}" class="add-btn" style="padding: 8px 12px; text-decoration: none">Download as PDF</a>
    </div>
</div>
@endsection
