<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        @import url(//fonts.googleapis.com/earlyaccess/notosansbengali.css);
        @import url(//fonts.googleapis.com/css?family=Oxygen+Mono);

        * {
            margin: 0;
            border: 0;
            padding: 0;
            font-family: 'Noto Sans Bengali', sans-serif;
        }

        .barcode {
            width: 4in;
            font-size: 11px;
            overflow: hidden;
        }

        .barcode-col {
            /* margin: 0.1in 0; */
            width: 1.35in;
            height: 0.8in;
            position: relative;
        }

        .barcode-left {
            float: left;
            /* margin-left: 5px; */
            height: 50mm;
            width: 50mm;
        }

        .box_stiker {
            /* height: 35mm; */
            margin-bottom: 5px;
            padding-left: 3mm;
        }

        .box_header {
            height: 6mm;
        }

        .box_middle {
            display: flex;

        }

        .box_size {
            font-size: 20px;
            font-weight: bold;
            width: 24.33%;
            padding-left: 7px;
        }

        .box_qr_code {
            width: 33.33%;
        }

        .box_price {
            width: 33.33%;
        }

        .box_barcode_top {
            display: flex;
        }

        .box_barcode_top_category {
            width: 50%
        }

        .box_barcode_top_color {
            width: 50%;
            text-align: right;
            padding-right: 3mm
        }

        .shoe_stiker {
            height: 16mm;
        }

        .shoe_logo {
            width: 15%;
        }

        .shoe_body {
            width: 44mm;
            float: right;
        }

        .shoe_header {
            display: flex;
            margin-bottom: -7px;
        }

        .shoe_size {
            font-size: 12px;
            font-weight: bold;
            width: 40%
        }

        .shoe_qrcode {
            width: 15%
        }

        .shoe_price {
            width: 45%
        }

        .shoe_barcode_top {
            display: flex;
        }

        .shoe_barcode_top_category {
            width: 50%;
            text-align: left;
            font-size: 9px;
        }

        .shoe_barcode_top_color {
            width: 50%;
            text-align: right;
            padding-right: 3mm;

        }

        .shoe_footer {
            margin-top: -5px;
            text-align: center;
        }


        .barcode-right {
            float: right;
            height: 50mm;
            width: 50mm;
        }

        .barcode-col canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 1.35in;
            height: 0.65in;
        }

        .barcode-col p {
            /* position: absolute; */
        }

        .company-name {
            top: 0;
            left: 0.08in;
            font-weight: bold;
        }

        .shoe-id {
            top: 0;
            right: 0.08in;
            font-weight: bold;
            font-family: 'Oxygen Mono', monospace;
        }

        .shoe-description {
            bottom: 0;
            right: 0.08in;
            text-align: right;
        }

        .print-button {
            background: green;
            text-align: center;
            text: white;
            color: white;
            padding: 13px 42px 15px;
            margin-left: 98px;
            top: 63px;
            margin-top: 12px;
            border-radius: 6px;
            font-size: -12px !important;

        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>

    <button class="print-button" style=" cursor: pointer;"> 🖨️ Print</button>
    @foreach ($entries as $entry)
        <div class="barcode">
            @for ($i = 0; $i < $entry->count; $i++)
                <div class="barcode-col {{ $i % 2 == 0 ? 'barcode-left' : 'barcode-right' }}">
                    <div class="box_stiker">
                        <div class="box_header">
                            @if (isset($memo))
                                <div class="box_memo_no"><span style="font-weight: bolder">M-{{ @$memo }}</span>
                                </div>
                            @endif
                            <div class="box_logo"></div>
                        </div>
                        <div class="box_middle">
                            <div class="box_size">{{ $entry->size['size_name'] ?? '' }}</div>
                             <div class="box_qr_code"> <img
                                    src="data:image/png;base64,{{ DNS2D::getBarcodePNG($entry->shoe->code, 'QRCODE') }}"
                                    alt="barcode" style="height:6mm; width:6mm"></div>
                            <div class="box_price"><span>MRP</span><b
                                    style="font-size:17px">{{ $entry->shoe->retail_price }}</b></div>
                        </div>

                        <div class="box_bercode">
                            <div class="box_barcode_top">
                                <div class="box_barcode_top_category">
                                    {{ $entry->shoe->category->full_name }}
                                </div>
                                <div class="box_barcode_top_color">{{ $entry->shoe->color->name }}</div>
                            </div>

                             <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($entry->shoe->code, 'C39') }}"
                                alt="barcode" style="width: 95%;height: 8mm"><br>

                             <div class="footer" style="text-align: center;margin-top: -7px; font-size:20px;">
                                 <p class="shoe-id">{{ $entry->shoe->code }}</p>
                             </div>
                        </div>
                    </div>

                    <div class="shoe_stiker">
                        <div class="shoe_logo"></div>
                        <div class="shoe_body">
                            <div class="shoe_header">
                                <div class="shoe_size"><span
                                        style="font-size: 17px">{{ $entry->size['size_name'] ?? '' }}</span>
                                    @if (isset($memo))
                                        <span>M-{{ @$memo }}</span>
                                    @endif
                                </div>
                                 <div class="shoe_qrcode">
                                    <img src="data:image/png;base64,{{ DNS2D::getBarcodePNG($entry->shoe->code, 'QRCODE') }}"
                                        alt="barcode" style="height:5mm;">
                                </div>
                                <div class="shoe_price">MRP <b
                                        style="font-size: 17px;">{{ $entry->shoe->retail_price }}</b></div>
                            </div>
                            <div class="shoe_barcode_top">
                                <div class="shoe_barcode_top_category"> {{ $entry->shoe->category->full_name }}</div>
                                <div class="shoe_barcode_top_color">{{ $entry->shoe->color->name }}</div>

                            </div>
                            <div class="shoe_barcode">
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($entry->shoe->code, 'C39') }}"
                                    alt="barcode" style="width: 95%;height: 5mm"><br>
                            </div>
                            <div class="shoe_footer">
                                <p class="shoe-id" style="font-size: 9px">{{ $entry->shoe->code }}</p>
                            </div>

                        </div>


                    </div>
                </div>
            @endfor
        </div>
    @endforeach
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jsbarcode/3.5.8/barcodes/JsBarcode.code128.min.js">
    </script>
    <script>
        $('.print-button').on('click', function() {
            window.print();
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.barcode-col canvas').each(function() {
                var shoeid = $(this).attr('data-id');
                var id = $(this).attr('id');
                JsBarcode('#' + id, shoeid, {
                    format: 'CODE128',
                    height: 12,
                    width: 2,
                    displayValue: false
                });
            });
        });
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
