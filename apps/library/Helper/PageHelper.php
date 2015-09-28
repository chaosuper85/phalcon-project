<?php

namespace Library\Helper;

/**
 *  分页
 */

class PageHelper
{
    private     $pageNo     ; // 当前 页码
    private     $pageSize   ; // 每页 显示 记录数
    private     $startRow   ; // 当前开始 第几条 数据
    private     $data       ; // 分页显示的 数据
    private     $totalRows  ; // 总的 记录条数
    private     $totalPages ; // 总的页数


    /**
     *
     */
     public function __construct( $pageNo = 1, $pageSize = 10 ){
         $this->pageNo   = empty( $pageNo  ) ? 1 : $pageNo  ;
         $this->pageSize = empty( $pageSize) ? 10: $pageSize;
         $this->getStartRow();
     }

    public function  getStartRow(){
        if( $this->pageNo <= 0){
            $this->startRow = 0;
        }else{
            $this->startRow = ($this->pageNo -1) * $this->pageSize ;
        }
        return $this->startRow;
    }

    public function setTotalRows( $totalRows ){
        $this->totalRows  = $totalRows;
        $remains = ($totalRows % $this->pageSize) ;
        $this->totalPages = ($totalRows - $remains)/$this->pageSize + ( $remains >0 ? 1:0 );
        return $this;
    }

    public function setData( $data ){
        $this->data = $data;
        return $this;
    }

    public function getData(  ){
        return $this->data;
    }

    public function getPageSize(){
        return $this->pageSize;
    }

    public function  toArray(){
        return array(
            "page_no"    => $this->pageNo,
            "page_size"   => $this->pageSize,
            //"startRow"  => $this->startRow,
            "data"      => $this->data,
            "data_sum" => $this->totalRows,
            "page_sum"=>$this->totalPages,
        );
    }





}