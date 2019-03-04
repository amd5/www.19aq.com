<?php
namespace app\admin\model;

use think\Model;

class Article extends Model
{
	public static function list($page, $limit, $title=false){

		$where = $title ? "a.title like '%$title%'" : '';
		$result = self::alias('a')->page($page)->join('sort s', 's.sid = a.sort', 'left')
		->field('a.*,s.sortname')
		->where($where)
		->limit($limit)
		->order('id desc')
		->cache(true,8640000)
		->select();

		$count = self::cache(true,8640000)->count();
		foreach ($result as $key => $value) {
			$value['id_count'] = $count;
			$value['addtime'] = date('Y-m-d H:i:s', $value['addtime'] ?: time());
		}

		return $result;
	}

	# web=false 后台文章  web=true 前台文章
	public static function details($id ,$web=false){
		//每次被访问增加阅读1
		self::where('id',$id)->setInc('view');
		$result = self::where('id',$id)->cache(true,8640000)->find();
		if ($web) {
			$result = self::where('id',$id)->where('password','')->cache(true,8640000)->find();
		}
        $result['tagname'] = false;
        #查询文章所属分类
        if (isset($result['sort'])) {
            $res = Sort::where('sid',$result['sort'])->cache(true,8640000)->find();
            if (isset($res['sortname'])) {
                $result['sorts'] = $res['sortname'];
            }
        }
        #查询文章所有标签
        if (isset($result['id'])) {
            $taga = Taga::where('gid',$result['id'])->cache(true,8640000)->select();
            if (count($taga) > 0) {
                foreach ($taga as $key => $value) {
                    $tag = Tag::where('tid',$value['tid'])->cache(true,8640000)->find();
                    $tags[] = $tag['tagname']; 
                }
                $result['tagname'] = $tags;
            }
        }
		return $result;
	}


	public static function nian(){   
        return self::order('days','desc')->field('FROM_UNIXTIME(addtime,"%Y") as days,COUNT(*) as COUNT')->GROUP('days')->cache(true,8640000)->select();
    }

    public static function yue(){   
        return self::order('days','desc')->field('FROM_UNIXTIME(addtime,"%Y-%m") as days,COUNT(*) as COUNT')->GROUP('days')->cache(true,8640000)->select();
    }

    #前台-首页文章列表
    public static function lists(){
        // $result = Cache('indexlists');
        // if (!$result) {
            if (session('id')) {
                $result = self::order('id desc')->limit(15)->cache(true,8640000)->paginate();
            }else{
                $result = self::where('password','')->order('id desc')->limit(15)->cache(true,8640000)->paginate();
            }
            // Cache::set('indexlists',$result,2592000);
        // }
        // dump(Cache('1'));
    	
    	$result = self::gongyong($result);
    	return $result;
    }
    #前台-标签页文章列表
    public static function taglists($id){
    	$tagname = Tag::where('tagname',$id)->cache(true,8640000)->find();
    	if (count($tagname) == 0 ) {return;}
    	$taga = Taga::where('tid',$tagname['tid'])->cache(true,8640000)->select();
    	if (count($taga) == 0 ) {return;}
    	#处理整合标签为where in格式
    	$data = '';
    	foreach ($taga as $key => $value) {
    		$data .= $value['gid'].',';
    	}
    	$data = trim($data,",");
    	#根据ID 查询文章
    	$result = self::where('id','in',$data)->order('id desc')->limit(15)->cache(true,8640000)->paginate();
    	if (!session('id')) {
    		$result = self::where('id','in',$data)->where('password','')->order('id desc')->limit(15)->cache(true,8640000)->paginate();
    	}

    	$result = self::gongyong($result);

    	return $result;
    }
    #前台-分类页文章列表
    public static function sortlists($id){
        $sid = Sort::where('sortname',$id)->cache('sid'.$id,2592000)->find();
        $result = self::where('sort',$sid['sid'])->order('id desc')->limit(15)->cache(true,8640000)->paginate();
        if (!session('id')) {
            $result = self::where('sort',$sid['sid'])->where('password','')->order('id desc')->limit(15)->cache(true,8640000)->paginate();
        }
        $result = self::gongyong($result);
        return $result;
    }
    #前台-归档页文章列表
    public static function recordlists($id){
        //开始时间
        $nian = substr($id, 0, 4);  $yue  = substr($id, 4, 5);
        $sj   = $nian.'-'.$yue.'-'.'01'.' '.'00:00:00';
        $stsj = strtotime($sj);
        //结束时间
        $mdays = date( 't', strtotime($sj) );
        $end_time = date( 'Y-m-' . $mdays . ' 23:59:59', strtotime($sj));
        $endsj = strtotime($end_time);

        $result = self::where('addtime','>=',$stsj)->where('addtime','<=',$endsj)->limit(15)->cache(true,8640000)->paginate();
        if (!session('id')) {
            $result = self::where('addtime','>=',$stsj)->where('addtime','<=',$endsj)->where('password','')->limit(15)->cache(true,8640000)->paginate();
        }

        $result = self::gongyong($result);
        return $result;
    }

    #前台-搜索页文章列表
    public static function searchlist($id){
        $id      = trim($id," ");
        $result = self::where("title LIKE :id ", ['id' => '%'.$id.'%'])
        ->cache(true,8640000)
        ->paginate(15);
        foreach ($result as $key => $value) {
            $lycount = Comment::where('pid',$value['id'])->cache(true,8640000)->count();
            $value['lycount'] = $lycount;
        }
        return $result;
    }

    #公共方法
    public static function gongyong($result){
    	if (!$result) {return ;}
    	foreach ($result as $k => $v) {
            # 修改时间戳
            $v['addtime'] = date('Y-m-d H:i:s', $v['addtime'] ?: time());

			$res = Taga::where('gid',$v['id'])->cache(true,8640000)->select();
			$data = [];
			if ($res) {
				foreach ($res as $kk => $vv) {
    				$tag = Tag::where('tid',$vv['tid'])->field('tagname')->cache(true,8640000)->find();
    				array_push($data, $tag['tagname']);
    			}
			}
			$v['tag'] = $data;
            $lycount = Comment::where('pid',$v['id'])->cache(true,8640000)->count();
            $v['lycount'] = $lycount;
		}
		return $result;
    }

}
