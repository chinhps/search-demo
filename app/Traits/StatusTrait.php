<?php

namespace App\Traits;

trait StatusTrait
{
    public function measurementRequestStatus($status)
    {
        $msg = "Không xác định";
        switch ($status) {
            case 1:
                $msg = "Đã hoàn thành";
                break;
            case 0:
                $msg = "Đang xử lý";
                break;
            case 2:
                $msg = "Có lỗi xảy ra!";
                break;
            default:
                break;
        }
        return $msg;
    }
}
