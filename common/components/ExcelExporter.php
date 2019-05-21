<?php

class ExcelExporter
{
        const CRLF = "\r\n";

        /**
         * Outputs active record resultset to an xml based excel file
         *
         * @param $filename - name of output filename
         * @param $data - active record data set
         * @param $title - title displayed on top
         * @param $header - boolean to show/hide header
         * @param $fields - array of fields to export
         */
        public static function sendAsXLS($filename, $data, $title = false, $header = false, $fields = false)
        {
                $export = self::xls($data, $title, $header, $fields);
                self::sendHeader($filename, strlen($export), 'vnd.ms-excel');
                echo $export;
                Yii::app()->end();
        }

        /**
         * Send file header
         *
         * @param $filename - filename for created file
         * @param $length - size of file
         * @param $type - mime type of exported data
         */
        private static function sendHeader($filename, $length, $type = 'octet-stream')
        {
                if (strtolower(substr($filename, -4)) != '.xls')
                        $filename .= '.xls';

                header("Content-type: application/$type");
                header("Content-Disposition: attachment; filename=$filename");
                header("Content-length: $length");
                header('Pragma: no-cache');
                header('Expires: 0');
        }

        /**
         * Private method to create xls string from active record data set
         *
         * @param $data - active record data set
         * @param $title - title displayed on top
         * @param $header - boolean to show/hide header
         * @param $fields - array of fields to export
         */
        private static function xls($data, $title, $header, $fields)
        {
                $str = '<html>' . self::CRLF
                . '<head>' . self::CRLF
                . '<meta http-equiv="content-type" content="text/html; charset=utf-8">' . self::CRLF
                . '</head>' . self::CRLF
                . '<body style="text-align:center">' . self::CRLF;

                if ($title)
                        $str .= "<b>$title</b><br /><br />" . self::CRLF
                        . Yii::t('main', 'export_lines') . ': ' . count($data) . '<br />' . self::CRLF
                        . Yii::t('main', 'export_date') . ': ' . Yii::app()->dateFormatter->formatDateTime($_SERVER['REQUEST_TIME']) . '<br /><br />' . self::CRLF;

                if ($data)
                {
                        $str .= '<table style="text-align:left" border="1" cellpadding="0" cellspacing="0">' . self::CRLF;

                        if (!$fields)
                                $fields = array_keys($data[0]->attributes);

                        if ($header)
                        {
                                $str .= '<tr>' . self::CRLF;
                                foreach ($fields as $field)
                                        $str .= '<th>' . $data[0]->getAttributeLabel($field) . '</th>' . self::CRLF;
                                $str .= '</tr>' . self::CRLF;
                        }

                        foreach ($data as $row)
                        {
                                $str .= '<tr>' . self::CRLF;
                                foreach ($fields as $field)
                                        $str .= '<td>' . $row->$field . '</td>' . self::CRLF;
                                $str .= '</tr>' . self::CRLF;
                        }

                        $str .= '</table>' . self::CRLF;
                }

                $str .= '</body>' . self::CRLF
                . '</html>';

                return $str;
        }
}