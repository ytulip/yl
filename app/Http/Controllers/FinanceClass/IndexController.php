<?php

namespace App\Http\Controllers\FinanceClass;

use App\Http\Controllers\Controller;
use App\Log\Facades\Logger;
use App\Model\Admin;
use App\Model\Banner;
use App\Model\Book;
use App\Model\CashStream;
use App\Model\Essay;
use App\Model\FinanceClass;
use App\Model\FinanceUser;
use App\Model\FoodMenu;
use App\Model\InvitedCodes;
use App\Model\Message;
use App\Model\MonthGetGood;
use App\Model\Neighborhood;
use App\Model\Order;
use App\Model\Period;
use App\Model\Product;
use App\Model\ProductAttr;
use App\Model\RandomGet;
use App\Model\RandomPool;
use App\Model\ServeUser;
use App\Model\SignRecord;
use App\Model\SubFoodOrders;
use App\Model\SyncModel;
use App\Model\User;
use App\Model\UserAddress;
use App\Util\CommKit;
use App\Util\DownloadExcel;
use App\Util\Kit;
use App\Util\OrderStatical;
use App\Util\SmsTemplate;
use App\Util\TotalStatical;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class IndexController extends Controller
{
    public function getIndex()
    {
//        return view('admin.index');
        return view('admin.');
    }

}