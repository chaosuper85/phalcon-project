<?php   namespace Services\DataService;

use PageViews;
use Phalcon\Mvc\User\Component;
use Library\Log\Logger;

/***
 * Class PVService
 * 页面访问统计
 * 迁移： haibo 未完成
 */
class PVService extends Component
{

    public function savePVInfo( $page_url, $ip, $platform, $version )
    {
        $pageView = new PageViews();
        $pageView->page_url = $page_url;
        $pageView->ip = $ip;
        $pageView->platform = $platform;
        $pageView->version = $version;
        $ret = $pageView->save();

        Logger::info('savePvInfo '.$pageView->getMessage());
        return  $ret;
    }

    public function queryPVUVInfo( $begin_time='', $end_time='', $platform='', $version='', $search_type='' )
    {
        if( empty($search_type) || $search_type == 'all' )
        {
            $pvInfo =  $this->queryPVInfo( $begin_time, $end_time, $platform, $version );

            $uvInfo =  $this->queryUVInfo( $begin_time, $end_time, $platform, $version );

        }else if( $search_type == 'pv' )
        {
            $pvInfo =  $this->queryPVInfo( $begin_time, $end_time, $platform, $version );
        }else if( $search_type == 'uv' )
        {
            $uvInfo =  $this->queryUVInfo( $begin_time, $end_time, $platform, $version );
        }

        $result = array(
            'begin_time' => $begin_time,
            'end_time' => $end_time,
            'platform' => $platform,
            'version' => $version,
            'search_type' => $search_type,

            'data' => array(
                'pv'=> empty($pvInfo)?array():$pvInfo,
                'uv'=> empty($uvInfo)?array():$uvInfo,
            ),

        );

        return $result;
    }

    public function queryPVInfo( $begin_time, $end_time, $platform, $version )
    {

        $sql = "SELECT page_url, COUNT(page_url) as count, platform, version  FROM page_views WHERE TRUE ";

        if( !empty($begin_time) ) {
            $sql .= " AND created_at > ?1 ";
            $paramArr[] = $begin_time;
        }

        if( !empty($end_time) ) {
            $sql .= " AND created_at < ?2 ";
            $paramArr[] = $end_time;
        }

        if( !empty($platform) ) {
            $sql .= " AND platform = ?3 ";
            $paramArr[] = $platform;
        }

        if( !empty($version) ) {
            $sql .= " AND version = ?4 ";
            $paramArr[] = $version;
        }

        $sql .= " GROUP BY page_url, platform, version ORDER BY count DESC ";

        $res = $this->db->fetchAll($sql);

        if($res) {
            //修改查询的结果来优化管理后台字段显示。枚举值->文字
            $hashArr = $this->constant->PLATFORM_ENUM;
            foreach($res as &$v) {
                if( isset($hashArr[$v['platform']]))
                    $v['platform'] = $hashArr[$v['platform']];
            }
        }

        return $res;
    }

    public function queryUVInfo( $begin_time, $end_time, $platform, $version )
    {

        $sql = "SELECT b.page_url,COUNT(ip) AS count , b.platform, b.version FROM( SELECT DISTINCT(ip), page_url, platform, version  FROM page_views WHERE TRUE ";

        if( !empty($begin_time) )
        {
            $sql .= " AND created_at > '$begin_time' ";

        }

        if( !empty($end_time) )
        {
            $sql .= " AND created_at < '$end_time' ";
        }

        if( !empty($platform) )
        {
            $sql .= " AND platform = '$platform' ";
        }

        if( !empty($version) )
        {
            $sql .= " AND version = '$version' ";
        }

        $sql .= " GROUP BY page_url, ip, platform, version) b GROUP BY page_url, platform, version ORDER BY count DESC ";

        $res = $this->db->fetchAll($sql);
        //修改查询的结果来优化管理后台字段显示。枚举值->文字
        $hashArr = $this->constant->PLATFORM_ENUM;
        foreach($res as $k=>&$v) {
            $v['platform'] = $hashArr[$v['platform']];
        }

        return $res;
    }

}