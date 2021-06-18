<?php

namespace App\Http\Controllers\Yhpc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YhpcController extends Controller
{

//    获取配置类型
    public function getPcType(){
        try {
            $data = DB::table('peizhi_type')->get();
            return ['data'=>$data,'status'=>200,'message'=>'获取分类成功！'];
        }catch (\Exception $e){
            return ['data'=>$e,'status'=>201,'message'=>'获取分类失败！'];
        }
    }

//    上传图片
    public function upload(Request $request){
//        获取上传的文件
        $imge = $request->file('file');
        try {
            //        判断上传的文件是否存在
            if($imge){
//              存在的话执行以下操作
//              获取原文件名
                $name = $imge->getClientOriginalName();
//              获取文件的扩展名
                $extendname = $imge->getClientOriginalExtension();
//              获取文件的类型
                $type = $imge->getClientMimeType();
//              获取文件的绝对路径
                $path = $imge->getRealPath();
//              建立保存的文件名 时间+扩展名的组合
                $filename = date('Y-m-d').'/'.uniqid().'.'.$extendname;
//              保存文件
//              disk(文件存放的路径 即在disk配置中的tileImg数组)
//              Storage的put方法来保存文件 put('保存的文件名','上传文件的绝对路径客户端的绝对路径')
                $bool = \Storage::disk('pcImg')->put($filename,file_get_contents($path));
//              上传成功后直接返回保存时的文件名和 后端的图片的访问路径+文件名
                return [
                    'data'=>['tmp_path'=>$filename,'url'=>'http://localhost/apiToken/laravel-airlock-sample/storage/app/public/pcImg/'.$filename],
                    'status'=>200,
                    'message'=>'上传成功！'
                ];
            }else{
                return [
                    'status'=>201,
                    'message'=>'上传失败'
                ];
            }
        }catch (\Exception $e){
            return [
                'status'=>201,
                'message'=>'上传出错'
            ];
        }
    }

//    删除图片
    public function delImg(Request $request){
        try {
//            使用 Storage::delete()方法删除文件 里面传文件的后端相对路径 成功返回 true 失败返回false
//            默认会在app/目录下开始移除 所以传进来的的路径是public/tileImg 从public开始就可以了
//            判断传进来的的博客id是否存在 存在就执行 不存在返回错误提示
//            has() 判断传入的值是否存在 如果前端有传入博客id说明需要进行博客的修改操作
//            删除后端文件夹中的图片 传入文件的后端相对路径即可
            \Storage::delete($request->input('filename'));
//            删除成功
            return ['status'=>200,'message'=>'移除成功'];
        }catch (\Exception $e){
//            移除失败
            return ['status'=>201,'message'=>'移除失败'];
        }
    }

//    新增配置
    public function addPeiZhi(Request $request){
        try {
//            进行新增操作 使用模型的 insert 方法进行插入操作 插入成功会返回新增的数据
            $data = DB::table('peizhi')->insert($request->all());
//            返回数据
            return ['data'=>$data,'status'=>200,'message'=>'发布博客成功！'];
        }catch (\Exception $e){
            //           发布失败 返回失败提示
            return ['status'=>201,'message'=>'发布失败'];
        }
    }

    //    新增电脑硬件
    public function addHardWare(Request $request){
        try {
//            进行新增操作 使用模型的 insert 方法进行插入操作 插入成功会返回新增的数据
            $data = DB::table('hardware')->insert($request->all());
//            返回数据
            return ['data'=>$data,'status'=>200,'message'=>'添加硬件成功！'];
        }catch (\Exception $e){
            //           发布失败 返回失败提示
            return ['status'=>201,'message'=>'添加失败'];
        }
    }
}
