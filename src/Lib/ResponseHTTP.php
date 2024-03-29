<?php
namespace Lib;
class ResponseHttp{
    /**
     * Devuelve el mensaje de estado de el codigo de estado
     * @param $code int Codigo de estado
     * @return string Mensaje de estado
     */
    private static function getStatusMessage($code):string
    {
        $statusMessage = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
        ];
        return ($statusMessage[$code]) ?: $statusMessage[500];
    }



    /**
     * Devuelve el mensaje de estado y el mensaje de respuesta en formato JSON
     * @param int $status Codigo de estado
     * @param string $res Mensaje de respuesta
     * @return string Mensaje de estado y mensaje de respuesta en formato JSON
     */
    final public static function statusMessage(int $status, string $res, string $token):string{
        http_response_code($status);

        $message= [
            'status' => self::getStatusMessage($status),
            'message' => $res,
            'token'=>$token
        ];
        return json_encode($message);
    }
}