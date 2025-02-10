@extends('layouts.main')

@section('content-header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>{{ $title }}</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dahsboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <form action="" method="GET">
                    <div class="card-body">

                        <div class="form-group row">
                            <span class="col-sm-2 col-form-label">Pilih Tanggal Akad</span>
                            <div class="col-sm-6">
                                <input type="date" class="form-control" name="ibu_kandung" id="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <span class="col-sm-2 col-form-label"></span>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
