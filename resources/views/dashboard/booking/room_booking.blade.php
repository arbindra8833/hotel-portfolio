@extends('layouts.dashboard')

@section('content')
    <div class="db-cent-3">
        <div class="db-cent-table db-com-table">
            <div class="db-title">
                <h3><img src="{{ asset('front/images/icon/dbc5.png') }}" alt="" /> My Room Bookings</h3>
                <p>View all of your hotel room bookings here.</p>
            </div>
            <div class="db-title">
                @foreach ($errors->all() as $error)
                    <p style="color:red">{{ $error }}</p>
                @endforeach

                @if (Session::has('flash_message'))
                    <p style="color:forestgreen">{{ Session::get('flash_title') }}, {{ Session::get('flash_message') }}</p>
                @endif
                @if (Session::has('qr'))
                    {{ Session::get('qr') }}
                    <button id="downloadSVG">Download</button>
                @endif

            </div>
            <table class="bordered responsive-table">
                <thead>
                    <tr>
                        <th>Room No</th>
                        <th>Type</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($room_bookings as $index => $room_booking)
                        <tr>
                            <td>{{ $room_booking->room->room_number }}</td>
                            <td>{{ $room_booking->room->room_type->name }}</td>
                            <td>{{ $room_booking->arrival_date }}</td>
                            <td>{{ $room_booking->departure_date }}</td>
                            <td>Rs. {{ $room_booking->room_cost }}</td>
                            <td>
                                @if ($room_booking->status == 'pending')
                                    <span class="label label-default">Pending</span>
                                @elseif($room_booking->status == 'checked_in')
                                    <span class="label label-primary">Checked In</span>
                                @elseif($room_booking->status == 'checked_out')
                                    <span class="label label-success">Checked Out</span>
                                @else
                                    <span class="label label-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                @if ($room_booking->payment == true)
                                    <span class="db-success">Paid</span>
                                @else
                                    <span class="db-not-success">Not Paid</span>
                                @endif

                            </td>
                            <td>
                                @if ($room_booking->status !== 'pending')
                                    <a href="{{ url('dashboard/room/booking/' . $room_booking->id . '/review') }}"><span
                                            class="label label-primary">Review</span></a>
                                @endif
                                @if ($room_booking->status !== 'cancelled')
                                    <a href="{{ url('dashboard/room/booking/' . $room_booking->id . '/cancel') }}"><span
                                            class="label label-danger">Cancel</span></a>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td>Sorry, no bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $room_bookings->links() }}
    </div>
@endsection
@section('scripts')
    <script>
        function downloadSVGAsText() {
            const svg = document.querySelector('svg');
            const base64doc = btoa(unescape(encodeURIComponent(svg.outerHTML)));
            const a = document.createElement('a');
            const e = new MouseEvent('click');
            a.download = 'download.svg';
            a.href = 'data:image/svg+xml;base64,' + base64doc;
            a.dispatchEvent(e);
        }

        function downloadSVGAsPNG(e) {
            const canvas = document.createElement("canvas");
            const svg = document.querySelector('svg');
            const base64doc = btoa(unescape(encodeURIComponent(svg.outerHTML)));
            const w = parseInt(svg.getAttribute('width'));
            const h = parseInt(svg.getAttribute('height'));
            const img_to_download = document.createElement('img');
            img_to_download.src = 'data:image/svg+xml;base64,' + base64doc;
            console.log(w, h);
            img_to_download.onload = function() {
                console.log('img loaded');
                canvas.setAttribute('width', w);
                canvas.setAttribute('height', h);
                const context = canvas.getContext("2d");
                //context.clearRect(0, 0, w, h);
                context.drawImage(img_to_download, 0, 0, w, h);
                const dataURL = canvas.toDataURL('image/png');
                if (window.navigator.msSaveBlob) {
                    window.navigator.msSaveBlob(canvas.msToBlob(), "download.png");
                    e.preventDefault();
                } else {
                    const a = document.createElement('a');
                    const my_evt = new MouseEvent('click');
                    a.download = 'download.png';
                    a.href = dataURL;
                    a.dispatchEvent(my_evt);
                }
                //canvas.parentNode.removeChild(canvas);
            }
        }

        const downloadPNG = document.querySelector('#downloadSVG');
        downloadPNG.addEventListener('click', downloadSVGAsText);
    </script>
@endsection
