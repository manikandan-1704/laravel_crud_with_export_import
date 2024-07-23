@extends('layouts.app')

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <h3>Item Details</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">Name:</label>
                        <p id="name" class="form-control-plaintext">{{ $item->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="description" class="font-weight-bold">Description:</label>
                        <p id="description" class="form-control-plaintext">{{ $item->description }}</p>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <a href="{{ route('items.index') }}" class="btn btn-secondary">Back to List</a>
                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning">Edit Item</a>
            </div>
        </div>
    </div>
@endsection
