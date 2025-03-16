<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>
<body>
    <!-- 商品登録フォーム -->
     <h1>商品登録</h1>
     <form action={{route('items.store')}} method="POST">
        @csrf
        <label>製品名：<input type="text" name="name" required></label>
        <label>価格：<input type="number" name="price" required></label>
        <button type="submit" onclick="return confirmEntry()">登録</button>
    </form>

    <hr>

    <!-- 登録商品一覧 -->
    <h1>商品一覧</h1>
    
    <!-- 削除フォーム -->
    <form action={{route('items.delete')}} method="POST">
        @csrf
        @method('DELETE')
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
                <td>{{ $item->name }}</td>
                <td>{{ $item->price }}円</td>
            </tr>
            @endforeach
        </table>
        <!-- 選択した製品を削除するボタン -->
        <button type="submit" onclick="return confirmDelete()">選択した製品を商品一覧から消去</button>
    </form>
    <br>
    <a href="{{ route('items.updatePage' )}}">>>製品情報編集ページへ移動</a>

    <hr>

    <!-- 削除商品一覧 -->
    <h1>削除商品一覧</h1>

    <!-- 復元フォーム -->
    <form action={{route('items.restore')}} method="POST">
        @csrf
        <table border="1">
            <tr>
                <th>選択</th>
                <th>ID</th>
                <th>製品名</th>
                <th>価格</th>
            </tr>
            @foreach ($deleteItems as $item)
            <tr>
                <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}"></td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->price }}円</td>
            </tr>
            @endforeach
        </table>
        <!-- 選択した製品を復元するボタン -->
        <button type="submit" onclick="return confirmRestore()">選択した製品を商品一覧へ復元</button>
    </form>
    <br>
    <a href="{{ route('items.forceDeletePage' )}}">>>完全削除ページへ移動</a>



    <!-- JavaScript -->
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

        function confirmEntry() {
                // 入力フィールドの値を取得
            let productName = document.querySelector('input[name="name"]').value;
            let productPrice = document.querySelector('input[name="price"]').value;

            // 確認メッセージを作成
            let message = "本当に登録しますか？\n\n";
            message += `製品名: ${productName}\n`;
            message += `価格: ${productPrice}円\n`;
            return confirm(message);
        }

        function confirmDelete() {
            //チェックボックスの要素を全て取得
            let deleteCheckboxes = document.querySelectorAll('form[action$="{{ route('items.delete') }}"] input[name="ids[]"]:checked');
            let restoreCheckboxes = document.querySelectorAll('form[action$="{{ route('items.restore') }}"] input[name="ids[]"]:checked');

            //1つも選択されてなければ処理を中止
            if (deleteCheckboxes.length == 0) {
                alert('削除する商品を選択してください');
                return false;
            }

            //復元のチェックボックスにチェックが入っている場合は処理を中止
            if (restoreCheckboxes.length > 0) {
                alert('復元のチェックボックスにチェックが入っています。チェックを外してください。');
                return false;
            }

            //確認ダイアログを表示し、キャンセルされた場合は処理を中止
            let message = "本当に削除しますか？\n\n";
            deleteCheckboxes.forEach(item => {
                let id = item.value;
                let name = item.getAttribute('data-name');
                let price = item.getAttribute('data-price');
                message += `ID: ${id}, 名称: ${name}, 価格: ${price}円\n`;
             });

            return confirm(message);
        }

        function confirmRestore() {
            //チェックボックスの要素を全て取得
            let deleteCheckboxes = document.querySelectorAll('form[action$="{{ route('items.delete') }}"] input[name="ids[]"]:checked');
            let restoreCheckboxes = document.querySelectorAll('form[action$="{{ route('items.restore') }}"] input[name="ids[]"]:checked');

            //1つも選択されてなければ処理を中止
            if (restoreCheckboxes.length == 0) {
                alert('復元する商品を選択してください');
                return false;
            }

            //削除のチェックボックスにチェックが入っている場合は処理を中止
            if (deleteCheckboxes.length > 0) {
                alert('削除のチェックボックスにチェックが入っています。チェックを外してください。');
                return false;
            }

            //確認ダイアログを表示し、キャンセルされた場合は処理を中止
            let message = "本当に復元しますか？\n\n";
            restoreCheckboxes.forEach(item => {
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