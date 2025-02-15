<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\Like;

class ItemController extends Controller
{
    /**
     * 商品一覧画面を表示
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $tab = $request->query('tab', 'all'); // デフォルトは全商品

        if ($tab === 'mylist' && Auth::check()) {
            // いいねした商品の取得
            $items = Item::whereIn('id', Like::where('user_id', Auth::id())->pluck('item_id'))
                ->when($search, fn($query) => $query->where('name', 'like', '%' . $search . '%'))
                ->with('buyer')
                ->get();
        } else {
            // 全商品の取得（検索対応）
            $items = Item::when($search, fn($query) => $query->where('name', 'like', '%' . $search . '%'))
                ->with('buyer')
                ->get();
        }

        return view('items.index', compact('items', 'search', 'tab'));
    }

    /**
     * いいねした商品の一覧を表示
     */
    public function mylist(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $search = $request->query('search');
        $items = Item::whereIn('id', Like::where('user_id', Auth::id())->pluck('item_id'))
            ->when($search, fn($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->with('buyer')
            ->get();

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

        // 画像の保存
        $path = $request->file('image')->store('public/item_images');

        // 商品情報の保存
        Item::create([
            'categories' => json_encode($request->categories),
            'condition' => $request->condition,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $path,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('items.index')->with('success', '商品を出品しました');
    }

    /**
     * 商品詳細画面を表示
     */
    public function show($id)
    {
        $item = Item::with(['likes', 'comments.user'])->findOrFail($id);
        $item->categories = json_decode($item->categories, true);

        // いいね済み判定
        $liked = Auth::check() ? $item->likes->contains('user_id', Auth::id()) : false;

        return view('items.detail', compact('item', 'liked'));
    }

    /**
     * いいね追加（JavaScriptなし）
     */
    public function like($id)
    {
        $item = Item::findOrFail($id);

        Like::updateOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
        ]);

        return redirect()->back()->with('success', 'いいねしました！');
    }

    /**
     * いいね解除（JavaScriptなし）
     */
    public function unlike($id)
    {
        $item = Item::findOrFail($id);

        Like::where('user_id', Auth::id())->where('item_id', $item->id)->delete();

        return redirect()->back()->with('success', 'いいねを解除しました。');
    }

    /**
     * 商品にコメントを投稿する処理
     */
    public function comment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        $item = Item::findOrFail($id);

        $item->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return redirect()->route('items.detail', $id)->with('success', 'コメントを送信しました');
    }

    /**
     * 商品購入画面を表示
     */
    public function purchase($item_id)
    {
        $item = Item::with('user')->findOrFail($item_id);
        $user = Auth::user();

        return view('items.purchase', compact('item', 'user'));
    }

    /**
     * 配送先住所変更画面を表示
     */
    public function changeAddress($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user(); //ユーザー情報を取得
        return view('items.change_address', compact('item','user'));
    }

    /**
     * 商品の購入処理
     */
    public function completePurchase(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->update([
            'buyer_id' => Auth::id(),
            'delivery_postal_code' => $request->input('postal_code', $item->delivery_postal_code),
            'delivery_address' => $request->input('address', $item->delivery_address),
            'delivery_building' => $request->input('building', $item->delivery_building),
        ]);

        return redirect()->route('items.index')->with('success', '購入が完了しました！');
    }

    /**
     * 配送先住所を更新する処理
     */
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ], [
            'postal_code.required' => '郵便番号を入力してください。',
            'address.required' => '住所を入力してください。',
            'postal_code.max' => '郵便番号は10文字以内で入力してください。',
            'address.max' => '住所は255文字以内で入力してください。',
        ]);

        $item = Item::findOrFail($item_id);

        $item->update([
            'delivery_postal_code' => $request->postal_code,
            'delivery_address' => $request->address,
            'delivery_building' => $request->building,
        ]);

        return redirect()->route('items.purchase', $item->id)->with('success', '住所を更新しました！');
    }
}
