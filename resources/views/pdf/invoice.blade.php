@extends('layouts.pdf')

@section('content')
    <header class="clearfix">
        <div id="logo">
            {{ Html::image('img/Full-LOGO-1.png', '') }}
        </div>
        <h1>INVOICE {{ $invoice->id }}</h1>
        <div id="company" class="clearfix">
            <div>GOFOREX</div>
            <div>87 Bulwer Rd <br/>Glenwood, Durban, 4001</div>
            <div>+27 71-920-0123</div>
            <div><a href="mailto:accounts@goforex.co.za">accounts@goforex.co.za</a></div>
        </div>
        <div id="project">
            <div><span>CLIENT</span> {{ Auth::user()->firstname .' '.Auth::user()->lastname }}</div>
            <div><span>MOBILE</span> {{ Auth::user()->cell }}</div>
            <div><span>LOCATION</span> {{ Auth::user()->location }}</div>
            <div><span>EMAIL</span> <a href="mailto:{{ Auth::user()->email }}">{{ Auth::user()->email }}</a></div>
            <div><span>DATE</span> {{ $invoice->created_at->format('F d, Y') }}</div>
            <div><span>DATE PAID</span> {{ $invoice->created_at->format('F d, Y') }}</div>
            <div><span>STATUS</span> {{ $invoice->status_is }}</div>
        </div>
    </header>

    <div id="main">
        <table>
            <thead>
            <tr>
                <th class="service">SERVICE</th>
                <th class="desc">DESCRIPTION</th>
                <th>PRICE</th>
                <th>QTY</th>
                <th>TOTAL</th>
            </tr>
            </thead>
            <tbody>

            @foreach($invoice->items as $item)
                <tr>
                    <td class="service">{{ $item->name }}</td>
                    <td class="desc">{{ $item->description }}</td>
                    <td class="unit">R{{ number_format($item->pivot->price,2,'.',',') }}</td>
                    <td class="qty">{{ $item->pivot->quantity }}</td>
                    <td class="total">R{{ number_format($item->pivot->quantity * $item->pivot->price,2,'.',',') }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="4">SUBTOTAL</td>
                <td class="total">R{{ number_format($invoice->amount,2,'.',',') }}</td>
            </tr>
            <tr>
                <td colspan="4">VAT 14%</td>
                <td class="total">R{{ number_format(0,2,'.',',') }}</td>
            </tr>
            <tr>
                <td colspan="4" class="grand total">GRAND TOTAL</td>
                <td class="grand total">R{{ number_format($invoice->amount,2,'.',',') }}</td>
            </tr>
            </tbody>
        </table>
        <div id="notices">
            <div>NOTICE:</div>
            <div class="notice">An unpaid Invoice will be removed from the system after 14 days.<br><br>
                Payment should be made via direct deposit or EFT to:
                <div id="project">
                    Please check booking confirmation email for corresponding banking details.
                </div>
            </div>
        </div>
    </div>

    <footer>
        Invoice was created on a computer and is valid without company the signature and stamp.
    </footer>
@endsection

@section('styles')

    <style>
        #project {
            float: left;
        }
        #project span {
            color: #D2AC67;
            text-align: right;
            width: 52px;
            margin-right: 10px;
            display: inline-block;
            font-size: 0.8em;
        }
        #company {
            float: right;
            text-align: right;
        }
        #project div,
        #company div {
            white-space: nowrap;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
        }
        table tr:nth-child(2n-1) td {
            background: #f1d2b6;
        }
        table th,
        table td {
            text-align: center;
        }
        table th {
            padding: 5px 20px;
            color: #D2AC67;
            border-bottom: 1px solid #D2AC67;
            white-space: nowrap;
            font-weight: normal;
        }
        table .service,
        table .desc {
            text-align: left;
        }
        table td {
            padding: 20px;
            text-align: right;
        }
        table td.service,
        table td.desc {
            vertical-align: top;
        }
        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }
        table td.grand {
            border-top: 1px solid #D2AC67;;
        }
        #notices .notice {
            color: #5D6975;
            font-size: 1.2em;
        }
                .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
@stop