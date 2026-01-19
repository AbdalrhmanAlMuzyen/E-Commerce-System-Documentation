<?php

namespace App\Repository;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;

class DashboardRepository{

    public function getUsersPurchaseStatistics()
    {
        return User::withCount(["orders as total_orders"=>function($query){
            $query->whereIn("status",["paid","processing","delivered"]);
        }])
        
        ->withSum(["orders as total_spent"=>function($query){
            $query->whereIn("status",["paid","processing","delivered"]);
        }],"total_price")
        
        ->withAvg(["orders as average_order_value"=>function($query){
            $query->whereIn("status",["paid","processing","delivered"]);
        }],"total_price")
        
        ->with(["orders"=>function($query){
            $query->orderBy("created_at","DESC")->whereIn("status",["paid","processing","delivered"])->limit(1);
        }])
        
        ->orderBy("total_spent","DESC")
        ->get()->map(function ($user) {
            $order=$user->orders->first();
            if(!$order)
            {
                $user->last_purchase="no pruchases";
            }
            else{
                $user->last_purchase=$order->created_at->diffForHumans();
            }
            unset($user->orders);
            return $user;
        });
       
    }  

    public function getCategoryProductStatistics($category)
    {
        return $category->products()
            ->with([
                "productVariants" => function ($query) {
                    $query
                        ->withSum([
                            "orderItems as variant_sold_quantity" => function ($query) {
                                $query->whereHas("order", function ($query) {
                                    $query->whereIn("status", ["paid", "shipped", "delivered"]);
                                });
                            }
                        ], "quantity")
                        ->withCount([
                            "orderItems as variant_orders_count" => function ($query) {
                                $query->whereHas("order", function ($query) {
                                    $query->whereIn("status", ["paid", "shipped", "delivered"]);
                                });
                            }
                        ])
                        ->with(["size", "color"]);
                }
            ])
            ->withSum(["orderItems AS total_order_items_sold"=>function($query){
                    $query->whereHas("order",function($query){
                        $query->whereIn("orders.status", ["paid", "shipped", "delivered"]);
                    });
            }],"quantity")
            ->with(["orderItems"=>function($query)
            {
                $query->orderBy("created_at","DESC")->limit(1);
            }])
            ->selectRaw("products.*,COUNT(DISTINCT orders.id) AS orders_count,COALESCE(SUM(orders.total_price), 0) AS total_sold_revenue")
            ->leftJoin("product_variants", "product_variants.product_id", "products.id")
            ->leftJoin("order_items", "order_items.product_variant_id", "product_variants.id")
            ->leftJoin("orders", function ($join) {
                $join->on("orders.id", "order_items.order_id")
                    ->whereIn("orders.status", ["paid", "shipped", "delivered"]);
            })
            ->groupBy("products.id","products.name","products.description","products.price","products.category_id","products.created_at","products.updated_at")
            ->get()
            ->map(function($product){
                $order=$product->orderItems->first();
                if(!$order)
                {
                    $product->last_purchase="no pruchases";
                }
                else{
                    $product->last_purchase=$order->created_at->diffForHumans();
                }
                unset($product->orderItems);
                return $product;
            });
        
    }

    public function getOrdersStatistics()
    {
        return Order::selectRaw("
            COUNT(*) AS total_orders,

            COUNT(CASE WHEN status = 'pending' THEN 1 END) AS pending_orders,
            COUNT(CASE WHEN status = 'paid' THEN 1 END) AS paid_orders,
            COUNT(CASE WHEN status = 'shipped' THEN 1 END) AS shipped_orders,
            COUNT(CASE WHEN status = 'delivered' THEN 1 END) AS delivered_orders,
            COUNT(CASE WHEN status = 'cancelled' THEN 1 END) AS cancelled_orders,
            COUNT(CASE WHEN status = 'failed' THEN 1 END) AS failed_orders,

            SUM(CASE WHEN status = 'delivered' THEN total_price ELSE 0 END) AS total_revenue,

            IFNULL (
            (
                SUM(CASE WHEN status = 'delivered' THEN total_price ELSE 0 END) /
                NULLIF(COUNT(CASE WHEN status = 'delivered' THEN 1 END), 0)
            )
            ,0)
            AS average_order_value
        ")->first();
    }

    public function getTotalUsers()
    {
        return User::count();
    }

    public function getOneTimeCustomers()
    {
        return User::whereHas(
            'orders',
            fn ($q) => $q->whereIn('status', ['delivered','paid','shipped']),
            '=',
            1
        )->count();
    }

    public function getRepeatCustomers()
    {
        return User::whereHas(
            'orders',
            fn ($q) => $q->whereIn('status', ['delivered','paid','shipped']),
            '>',
            1
        )->count();
    }

    public function getSizeSalesOverview()
    {
        return Size::selectRaw("
                sizes.id,
                sizes.value,
                SUM(order_items.quantity) AS total_sold_quantity
            ")
            ->join("product_variants", "product_variants.size_id", "=", "sizes.id")
            ->join("order_items", "order_items.product_variant_id", "=", "product_variants.id")
            ->join("orders", function($query){
                $query->whereIn('status', ['delivered','paid','shipped']);
            })
            ->groupBy("sizes.id", "sizes.value")
            ->orderByDesc("total_sold_quantity")
            ->get();
    }

    public function getAverageRateBetweenOrders($user)
    {
        $orders=$user->orders()->whereIn("status",["paid","shipped","delivered"])->orderBy("created_at","DESC")->pluck("created_at");
        $days=[];
        if($orders->count() < 2 || $orders->isEmpty())
        {
            return 0;
        }
        for($i=1;$i<$orders->count();$i++)
        {
            $days[]=$orders[$i]->startOfDay()->diffInDays($orders[$i-1]->startOfDay());
        }
        return array_sum($days) / count($days) ;
    }

    public function getInactiveUsers()
    {
        return User::whereDoesntHave("orders",function($q){
            $q->where("created_at",">=",now()->subDays(30));
        })->get();
    }

    public function getTopPurchasedProducts(User $user)
    {
        return OrderItem::whereHas('order', function($q) use ($user) {
                $q->where('user_id', $user->id)
                ->whereIn('status', ['shipped','paid','delivered']);
            })
            ->join('product_variants', 'product_variants.id', '=', 'order_items.product_variant_id')
            ->join('products', 'products.id', '=', 'product_variants.product_id')
            ->selectRaw('products.*, SUM(order_items.quantity) as total_quantity')
            ->groupBy('products.id','products.category_id','products.name','products.description','products.price','products.created_at','products.updated_at')
            ->orderByDesc('total_quantity')
            ->limit(1)
            ->get();
    }


    public function getMostAddedButLeastOrdered()
    {
        return Product::selectRaw('products.*,COUNT(DISTINCT cart_items.id) AS added_to_cart_count,COUNT(DISTINCT order_items.id) AS completed_orders_count')
            ->join('product_variants', 'product_variants.product_id', '=', 'products.id')
            ->join('cart_items', 'cart_items.product_variant_id', '=', 'product_variants.id')
            ->leftJoin('order_items', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->leftJoin('orders', function($join){
                $join->on("orders.id","order_items.order_id")->whereIn("status",["delivered","paid","shipped"]);
            })
            ->groupBy('products.id','products.name','products.price','products.description','products.category_id','products.created_at','products.updated_at')
            ->havingRaw('added_to_cart_count > completed_orders_count')
            ->orderByDesc('added_to_cart_count')   
            ->orderBy('completed_orders_count') 
            ->get();
    }




}