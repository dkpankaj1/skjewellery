<div style="min-height: 138.5mm;">
    <table width="100%">
        <tr>
            <td width="50%">
                @if ($logo)
                    <img class="header-logo" style="height:30px" src="{{ $logo }}" alt="Company Logo">
                @else
                    @if ($invoice->customer->company)
                        <h2 class="header-logo">{{ $invoice->customer->company->name }}</h2>
                    @endif
                @endif
            </td>
            <td style="text-align: right;">
                <b>GSTIN No: </b>{{ trim('09AHWPV1069K2ZB') }}
            </td>
        </tr>
    </table>

    <center>
        <small style="font-size: 0.7rem;font-weight:bold">
            {!! trim($company_address) !!}
        </small>
    </center>

    <table style="width: 100%;border-bottom:solid 1px;border-top:solid 1px;margin-top:1mm">
        <tr>
            <td>
                <b>@lang('pdf_invoice_number') : </b> {{ $invoice->invoice_number }} <br />
            </td>
            <td style="text-align:right;">
                <b>@lang('pdf_invoice_date') :</b> {{ $invoice->formattedInvoiceDate }}
            </td>
        </tr>
    </table>

    <table style="width: 100%;border-bottom:solid 1px;font-size:0.6rem">
        <tr>
            <td>

                @if ($billing_address)
                    <b>@lang('pdf_bill_to')</b> <br>

                    {!! $billing_address !!}
                @endif

            </td>
        </tr>

    </table>

    <table class="border" style="width: 100%">

        <tr style="background-color:#3d3c3c;color:#fff;border:solid 1px #000">
            <th>#</th>
            <th>@lang('pdf_items_label')</th>

            <th>@lang('pdf_quantity_label')</th>
            <th>@lang('pdf_price_label')</th>
            @if ($invoice->discount_per_item === 'YES')
                <th>@lang('pdf_discount_label')</th>
            @endif
            @if ($invoice->tax_per_item === 'YES')
                <th>@lang('pdf_tax_label')</th>
            @endif
            <th>@lang('pdf_amount_label')</th>
        </tr>


        @php
            $index = 1;
            $space = 35;
        @endphp
        @foreach ($invoice->items as $item)
            <tr>
                <td>
                    {{ $index }}
                </td>
                <td>
                    <span>{{ $item->name }}</span><br>
                    <span>{!! nl2br(htmlspecialchars($item->description)) !!}</span>
                </td>
                <td>
                    {{ $item->quantity }} @if ($item->unit_name)
                        {{ $item->unit_name }}
                    @endif
                </td>
                <td>
                    {!! format_money_pdf($item->price, $invoice->customer->currency) !!}
                </td>

                @if ($invoice->discount_per_item === 'YES')
                    <td>
                        @if ($item->discount_type === 'fixed')
                            {!! format_money_pdf($item->discount_val, $invoice->customer->currency) !!}
                        @endif
                        @if ($item->discount_type === 'percentage')
                            {{ $item->discount }}%
                        @endif
                    </td>
                @endif

                @if ($invoice->tax_per_item === 'YES')
                    <td>
                        {!! format_money_pdf($item->tax, $invoice->customer->currency) !!}
                    </td>
                @endif

                <td>
                    {!! format_money_pdf($item->total, $invoice->customer->currency) !!}
                </td>
            </tr>
            @php
                $index += 1;
                $space -= 7;
            @endphp
        @endforeach


        <tr>
            <td>
                <div style="height: {{ $space }}mm"></div>
            </td>
            <td></td>
            <td></td>
            @if ($invoice->discount_per_item === 'YES')
                <td></td>
            @endif
            @if ($invoice->tax_per_item === 'YES')
                <td></td>
            @endif
            <td></td>
            <td></td>
        </tr>



        <tr>
            <td colspan="4" style="text-align:right;"><b>@lang('pdf_subtotal')</b></td>
            <td colspan="1"><b>{!! format_money_pdf($invoice->sub_total, $invoice->customer->currency) !!}</b></td>
        </tr>

        @if ($invoice->discount > 0)
            @if ($invoice->discount_per_item === 'NO')
                <tr>
                    <td colspan="4" style="text-align:right;">
                        <b>
                            @if ($invoice->discount_type === 'fixed')
                                @lang('pdf_discount_label')
                            @endif
                            @if ($invoice->discount_type === 'percentage')
                                @lang('pdf_discount_label') ({{ $invoice->discount }}%)
                            @endif
                        </b>
                    </td>
                    <td colspan="1">
                        <b>
                            @if ($invoice->discount_type === 'fixed')
                                {!! format_money_pdf($invoice->discount_val, $invoice->customer->currency) !!}
                            @endif
                            @if ($invoice->discount_type === 'percentage')
                                {!! format_money_pdf($invoice->discount_val, $invoice->customer->currency) !!}
                            @endif
                        </b>
                    </td>
                </tr>
            @endif
        @endif


        @if ($invoice->tax_per_item === 'YES')
            @foreach ($taxes as $tax)
                <tr>
                    <td colspan="4" style="text-align:right;">
                        <b> {{ $tax->name . ' (' . $tax->percent . '%)' }}</b>
                    </td>
                    <td colspan="1"><b>{!! format_money_pdf($tax->amount, $invoice->customer->currency) !!}</b></td>
                </tr>
            @endforeach
        @else
            @foreach ($invoice->taxes as $tax)
                <tr>
                    <td colspan="4" style="text-align:right;"><b>{{ $tax->name . ' (' . $tax->percent . '%)' }}</b>
                    </td>
                    <td colspan="1"><b>{!! format_money_pdf($tax->amount, $invoice->customer->currency) !!}</b></td>
                </tr>
            @endforeach
        @endif


        <tr>
            <td colspan="4" style="text-align:right;"><b> @lang('pdf_total')</b></td>
            <td colspan="1"><b>{!! format_money_pdf($invoice->total, $invoice->customer->currency) !!}</b></td>
        </tr>
        {{-- <tr>
            <td colspan="4" style="text-align:right;"><b>Paid Amount (₹)</b></td>
            <td colspan="1"><b>350</b></td>
        </tr> --}}
        <tr>
            <td colspan="5"><b>Due Amount : {!! format_money_pdf($invoice->due_amount, $invoice->customer->currency) !!}</b></td>
        </tr>
        <tr>
            <td colspan="5"> <b>@lang('pdf_invoice_due_date')</b> : {{ $invoice->formattedDueDate }}</td>
        </tr>
    </table>

    <div>
        <table style="width: 100%;">
            <tr>
                <td style="text-align:right;padding: 1.25rem 0.25rem">
                    <span>Authorized Signature __________________</span>
                </td>
            </tr>
        </table>
        @if ($notes)
            <table class="border" style="width: 100%;">
                <tr>
                    <td>
                        <small style="padding:0.5mm">
                            <div class="notes-label">
                                <b>@lang('pdf_notes') : </b>
                            </div>
                            {!! $notes !!}
                        </small>
                    </td>
                </tr>
            </table>
        @endif
    </div>
</div>
