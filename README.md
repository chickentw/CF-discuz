# Discuz! Cloudflare Turnstile Plugin

這是一個專為 **Discuz! X3.5 / X4.0 / X5.0** 設計的 Cloudflare Turnstile 驗證碼插件。它能有效替代傳統的圖形驗證碼，提供更流暢的用戶體驗並強力阻擋垃圾註冊與機器人發帖。

## 功能特點

- **兼容性強**：完美支持最新版 Discuz! X5.0 及較早的 X3.5/X4.0 版本。
- **無縫集成**：採用 Discuz! 內建 Hook 技術，無需修改核心代碼或模板。
- **後台配置**：
  - 設定 Cloudflare Site Key 與 Secret Key。
  - 自定義啟用場景：支持 **註冊**、**登入**、**發新帖**、**回帖**。
- **優質體驗**：使用 Cloudflare Turnstile，用戶通常無需進行繁瑣的點選即可完成驗證。

## 安裝說明

1. **下載與上傳**：
   - 下載本倉庫代碼。
   - 將 `source/plugin/cf_turnstile` 目錄上傳至您 Discuz! 網站的對應路徑。

2. **安裝插件**：
   - 進入 Discuz! 管理後台 -> **插件** -> **未安裝插件**。
   - 找到 **Cloudflare Turnstile 驗證碼**，點擊 **安裝**。

3. **系統設置**：
   - 安裝完成後，點擊 **插件設置**。
   - 填寫您的 Cloudflare **Site Key (站點金鑰)** 與 **Secret Key (通信金鑰)**。
   - 勾選需要啟用驗證的項目。

4. **啟用插件**：
   - 在插件列表頁面將其狀態設置為 **啟用**。

## 獲取 Cloudflare Keys

您可以前往 [Cloudflare Turnstile 官網](https://www.cloudflare.com/products/turnstile/) 免費申請密鑰。

## 注意事項

- 請確保您的伺服器環境已啟用 PHP `cURL` 擴充，以便與 Cloudflare 伺服器進行驗證通訊。
- 如果您使用的是高度自定義的非標準模板，可能需要根據實際情況微調 Hook 位置（本插件預設使用標準 `_input` Hook）。

## 授權條款

基於 MIT 授權條款開源。

---