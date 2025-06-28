<!DOCTYPE html>
<html lang="en">

<head>
    <title>@lang('pdf_invoice_label') - {{ $invoice->invoice_number }}</title>
    <style>
        body {
            box-sizing: border-box;
            font-size: .6rem;
            font-family: "Lucida Console", "Courier New", monospace;
        }

        .td,
        .tr,
        .th {
            font-size: .5rem;
        }

        .border {
            border-collapse: collapse;
        }

        .border,
        .border th,
        .border td {
            padding: .25rem;
            border: 1px solid #000;
        }
    </style>
</head>

<body>
    <table style="width: 100%">
        <tr>
            <td style="width: 50%; border: double 1px #000">
                @include('app.pdf.invoice.partials.single')
            </td>

            <td style="width: 50%; border: double 1px #000">
                @include('app.pdf.invoice.partials.single')
            </td>
        </tr>
    </table>
</body>

</html>
