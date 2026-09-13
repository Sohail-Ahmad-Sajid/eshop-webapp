<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller {
    public function dashboard() {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => Order::where('status','delivered')->sum('total_amount'),
            'pending' => Order::where('status','pending')->count()
        ];
        
        $products=Product::all();
        $orders=Order::all();
        return view('admin.dashboard', compact('stats','orders','products'));
    }
    
    public function products() {
        $products = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('admin.addproducts', compact('products','categories'));
    }
    
    public function storeProduct(Request $request) {
        $request->validate([
            'name'=>'required',
            'category_id'=>'required',
            'price'=>'required|numeric',
            'stock'=>'required|integer'
        ]);
        
        $data = $request->all();
        $data['slug'] = str()->slug($request->name);
        
        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products','public');
        }
        
        Product::create($data);
        return back()->with('success','Product added');
    }
    public function showUpdateProduct($id){
       $products= Product::where('id',$id)->firstOrfail();
        $categories=Category::all();
        return view('admin.updateproduct',compact('products','categories'));

    }
    public function updateProduct(Request $request, $id){
       $products= Product::where('id',$id)->firstOrfail();

       $request->validate([
            'name'=>'required',
            'price'=>'required|numeric',
            'stock'=>'required|integer'
        ]);
        
        // Product::create([
        //     'name'=>$request->name,
        //     'category_id'=>$request->category_id,
        //     'price'=>$request->price,
        //     'stock'=>$request->stock,
        //     'slug'=>str()->slug($request->name)

        // ]);
        $data = $request->all();
        $data['slug'] = str()->slug($request->name);

        
        if($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products','public');
        }
        if($request->description == []){

           $data['description']= $products->description;
        }
        
        Product::find($id)->update($data);
        return back()->with('success','Product Updated!');


    }
    public function deleteImage($id){
        Product::where('id',$id)->update(['image'=>NULL]);
        return back()->with('success','Remove image'); 
    }
    public function changeCate(Request $request, $id){
                Product::where('id',$id)->update(['category_id'=>$request->category_id]);
        return back()->with('success','Category changed'); 

    }
     public function addCategory(Request $request){

    Category::create([
        'name'=>$request->name,
        'slug'=>str()->slug($request->name)
    ]);
        return back();
    }
    public function orders() {
        $orders = Order::with('user','items.product')->latest()->get();
        return view('admin.dashboard', compact('orders'));
    }

    public function showCategory(){
         $categories = Category::all();
     return view('admin.addcategory' ,compact('categories'));


    }


    public function addCate(Request $request){

    Category::create([
        'name'=>$request->name,
        'slug'=>str()->slug($request->name)
    ]);
        return redirect('/admin/product');
    }
    public function deleteCate(Request $request){
        $isproduct=Product::where('category_id', $request->id)->count();
        if($isproduct == 0){
        Category::where('id',$request->id)->delete();
        return back()->with('success','Category deleted');
        }
        else{
                    return back()->with('error','this category contain products ');

        }

    }
    public function updateOrderStatus(Request $request, $id) {
        Order::find($id)->update(['status'=>$request->status]);
        return back()->with('success','Order updated');
    }
}
