<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function query(){

        // Create
        DB::table('users')->insert([
            ['email' => 'taylor@example.com', 'votes' => 0],
            ['email' => 'dayle@example.com', 'votes' => 0]
        ]);

        // Update
        DB::table('users')
            ->where('id', 1)
            ->update(['votes' => 1]);

        // Delete
        DB::table('users')->where('votes', '>', 100)->delete();

        // One To Many
        $query_1 = DB::table('products')
                     ->join('suppliers', 'products.supplier_id', '=', 'suppliers.id')
                     ->select('products.name','price','suppliers.name as supplier')
                     ->get();

        // Many To Many
        $query_2 = DB::table('products_suppliers')
                     ->join('products', 'products_suppliers.products_id', '=', 'products.id')
                     ->join('suppliers', 'products_suppliers.suppliers_id', '=', 'suppliers.id')
                     ->select('products.name','price','suppliers.name as supplier')
                     ->get();
        
        // One To One
        $query_3 = DB::table('products_suppliers')
                     ->join('products', 'products_suppliers.products_id', '=', 'products.id')
                     ->join('suppliers', 'products_suppliers.suppliers_id', '=', 'suppliers.id')
                     ->leftJoin('products_details', 'products_suppliers.products_id', '=', 'products_details.product_id')
                     ->select('products.name','price','suppliers.name as supplier','products_details.comment')
                     ->get();

        // Aggregates
        $count = DB::table('users')->count();

        $max = DB::table('orders')->max('price');

        $min = DB::table('orders')->min('price');

        $avg = DB::table('orders')->avg('price');

        $sum = DB::table('orders')->sum('price');

        // OrderBy
        $query_3 = DB::table('products')
                     ->orderBy('name', 'desc')
                     ->get();

        // GroupBy
        $query_3 = DB::table('products')
                     ->groupBy('supplier_id')
                     ->get();


        // Having
        $query_3 = DB::table('products')
                     ->groupBy('supplier_id')
                     ->having('supplier_id', '>', 100)
                     ->get();

        // HavingRaw
        $users = DB::table('products')
                   ->groupBy('supplier_id')
                   ->havingRaw('SUM(price) > 2500')
                   ->get();
       

        // Union
        $first = DB::table('users')
                    ->whereNull('first_name');

        $users = DB::table('users')
                    ->whereNull('last_name')
                    ->union($first)
                    ->get();

        // Collection
        where('price', '=', 100);
        whereIn('price', [150, 200]);
        whereNotIn('price', [150, 200]);
        push(5);
        random();
        pluck('name');
        first();
        except(['price', 'discount']);
        get('foo', 'default-value');
        has('product');
        implode('product', ', ');
        isEmpty();
        isNotEmpty();
        all();
        slice(4, 2);
        
        $ProporsionalByMont = $DB::select(DB::raw("
            SELECT
                bd.id,
                bd.deleted_at,
                IF(MONTH(bd.tanggal_mulai) = MONTH('".$month."'),
                    DATEDIFF(bd.tanggal_mulai, bd.tanggal_selesai ),
                    DATEDIFF(DATE_FORMAT('".$month."' ,'%Y-%m-01'), bd.tanggal_selesai)
                ) + 1 AS 'total_hari_penarikan'
            FROM
                booking_detail bd
            JOIN
                booking b ON bd.booking_id = b.id AND b.deleted_at IS NULL AND b.status_booking_id != '".BOOKING_CANCELED."'
            WHERE
                DATE(bd.tanggal_selesai) BETWEEN DATE_FORMAT('".$month."' ,'%Y-%m-01') AND LAST_DAY('".$month."')
                OR
                DATE(bd.tanggal_mulai) BETWEEN DATE_FORMAT('".$month."' ,'%Y-%m-01') AND LAST_DAY('".$month."')
        "));
    }
}
