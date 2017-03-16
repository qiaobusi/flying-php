<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Web\BaseController;
use Illuminate\Http\Request;
use DB;


class GoodevilController extends BaseController
{
    //文章类型
    private $typeGood = 1;
    private $typeEvil = 2;

    //文章状态
    private $stateShow = 1;
    private $stateHide = 2;
    private $stateDefault = 1;

    //每页数量
    private $perPage = 10;

    //扬善分页
    public function getGood(Request $request)
    {
        $all = $request->all();

        $last_id = isset($all['last_id']) ? $all['last_id'] : 0;
        $perPage = isset($all['perPage']) ? $all['perPage'] : $this->perPage;

        $articles = DB::table('articles')
            ->select('id', 'title', 'created_at')
            ->where('type', $this->typeGood)
            ->where('state', $this->stateShow);

        if ($last_id != 0) {
            $articles = $articles->where('id', '<=', $last_id);
        }

        $articles = $articles->orderBy('id', 'desc')
            ->take($perPage)
            ->get();

        $array = [
            'status' => 1001,
            'data' => $articles,
            'info' => '获取数据成功',
        ];

        return response()->json($array);
    }

    //惩恶分页
    public function getEvil(Request $request)
    {
        $all = $request->all();

        $last_id = isset($all['last_id']) ? $all['last_id'] : 0;
        $perPage = isset($all['perPage']) ? $all['perPage'] : $this->perPage;

        $articles = DB::table('articles')
            ->select('id', 'title', 'created_at')
            ->where('type', $this->typeEvil)
            ->where('state', $this->stateShow);

        if ($last_id != 0) {
            $articles = $articles->where('id', '<=', $last_id);
        }

        $articles = $articles->orderBy('id', 'desc')
            ->take($perPage)
            ->get();

        $array = [
            'status' => 1001,
            'data' => $articles,
            'info' => '获取数据成功',
        ];

        return response()->json($array);
    }

    //写入文章
    public function insertArticle(Request $request)
    {
        $all = $request->all();

        $data = [
            'type' => $all['type'],
            'user_id' => $all['user_id'],
            'tag' => $all['tag'],
            'title' => $all['title'],
            'content' => $all['content'],
            'state' => $this->stateDefault,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $result = DB::table('articles')
            ->insert($data);

        if ($result) {
            $array = [
                'status' => 1001,
                'data' => null,
                'info' => '发布成功',
            ];

            return response()->json($array);
        } else {
            $array = [
                'status' => 1000,
                'data' => null,
                'info' => '发布失败',
            ];

            return response()->json($array);
        }
    }

    


}

?>