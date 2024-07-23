@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Items</h3>
        <a href="{{ route('items.create') }}" class="btn btn-primary float-right">Create Item</a>
        <a href="{{ route('export') }}" class="btn btn-success float-right mr-2">Export Items</a>
        <button id="importBtn" class="btn btn-warning float-right mr-2">Import Items</button>
        <form id="importForm" action="{{ route('import') }}" method="POST" enctype="multipart/form-data" style="display:none;">
            @csrf
            <input type="file" name="file" id="fileInput" accept=".csv, .xlsx" required>
        </form>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if ($items->isEmpty())
        <p class="text-center">No items found.</p>
        @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>
                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/custom.js') }}"></script>
@endsection