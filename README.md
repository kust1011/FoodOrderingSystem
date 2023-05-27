## 網站功能說明
這是一個線上訂餐網站，用戶可以在此網站上進行註冊、登入、尋找商店、查看菜單、下訂單、管理訂單以及進行儲值和交易紀錄查詢等操作。

### 功能一：Home
* 導覽列：提供連結到 Home、Shop、My Order、Shop Order、Transaction Record 頁面，以及登出功能。
* 登入：用戶可以進行登入操作。
    * 成功：轉跳至主頁。
    * 失敗：彈出框顯示「登入失敗」。
### 功能二：Shop
* 導覽列：提供連結到 Home、Shop、My Order、Shop Order、Transaction Record 頁面，以及登出功能。
* 註冊店家：
    * 成功：彈出框顯示「註冊成功」，並重整頁面。
    * 失敗：顯示對應的「錯誤訊息」，可使用彈出框或欄位旁顯示。
        * 店名已被註冊。
        * 欄位空白。
        * 輸入格式不對。
* 店家資訊：顯示店名、店類別、經度、緯度；不可更新。
* 餐點資訊：列出自己店家所有餐點。
* 新增餐點：輸入餐點名稱、價格、庫存數量、上傳圖片。
* 修改餐點：可以對餐點修改價格、庫存數量。
* 新增或修改失敗：顯示對應的「錯誤訊息」，可使用彈出框或欄位旁顯示。
    * 欄位空白。
    * 輸入格式不對。
* 刪除餐點。
### 功能三：My Order
* 導覽列：提供連結到 Home、Shop、My Order、Shop Order、Transaction Record 頁面，以及登出功能。
* 訂單篩選：用戶可以根據訂單狀態進行篩選，包括所有訂單、未完成訂單、已完成訂單和已取消訂單。
* 訂單列表：列出符合篩選條件的訂單，包括訂單編號、商店名稱、訂單狀態和金額。
* 查看訂單：用戶可以點擊訂單列表中的訂單進入訂單詳細內容頁面，查看該訂單的詳細資訊。
### 功能四：Shop Order
* 導覽列：提供連結到 Home、Shop、My Order、Shop Order、Transaction Record 頁面，以及登出功能。
* 訂單篩選：用戶可以根據訂單狀態進行篩選，包括所有訂單、未完成訂單、已完成訂單和已取消訂單。
* 訂單列表：列出符合篩選條件的訂單，包括訂單編號、用戶名稱、訂單狀態和金額。
* 查看訂單：用戶可以點擊訂單列表中的訂單進入訂單詳細內容頁面，查看該訂單的詳細資訊。
* 取消訂單：用戶可以取消未完成的訂單，退款並刷新訂單列表。
* 完成訂單：用戶可以標記未完成的訂單為已完成，刷新訂單列表。
### 功能五：Transaction Record
* 導覽列：提供連結到 Home、Shop、My Order、Shop Order、Transaction Record 頁面，以及登出功能。
* 紀錄篩選：用戶可以根據交易行為進行篩選，包括所有交易、付款、收款和儲值。
* 紀錄列表：列出符合篩選條件的交易紀錄，包括交易編號、交易行為、金額和時間。
### 功能六：Recharge
* 儲值成功：用戶可以進行儲值操作，成功後彈出框顯示「儲值成功」，生成交易紀錄並刷新頁面。
* 儲值失敗：用戶在儲值過程中遇到錯誤，彈出框或欄位旁印出對應的「錯誤訊息」。