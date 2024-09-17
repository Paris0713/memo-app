let offset = 0;
let loading = false;

function loadMemos() {
    if (loading) return;
    loading = true;

    fetch(`/api/get_memos.php?offset=${offset}`) // 修正
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(memos => {
            const memoList = document.querySelector('.nav ul');
            memos.forEach(memo => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <h3><a href="view_memo.php?id=${memo.id}">${memo.title}</a></h3>
                    <p>${memo.content.substring(0, 50)}...</p> <!-- 抜粋を表示 -->
                    <a href="./api/edit_memo.php?id=${memo.id}">編集</a>
                    <a href="./api/delete_memo.php?id=${memo.id}">削除</a>
                `;
                memoList.appendChild(li);
            });
            offset += memos.length;
            // リクエスト成功時にリセット
            loading = false;
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            // エラー時もリセット
            loading = false;
        });
}

// 初回読み込み
loadMemos();

// スクロールイベントの監視
window.addEventListener('scroll', () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
        loadMemos();
    }
});
