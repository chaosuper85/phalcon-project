<?php  namespace Services\Service;

use Library\Helper\StringHelper;
use Library\Log\Logger;
use Phalcon\Mvc\User\Component;
use PHPExcel;
use PHPExcel_Reader_Excel5;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet_PageSetup;

require_once VENDOR_DIR.'/PHPExcel/Classes/PHPExcel.php';


/**
 * Created by PhpStorm.
 * User: wanghaibo
 * Date: 15/8/24
 * Time: 下午8:20
 */
class ExcelService extends Component
{
    private  $type;
    private  $excel;
    private  $cfg;
    private  $offset = 1 ;

    public static $form_enum = array(
        0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',9=>'J',10=>'K',11=>'L',12=>'M',13=>'N',14=>'O'
    );


    public function __construct()
    {
        $this->cfg = $this->common_config->excel;

        $this->excel = new \PHPExcel();
    }


    /**
     * 功能: 读取excel数据到数组
     * @param string $name
     * @param $out
     * @param array $seek 跳过的行列
     * @return bool
     */
    public function read($name='', $type, &$out, $seek=array())
    {
        //读取文件所需参数
        $seek[0] = isset($seek[0]) ? $seek[0]:0;    //读取起始位置
        $seek[1] = isset($seek[1]) ? $seek[1]:0;
        $form_enum = self::$form_enum;

        $path = $this->config->application->tempDir;    //根目录
        $filePath = $path.$name;
        $reader = new \PHPExcel_Reader_Excel2007();     //版本

        //为了可以读取所有版本
        if(!$reader->canRead($filePath)) {
            $reader = new \PHPExcel_Reader_Excel5();
            if(!$reader->canRead($filePath)) {
                Logger::warn('未发现Excel文件！');
                return false;
            }
        }

        //读取
        $excel = $reader->load($filePath);          //读取Excel文件
        $cur_sheet = $excel->getSheet(0);           //选择第一个工作表

        //获取行数列数
        $wide = $cur_sheet->getHighestColumn();
        $high = $cur_sheet->getHighestRow();
        $wide_int = array_keys($form_enum, $wide);
        if( !isset($wide_int[0])) {
            Logger::warn('excel 太大');
            return false;
        }
        $wide_int = $wide_int[0];

        //EXCEL第一行作为表头
        $form_head = array();
        $x = $form_enum[$seek[0]];
        $y = $seek[1];

        $i = $wide_int;
        $cfg = $this->cfg[$type];
        while($i > $seek[0]-1) {
            $str = trim( $cur_sheet->getCell($form_enum[$i].$y)->getValue());
            $str = isset( $cfg[$str]) ? $cfg[$str] : $str;
            $form_head[$form_enum[$i]] = $str;
            $i--;
        }

        //表格数据to数组。默认编码是utf8
        for($currentRow=($y+1); $currentRow<=$high; $currentRow++)
        {
            for($currentColumn=$x; $currentColumn<=$wide; $currentColumn++)
            {
                if( !isset($form_head[$currentColumn])) {
                    $out = array();
                    Logger::warn('read excel : 文件格式不匹配');
                    return false;
                }
                $col_name = $form_head[$currentColumn];
                $pos = $currentColumn.$currentRow;

                $out[$currentRow-($y+1)][$col_name] = $cur_sheet->getCell($pos)->getValue();
            }
        }


    }

    /**
     * 功能:
     * 备注:
     * @param $in
     * @param int $type 模板类型
     * @return bool
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public function write(&$in, $type='carteam')
    {
        if( empty($in))
            return false;

        $excel = new \PHPExcel();
        $excel->setActiveSheetIndex(0);
        $cur_sheet = $excel->getActiveSheet();

        //表格内容版式
        $x = $this->cfg->$type->seek_x;                     //表格正文起始位置
        $y = $this->cfg->$type->seek_y;
        $high = count( $in)+$y;
        $wide = self::$form_enum[ $x+count($in[0]) ];

        $title   = $this->cfg->$type->form_title;           //增加title、制表人、时间
        $usrname = '管理员';
        $time    = date('Y-m-d H:i:s');
        $cur_sheet->getCell('C1')->setValue($title);
        $cur_sheet->getCell($x.'2')->setValue('制表人：'.$usrname.'-'.$time);

        $i = $x;
        foreach($in[0] as $k=>$v) {                         //插入表格表头
            $form_head[$i] = $k;
            if( !isset($this->cfg->$type->$k)) {
                Logger::warn('导出数据到excel：common_config 缺少模板字段');
                $cur_sheet->setCellValue($i.$y,$k);
                //unset($form_head[$i]);
            }else {
                $cur_sheet->setCellValue($i.$y,$this->cfg->$type->$k);
            }
            $i++;
        }

//        //写数据到EXCEL，默认编码utf8
//        for($cur_row=($y+1); $cur_row<=$high; $cur_row++)   //行
//        {
//            for($cur_col=$x; $cur_col<=$wide; $cur_col++)   //列
//            {
//                $pos = $cur_col.$cur_row;
//
//                $data = $in[$cur_row-$y-1][$form_head[$cur_col]];
//                $cur_sheet->getCell($pos)->setValue($data);
//            }
//        }

        //写数据到EXCEL，默认编码utf8
        for($cur_col=$x; $cur_col<=$wide; $cur_col++)   //列
        {
            if( !isset($form_head[$cur_col]))
                continue;
            for($cur_row=($y+1); $cur_row<=$high; $cur_row++)   //行
            {
                $pos = $cur_col.$cur_row;
                $data = $in[$cur_row-$y-1][$form_head[$cur_col]];
                $cur_sheet->getCell($pos)->setValue($data);
            }
        }

        //表格样式设置
        $cur_sheet->getColumnDimension('A')->setWidth(10);  // 列的宽度
        $cur_sheet->getColumnDimension('B')->setWidth(10);
        $cur_sheet->getColumnDimension('C')->setWidth(20);
        $cur_sheet->getColumnDimension('D')->setWidth(15);
        $cur_sheet->getColumnDimension('E')->setWidth(15);
        $cur_sheet->getHeaderFooter()->setOddHeader('&L&BPersonal cash register&RPrinted on &D');
        $cur_sheet->getHeaderFooter()->setOddFooter('&L&B' . $excel->getProperties()->getTitle() . '&RPage &P of &N');

        $cur_sheet->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);  // 设置页方向和规模
        $cur_sheet->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

        //导出
        if($this->cfg->ver == '2007') { //导出excel2007
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $objWriter->save('php://output');
            exit;
        } else {  //导出excel2003
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$title.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }

    }


    /**
     * 导出EXcle 返回文件的绝对路径
     * @param $header
     * @param $data
     * @return 文件绝对路径
     */
    public  function  exportExcel( $header , $data ){
        $objExcel = new PHPExcel();
        $sheet = $objExcel->setActiveSheetIndex(0);
        if( !empty( $header ) ){
            $this->offset = 1;
            foreach( $header as $colIndex => $value ){
                $pCoordinate = $this->getCellIndex( 0, $colIndex);
                $sheet->setCellValue( $pCoordinate, $value);
            }
            $this->offset = 2;
        }
        if( !empty( $data ) ){
            foreach( $data as $rowIndex => $rowArr ){
                foreach( $rowArr as $colIndex => $value ){
                    $pCoordinate = $this->getCellIndex( $rowIndex, $colIndex);
                    $sheet->setCellValue( $pCoordinate, $value);
                }
            }
        }
        $objExcel->getProperties()->setCreator("北京箱典典科技有限公司")
            ->setLastModifiedBy( StringHelper::strToDate( time() ) )
            ->setTitle("北京箱典典科技有限公司")
            ->setSubject(" 北京箱典典科技有限公司 Office 2007 XLSX ")
            ->setDescription("北京箱典典科技有限公司 ")
            ->setKeywords("北京箱典典科技有限公司");
        $fileName = $this->config->application->tempDir."/".time().rand(100000,999999).".xls";
        $objWriter = PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
        $objWriter->save( $fileName );
        return $fileName;
    }


    /**
     *  获取单元格索引
     */
    private function  getCellIndex( $rowIndex, $columnIndex ){
        $prefix = self::$form_enum[ $columnIndex ];// 0,1,2
        $index  = $prefix.($rowIndex+ $this->offset );
        return $index;
    }

    public function  makeBoxCodeExcel(){
        try{





        }catch (\Exception $e){
            Logger::warn("makeBoxCodeExcel error msg:{%s}",$e->getMessage());
        }

    }


















}