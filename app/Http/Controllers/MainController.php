<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;
use App\iv;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\Session;
use Datatables;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function data_sj()
    {
        $data = iv::select('id','invoice','doaii','terima_finance','user')->groupBy('doaii');        
        return Datatables::of($data)
        ->addColumn('action', function ($data) {
                return '<a class="btn btn-warning btn-xs" href="edit_sj/'.$data->id.'">Edit</a>
                <a class="btn btn-danger btn-xs" href="delete_sj/'.$data->id.'">Del</a>
                ';
            })
        ->make();
    }  
    public function data_report()
    {
        $data = iv::select('invoice',DB::raw('COUNT(terima_finance) as terima_finance_count'),DB::raw('COUNT(doaii) as do_aii_count'))->groupBy('invoice')->havingRaw('count(terima_finance)=count(doaii)');      
        return Datatables::of($data)        
        ->make();
    }   
    public function data_report_pending()
    {
        $data = iv::select('invoice',DB::raw('COUNT(terima_finance) as terima_finance_count'),DB::raw('COUNT(doaii) as do_aii_count'))->groupBy('invoice')->havingRaw('count(terima_finance)!=count(doaii)');      
        return Datatables::of($data)        
        ->make();
    }     
    public function index()
    {
        return redirect('/dashboard');
    }       
    public function dashboard()
    {
        return view('dashboard');
    }    
    public function upload_iv_dashboard()
    {
        return view('upload_iv');
    }
    public function upload_iv_dashboard_store()
    {        
        if(Input::hasFile('iv')){
            $path = Input::file('iv')->getRealPath();
            $data = Excel::load($path)->get();
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $insert[] = 
                    [
                    'invoice' => $value->invoice,
                    'doaii' => $value->doaii,                    
                    ];
                }
                $insert=array_filter($insert, function($value) { return !is_null($value['doaii']) && $value['doaii'] !== ''; });
                if(!empty($insert)){
                    foreach($insert as $row) {
                    if($row['invoice']!=null){
                    iv::create($row);                    
                    }
                    }
                    $total_upload="Sukses Scan SJ, Total Upload=".count($insert)." Invoice";
                    Session::flash('message', $total_upload); 
                }else{
                    Session::flash('danger', 'Gagal Upload SJ');
                }
            }
        }
        Session::flash('danger', 'Something Wrong Contact Administrator'); 
        return redirect('/dashboard');
    }    
    public function terima_finance()
    {
        return view('terima_finance');
    }    
    public function update_fin_upload()
    {
        if(Input::hasFile('update_fin_upload')){
            $path = Input::file('update_fin_upload')->getRealPath();
            $data = Excel::load($path)->get();
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $cek=sj::where('doaii',$value->doaii)->whereNotNull('doaii')->get();
                    if($cek->toArray()!=null){
                    $insert[] = 
                    [
                    'doaii' => $value->doaii,
                    ];
                    }else{
                    $error[] = 
                    [
                    'doaii' => $value->doaii,
                    ];  
                    }                    
                } 
                $insert=array_filter($insert, function($value) { return !is_null($value['doaii']) && $value['doaii'] !== ''; });     
                $no=0;
                $noo=0;
                if(!empty($insert)){                    
                    foreach($insert as $row) {
                    $cek=iv::where('doaii',$row)->first();
                    if($cek['terima_finance']===null){                    
                    $no++;
                    $sukses_upload[] = 
                    [
                    'doaii' => $cek['doaii'],
                    ];
                    iv::where('doaii',$row)->update(['terima_finance' =>\Carbon\Carbon::now()]);  
                    $total_upload="Sukses Scan SJ, Total Upload=".$no." SJ";
                    Session::flash('message', $total_upload);    
                    }else{
                        $terima_finance[] = 
                            [
                            'doaii' => $cek['doaii'],
                            ];
                        $noo++;
                        Session::flash('danger', 'Gagal Upload ' .$noo. ' SJ Sudah Kirim Finance');  
                    }                                                            
                } 
                }else{
                    Session::flash('danger', 'Gagal Upload SJ');
                }
            }
        }else{
        Session::flash('danger', 'Something Wrong Contact Administrator'); 
        }
        if(!empty($error)&&!empty($terima_finance)&&!empty($sukses_upload)){
        Excel::create('SJ Error', function($excel) use($error,$terima_finance,$sukses_upload) {
            $excel->sheet('SJ Tidak Ada Di Master', function($sheet) use($error) {
                $sheet->fromArray($error);
            });
            $excel->sheet('SJ Sudah Terima Finance', function($sheet) use($terima_finance) {
                $sheet->fromArray($terima_finance);
            });
            $excel->sheet('SJ Sukses Upload', function($sheet) use($sukses_upload) {
                $sheet->fromArray($sukses_upload);
            });

        })->export('xlsx');
        }elseif(!empty($error)&&!empty($sukses_upload)){
            Excel::create('SJ Error', function($excel) use($error,$sukses_upload) {
                $excel->sheet('SJ Tidak Ada Di Master', function($sheet) use($error) {
                    $sheet->fromArray($error);
                });
                $excel->sheet('SJ Sukses Upload', function($sheet) use($sukses_upload) {
                    $sheet->fromArray($sukses_upload);
                });
    
            })->export('xlsx');
            }
            elseif(!empty($terima_finance)&&!empty($sukses_upload)){
                Excel::create('SJ Error', function($excel) use($terima_finance,$sukses_upload) {
                    $excel->sheet('SJ Sudah Terima Finance', function($sheet) use($terima_finance,$sukses_upload) {
                        $sheet->fromArray($terima_finance);
                    });
                    $excel->sheet('SJ Sukses Upload', function($sheet) use($sukses_upload) {
                        $sheet->fromArray($sukses_upload);
                    });
        
                })->export('xlsx');
                }
        elseif(!empty($error)&&!empty($terima_finance)){
            Excel::create('SJ Error', function($excel) use($error,$terima_finance) {
                $excel->sheet('SJ Tidak Ada Di Master', function($sheet) use($error,$terima_finance) {
                    $sheet->fromArray($error);
                });
                $excel->sheet('SJ Sudah Terima Finance', function($sheet) use($terima_finance) {
                    $sheet->fromArray($terima_finance);
                });
    
            })->export('xlsx');
            }
        elseif(!empty($error)){
            Excel::create('SJ Error', function($excel) use($error) {
                $excel->sheet('SJ Tidak Ada Di Master', function($sheet) use($error) {
                    $sheet->fromArray($error);
                });
    
            })->export('xlsx'); 
        }elseif(!empty($terima_finance)){
            Excel::create('SJ Error', function($excel) use($terima_finance) {
                $excel->sheet('SJ Sudah Terima Finance', function($sheet) use($terima_finance) {
                    $sheet->fromArray($terima_finance);
                });
    
            })->export('xlsx'); 
        }
        return redirect('/sj/dashboard');
    }

    public function terima_finance_store()
    {        
        $data=iv::where('doaii', request()->doaii)->first();        
            if(!empty($data)){
                if($data->terima_finance==null){
                    iv::where('doaii', request()->doaii)
             ->update(['terima_finance' =>\Carbon\Carbon::now(),'user'=>Auth::user()->name]);
             Session::flash('message', 'Sukses Simpan Nomor Invoice = '.$data->invoice); 
             return redirect('/terima_finance')->with(['success' => 'Berhasil']);
                }else{
                    Session::flash('danger', 'Gagal Simpan Nomor Invoice dengan DOAII '.$data->doaii.' sudah pernah di SCAN'); 
             return redirect('/terima_finance');
                }             
            }else{  
            Session::flash('danger', 'Something Wrong Contact Administrator');                
             return redirect('/terima_finance');
            }        
    }    
    public function report()
    {
         return view('report');
    }
    public function report_pending()
    {
         return view('report_pending');
    }
}
