document.addEventListener('DOMContentLoaded', () => {
    let offset = 0; // 初期オフセット
    const limit = 5; // 1回のリクエストで取得するメモの数
    const memoList = document.getElementById('memo-list');
    // .navエリアを取得
    const navArea = document.querySelector('.nav');
    let loading = false; // ローディングフラグ

    // 初期データの表示
    fetchMoreMemos();

    navArea.addEventListener('scroll', () => {
        console.log('スクロールイベントが発生しました');
        if (navArea.scrollTop + navArea.clientHeight >= navArea.scrollHeight - 50 && !loading) {
            // .navエリアの下部に近づいた場合
            fetchMoreMemos();
        }
    });

    function fetchMoreMemos() {
        if (loading) return;
        loading = true;
        console.log('メモの読み込みを開始します');

        fetch(`./api/get_memos.php?offset=${offset}&limit=${limit}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    data.forEach(memo => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                            <h3><a href="view_memo.php?id=${memo.id}">${memo.title}</a></h3>
                            <p>${truncateText(memo.content, 50)}</p> <!-- 50文字に制限 -->
                            <a href="./api/edit_memo.php?id=${memo.id}">編集</a>
                            <a href="./api/delete_memo.php?id=${memo.id}">削除</a>
                        `;
                        memoList.appendChild(li);
                    });
                    offset += data.length; // オフセットを更新
                }
                loading = false;
                console.log('メモの読み込みが完了しました');
            })
            .catch(error => {
                console.error('Error:', error);
                loading = false;
            });
    }

    function truncateText(text, maxLength) {
        if (text.length > maxLength) {
            return text.substring(0, maxLength) + '...';
        }
        return text;
    }
});
