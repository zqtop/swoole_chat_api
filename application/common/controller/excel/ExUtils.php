<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/6/13
 * Time: 13:56
 */

namespace app\common\controller\excel;

/**
 * Class ExUtils
 * @package app\common\controller
 * excel工具类
 */
class ExUtils
{

    /**
     * @param $fileName  文件所在路径
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * 读取excel文件内容
     */

    public static  function  readExcel( $fileName )
    {
        // var_dump($fileName);exit;
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        return $sheetData;
    }



}