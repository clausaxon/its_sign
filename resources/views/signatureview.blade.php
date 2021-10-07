<!DOCTYPE html>
<html>
<head>
	<title>Search Signature</title>
</head>
<body>

	<style type="text/css">
		.pagination li{
			float: left;
			list-style-type: none;
			margin:5px;
		}
	</style>

	<h3>Cari Tanda Tangan:</h3> </br>
	<form action="/signatureview/cari" method="GET">
		<input type="text" name="cari" placeholder="Masukan Kode" value="{{ old('cari') }}">
		<input type="submit" value="CARI">
	</form>
		
	<br/>

	<table border="1">
		<tr>
            <th>Kode</th>
			<th>Nama</th>
			<th>Perihal</th>
			<th>Lihat Detail</th>
			<th>Ubah Perihal</th>
		</tr>
		@foreach($signature as $s)
		<tr>
            <td>{{ $s->sigcode}}</td>
			<td>{{ $s->name }}</td>
			<td>{{ $s->perihal }}</td>
			<td><a href="{{ url('/signature/'. $s->id) }}" class="btn btn-success">Detail</a></td>
            <td><a href="{{ url('/ubahperihal/'. $s->id) }}" class="btn btn-success">Ubah Perihal</a></td>
		</tr>
		@endforeach
	</table>

	<br/>
	Halaman : {{ $signature->currentPage() }} <br/>
	Jumlah Data : {{ $signature->total() }} <br/>
	Data Per Halaman : {{ $signature->perPage() }} <br/>


	{{ $signature->links() }}


</body>
</html>
