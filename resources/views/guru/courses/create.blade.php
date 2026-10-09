@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Tambah Kelas</h4>

                <form action="{{ route('guru.courses.store') }}" method="POST">
                    @csrf
                    @include('guru.courses._form')

                    <button class="btn btn-primary">Simpan</button>
                    <a href="{{ route('guru.courses.index') }}" class="btn btn-link">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection