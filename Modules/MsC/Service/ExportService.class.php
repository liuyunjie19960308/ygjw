<?php
namespace MsC\Service;

/**
 * Class ExportService
 * @package MsC\Service
 * 导出
 */
class ExportService extends MscBaseService
{

    /**
     * [$fileName 文件名]
     * @var string
     */
    protected $fileName = '未命名';

    /**
     * [$savePath 文件保存路径]
     * @var string
     */
    protected $savePath = '';

    /**
     * 初始化
     */
    public function __construct() {
        //导入核心类库
        vendor('PHPExcel.PHPExcel');
        vendor('PHPExcel.PHPExcel.IOFactory');
        vendor('PHPExcel.PHPExcel.Writer.Excel5');
        //vendor('PHPExcel.PHPExcel.Writer.Excel2007');
        vendor('PHPExcel.PHPExcel.Style.Alignment');
        vendor('PHPExcel.PHPExcel.Style.NumberFormat');
        vendor('PHPExcel.PHPExcel.Worksheet.Drawing');
    }

    /**
     * @param $file_name
     * 设置文件名
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @param $save_path
     * 设置文件保存路径
     */
    public function setSavePath($savePath)
    {
        $this->savePath = $savePath;
    }

    /**
     * @param array $request
     * @return mixed
     * 订单数据导出
     */
    public function orderExport($request = []) {
        //获取PHPExcel对象
        $PHPExcelObj = $this->getPHPExcelInstance();
        //设置文件属性信息
        $PHPExcelObj = $this->setProperties($PHPExcelObj);
        //设置表格样式
        //列宽
        $PHPExcelObj->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('D')->setWidth(40);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('E')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('M')->setWidth(25);
        //行高
        $PHPExcelObj->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
        //字体加粗
        $PHPExcelObj->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
        //垂直方向对齐格式
        $PHPExcelObj->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置数字格式
        $PHPExcelObj->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);
        //设置表格数据
        //标题数据
        if($request['order_type'] == 1) { //返利订单
            $PHPExcelObj->setActiveSheetIndex(0)
                ->setCellValue('A1', '订单号')
                ->setCellValue('B1', '收货人姓名')
                ->setCellValue('C1', '收货人电话')
                ->setCellValue('D1', '收货人地址')
                ->setCellValue('E1', '支付方式')
                ->setCellValue('F1', '商品总额')
                ->setCellValue('G1', '实付款')
                ->setCellValue('H1', '返利积分数')
                ->setCellValue('I1', '订单状态')
                ->setCellValue('J1', '下单时间');
        }
        if($request['order_type'] == 2) { //积分订单
            $PHPExcelObj->setActiveSheetIndex(0)
                ->setCellValue('A1', '订单号')
                ->setCellValue('B1', '收货人姓名')
                ->setCellValue('C1', '收货人电话')
                ->setCellValue('D1', '收货人地址')
                ->setCellValue('E1', '支付方式')
                ->setCellValue('F1', '商品总额')
                ->setCellValue('G1', '运费总额')
                ->setCellValue('H1', '满减优惠')
                ->setCellValue('I1', '实付款')
                ->setCellValue('J1', '店铺名称')
                ->setCellValue('K1', '订单状态')
                ->setCellValue('L1', '下单时间')
                ->setCellValue('M1', '物流单号');
        }


        //主体数据
        $result = D('Bms/OrderInfo', 'Logic')->getList(array_merge($request, ['page_size'=>0]));

        foreach($result['list'] as $key => $value) {
            //行号
            $row_num = $key + 2;
            //状态名称
            $status_name = D('FrontC/OrderInfo', 'Service')->getStatusName($value['status']);
            //设置主体数据
            if($request['order_type'] == 1) { //返利订单
                $PHPExcelObj->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row_num, $value['order_sn'])
                    ->setCellValue('B' . $row_num, $value['contacts'])
                    ->setCellValue('C' . $row_num, $value['mobile'])
                    ->setCellValue('D' . $row_num, $value['province_name'].$value['city_name'].$value['district_name'].$value['address'])
                    ->setCellValue('E' . $row_num, get_payment_name($value['payment']))
                    ->setCellValue('F' . $row_num, '￥' . $value['goods_amounts'])
                    ->setCellValue('G' . $row_num, '￥' . $value['pay_amounts'])
                    ->setCellValue('H' . $row_num, $value['rbt_quota_total'])
                    ->setCellValue('I' . $row_num, $status_name)
                    ->setCellValue('J' . $row_num, timestamp2date($value['create_time']));
            }
            if($request['order_type'] == 2) { //积分订单
                $PHPExcelObj->setActiveSheetIndex(0)
                    ->setCellValue('A' . $row_num, $value['order_sn'])
                    ->setCellValue('B' . $row_num, $value['contacts'])
                    ->setCellValue('C' . $row_num, $value['mobile'])
                    ->setCellValue('D' . $row_num, $value['province_name'].$value['city_name'].$value['district_name'].$value['address'])
                    ->setCellValue('E' . $row_num, get_payment_name($value['payment']))
                    ->setCellValue('F' . $row_num, $value['goods_itg_amounts'])
                    ->setCellValue('G' . $row_num, $value['freight_itg_amounts'])
                    ->setCellValue('H' . $row_num, $value['full2cut_itg_amounts'])
                    ->setCellValue('I' . $row_num, $value['pay_itg_amounts'])
                    ->setCellValue('J' . $row_num, $value['shop_name'])
                    ->setCellValue('K' . $row_num, $status_name)
                    ->setCellValue('L' . $row_num, timestamp2date($value['create_time']))
                    ->setCellValue('M' . $row_num, $value['logistics_number']);
            }
        }

        //设置工作表名称
        $PHPExcelObj->getActiveSheet()->setTitle('订单数据');
        //设置打开表格文件默认的工作表为第一个
        $PHPExcelObj->setActiveSheetIndex(0);

        //保存文件
        $this->saveFile($PHPExcelObj);

        exit;
    }

    /**
     * @param array $request
     * @return mixed
     * 用户数据导出
     */
    public function memberExport1($request = [])
    {
        //获取PHPExcel对象
        $PHPExcelObj = $this->getPHPExcelInstance();
        //设置文件属性信息
        //$PHPExcelObj = $this->setProperties($PHPExcelObj);
        //设置表格样式
        $PHPExcelObj->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('I')->setWidth(30);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        //行高
        $PHPExcelObj->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
        //字体加粗
        $PHPExcelObj->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);
        //垂直方向对齐格式
        $PHPExcelObj->getActiveSheet()->getStyle('A1:R1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置表格数据
        //标题数据
        $PHPExcelObj->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '姓名')
            ->setCellValue('C1', '身份证号')
            ->setCellValue('D1', '手机号')
            ->setCellValue('E1', '邮箱')
            ->setCellValue('F1', '学校/单位')
            ->setCellValue('G1', '职业')
            ->setCellValue('H1', '报名类目')
            ->setCellValue('I1', '赛区')
            ->setCellValue('J1', '自媒体账号')
            ->setCellValue('K1', '自媒体粉丝')
            ->setCellValue('L1', '是否参加红人培训')
            ->setCellValue('M1', '报名时间');
            //->setCellValue('N1', '状态');

        //主体数据
        $result = D('Bms/UserProfile', 'Logic')->getList(array_merge($request, ['page_size'=>0]));

        foreach($result['list'] as $key => $value) {
            //行号
            $row_num = $key + 2;
            //设置主体数据
            $PHPExcelObj->setActiveSheetIndex(0)
                ->setCellValue('A' . $row_num, $value['sn'])
                ->setCellValue('B' . $row_num, $value['name'])
                ->setCellValue('C' . $row_num, $value['id_num'].' ')
                ->setCellValue('D' . $row_num, $value['mobile'])
                ->setCellValue('E' . $row_num, $value['email'])
                ->setCellValue('F' . $row_num, $value['agency'])
                ->setCellValue('G' . $row_num, $value['profession'])
                ->setCellValue('H' . $row_num, $value['classes'])
                ->setCellValue('I' . $row_num, $value['division'])
                ->setCellValue('J' . $row_num, $value['media'])
                ->setCellValue('K' . $row_num, $value['media_fans'])
                ->setCellValue('L' . $row_num, $value['is_training'])
                ->setCellValue('M' . $row_num, timestamp2date($value['create_time']));
                //->setCellValue('M' . $row_num, get_status_title($value['status']));
            }

        //设置工作表名称
        $PHPExcelObj->getActiveSheet()->setTitle('选手数据');
        //设置打开表格文件默认的工作表为第一个
        $PHPExcelObj->setActiveSheetIndex(0);

        //保存文件
        $this->saveFile($PHPExcelObj, date('Y-m-d H:i:s'));

        exit;
    }

    /**
     * @param array $request
     * @return mixed
     * 用户数据导出
     */
    public function memberExport2($request = [])
    {
        //获取PHPExcel对象
        $PHPExcelObj = $this->getPHPExcelInstance();
        //设置文件属性信息
        //$PHPExcelObj = $this->setProperties($PHPExcelObj);
        //设置表格样式
        $PHPExcelObj->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('M')->setWidth(20);
        $PHPExcelObj->getActiveSheet()->getColumnDimension('N')->setWidth(20);
        //行高
        $PHPExcelObj->getActiveSheet()->getRowDimension('1')->setRowHeight(22);
        //字体加粗
        $PHPExcelObj->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);
        //垂直方向对齐格式
        $PHPExcelObj->getActiveSheet()->getStyle('A1:R1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置表格数据
        //标题数据
        $PHPExcelObj->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '姓名')
            ->setCellValue('C1', '身份证号')
            ->setCellValue('D1', '手机号')
            ->setCellValue('E1', '邮箱')
            ->setCellValue('F1', '性别')
            ->setCellValue('G1', '擅长类目')
            ->setCellValue('H1', '推荐人')
            ->setCellValue('I1', '赛区')
            ->setCellValue('J1', '报名时间');
            //->setCellValue('N1', '状态');

        //主体数据
        $result = D('Bms/UserProfile', 'Logic')->getList(array_merge($request, ['page_size'=>0]));

        foreach($result['list'] as $key => $value) {
            //行号
            $row_num = $key + 2;
            //设置主体数据
            $PHPExcelObj->setActiveSheetIndex(0)
                ->setCellValue('A' . $row_num, $value['sn'])
                ->setCellValue('B' . $row_num, $value['name'])
                ->setCellValue('C' . $row_num, $value['id_num'].' ')
                ->setCellValue('D' . $row_num, $value['mobile'])
                ->setCellValue('E' . $row_num, $value['email'])
                ->setCellValue('F' . $row_num, $value['gender'])
                ->setCellValue('G' . $row_num, $value['classes'])
                ->setCellValue('H' . $row_num, $value['comm'])
                ->setCellValue('I' . $row_num, $value['division'])
                ->setCellValue('J' . $row_num, timestamp2date($value['create_time']));
                //->setCellValue('M' . $row_num, get_status_title($value['status']));
            }

        //设置工作表名称
        $PHPExcelObj->getActiveSheet()->setTitle('选手数据');
        //设置打开表格文件默认的工作表为第一个
        $PHPExcelObj->setActiveSheetIndex(0);

        //保存文件
        $this->saveFile($PHPExcelObj, date('Y-m-d H:i:s'));

        exit;
    }

    /**
     * @return \PHPExcel
     * 获取PHPExcel核心类对象
     */
    protected function getPHPExcelInstance()
    {
        $PHPExcelObj = new \PHPExcel();
        return $PHPExcelObj;
    }

    /**
     * @param null $PHPExcelObj
     * @return null
     * 设置文件属性信息
     */
    protected function setProperties($PHPExcelObj = NULL) {
        //文件属性信息
        $PHPExcelObj->getProperties()
            ->setTitle('订单表格') //标题
            ->setSubject('订单表格') //主题
            ->setCreator('黑暗中的武者') //作者
            ->setManager('黑暗中的武者') //经理
            ->setCompany('') //单位
            ->setCategory('订单') //类别
            ->setKeywords('订单') //关键字
            ->setDescription('第一个测试文档') //备注
            ->setLastModifiedBy('黑暗中的武者'); //最后修改者
        return $PHPExcelObj;
    }

    /**
     * @param null $PHPExcelObj
     * @throws \PHPExcel_Reader_Exception
     * 保存文件
     */
    protected function saveFile($PHPExcelObj = NULL, $date = '')
    {
        //清除缓冲区,避免乱码
        ob_end_clean();
        //设置http头信息
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $this->fileName . '-' . $date . '.xlsx');
        header('Cache-Control: max-age=0'); //兼容IE9
        header('Cache-Control: max-age=1'); //兼容 SLL服务IE
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        //获取 写文件类对象
        $WriterObj = \PHPExcel_IOFactory::createWriter($PHPExcelObj, 'Excel5');
        //保存文件到
        $WriterObj->save($this->savePath . '/' . iconv("utf-8", "gb2312", $this->fileName) . '-' . $date . '.xlsx');
        //文件通过浏览器下载
        $WriterObj->save('php://output');
        exit;
    }



    function document($objPHPExcel) {
        /**设置图片**/
        /*实例化插入图片类*/
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        /*设置图片路径 切记：只能是本地图片*/
        $objDrawing->setPath('./Uploads/Avatar/default.jpg');
        /*设置图片高度*/
        $objDrawing->setWidth(200);
        $img_height[] = $objDrawing->getHeight();
        /*设置图片要插入的单元格*/
        $objDrawing->setCoordinates('K1');
        /*设置图片所在单元格的格式*/
        $objDrawing->setOffsetX(10);
        $objDrawing->setOffsetY(10);
        $objDrawing->setRotation(0); //旋转
        $objDrawing->getShadow()->setVisible(true); //阴影
        $objDrawing->getShadow()->setDirection(50); //阴影方向
        $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

        $objPHPExcel->getActiveSheet()->setCellValue('C5', '=SUM(C2:C4)');
        $objPHPExcel->getActiveSheet()->setCellValue('B8', '=MIN(B2:C5)');

        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A18:E22');
        //分离单元格
        $objPHPExcel->getActiveSheet()->unmergeCells('A28:B28');

        //冻结窗口
        $objPHPExcel->getActiveSheet()->freezePane('A2');

        //保护cell
        $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
        $objPHPExcel->getActiveSheet()->protectCells('A3:E13', 'PHPExcel');

        //设置数字格式
        $objPHPExcel->getActiveSheet()->getStyle('E4')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);
        $objPHPExcel->getActiveSheet()->duplicateStyle($objPHPExcel->getActiveSheet()->getStyle('E4'), 'E5:E13');
        //设置文本格式
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('E1', $data[4], \PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setWrapText(true);

        //设置宽width
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);

        //设置font
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        $objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);
        $objPHPExcel->getActiveSheet()->getStyle('D13')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('E13')->getFont()->setBold(true);

        //设置align
        $objPHPExcel->getActiveSheet()->getStyle('D11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('D12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('D13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('A18')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY);
        //垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A18')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        //设置column的border
        $objPHPExcel->getActiveSheet()->getStyle('A4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('B4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('C4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('D4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objPHPExcel->getActiveSheet()->getStyle('E4')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //设置border的color
        $objPHPExcel->getActiveSheet()->getStyle('D13')->getBorders()->getLeft()->getColor()->setARGB('FF993300');
        $objPHPExcel->getActiveSheet()->getStyle('D13')->getBorders()->getTop()->getColor()->setARGB('FF993300');
        $objPHPExcel->getActiveSheet()->getStyle('D13')->getBorders()->getBottom()->getColor()->setARGB('FF993300');
        $objPHPExcel->getActiveSheet()->getStyle('E13')->getBorders()->getTop()->getColor()->setARGB('FF993300');
        $objPHPExcel->getActiveSheet()->getStyle('E13')->getBorders()->getBottom()->getColor()->setARGB('FF993300');
        $objPHPExcel->getActiveSheet()->getStyle('E13')->getBorders()->getRight()->getColor()->setARGB('FF993300');

        //设置填充颜色
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('FF808080');
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objPHPExcel->getActiveSheet()->getStyle('B1')->getFill()->getStartColor()->setARGB('FF808080');
    }
}