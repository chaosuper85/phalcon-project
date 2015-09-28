<?php   namespace Services\DataService;

use Library\Log\Logger;
use \Phalcon\DiInterface;
use TbTickets;
use Library\Helper\QueryHelper;

use Phalcon\Mvc\User\Component;

/**
 *  工单申述
 *  auth haibo
 */
class TicketService extends  Component
{


    public function tickets(&$ret)
    {

        //ORM-筛选、排序
        $options['columns'] = 'id,sender_id,target_id,ticket_status,ticket_result,ticket_result_info';
        $ret = QueryHelper::cond('\TbTickets', $this->request, $options);

        //code TO 中文
        QueryHelper::codeToWord($data,'');

        //返回分页信息

        Logger::info('tickets sum:'.$ret['data_sum']);
        return $ret['data_sum'];
    }

    public function create($senderId,int $type ,$title,$content,$attachUrl){

        $ticket             =   new TbTickets();
        $ticket->sender_id   = $senderId;
        $ticket->ticket_type = $type;
        $ticket->ticket_title = $title;
        $ticket->ticket_text_content = $content;
        $ticket->ticket_attach_content_url = $attachUrl;
        $ticket->ticket_status             = 0 ; //todo
    }



}