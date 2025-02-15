<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // プロフィール編集画面を表示
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // プロフィールを更新
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('public/profile_images');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address = $request->address;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'プロフィールを更新しました');
    }

    // プロフィール画面を表示
    public function show(Request $request) // ✅ Requestを明示的に受け取る
    {
        $user = Auth::user(); // ログイン中のユーザー情報を取得
        $tab = $request->query('tab', 'sell'); // ✅ `tab` のデフォルト値を "sell" に設定

        // ✅ 選択されたタブに応じて取得データを切り替える
        $items = ($tab === 'buy')
            ? $user->purchasedItems()->with(['seller:id,name'])->get(['id', 'name', 'image'])
            : $user->listedItems()->with(['buyer:id,name'])->get(['id', 'name', 'image']);

        return view('profile.show', compact('user', 'items', 'tab'));
    }
}
