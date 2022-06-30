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
                <li><a href="report"><big><big><big><font face="calibri">COMPLETE </font></big></big></big> <span class="label label-warning"></span></a></li>
                <li class="active"><a href="report_pending"><big><big><big><font face="calibri">NOT COMPLETE </font></big></big></big> <span class="label label-warning"></span></a></li>
                </ul>
                <div class="panel-body">
                    <a href="{{asset("/terima_finance")}}" class="btn btn-md btn-success">SCAN FINANCE</a>
                    <br><br>                    
                    <table id="sj_per_inv_pending" class="table table-bordered table-condensed table-hover dt-responsive">
                <thead>                 
                <tr class="info">
                <th><small>INVOICE</small></th>  
                <th><small>Jumlah Scan DOAII</small></th>
                <th><small>Jumlah DOAII</small></th>                
            </tr>
        </thead>                   
            </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
