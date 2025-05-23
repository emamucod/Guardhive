<x-app-layout>
    @section('page-title', 'ESP32-CAM LIVE FEED')

    <div style="display: flex; justify-content: flex-end; padding: 1rem 2rem;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="stop-btn" style="padding: 8px 16px; font-size: 14px;">
                LOGOUT
            </button>
        </form>
    </div>

    <div class="stream-container">
        <div class="stream-box">
            <div class="stream-placeholder" id="stream-placeholder">
                Stream not started. Click "START STREAM" to begin.
            </div>
            <img id="stream" src="" alt="ESP32 Camera Stream" style="display: none;">
        </div>
        <div class="stream-error" id="stream-error"></div>
        <div class="controls">
            <button class="start-btn" id="start-btn">START STREAM</button>
            <button class="stop-btn" id="stop-btn" disabled>STOP STREAM</button>
            <button class="capture-btn" id="capture-btn" disabled>CAPTURE IMAGE</button>
        </div>
    </div>
</x-app-layout>
