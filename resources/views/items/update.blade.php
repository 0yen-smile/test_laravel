<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>製品情報更新</title>
</head>
<body>
        <!-- 削除商品一覧 -->
    <h1>商品情報を更新します。</h1>

    <!-- 更新フォーム -->
    <form action={{route('items.update')}} method="POST">
        @csrf
        @method('PUT')

        <table border="1">
            <tr>
                <th>選択</th>
                <th>ID</th>
                <th>製品名</th>
                <th>価格</th>
            </tr>
            @foreach ($items as $item)
            <tr>
                <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}"></td>
                <td>{{ $item->id }}</td>
                <td><input type="text" name="name[{{ $item->id }}]" value="{{ $item->name }}" required></td>
                <td><input type="number" name="price[{{ $item->id }}]" value="{{ $item->price }}" required></td>
                <!-- update_atを隠しフィールドで送信 -->
                <input type="hidden" name="updated_at[{{ $item->id }}]" value="{{ $item->updated_at }}">
            </tr>
            @endforeach
        </table>
        <!-- 選択した製品を編集するボタン -->
        <button type="submit" onclick="return confirmUpdate()">選択した製品を編集する</button>
    </form>
    <br>
     <a href="{{ route('items.index' )}}">>>商品登録ページへ戻る</a>

    <script>
        // 製品名、価格のエラーメッセージ
        document.addEventListener("DOMContentLoaded", function() {
            let errorMessage = "";

            @if (session('error_message'))
                alert("{{session('error_message')}}")
            @endif

            @if (session('success_message'))
                alert("{{session('success_message')}}")
            @endif

            @if ($errors->has('price'))
                errorMessage += "・価格設定が不正です\n";
                @foreach ($errors->get('price') as $error)
                    errorMessage += "　- {{ $error }}\n";
                @endforeach
            @endif

            @if ($errors->has('name'))
                errorMessage += "・製品名設定が不正です\n";
                @foreach ($errors->get('name') as $error)
                    errorMessage += "　- {{ $error }}\n";
                @endforeach
            @endif

            if (errorMessage) {
                alert("入力エラーがあります。\n\n" + errorMessage);
            }
        });

        function confirmUpdate() {
            //チェックボックスの要素を全て取得
            let updateCheckboxes = document.querySelectorAll('input[name="ids[]"]:checked');

            //1つも選択されてなければ処理を中止
            if (updateCheckboxes.length == 0) {
                alert('編集する商品を選択してください');
                return false;
            }

            //確認ダイアログを表示し、キャンセルされた場合は処理を中止
            let message = "以下の内容で編集します。よろしいですか？\n\n";
            updateCheckboxes.forEach(item => {
                let id = item.value;
                let nameInput = document.querySelector(`input[name="name[${id}]"]`);
                let priceInput = document.querySelector(`input[name="price[${id}]"]`);
                let newName = nameInput ? nameInput.value : "（未入力）";
                let newPrice = priceInput ? priceInput.value : "（未入力）";

                message += `ID: ${id}, 名称: ${newName}, 価格: ${newPrice}円\n`;
             });

            return confirm(message);
        }
    </script>
</body>
</html>