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
        /**
         * 当前的金融产品
         */
        $product = Product::activeFinance();
        $query = Book::orderBy('books.id','desc')->leftJoin('users','users.id','=','books.user_id')->leftJoin('book_finance_count','book_finance_count.user_id','=','books.user_id')->selectRaw('books.*,users.phone,users.real_name,count')->where('product_id',$product->id);
        $paginate = $query->paginate(env('ADMIN_PAGE_LIMIT'));
        return view('finance.finance_user')->with('product',$product)->with('paginate', $paginate);
    }


    public function anyFill()
    {
        return view('finance.fill');
    }


    public function anyWenjuan()
    {
        return view('finance.wenjuan');
    }

    public function anySave()
    {
        $book = Book::find(Request::input('id'));
        $book->answer = Request::input('answer');
        $book->score_type = Request::input('score_type');
        $book->save();

        return $this->jsonReturn(1);
    }

}