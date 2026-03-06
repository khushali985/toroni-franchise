<h2>Selected Reservations</h2>

<table border="1" width="100%" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Date</th>
        <th>Status</th>
        <th>Amount</th>
    </tr>

    @foreach($reservations as $r)
    <tr>
        <td>{{ $r->id }}</td>
        <td>{{ $r->full_name }}</td>
        <td>{{ $r->phone_no }}</td>
        <td>{{ $r->date }} {{ $r->time }}</td>
        <td>{{ ucfirst($r->status) }}</td>
        <td> {{ $r->amount }}</td>
    </tr>
    @endforeach
</table>