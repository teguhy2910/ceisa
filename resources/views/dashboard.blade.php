@extends('layouts.app')
@section('content')
<div class="container-full">
    <div class="row">        
        <div class="col-md-12">
            @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            @if(Session::has('warning'))
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('warning') }}</p>
            @endif
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                <li class="active"><a class=""><big><big><big><font face="calibri">CEISA </font></big></big></big> <span class="label label-warning"></span></a></li>
                </ul>
                <div class="panel-body">
                    <a href="{{asset("/terima_finance")}}" class="btn btn-md btn-success">SCAN FINANCE</a>
                    <br><br>                    
                    <table id="data_all" class="table table-bordered table-condensed table-hover dt-responsive">
                <thead>                 
                <tr class="info">
                <th><small>ID</small></th>
                <th><small>INVOICE</small></th>    
                <th><small>DOAII</small></th>
                <th><small>Scan Finance</small></th>
                <th><small>USER</small></th>
                <th width=10%><small></small></th>

            </tr>
        </thead>                   
            </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
