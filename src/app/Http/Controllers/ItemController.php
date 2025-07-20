<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;

class ItemController extends Controller
{
    /**
     * 商品一覧画面を表示
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $tab = $request->query('tab', 'all');

        $query = Item::with('seller')->orderBy('created_at', 'desc');

        if ($tab === 'mylist' && Auth::check()) {
            $items = Item::whereIn('id', Like::where('user_id', Auth::id())->pluck('item_id'))
                ->when($search, function ($query) use ($search) {
                    return $query->where('name', 'like', '%' . $search . '%');
                })
                ->with('seller')
                ->get();
        } else {
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            $items = $query->get();
        }

        return view('items.index', compact('items', 'search', 'tab'));
    }

    /**
     * 商品出品画面を表示
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * 商品を出品する処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'string',
            'condition' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048',
        ]);

        $path = $request->file('image')->store('public/item_images');

        // 🔹 最初のカテゴリーを取得（カテゴリがある場合のみ）
        $category_id = null;
        if (!empty($request->categories)) {
            $category_name = $request->categories[0]; // 最初のカテゴリを取得
            $category = Category::where('name', $category_name)->first();

            if ($category) {
                $category_id = $category->id;
            }
        }

        // 🔹 Itemモデルを使って保存
        $item = Item::create([
            'categories' => json_encode($request->categories),
            'condition' => $request->condition,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $path,
            'user_id' => Auth::id(),
            'category_id' => $category_id, // ✅ category_id をセット
        ]);

        return redirect()->route('items.index')->with('success', '商品を出品しました');
    }

    /**
     * 商品詳細画面を表示
     */
    public function show($id)
    {
        $item = Item::with(['likes', 'comments.user'])->findOrFail($id);

        if (!is_array($item->categories) && !is_null($item->categories)) {
            $item->categories = json_decode($item->categories, true);
        }



        $liked = Auth::check() ? $item->likes->contains('user_id', Auth::id()) : false;

        return view('items.detail', compact('item', 'liked'));
    }

    /**
     * 出品した商品の一覧を表示
     */
    public function myItems()
    {
        $items = Item::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.show', compact('items'));
    }

    /**
     * 商品購入画面を表示
     */
    public function purchase($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('items.purchase', compact('item', 'user'));
    }

    /**
     * 配送先住所変更画面を表示
     */
    public function changeAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('items.change_address', compact('item', 'user'));
    }

    /**
     * 配送先住所を更新する処理
     */
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required|string|regex:/^\d{3}-\d{4}$/',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ],[
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',
            'address.required' => '住所を入力してください。',
            'address.max' => '住所は最大255文字まで入力できます。',
        ]);

        $user = Auth::user();
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect()->route('items.purchase', $item_id)->with('success', '配送先住所が更新されました。');
    }

    /**
     * 商品の購入処理
     */
    public function completePurchase(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        // すでに購入されているかチェック
        if ($item->buyer_id !== null) {
            return redirect()->route('items.detail', $item->id)->with('error', 'この商品はすでに購入されています。');
        }

        // 購入処理
        $item->buyer_id = Auth::id();
        $item->save();

        return redirect()->route('items.index')->with('success', '商品を購入しました！');
    }

    /**
     * 商品に「いいね」を追加する処理
     */
    public function like($id)
    {
        $item = Item::findOrFail($id);

        // すでに「いいね」していない場合のみ追加
        if (!Like::where('user_id', Auth::id())->where('item_id', $id)->exists()) {
            Like::create([
                'user_id' => Auth::id(),
                'item_id' => $id,
            ]);
        }

        return back()->with('success', '商品を「いいね」しました');
    }

    /**
     * 商品の「いいね」を解除する処理
     */
    public function unlike($id)
    {
        $item = Item::findOrFail($id);

        // 「いいね」を削除
        Like::where('user_id', Auth::id())->where('item_id', $id)->delete();

        return back()->with('success', '「いいね」を解除しました');
    }

    /**
     * 商品にコメントを追加する処理
     */
    public function comment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $item = Item::findOrFail($id);

        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $id,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'コメントを追加しました');
    }

}
