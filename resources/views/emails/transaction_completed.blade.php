<p>{{ $transaction->buyer->name }} さんとの取引が完了しました。</p>
<p>商品名：{{ $transaction->item->name }}</p>
<p>金額：¥{{ number_format($transaction->item->price) }}</p>
<p>ご確認ください。</p>
