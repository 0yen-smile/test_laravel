<?php

namespace App\Http\Controllers;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller {

    // 一覧表示
    public function index() {
        $items = Item::all();
        $deleteItems = Item::onlyTrashed()->get();
        return view('items.index', compact('items', 'deleteItems'));
    }
    
    // データ登録
    public function store(Request $request) {
        try{
            // バリデーション(フォームの入力が正しいかどうかをチェック)
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|integer|min:0|max:2147483647',
            ],[
                'name.required' => '製品名設定が不正です',
                'name.string'   => '製品名は文字列である必要があります',
                'name.max'      => '製品名は255文字以内で入力してください',
                'price.required' => '価格設定が不正です',
                'price.integer'  => '価格は整数で入力してください',
                'price.min'      => '価格は0以上を指定してください',
                'price.max'      => '価格は2147483647以下を指定してください',
            ]);

            // データ登録
            Item::create([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            // 一覧ページへリダイレクト（登録後に更新）
            session()->flash('success_message', '製品を登録しました。');
            return redirect()->route('items.index');

        } catch(\Exception $e) {
            $timestamp = now()->toDateTimeString();
            // エラーログに詳細を記録
            \Log::error('製品削除処理でエラーが発生しました: ' . $e->getMessage(),[
                'timestamp' => $timestamp,
                'request' => $request->all(),
                'error' => $e->getTraceAsString(),
            ]);
            
            // エラーメッセージをセッションに保存して一覧ページにリダイレクト
            session()->flash('error_message', '製品の登録中にエラーが発生しました。もう一度お試しください。');
            return redirect()->route('items.index');
        }
    }

    //データ削除
    public function delete(Request $request) {
        $ids = $request->input('ids');

        if(!empty($ids)) {
            try{
                //選択商品がすでに削除されていないかチェック
                $deletableItems = Item::whereIn('id', $ids)->whereNull('deleted_at')->get();
                if ($deletableItems->isEmpty()) {
                    // エラーメッセージをセッションに保存
                    session()->flash('error_message', '選択した製品は既に削除されています。');
                    return redirect()->route('items.index');
                }

                //削除処理
                Item::whereIn('id', $ids)->delete();

                //成功メッセージ、一覧ページへリダイレクト（登録後に更新）
                session()->flash('success_message', '選択した製品を削除しました。');
                return redirect()->route('items.index');

            } catch(\Exception $e) {
                $timestamp = now()->toDateTimeString();
                // エラーログに詳細を記録
                \Log::error('製品削除処理でエラーが発生しました: ' . $e->getMessage(),[
                    'timestamp' => $timestamp,
                    'request' => $request->all(),
                    'error' => $e->getTraceAsString(),
                ]);

                // エラーメッセージをセッションに保存して一覧ページにリダイレクト
                session()->flash('error_message', '製品の削除中にエラーが発生しました。もう一度お試しください。');
                return redirect()->route('items.index');
            }
        }
    }

    //データ復元
    public function restore(Request $request) {
        $ids = $request->input('ids');

        if(!empty($ids)) {
            try{
                //選択商品がすでに復元されていないかチェック
                $restorableItems = Item::onlyTrashed()->whereIn('id', $ids)->get();
                 if ($restorableItems->isEmpty()) {
                 // すでに復元済のアイテムが選択されている場合、エラーメッセージをセッションに保存
                session()->flash('error_message', '選択した製品は既に復元されています。');
                return redirect()->route('items.index');
                }

                //復元処理
                Item::onlyTrashed()->whereIn('id', $ids)->restore();
            
                //成功メッセージ、一覧ページへリダイレクト（登録後に更新）
                session()->flash('success_message', '選択した製品を復元しました。');
                return redirect()->route('items.index');

            } catch(\Exception $e) {
                $timestamp = now()->toDateTimeString();
                // エラーログに詳細を記録
                \Log::error('製品削除処理でエラーが発生しました: ' . $e->getMessage(),[
                    'timestamp' => $timestamp,
                    'request' => $request->all(),
                    'error' => $e->getTraceAsString(),
                ]);

                // エラーメッセージをセッションに保存して一覧ページにリダイレクト
                session()->flash('error_message', '製品の削除中にエラーが発生しました。もう一度お試しください。');
                return redirect()->route('items.index');
            }
        }
    }

    //完全削除ページの表示処理
    public function showForceDeletePage() {
        $deletedItems = Item::onlyTrashed()->get();
        return view('items.force_delete', compact('deletedItems'));
    }

    //完全削除処理
    public function forceDelete(Request $request) {
        $ids = $request->input('ids');
        if(empty($ids)) {
            session()->flash('error_message', '編集する商品が選択されていません。');
            return redirect()->route('items.force_delete'); 
        }     
    
        if(!empty($ids)) {
            try{
                //選択商品がすでに完全削除されていないかチェック
                $restorableItems = Item::onlyTrashed()->whereIn('id', $ids)->get();
                 if ($restorableItems->isEmpty()) {
                 // すでに削除済のアイテムが選択されている場合、エラーメッセージをセッションに保存
                session()->flash('error_message', '選択した製品は既に削除されています。');
                return redirect()->route('items.index');
                }

                //完全削除処理
                Item::onlyTrashed()->whereIn('id', $ids)->forceDelete();
            
                //成功メッセージ、一覧ページへリダイレクト（登録後に更新）
                session()->flash('success_message', '選択した製品を完全に削除しました。');
                return redirect()->route('items.index');

            } catch(\Exception $e) {
                $timestamp = now()->toDateTimeString();
                // エラーログに詳細を記録
                \Log::error('削除処理でエラーが発生しました: ' . $e->getMessage(),[
                    'timestamp' => $timestamp,
                    'request' => $request->all(),
                    'error' => $e->getTraceAsString(),
                ]);

                // エラーメッセージをセッションに保存して一覧ページにリダイレクト
                session()->flash('error_message', '製品の削除中にエラーが発生しました。もう一度お試しください。');
                return redirect()->route('items.index');
            }
        }
    }

    //製品編集ページの表示処理
    public function showUpdatePage() {
        $items = Item::all();
        return view('items.update', compact('items'));
    }

    public function update(Request $request) {
        $ids = $request->input('ids');
        
        try {        
            // バリデーション
            foreach($ids as $id) {
                $request->validate([
                    "name.$id" => 'required|string|max:255',
                    "price.$id" => 'required|integer|min:0|max:2147483647',
                    
                ], [
                    'price.*.required' => '価格が入力されていません。',
                    'price.*.integer' => '価格は整数で入力してください。',
                    'price.*.min' => '価格は0以上を指定してください。',
                    'price.*.max' => '価格は2147483647以下を指定してください。',
                ]);

                // 対象商品の価格を更新
                $item = Item::findOrFail($id);
                $item->update([
                    'name' => $request->input("name.$id"),
                    'price' => $request->input("price.$id"),
                ]);
            }

            // 成功メッセージ
            session()->flash('success_message', '商品情報を更新しました。');
            return redirect()->route('items.index');

        } catch (\Exception $e) {
            \Log::error('商品編集中にエラー: ' . $e->getMessage(), ['error' => $e->getTraceAsString()]);
            session()->flash('error_message', '商品編集中にエラーが発生しました。');
            return redirect()->route('items.update');
        }
    }
}