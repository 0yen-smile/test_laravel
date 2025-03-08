<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>完全削除</title>
</head>
<body>
    <!-- 削除商品一覧 -->
    <h1>選択した製品を完全に削除します。</h1>

    <!-- 完全削除フォーム -->
    <form action={{route('items.forceDelete')}} method="POST">
        @csrf
        @method('DELETE')

        <table border="1">
            <tr>
                <th>選択</th>
                <th>ID</th>
                <th>製品名</th>
                <th>価格</th>
            </tr>
            @foreach ($deletedItems as $item)
            <tr>
                <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}"></td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->price }}円</td>
            </tr>
            @endforeach
        </table>
        <!-- 選択した製品を完全削除するボタン -->
        <button type="submit" onclick="return confirmForceForceDelete()">選択した製品をデータベースから完全に削除する</button>
    </form>
    <br>
     <a href="{{ route('items.index' )}}">>>商品登録ページへ戻る</a>

    <script>
        function confirmForceForceDelete() {
            //チェックボックスの要素を全て取得
            let deleteCheckboxes = document.querySelectorAll('form[action$="{{ route('items.forceDelete') }}"] input[name="ids[]"]:checked');

            //1つも選択されてなければ処理を中止
            if (deleteCheckboxes.length == 0) {
                alert('削除する商品を選択してください');
                return false;
            }

            //確認ダイアログを表示し、キャンセルされた場合は処理を中止
            let message = "本当に削除しますか？この操作は取り消す事が出来ません。\n\n";
            deleteCheckboxes.forEach(item => {
                let id = item.value;
                let name = item.getAttribute('data-name');
                let price = item.getAttribute('data-price');
                message += `ID: ${id}, 名称: ${name}, 価格: ${price}円\n`;
             });

            return confirm(message);
        }
    </script>
</body>
</html>