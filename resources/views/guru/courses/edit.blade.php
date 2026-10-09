@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Edit Kelas</h4>

                <form action="{{ route('guru.courses.update', $course) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('guru.courses._form')

                    <button class="btn btn-primary">Perbarui</button>
                    <a href="{{ route('guru.courses.show', $course) }}" class="btn btn-link">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection